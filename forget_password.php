<?php
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
		echo 'get';
		echo $email;
		$sql  = "SELECT * FROM ".$site['database']['membership']." ";
		$sql .= "WHERE `mmemberEmail` = '".$email."' ";
		$result = sql_exec($sql);
		$members = $result->fetch_assoc();
		var_dump($members);
	}
?>
<p>Please input your email address:</p>
<input type="text" id="fp-email" /><br>
<input type="button" id="fp-submit" value="Submit" onclick="fpsubmit()"/>
<!-- <script type='text/javascript' src='include/jquery-3.2.0.min.js'></script> -->
<script>
function fpsubmit(){
	post('/forget-password', {email: document.getElementById('fp-email').value});
// 	var url = "forget-password?email=" + document.getElementById('fp-email').value;
// 	window.location.href=url;
}
function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}
</script>
