function checkAll(bx) {
  var cbs = document.getElementsByTagName('input');
  for(var i=0; i < cbs.length; i++) {
    if(cbs[i].type == 'checkbox') {
      cbs[i].checked = bx.checked;
    }
  }
}

$(document).ready(function(){
	var div = document.getElementById('appendHerePlease');
	var div2 = document.getElementById('appendAdvisorsHere');
	var iter = 4;
	var iter2 = 2;
    $("#moreStudents").click(function(){
		append = "Student " + iter + " Name: <input class=\"form_field\" type = \"text\" name = \"student" + iter + "\"> <br>";
		div.innerHTML = div.innerHTML + append;
		iter = iter + 1;
    });
	    $("#moreAdvisors").click(function(){
		append = "Advisor " + iter2 + " Name: <input class=\"form_field\" type = \"text\" name = \"advisor" + iter2 + "\"> <br>";
		div2.innerHTML = div2.innerHTML + append;
		iter2 = iter2 + 1;
    });
});

var inputs = document.querySelectorAll( '.form_hidden' );
Array.prototype.forEach.call( inputs, function( input )
{
	var label	 = input.nextElementSibling,
		labelVal = label.innerHTML;

	input.addEventListener( 'change', function( e )
	{
		var fileName = '';
		if( this.files && this.files.length > 1 )
			fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
		else
			fileName = e.target.value.split( '\\' ).pop();

		if( fileName )
			label.querySelector( 'span' ).innerHTML = fileName;
		else
			label.innerHTML = labelVal;
	});
});
