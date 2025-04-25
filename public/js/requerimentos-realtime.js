document.addEventListener('DOMContentLoaded', function() {
    if (!window.location.pathname.includes('/cradt')) {
        return;
    }
    
    let lastCheck = new Date().getTime();
    let lastUpdateTime = null; 
    let isModalOpen = false;
    let notificationShown = false;
    const CHECK_INTERVAL = 10000; 
    
    console.log('Sistema de atualização em tempo real iniciado:', new Date().toLocaleTimeString());
    
    function checkIfModalOpen() {
        const modals = document.querySelectorAll('.modal');
        for (const modal of modals) {
            if (modal.classList.contains('show') || getComputedStyle(modal).display !== 'none') {
                return true;
            }
        }
        return false;
    }
    
    function checkForUpdates() {
        isModalOpen = checkIfModalOpen();
        
        const url = `/api/requerimentos/check-updates?_=${new Date().getTime()}`;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na resposta: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Resposta da API:', data);
                
                if (!data.lastUpdate) {
                    console.error('API não retornou timestamp válido:', data);
                    return;
                }
                
                const serverLastUpdate = new Date(data.lastUpdate).getTime();
                
                if (lastUpdateTime === null) {
                    lastUpdateTime = serverLastUpdate;
                    console.log('Primeira verificação, armazenando timestamp:', new Date(lastUpdateTime).toISOString());
                    return;
                }
                
                console.log('Verificando timestamps:', {
                    lastUpdateConhecido: new Date(lastUpdateTime).toISOString(),
                    serverLastUpdate: new Date(serverLastUpdate).toISOString(),
                    isModalOpen: isModalOpen,
                    shouldReload: serverLastUpdate > lastUpdateTime
                });
                
                if (serverLastUpdate > lastUpdateTime) {
                    console.log('Atualização detectada!');
                    lastUpdateTime = serverLastUpdate; 
                    
                    if (!isModalOpen) {
                        console.log('Recarregando a página...');
                        window.location.reload();
                    } else if (!notificationShown) {
                        console.log('Modal aberto, exibindo notificação...');
                        showNotification('Um requerimento foi atualizado. Recarregue a página para ver as mudanças.');
                        notificationShown = true;
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao verificar atualizações:', error);
            });
    }
    
    function showNotification(message) {
        const existingNotification = document.querySelector('.requerimento-update-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = 'fixed-top alert alert-warning alert-dismissible fade show m-3 requerimento-update-notification';
        notification.style.zIndex = '9999';
        notification.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        notification.setAttribute('role', 'alert');
        notification.innerHTML = `
            <strong><i class="fas fa-bell"></i> Notificação:</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <button type="button" class="btn btn-sm btn-primary ms-3" id="reloadPageBtn">Recarregar agora</button>
        `;
        
        document.body.appendChild(notification);
        
        document.getElementById('reloadPageBtn').addEventListener('click', function() {
            window.location.reload();
        });
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
                notificationShown = false;
            }
        }, 30000);
    }
    
    document.body.addEventListener('hidden.bs.modal', function(e) {
        console.log('Modal fechado:', e.target.id);
        
        setTimeout(() => {
            isModalOpen = checkIfModalOpen();
            console.log('Estado após fechar modal:', { isModalOpen, notificationShown });
            
            if (!isModalOpen && notificationShown) {
                console.log('Recarregando após fechar modal...');
                window.location.reload();
            }
        }, 300);
    });
    
    setInterval(checkForUpdates, CHECK_INTERVAL);
    
    setTimeout(checkForUpdates, 1000);
});