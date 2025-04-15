let currentStatus = 'todos'; 

function filterJustificativas(status) {
    if (status === currentStatus && !window.location.search.includes('page=')) {
        return;
    }
    
    const justificativas = document.querySelectorAll('.justificativa-item');
    let visibleCount = 0;
    
    let messageContainer = document.getElementById('status-message');
    if (!messageContainer) {
        messageContainer = document.createElement('div');
        messageContainer.id = 'status-message';
        messageContainer.className = 'alert alert-info mt-3 text-center shadow-sm';
        messageContainer.style.borderRadius = '10px';
        messageContainer.style.maxWidth = '600px';
        messageContainer.style.margin = '20px auto';
        const container = document.querySelector('.justificativa-item');
        if (container) {
            container.parentNode.appendChild(messageContainer);
        }
    }
    
    const urlParams = new URLSearchParams(window.location.search);
    const oldStatus = urlParams.get('status');
    urlParams.set('status', status);
    
    if (oldStatus !== status) {
        urlParams.delete('page');
    }
    
    window.history.pushState({}, '', `?${urlParams.toString()}`);
    
    justificativas.forEach(item => {
        if (status === 'todos') {
            item.style.display = 'block';
            visibleCount++;
        } else if (status === 'em_aberto') {
            if (item.dataset.status === 'em_andamento' || item.dataset.status === 'pendente') {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        } else {
            if (item.dataset.status === status) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        }
    });

    const titulo = document.getElementById('situacao-titulo');
    if (titulo) {
        switch(status) {
            case 'pendente':
                titulo.textContent = 'Processos em Atenção:';
                break;
            case 'indeferido':
                titulo.textContent = 'Processos Indeferidos:';
                break;
            case 'finalizado':
                titulo.textContent = 'Processos Resolvidos:';
                break;
            case 'em_andamento':
                titulo.textContent = 'Processos em Andamento:';
                break;
            case 'em_aberto':
                titulo.textContent = 'Processos em Aberto:';
                break;
            case 'todos':
            default:
                titulo.textContent = 'Todos os Processos:';
        }
    }

    if (visibleCount === 0) {
        messageContainer.style.display = 'block';
        switch(status) {
            case 'pendente':
                messageContainer.innerHTML = '<i class="fas fa-exclamation-circle"></i> Não há requerimentos com pendências';
                break;
            case 'indeferido':
                messageContainer.innerHTML = '<i class="fas fa-times-circle"></i> Não há requerimentos indeferidos';
                break;
            case 'finalizado':
                messageContainer.innerHTML = '<i class="fas fa-check-circle"></i> Não há requerimentos resolvidos';
                break;
            case 'em_andamento':
                messageContainer.innerHTML = '<i class="fas fa-clock"></i> Não há requerimentos em andamento';
                break;
            case 'todos':
                messageContainer.innerHTML = '<i class="fas fa-info-circle"></i> Não há requerimentos';
                break;
            default:
                messageContainer.innerHTML = '<i class="fas fa-info-circle"></i> Não há requerimentos';
        }
    } else {
        messageContainer.style.display = 'none';
    }
    
    updatePaginationLinks(status);
    
    currentStatus = status;
    
    highlightActiveButton(status);
    
    if (oldStatus !== status && !urlParams.has('page')) {
        setTimeout(() => {
            window.location.href = `?status=${status}`;
        }, 50);
    }
}

function updatePaginationLinks(status) {
    document.querySelectorAll('.pagination a').forEach(link => {
        if (link.hasAttribute('href')) {
            const url = new URL(link.href, window.location.origin);
            url.searchParams.set('status', status);
            link.href = url.toString();
        }
    });
}

function highlightActiveButton(status) {
    document.querySelectorAll('.filter-btn').forEach(btn => {
        if (btn.dataset.status === status) {
            btn.classList.add('font-bold');
            btn.style.opacity = '1';
        } else {
            btn.classList.remove('font-bold');
            btn.style.opacity = '0.8';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status') || 'todos';
    
    currentStatus = status;
    
    highlightActiveButton(status);
    
    updatePaginationLinks(status);
});