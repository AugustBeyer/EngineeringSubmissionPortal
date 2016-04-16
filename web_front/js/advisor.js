function checkAll(bx) {
  var cbs = document.getElementsByTagName('input');
  for(var i=0; i < cbs.length; i++) {
    if(cbs[i].type == 'checkbox') {
      cbs[i].checked = bx.checked;
    }
  }
}

$(document).ready(function(){
	var div = document.getElementById('appendHere');
	var iter = 4;
    $("#moreStudents").click(function(){
		append = "Student " + iter + " Name: <input type = \"text\" name = \"student" + iter + "\"> <br>"
		div.innerHTML = div.innerHTML + append;
    });
});
