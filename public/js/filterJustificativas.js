let currentStatus = 'Em andamento';

function filterJustificativas(status) {
    const justificativas = document.querySelectorAll('.justificativa-item');
    let visibleCount = 0;
    
    // Create or get message container
    let messageContainer = document.getElementById('status-message');
    if (!messageContainer) {
        messageContainer = document.createElement('div');
        messageContainer.id = 'status-message';
        messageContainer.className = 'alert alert-info mt-3 text-center shadow-sm';
        messageContainer.style.borderRadius = '10px';
        messageContainer.style.maxWidth = '600px';
        messageContainer.style.margin = '20px auto';
        document.querySelector('.justificativa-item').parentNode.appendChild(messageContainer);
    }
    
    window.history.pushState({}, '', `#${status}`);
    
    justificativas.forEach(item => {
        if (status === 'em_aberto') {
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
    if (visibleCount === 0) {
        messageContainer.style.display = 'block';
        switch(status) {
            case 'pendente':
                messageContainer.innerHTML = '<i class="fas fa-exclamation-circle"></i> Não há requerimentos com pendências';
                titulo.textContent = 'Processos em Atenção:';
                break;
            case 'indeferido':
                messageContainer.innerHTML = '<i class="fas fa-times-circle"></i> Não há requerimentos indeferidos';
                titulo.textContent = 'Processos Indeferidos:';
                break;
            case 'finalizado':
                messageContainer.innerHTML = '<i class="fas fa-check-circle"></i> Não há requerimentos resolvidos';
                titulo.textContent = 'Processos Resolvidos:';
                break;
            case 'em_andamento':
                messageContainer.innerHTML = '<i class="fas fa-clock"></i> Não há requerimentos em andamento';
                titulo.textContent = 'Processos em Andamento:';
                break;
            default:
                messageContainer.innerHTML = '<i class="fas fa-info-circle"></i> Não há requerimentos';
                titulo.textContent = 'Processos em Aberto:';
        }
    } else {
        messageContainer.style.display = 'none';
        switch(status) {
            case 'atencao':
                titulo.textContent = 'Processos em Atenção:';
                break;
            case 'indeferido':
                titulo.textContent = 'Processos Indeferidos:';
                break;
            case 'resolvido':
                titulo.textContent = 'Processos Resolvidos:';
                break;
            case 'em_andamento':
                titulo.textContent = 'Processos em Andamento:';
                break;
            default:
                titulo.textContent = 'Processos em Aberto:';
        }
    }
    
    currentStatus = status;
}

function updateStatusAndFilter(justificativaId, newStatus) {
    currentStatus = newStatus;
    
    const justificativa = document.querySelector(`#justificativa-${justificativaId}`);
    justificativa.dataset.status = newStatus;
    justificativa.setAttribute('andamento', newStatus);
    
    const statusBadge = justificativa.querySelector('.badge');
    statusBadge.className = `badge bg-${getBadgeClass(newStatus)}`;
    statusBadge.textContent = getStatusText(newStatus);
    
    filterJustificativas(newStatus);
}

function getBadgeClass(status) {
    switch(status) {
        case 'atencao': return 'warning';
        case 'resolvido': return 'success';
        case 'indeferido': return 'danger';
        case 'em_andamento': return 'info';
        default: return 'secondary';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'atencao': return 'Pendência';
        case 'resolvido': return 'Resolvido';
        case 'indeferido': return 'Indeferido';
        case 'em_andamento': return 'Em andamento';
        default: return 'Em aberto';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash.replace('#', '');
    if (hash) {
        filterJustificativas(hash); // Aplica o filtro do hash, se existir
    } else {
        filterJustificativas('em_andamento'); // Filtro padrão: "Em andamento"
    }
});