(function() {
    const path = window.location.pathname;
    
    // Deixe ele rodar até na raiz ou outras rotas onde possa haver requerimentos listados
    const validPaths = ['/cradt', '/requerimentos', '/dashboard', '/diretor-geral', '/painel'];
    let isModalOpen = false;
    let notificationShown = false;

    console.log('Iniciando script Real-Time...');

    function checkIfModalOpen() {
        const modals = document.querySelectorAll('.modal');
        for (const modal of modals) {
            if (modal.classList.contains('show') || getComputedStyle(modal).display !== 'none') {
                return true;
            }
        }
        return false;
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
        setTimeout(() => {
            isModalOpen = checkIfModalOpen();
            if (!isModalOpen && notificationShown) {
                window.location.reload();
            }
        }, 300);
    });

    function setupEcho() {
        const userIdMeta = document.querySelector('meta[name="user-id"]');
        const userRoleMeta = document.querySelector('meta[name="user-role"]');
        
        if (!userIdMeta) return;

        const userId = userIdMeta.getAttribute('content');
        const userRole = userRoleMeta ? userRoleMeta.getAttribute('content') : '';

        const listenToEvents = (channel) => {
            channel
                .listen('.ApplicationRequestCreated', (e) => {
                    handleUpdate('Um novo requerimento foi criado.');
                })
                .listen('.ApplicationStatusChanged', (e) => {
                    handleUpdate('O status de um requerimento foi atualizado.');
                });
        };

        const isAdmin = ['Cradt', 'Manager', 'Diretor Geral'].includes(userRole);
        
        if (isAdmin) {
            listenToEvents(window.Echo.private('admin.requerimentos'));
            console.log('Inscrito no canal: admin.requerimentos');
        } else {
            listenToEvents(window.Echo.private('App.Models.User.' + userId));
            console.log('Inscrito no canal: App.Models.User.' + userId);
        }
    }

    function handleUpdate(message) {
        console.log('Websocket Recebeu:', message);
        isModalOpen = checkIfModalOpen();
        if (!isModalOpen) {
            window.location.reload();
        } else if (!notificationShown) {
            showNotification(message + 'Recarregue a página para ver as mudanças.');
            notificationShown = true;
        }
    }

    // Espera até que window.Echo seja definido (pois ele vem do app.js compilado via Vite)
    const checkEcho = setInterval(() => {
        if (typeof window.Echo !== 'undefined') {
            clearInterval(checkEcho);
            setupEcho();
        }
    }, 200);
})();
