<?php
if (isset($_POST['email'])) {
	$email = $_POST['email'];

	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

		global $db;

		// prepare and bind
		$sql = "SELECT * FROM ".$site['database']['membership']." ";
		$sql .= "WHERE `mmemberEmail` = ?";

		$stmt = $db->prepare($sql);
		$stmt->bind_param("s", $email);

		if (!$stmt->execute()) {
			echo $stmt->error;
		}

		$result = $stmt->get_result();
		$members = $result->fetch_assoc();

		$stmt->free_result();
		$stmt->close();

		if ($members != NULL){

			$name = $members["mmemberNameF"];
			$useremail = $members["mmemberEmail"];
			$password = $members["mmemberPassword"];

			$mail_html ="
			<!doctype html>
			<html>
			<img src='http://www.newglobalmel.com.au/images/email-header.jpg' style='width:404px;height:108px;'>
			<p>Hi ".$name."<br>
			We received a request to reset the password for your New Global Trading account. <br><br>

			Your username is : ".$useremail."<br><br>

			Your password is : ".$password."</n> <br>
			<br>

			If you didn't send the request, please ignore this message.<br>
			For general inquires or to request support with your account, please email sales@newglobalmel.com.au.
			</p>

			<p>Regards,<br>
			Stanley Shi<br>
			New Global Trading Manager<br>
			Website: www.newglobalmel.com.au<br>
			Email: sales@newglobalmel.com.au </p>

			</html>

			";

			$to = $email;
			$subject = "New Global Trading - Forget Your Password";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			$headers .= 'From: sales@newglobalmel.com.au' . "\r\n";
			mail($to,$subject,$mail_html,$headers);

			echo "<h1 style='font-family:Montserrat;color:#4ABDAC;text-align:center;font-size:48px'>DONE!</h1>";
			echo "<p style='font-size:18px'>Your password has been sent to your email successfully.</p>";
		}
		else{
			echoerror();
		}
	}
	else{
		echoerror();
	}
}
else{
	echo "<div id='wholesaler' style='height:440px'>";
	echo "<a href='/forget-password'><div class='login-tab login-tab-active'>Forget Password</div></a>";
	echo "<a href='/become-a-member'><div class='login-tab login-tab-inactive'>Register</div></a>";
	echo "<div style='padding-top: 152px' >";
	echo "<div class='login-div'><img style='margin:0 11px 0 19px;' class='login-logo' src='/images/new/login-email.png'/>";
	echo "<input type='text' id='fp-email' size='20' maxlength='50' placeholder='Enter Email Address'/></div>";
	echo "<input type='submit' value='Submit' onclick='fpsubmit()'/>";
	echo "</div>";
}

function echoerror(){
	echo "<div id='wholesaler' style='height:440px'>";
	echo "<a href='/forget-password'><div class='login-tab login-tab-active'>Forget Password</div></a>";
	echo "<a href='/become-a-member'><div class='login-tab login-tab-inactive'>Register</div></a>";
	echo "<div style='padding-top: 152px' >";
	echo "<div class='login-div'><img style='margin:0 11px 0 19px;' class='login-logo' src='/images/new/login-email.png'/>";
	echo "<input type='text' id='fp-email' size='20' maxlength='50' placeholder='Enter Email Address'/><p style='position: absolute;
	left: 120px;top:40px;color:#FC4A1A;'>Please enter a valid email address</p></div>";
	echo "<input type='submit' value='Submit' onclick='fpsubmit()'/>";
	echo "</div>";
	echo "</div>";
}

?>
<script>
function fpsubmit(){
	post('/forget-password', {email: document.getElementById('fp-email').value});
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
