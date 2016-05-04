var script = document.createElement('script');
script.src = 'http://code.jquery.com/jquery-1.11.0.min.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);
var elements = ["assignments","teams","grades"]    
function slideIn(show) {
    $(show).slideToggle();
}
function flipArrow(flip) {
    $(flip).toggleClass("rotate-180");
}
function removeNotification(notif) {
    $(notif).slideUp();
    console.log($(notif).attr('id'));
    $.ajax({
    	url: '../../backend/deleteNotification.php',
    	type: 'post',
    	data: {'notif_id': $(notif).attr('id')},
    	error: function(xhr, desc, err) {
        	console.log(xhr);
        	console.log("Details: " + desc + "\nError:" + err);
      	}
    });
}
function switchView(view) {
    for (var index = 0; index < elements.length; index++)
    {
        $(elements[index]).slideUp();
    }
    $(view).slideDown();
}