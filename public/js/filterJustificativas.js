let currentStatus = 'Em andamento';

function filterJustificativas(status) {
    const justificativas = document.querySelectorAll('.justificativa-item');
    
    window.history.pushState({}, '', `#${status}`);
    
    justificativas.forEach(item => {
        if (status === 'todos') {
            item.style.display = 'block';
        } else {
            if (item.dataset.status === status) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        }
    });

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.status === status) {
            btn.classList.add('active');
        }
    });

    const titulo = document.getElementById('situacao-titulo');
    switch(status) {
        case 'todos':
            titulo.textContent = 'Todos os Processos:';
            break;
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
            titulo.textContent = 'Processos Situação:';
    }
    
    // Update currentStatus
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
        default: return 'Status';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash.replace('#', '');
    if (hash) {
        filterJustificativas(hash);
    }
});
