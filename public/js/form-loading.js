document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll(
        '.approve-form, .pendent-form, .deny-form, ' +
        '.event-form, .event-exception-form, .event-config-form, .event-edit-form'
    );
    const spinner = document.getElementById('global-loading-spinner');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (form.classList.contains('event-exception-form')) {
                const saveBtn = document.getElementById('saveExceptionBtn');
                if (saveBtn && saveBtn.hasAttribute('disabled')) {
                    e.preventDefault();
                    return; 
                }
            }
            
            spinner.classList.remove('hidden');
            
            const submitBtns = this.querySelectorAll('button[type="submit"]');
            submitBtns.forEach(btn => {
                btn.setAttribute('disabled', 'disabled');
                
                if (btn.classList.contains('bg-green-600')) {
                    btn.classList.add('bg-green-400');
                    btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                } else if (btn.classList.contains('bg-yellow-600')) {
                    btn.classList.add('bg-yellow-400');
                    btn.classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
                } else if (btn.classList.contains('bg-red-600')) {
                    btn.classList.add('bg-red-400');
                    btn.classList.remove('bg-red-600', 'hover:bg-red-700');
                } else if (btn.classList.contains('bg-blue-600')) {
                    btn.classList.add('bg-blue-400');
                    btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                } else if (btn.classList.contains('btn-primary')) {
                    btn.classList.add('opacity-75');
                    btn.classList.add('cursor-not-allowed');
                }
            });
        });
    });

    const profileChangeForms = document.querySelectorAll('form[action*="profile-requests"]');
    
    profileChangeForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            spinner.classList.remove('hidden');
            
            const buttons = this.querySelectorAll('button[type="submit"]');
            buttons.forEach(button => {
                button.setAttribute('disabled', 'disabled');
                button.classList.add('opacity-75');
            });
        });
    });
});