$(document).ready(function () {
    $('.portfolio-flters li').click(function () {
        var value = $(this).data('filter').toLowerCase();

        // Muestra todos los elementos para que puedan ser filtrados nuevamente
        if (value == '*') {
            $(".portfolio-container .portfolio-item").removeClass('hidden').fadeIn('slow');
        } else {
            $(".portfolio-container .portfolio-item:not(." + value + ")").addClass('hidden').fadeOut('slow');
            $(".portfolio-container .portfolio-item:is(." + value + ")").removeClass('hidden').fadeIn('slow');
        }
        // Elimina la clase "filter-active" de todos los elementos de filtro y agrégala al filtro clickeado
        $('.portfolio-flters li').removeClass('filter-active');
        $(this).addClass('filter-active');
    });
});