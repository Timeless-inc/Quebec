document.addEventListener('DOMContentLoaded', function() {
    const verDetalhesButtons = document.querySelectorAll('.ver-detalhes-btn');

    verDetalhesButtons.forEach(button => {
        button.addEventListener('click', function() {
            const requerimentoId = this.getAttribute('data-requerimento-id');
            const novoBadge = document.getElementById('novo-badge-' + requerimentoId);

            if (novoBadge) {
                novoBadge.remove();

                fetch('/requerimento/' + requerimentoId + '/marcar-como-visto', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            console.error('Erro ao marcar requerimento como visto');
                        }
                    })
                    .catch(error => {
                        console.error('Erro na requisição:', error);
                    });
            }
        });
    });
});