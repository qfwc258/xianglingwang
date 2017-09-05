function ShowF() {


    $("#f3").addClass("show animated fadeInRight");
    $("#f4").addClass("show animated fadeInLeft");
    $('#f4').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {

        $("#f2").addClass("show  animated fadeIn");
        $("#f7").fadeIn(2000);
        $("#f8").fadeIn(3000);
    });
}