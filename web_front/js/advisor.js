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
		append = "Student " + iter + " Name: <input type = \"text\" name = \"student" + iter + "\"> <br>";
		div.innerHTML = div.innerHTML + append;
		iter = iter + 1;
    });
	    $("#moreAdvisors").click(function(){
		append = "Advisor " + iter2 + " Name: <input type = \"text\" name = \"advisor" + iter2 + "\"> <br>";
		div2.innerHTML = div2.innerHTML + append;
		iter = iter + 1;
    });
});
