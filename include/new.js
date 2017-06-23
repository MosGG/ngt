window.onload = function(){
	var left_height = document.getElementById('leftcontainer-bg').offsetHeight;
	var right_height = document.getElementById('middle').offsetHeight;
	if (left_height > right_height){
		document.getElementById('middle').style.height = left_height + 'px';
	} else {
		document.getElementById('leftcontainer-bg').style.height = right_height + 'px';
	}
};

function passwordeye(){
	var type = document.getElementById('input-password').type;
	if (type == 'password') {
		document.getElementById('input-password').type = 'text';
	} else {
		document.getElementById('input-password').type = 'password';
	}
};

function formLogout(){
    var form1 = document.createElement("form");  
    document.body.appendChild(form1);
    var input = document.createElement("input"); 
    input.type = "text";  
    input.name = "logout";  
    input.value = "Logout";  
    form1.appendChild(input); 
    form1.method = "POST";  
    form1.action = "/login/";  
    form1.submit();   
    document.body.removeChild(form1);  
}

function moveSearchDiv(){
	var searchDiv = document.getElementById('search-div');
	var left = searchDiv.style.left;
	if (left == "" || left == "78px") {
	 	searchDiv.style.left = "342px";
	} else {
		searchDiv.style.left = "78px";
	}
}