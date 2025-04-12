function initDatepicker() {
    const urlParams = new URLSearchParams(window.location.search);
    
    const datepicker = new tempusDominus.TempusDominus(document.getElementById('datePicker'), {
        localization: {
            format: 'dd/MM/yyyy', 
            locale: 'pt-br',
            today: 'Hoje',
            clear: 'Limpar',
            close: 'Fechar'
        },
        display: {
            icons: {
                time: 'fas fa-clock',
                date: 'fas fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'fas fa-trash',
                close: 'fas fa-times'
            },
            buttons: {
                today: true,
                clear: true,
                close: true
            },
            calendarWeeks: false,
            viewMode: 'calendar',
            toolbarPlacement: 'top',
            keepOpen: false,
            components: {
                clock: false,
                date: true
            }
        }
    });
    
    const datePickerContainer = document.querySelector('.date-picker-container');
    if (datePickerContainer) {
        datePickerContainer.addEventListener('click', function() {
            datepicker.toggle();
        });
    }
    
    datepicker.subscribe(tempusDominus.Namespace.events.change, (e) => {
        if (e.date) {
            setTimeout(() => {
                document.getElementById('datePicker').closest('form').submit();
            }, 100);
        } else if (!e.date && document.getElementById('datePicker').value === '') {
            if (urlParams.has('date_filter')) {
                document.getElementById('datePicker').closest('form').submit();
            }
        }
    });
    
    const datePicker = document.getElementById('datePicker');
    if (datePicker && datePicker.value) {
        datePicker.classList.add('bg-blue-50', 'border-blue-300');
        
        datePicker.addEventListener('change', function(e) {
            if (e.target.value) {
                const formattedDate = formatDateToDisplay(e.target.value);
                console.log('Data formatada:', formattedDate);
                
                const dateDisplayElement = document.getElementById('dateDisplay');
                if (dateDisplayElement) {
                    dateDisplayElement.textContent = formattedDate;
                }
            }
        });
    }
}

function formatDateToDisplay(dateString) {
    const parts = dateString.split('-');
    if (parts.length === 3) {
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return dateString;
}

function clearFilters() {
    const searchInput = document.querySelector('input[name="search"]');
    const dateInput = document.getElementById('datePicker');
    const status = new URLSearchParams(window.location.search).get('status') || 'todos';
    
    if (searchInput) searchInput.value = '';
    if (dateInput) dateInput.value = '';
    
    window.location.href = `?status=${status}`;
}

document.addEventListener('DOMContentLoaded', function() {
    initDatepicker();
});