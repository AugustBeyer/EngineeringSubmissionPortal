    var scrollY = 0;
    var distance = 40;
    var speed = 25;
/*
    function autoScrollTo(element) {
        var currentY = window.pageYOffset;
        var targetY = document.getElementById(element).offsetTop;
        var bodyHeight = document.body.offsetHeight + 50;
        var yPosition = currentY + window.innerHeight + 50;
        var animator = setTimeout('autoScrollTo(\''+element+'\')',speed);
        //scroll down
        if(currentY > targetY){
            if(currentY > targetY+distance){
                scrollY = currentY-distance;
                window.scroll(currentY,scrollY);
            } else{
                clearTimeout(animator);
            }
        } 
        else{
            if(currentY < targetY-distance){
                scrollY = currentY+distance;
                window.scroll(currentY,scrollY);
            } else{
                clearTimeout(animator);
            }
        }
    }
    */
function autoScrollTo(prop){
    $('html,body').animate({scrollTop: $("#"+prop).offset().top+
 parseInt($("#"+prop).css('padding-top'),10) },'slow'); 
}  