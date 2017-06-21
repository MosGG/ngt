window.onload = function(){
	var left_height = document.getElementById('leftcontainer-bg').offsetHeight;
	var right_height = document.getElementById('middle').offsetHeight;
	if (left_height > right_height){
		document.getElementById('middle').style.height = left_height + 'px';
	} else {
		document.getElementById('leftcontainer-bg').style.height = right_height + 'px';
	}
};