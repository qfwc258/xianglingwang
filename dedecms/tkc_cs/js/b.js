function ShowB() {



    $("#b1").addClass("show animated fadeInRight");
    $("#b2").addClass("show animated fadeInLeft");
    $("#b4").addClass("show animated fadeInRight");
    $('#b4').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {

        $("#b3").addClass("show  animated fadeIn");
    });


    $("#b5").addClass("show animated fadeIn fadeOut infinite");
}