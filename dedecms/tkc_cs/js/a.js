function ShowA() {


    var anime_a0 = {
        targets: '#a1',

        rotate: "10turn",
        easing: 'easeInOutSine',

        duration: 600,
        loop: true
    }
    anime(anime_a0);

    $("#a4").addClass("animated fadeInRight");
    $("#a5").addClass("animated fadeInLeft");

    $('#a5').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
        $('#a3').show();
        $("#a3").addClass("animated fadeIn");
    });

}