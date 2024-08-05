document.addEventListener('DOMContentLoaded', () => {

    $(document).ready(function() {
        // Inicializa el Datepicker
        $("#startDate, #endDate").datepicker({
            dateFormat: "yy-mm-dd"
        });

        $("#searchBtninventario").on('click', function() {
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();
            let errorMessage = $('#error-message');

            errorMessage.text('').hide();

            // Validación de fechas
            if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                alert("La fecha inial no puede ser mayor a la fecha final");
                return; // Salimos sin hacer la búsqueda
            }
        });
    });
});