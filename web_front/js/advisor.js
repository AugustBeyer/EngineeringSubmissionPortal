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
		append = "<label>Student " + iter + " Name</label> <input class=\"form_field\" type = \"text\" name = \"students[]\"> <br>";
		div.innerHTML = div.innerHTML + append;
		iter = iter + 1;
    });
	    $("#moreAdvisors").click(function(){
				if(iter2 == 1)
				{
						append = "<br><label>Advisor " + iter2 + " Name</label> <input class=\"form_field\" type = \"text\" name =\"advisors[]\"> <br>";
				}
				else
				{
						append = "<label>Advisor " + iter2 + " Name</label> <input class=\"form_field\" type = \"text\" name =\"advisors[]\"> <br>";
				}
		div2.innerHTML = div2.innerHTML + append;
		iter2 = iter2 + 1;
    });
});

$(document).ready(function(){
	var div = document.getElementById('appendHerePlease');
	var div2 = document.getElementById('appendAdvisorsHere');
	var iter = 2;
	var iter2 = 1;
    $("#editMoreStudents").click(function(){
		append = "New Student: <input class=\"form_field\" type = \"text\" name = \"students[]\"> <br>";
		div.innerHTML = div.innerHTML + append;
		iter = iter + 1;
    });
	    $("#editMoreAdvisors").click(function(){
				if(iter2 == 1)
				{
						append = "<br>New Advisor Name: <input class=\"form_field\" type = \"text\" name =\"advisors[]\"> <br>";
				}
				else
				{
						append = "New Advisor Name: <input class=\"form_field\" type = \"text\" name =\"advisors[]\"> <br>";
				}
		div2.innerHTML = div2.innerHTML + append;
		iter2 = iter2 + 1;
    });
});
