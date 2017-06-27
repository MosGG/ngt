
<?php
if (isset($_POST['email'])) {
		$email = $_POST['email'];

		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$servername = "localhost";
				$username = "root";
				$password = "root";
				$dbname = "newg_hosting";

				// Create connection
				$conn = new mysqli($servername, $username, $password, $dbname);

				// Check connection
				if ($conn->connect_error) {
    			die("Connection failed: " . $conn->connect_error);
				}

				// prepare and bind
				$sql = "SELECT * FROM ".$site['database']['membership']." ";
				$sql .= "WHERE `mmemberEmail` = ?";
				// echo $sql;
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("s", $email);

				if (!$stmt->execute()) {
   			echo $stmt->error;
				}

				$result = $stmt->get_result();
				$members = $result->fetch_assoc();

				$stmt->free_result();

				$stmt->close();
				$conn->close();

			if ($members != NULL){

				$name = $members["mmemberNameF"];
				$useremail = $members["mmemberEmail"];
				$password = $members["mmemberPassword"];

		$mail_html ="
		<!doctype html>
		<html>
		<img src='/images/headernew.png' style='width:404px;height:108px;'>
		<p>Hi ".$name."<br>
    Thank you for applying for membership and joining our mailing list. <br><br>

	  Your username is : ".$useremail."<br><br>

		Your password is : ".$password."</n> <br>
		<br></p>



		<p>Regards,<br>
		Stanley Shi<br>
		New Global Trading Manager<br>
		Website: www.newglobalmel.com.au<br>
		Email: sales@newglobalmel.com.au </p>

		</html>


		";


		$to = $email;
		$subject = "Reset email";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: sales@newglobalmel.com.au' . "\r\n";
		mail($to,$subject,$mail_html,$headers);

		// echo "<p style='font-size:18px'>Hi, ".$members["mmemberNameF"]."</p>";
		echo "<h1 style='font-family:Montserrat;color:#4ABDAC;text-align:center;font-size:70px'>SUCCESSFUL!</h1>";
		echo "<p style='font-size:18px'>Your password has been sent to your email successfully.</p>";
	}
else{
	echo "<div id='wholesaler' style='height:440px'>";
	echo "<a href='/forget-password'><div class='login-tab login-tab-active'>Forget Password</div></a>";
	echo "<a href='/become-a-member'><div class='login-tab login-tab-inactive'>Register</div></a>";
	echo "<div style='padding-top: 152px' >";
	echo "<div class='login-div'><img style='margin:0 11px 0 19px;' class='login-logo' src='/images/new/login-email.png'/>";
	echo "<input type='text' id='fp-email' size='20' maxlength='50' placeholder='Enter Email Address'/><p style='position: absolute;
    left: 120px;'>Your email is not registered</p></div>";
	echo "<input type='submit' value='Submit' onclick='fpsubmit()'/>";
	echo "</div>";
	echo "</div>";

}
}
else{
	echo "<div id='wholesaler' style='height:440px'>";
	echo "<a href='/forget-password'><div class='login-tab login-tab-active'>Forget Password</div></a>";
	echo "<a href='/become-a-member'><div class='login-tab login-tab-inactive'>Register</div></a>";
	echo "<div style='padding-top: 152px' >";
	echo "<div class='login-div'><img style='margin:0 11px 0 19px;' class='login-logo' src='/images/new/login-email.png'/>";
	echo "<input type='text' id='fp-email' size='20' maxlength='50' placeholder='Enter Email Address'/><p style='position: absolute;
    left: 120px;'>This is invalid email</p></div>";
	echo "<input type='submit' value='Submit' onclick='fpsubmit()'/>";
	echo "</div>";
	echo "</div>";
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

?>


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
