var timer2 = setInterval(function () {
    if ($("#d").hasClass("swiper-slide swiper-slide-active")) {
        $("#d1").addClass("animated fadeInLeft");
        $("#d2").addClass("animated fadeInRight");
        $('#d2').show();
        $('#d1').show();
        $('#d2').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
            $('#d3').show();
            $("#d3").addClass("animated fadeIn");
        });
    }
}, 1000);
