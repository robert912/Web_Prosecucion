$(document).ready(function () {
    $('.portfolio-flters li').click(function() {
        var value = $( this ).data('filter').toLowerCase();
        console.log(value)
        $(".portfolio-container .portfolio-item").filter(function () {
            $(this).toggle(value === "*" || $(this)[0].className.toLowerCase().indexOf(value)> -1)
        });
    });
    $('.portfolio-flters li').click(function() {
        $('.portfolio-flters li').removeClass('active');
        $(this).addClass('active');
      });
});