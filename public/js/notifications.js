let notifications = [];

/**
 * Busca notificações do servidor e atualiza a interface.
 */
function fetchNotifications() {
    fetch('/notifications')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            notifications = data;
            updateNotificationUI();
        })
        .catch(error => {
            console.error('Erro:', error);
            showTypedPopupNotification('Ocorreu um erro ao processar a notificação. Tente novamente.', 'error');
            notification.is_read = false;
            updateNotificationUI();
        });
}

/**
 * Atualiza a interface de notificações e o estado do botão.
 */
function updateNotificationUI() {
    const notificationCount = notifications.filter(n => !n.is_read).length;

    // Atualiza o estado do botão de notificações
    if (notificationCount > 0) {
        updateNotificationState('with-notifications', notificationCount);
    } else {
        updateNotificationState('default');
    }

    // Atualiza a lista de notificações
    const notificationList = document.getElementById('notifications');
    notificationList.innerHTML = '';
    
    // Se não houver notificações exibe a mensagem padrão
    if (notifications.length === 0) {
        const emptyMessage = document.createElement('li');
        emptyMessage.className = 'p-4 text-center text-gray-500 italic';
        emptyMessage.innerText = 'Você não tem notificações no momento.';
        notificationList.appendChild(emptyMessage);
    } else {
        // Adiciona as notificações à lista
        notifications.forEach(notification => {
            const li = document.createElement('li');
            li.className = notification.is_read ? 'p-3 border-b border-gray-200 text-gray-500' : 'p-3 border-b border-gray-200 text-gray-700';
            // Adiciona o ID como atributo data para seleção posterior
            li.setAttribute('data-notification-id', notification.id);
            
            // visual da notificação
            li.innerHTML = `
                <div class="flex justify-between items-center">
                    <span class="font-semibold">${notification.title}</span>
                    <span class="text-xs status-indicator ${notification.is_read ? 'text-gray-500' : 'text-green-700 font-bold'}">${notification.is_read ? 'Lida' : 'Nova'}</span>
                </div>
                <div class="text-sm mt-1">${notification.message}</div>
            `;
            
            // cor diferente se não foi lida a notificação
            if (!notification.is_read) {
                li.style.backgroundColor = '#f7f7f7';
            }
            
            li.onclick = () => markAsRead(notification.id);
            notificationList.appendChild(li);
        });
    }
}

/**
 * Alterna a exibição da lista de notificações.
 */
function toggleNotifications() {
    const list = document.getElementById('notification-list');
    list.style.display = list.style.display === 'none' ? 'block' : 'none';
}

/**
 * Marca uma notificação como lida e a remove da lista após um curto período.
 * 
 * @param {number} id - O ID da notificação.
 */
