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
	var iter = 2;
	var iter2 = 1;
    $("#moreStudents").click(function(){
		append = "Student " + iter + " Name: <input class=\"form_field\" type = \"text\" name = \"students[]\"> <br>";
		div.innerHTML = div.innerHTML + append;
		iter = iter + 1;
    });
	    $("#moreAdvisors").click(function(){
		append = "Advisor " + iter2 + " Name: <input class=\"form_field\" type = \"text\" name =\"advisors[]\"> <br>";
		div2.innerHTML = div2.innerHTML + append;
		iter2 = iter2 + 1;
    });
});
