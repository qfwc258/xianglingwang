var timer = setInterval(function () {
    if ($("#c").hasClass("swiper-slide swiper-slide-active")) {
        $("#c1").addClass("animated fadeInLeft");
        $("#c5").addClass("animated fadeInRight");
        $('#c1').show();
        $('#c5').show();
        $('#c5').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
            $('#c4').show();
            $("#c4").addClass("animated fadeIn");
        });
    } else {
        setTimeout(timer)
    }
}, 1000);
window.onload = function () {
    (function () {

        var setCurrent = function () {

            var last = lyrics.get(index);
            $(last).removeClass("current");

            if (index > lineCount) {
                index = -1;
            }
            index += 1;

            var current = lyrics.get(index);
            $(current).addClass("current");

        }


        var scroll = function () {
            var current = $(".lyric li.current");
            if (typeof(current.parent().offset())=="undefined") {
                return
            } else {
                var top = current.parent().scrollTop() - (current.parent().offset().top - current.offset().top) - offset;
                current.parent().animate({
                    scrollTop: top
                });
            }
        }

        var index = -1;
        var lineCount = 44;
        var lyrics = $(".lyric li").not('.empty');
        var offset = 55;

        setInterval(setCurrent, 1500);
        setInterval(scroll, 1500);

    })();
}