function markAsRead(id) {
    // Busca a notificação na lista
    const notification = notifications.find(n => n.id === id);
    if (!notification || notification.is_read) return;
    
    // Encontra o elemento da notificação na lista
    const notificationItem = document.querySelector(`li[data-notification-id="${id}"]`);
    if (!notificationItem) return;
    
    // Marca como lida visualmente
    notification.is_read = true;
    notificationItem.className = 'p-3 border-b border-gray-200 text-gray-500';
    
    // Atualiza o texto e a classe do indicador de status
    const statusIndicator = notificationItem.querySelector('.status-indicator');
    if (statusIndicator) {
        statusIndicator.textContent = 'Lida';
        statusIndicator.className = 'text-xs status-indicator text-gray-500';
    }
    
    // Remove o fundo
    notificationItem.style.backgroundColor = '';
    
    // Atualiza o contador e o estado do botão
    const notificationCount = notifications.filter(n => !n.is_read).length;
    if (notificationCount > 0) {
        updateNotificationState('with-notifications', notificationCount);
    } else {
        updateNotificationState('default');
    }
    
    //Marca como lida no servidor
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro ao marcar notificação como lida');
        }
        return response.json();
    })
    .then(data => {
        console.log('Notificação marcada como lida:', data.message);
        
        // Adiciona animação de fade
        setTimeout(() => {
            notificationItem.style.transition = 'all 0.3s ease-out';
            notificationItem.style.opacity = '0';
            notificationItem.style.height = '0';
            notificationItem.style.padding = '0';
            notificationItem.style.margin = '0';
            notificationItem.style.overflow = 'hidden';
            
            // Remove o elemento do DOM após a animação
            setTimeout(() => {
                notificationItem.remove();
                
                // Verifica se a lista está vazia
                if (document.querySelectorAll('#notifications li').length === 0) {
                    // Limpa a lista completamente
                    document.getElementById('notifications').innerHTML = '';
                    
                    // Adiciona a mensagem de vazia
                    const emptyMessage = document.createElement('li');
                    emptyMessage.className = 'p-4 text-center text-gray-500 italic empty-message';
                    emptyMessage.innerText = 'Você não tem notificações no momento.';
                    document.getElementById('notifications').appendChild(emptyMessage);
                }
                
                // Exclui a notificação permanentemente do banco de dados
                fetch(`/notifications/${id}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Notificação excluída permanentemente:', data.message);
                })
                .catch(error => {
                    console.error('Erro ao excluir notificação permanentemente:', error);
                });
                
            }, 500);
        }, 1000);
    })
    .catch(error => {
        console.error('Erro:', error);
        // Reverte as mudanças visuais em caso de erro
        notification.is_read = false;
        updateNotificationUI();
    });
}

/**
 * Atualiza o estado do botão de notificações.
 * 
 * @param {string} state - O estado do botão ('default', 'with-notifications', 'opt-out').
 * @param {number} count - O número de notificações (apenas para o estado 'with-notifications').
 */
function updateNotificationState(state, count = 0) {
    const bellIcon = document.getElementById('notification-icon-bell');
    const notificationCount = document.getElementById('notification-count');

    if (state === 'default') {
        // Estado padrão: Sino amarelo sem contador
        bellIcon.innerText = '🔔';
        bellIcon.classList.remove('text-gray-400');
        bellIcon.classList.add('text-orange-500');
        notificationCount.style.display = 'none';
    } else if (state === 'with-notifications') {
        // Estado com notificações: Sino amarelo com contador vermelho
        bellIcon.innerText = '🔔';
        bellIcon.classList.remove('text-gray-400');
        bellIcon.classList.add('text-orange-500');
        notificationCount.style.display = 'block';
        notificationCount.innerText = count;
    } else if (state === 'opt-out') {
        // Estado opt-out: Sino cortado
        bellIcon.innerText = '🔕';
        bellIcon.classList.remove('text-orange-500');
        bellIcon.classList.add('text-gray-400');
        notificationCount.style.display = 'none';
    }
}

/**
 * Exibe uma notificação pop-up com a mensagem fornecida e uma barra de progresso.
 * Permite fechar a notificação ao clicar.
 * 
 * @param {string} message - A mensagem a ser exibida no pop-up.
 * @param {number} duration - Duração em milissegundos (padrão: 5000).
 */
function showPopupNotification(message, duration = 5000) {
    const popup = document.getElementById('popup-notification');
    const popupMessage = document.getElementById('popup-message');
    const timer = document.getElementById('notification-timer');
    
    if (!popup || !popupMessage || !timer) {
        console.error('Elementos de notificação não encontrados');
        return;
    }
    
    // Define a mensagem e mostra o popup
    popupMessage.textContent = message;
    popup.style.display = 'block';
    
    // Reset da barra de progresso
    timer.style.transition = 'none';
    timer.style.width = '100%';
    
    // Força um reflow para garantir que a transição seja aplicada
    timer.offsetHeight;
    
    // Inicia a animação da barra de progresso
    timer.style.transition = `width ${duration}ms linear`;
    timer.style.width = '0';
    
    // Armazena o ID do timeout para poder cancelá-lo se o usuário clicar
    const timeoutId = setTimeout(() => {
        popup.style.display = 'none';
    }, duration);
    
    // Indica que é clicável o pop-up
    popup.style.cursor = 'pointer'; 
    
    // Remove eventos de clique anteriores para evitar duplicação
    popup.removeEventListener('click', closePopup);
    
    // Função para fechar o popup
    function closePopup() {
        clearTimeout(timeoutId); // Cancela o timeout automático
        popup.style.display = 'none';
        popup.removeEventListener('click', closePopup); // Remove o event listener
    }
    
    // Adiciona o evento de clique
    popup.addEventListener('click', closePopup);
}

/**
 * Exibe uma notificação pop-up com cor personalizada.
 * 
 * @param {string} message - A mensagem a ser exibida.
 * @param {string} type - O tipo de notificação ('success', 'warning', 'error', 'info').
 * @param {number} duration - Duração em milissegundos.
 */
function showTypedPopupNotification(message, type = 'success', duration = 5000) {
    const popup = document.getElementById('popup-notification');
    const popupMessage = document.getElementById('popup-message');
    const timer = document.getElementById('notification-timer');
    
    if (!popup || !popupMessage || !timer) {
        console.error('Elementos de notificação não encontrados');
        return;
    }
    
    // Define as cores baseadas no tipo
    const colors = {
        success: '#4CAF50', // Verde
        warning: '#FF9800', // Laranja
        error: '#F44336',   // Vermelho
        info: '#2196F3'     // Azul
    };
    
    // Aplica a cor da barra de acordo com o tipo
    timer.style.backgroundColor = colors[type] || colors.success;
    
    // Exibe a notificação com a barra de progresso
    showPopupNotification(message, duration);
}

// !TESTES FOR DEVS!

// Teste com mensagem simples
//showPopupNotification('Requerimento enviado com sucesso!');

// Teste com diferentes tipos (se implementar a função de tipos)
//showTypedPopupNotification('Perfil atualizado!', 'success');
//showTypedPopupNotification('Lembre-se de completar seu perfil', 'info');
//showTypedPopupNotification('Evento termina em breve', 'warning');
//showTypedPopupNotification('Falha ao enviar documento', 'error');

// Inicializa o sistema de notificações ao carregar a página
document.addEventListener('DOMContentLoaded', fetchNotifications);