/**
 * Acewestt
 */
$(document).ready(function () {
    $('#menuButtonSVG').click(function () {
        document.getElementById('menuButtonRotate').beginElement();
        document.getElementById('menuButtonRotate1').beginElement();
        document.getElementById('menuButtonRotate2').beginElement();
        document.getElementById('menuButtonRotate3').beginElement();
        $('.navigation').addClass('active');
    });

    $('.navigation .closeButton').click(function () {
        $('.navigation').removeClass('active');
        document.getElementById('menubuttonInverse').beginElement();
        document.getElementById('menubuttonInverse1').beginElement();
        document.getElementById('menubuttonInverse2').beginElement();
        document.getElementById('menubuttonInverse3').beginElement();
    });
});