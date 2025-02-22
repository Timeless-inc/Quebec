document.addEventListener('DOMContentLoaded', function() {
    const tipoRequisicao = document.getElementById('tipoRequisicao');
    const anexoDiv = document.querySelector('[for="anexarArquivos"]').parentElement;
    
    function toggleAnexoVisibility() {
        const showAnexoFor = [1, 3, 4, 5, 10, 28];
        
        if (showAnexoFor.includes(Number(tipoRequisicao.value))) {
            anexoDiv.style.display = 'block';
        } else {
            anexoDiv.style.display = 'none';
        }
    }

    toggleAnexoVisibility();
    
    tipoRequisicao.addEventListener('change', toggleAnexoVisibility);
});