<style>
#register {
	width: 592px;
	margin: -40px auto 0 auto;
	background: #fff;
	border-bottom: 90px solid #EFEFEF;
}

#register table {
	width:100%;
}
#register table td{
	position: relative;
}
.reg-table input[type='text'], .reg-table input[type='password'] {
	margin:10px 10px;
	padding: 0px 10px 0 50px;
	width: calc(100% - 80px);
	height: 48px;
	letter-spacing: 1px;
	border: 1px solid #4A4A4A;
}
.reg-table input[type='text'], .reg-table input[type='password'], 
.reg-table select{
	font-size: 12px;
	font-family: Montserrat;
	color: #4A4A4A;
}	
.reg-table input[type='submit'] {
	display: block;
	border: none;
	width: 350px;
	height: 48px;
	background-color: #4ABDAC;
	line-height: 50px;
	color: #ffffff;
	margin:0 auto;
	font-size: 18px;
	text-align: center;
	text-decoration: none;
	text-transform: none;
	cursor: pointer;
	font-family: Montserrat;
}
.reg-table img{
	position:absolute;
	top:25px;
	left:30px;
	width:18px;
	height:22px;
}
.reg-table select{
	width:182px;
	height:38px;
	border: 1px solid #4A4A4A;
	margin-left:10px;
	margin-top:10px;
	padding-left:10px;
}
.red-star{
	color:#FC4A1A;
	padding-right: 3px;
}
#reg-required{
	margin:50px 0 10px 0;
	text-align: right;
	font-size: 12px;
}
#reg-form{
	padding: 107px 40px 0 40px;
}
#captcha{
	margin-left: 10px;
	width:202px;
	height:50px;
	margin-top:10px;
	float:left;
}
#tb-captcha{
	margin-top:10px;
	margin-bottom: 30px;
	position: relative;
}
#input-captcha{
	margin-left:20px;
	margin-top:10px;
	width: 170px;
	height: 48px;
	padding: 0 40px 0 10px;
	border:1px solid #4A4A4A;
	font-family: Montserrat;
}
#cap-reload{
	position:absolute;
	right:75px;
	top:25px;
}
#reg-submit{
	width:350px;
	height:50px;
	margin: 50px auto 10px auto;
	display:block;
	font-size: 18px;
	font-family: Montserrat;
	font-weight: normal;
}
#reg-submit:hover{
	color:;
}
#already{
	font-family: Montserrat-Ultralight;
	font-size: 14px;
	letter-spacing: .5px;
	text-align: center;
}
#already a{
	color:#4ABDAC;
}
.reg-error{
	border-color: #FC4A1A!important;
}
</style>
<?php 
var_dump($_SESSION['captcha']);
	$required = array(
		"mmemberBusiness",
		"mmemberABN",
		"mmemberNameF" ,
		"mmemberNameS",
		// "mmemberEmail",
		);
	if (!empty($_POST)) {
		foreach ($required as $require) {
			if (empty($_POST['submit'][$require])) {
				$error[$require] = "Y";
			}
		}
		if (strtolower($_POST['submit']['mmemberCaptcha']) != $_SESSION['captcha']) {
			$error['mmemberCaptcha'] = "Y"; 
		}
		if (!filter_var($_POST['submit']['mmemberEmail'], FILTER_VALIDATE_EMAIL)) {
			$error['mmemberEmail'] = "Y";
		} else {
			$sql = "SELECT `mmemberId` FROM ".$site['database']['membership']." WHERE `mmemberEmail` = '".$_POST['submit']['mmemberEmail']."'";
			$result = sql_exec($sql);
			$row = $result->fetch_assoc();
			if (!empty($row)) {
				$error['mmemberEmail'] = "Y";
				$error['msg'] = "<span style='color:#FC4A1A;position:relative;left:10px;top:-7px;'>This email has been registered.</span>";
			}
		}

		if (empty($error)) {
			$emailBits = explode("@",$_POST['submit']['mmemberEmail']);
			$password = $emailBits[0];
			$table['membership']['mmemberPassword']['insert']  = $password;
		}
	}
?>
<div id='register'>
	<a href='/login'>
		<div class='login-tab login-tab-inactive'>
			Login
		</div>
	</a>
	<div class='login-tab login-tab-active'>
		Register
	</div>
	<div id="reg-form">
	<div id='reg-required'><span class='red-star'>*</span>Required</div>
		<script type="text/javascript">function updateimage(){document.getElementById('captcha').src='http://www.newglobalmel.com.au/include/captcha.php?clear='+Math.round(new Date().getTime()/100);}</script>
		<form action="http://www.newglobalmel.com.au/become-a-member/" enctype="multipart/form-data" method="post">
			<table class="reg-table" cellspacing="0">
				<tbody>
					<tr>
						<td colspan="2">
							<img src='/images/new/footer-company.png'>
							<input type="text" maxlength="" name="submit[mmemberBusiness]"
							<?php echo (isset($error['mmemberBusiness']))?'class="reg-error"':'';?>
							value="<?php echo (!empty($_POST['submit']['mmemberBusiness']))?$_POST['submit']['mmemberBusiness']:'';?>" placeholder="Business Name *">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<img src='/images/new/login-abn.png'>
							<input type="text" maxlength="" name="submit[mmemberABN]" 
							<?php echo (isset($error['mmemberABN']))?'class="reg-error"':'';?>
							value="<?php echo (!empty($_POST['submit']['mmemberABN']))?$_POST['submit']['mmemberABN']:'';?>" placeholder="ABN *">
						</td>
					</tr>
					<tr>
						<td>
						<img src='/images/new/login-email.png'>
						<input type="text" maxlength="" name="submit[mmemberNameF]" 
						<?php echo (isset($error['mmemberNameF']))?'class="reg-error"':'';?>
						value="<?php echo (!empty($_POST['submit']['mmemberNameF']))?$_POST['submit']['mmemberNameF']:'';?>" placeholder="First Name *">
						</td>
						<td>
						<img src='/images/new/login-email.png'>
						<input type="text" maxlength="" name="submit[mmemberNameS]" 
						<?php echo (isset($error['mmemberNameS']))?'class="reg-error"':'';?>
						value="<?php echo (!empty($_POST['submit']['mmemberNameS']))?$_POST['submit']['mmemberNameS']:'';?>" placeholder="Surname *">
						</td>
					</tr>
					<tr>
						<!-- <th align="right">Email:<b>*</b></th> -->
						<td colspan="2">
						<img src='/images/new/footer-email.png'>
						<input type="text" maxlength="" name="submit[mmemberEmail]" 
						<?php echo (isset($error['mmemberEmail']))?'class="reg-error"':'';?>
						value="<?php echo (!empty($_POST['submit']['mmemberEmail']))?$_POST['submit']['mmemberEmail']:'';?>" placeholder="Email *">
						<?php if(isset($error['msg'])) {echo $error['msg'];} ?>
						</td>
					</tr>
					<tr>
						<td>
						<img src='/images/new/footer-tel.png'>
						<input type="text" maxlength="" name="submit[mmemberPhone]" 
						value="<?php echo (!empty($_POST['submit']['mmemberPhone']))?$_POST['submit']['mmemberPhone']:'';?>" placeholder="Phone">
						</td>
						<td>
						<img src='/images/new/login-mobile.png'>
						<input type="text" maxlength="" name="submit[mmemberMobilePhone]" 
						value="<?php echo (!empty($_POST['submit']['mmemberMobilePhone']))?$_POST['submit']['mmemberMobilePhone']:'';?>" placeholder="Mobile">
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<img src='/images/new/footer-location.png'>
						<input type="text" maxlength="" name="submit[mmemberAddress]" 
						value="<?php echo (!empty($_POST['submit']['mmemberAddress']))?$_POST['submit']['mmemberAddress']:'';?>" placeholder="Address">
						</td>
					</tr>
					<tr>
						<td>
						<img src='/images/new/footer-location.png'>
						<input type="text" maxlength="" name="submit[mmemberSuburb]" 
						value="<?php echo (!empty($_POST['submit']['mmemberSuburb']))?$_POST['submit']['mmemberSuburb']:'';?>" placeholder="Suburb">
						</td>
						<td>
						<img src='/images/new/footer-location.png'>
						<input type="text" maxlength="" name="submit[mmemberPostcode]" 
						value="<?php echo (!empty($_POST['submit']['mmemberPostcode']))?$_POST['submit']['mmemberPostcode']:'';?>" placeholder="Postcode">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<select name="submit[mmemberState]" id="mmemberState">
								<option value="" selected="selected">State</option>
								<option value="VIC">Victoria</option>
								<option value="NSW">New South Wales</option>
								<option value="ACT">ACT</option>
								<option value="QLD">Queensland</option>
								<option value="NT">Northern Territory</option>
								<option value="WA">Western Australia</option>
								<option value="SA">South Australia</option>
								<option value="TAS">Tasmania</option>
							</select>
							<script>
								document.getElementById('mmemberState').value = '<?php echo (!empty($_POST['submit']['mmemberState']))?$_POST['submit']['mmemberState']:'';?>';
							</script>
						</td>
					</tr>
					
				</tbody>
			</table>
			<div id='tb-captcha'>
				<a href="javascript:updateimage();">
				<img src="http://www.newglobalmel.com.au/include/captcha.php" border="0" name="captcha" id="captcha" alt="Click for New Security Code" title="Click for New Security Code" data-pagespeed-url-hash="1193997884" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">
				</a>
				<input id='input-captcha' type="text" name="submit[mmemberCaptcha]" value="" 
					<?php echo (isset($error['mmemberCaptcha']))?'class="reg-error"':'';?> placeholder="Enter the Security Code *">
				<input id='reg-submit' type="submit" name="submit[button]" value="Submit">
				<a href="javascript:updateimage();">
				<img id='cap-reload' title="Click for New Security Code" data-pagespeed-url-hash="1193997884" onload="pagespeed.CriticalImages.checkImageForCriticality(this);" src='/images/new/reg-cap-reload.png'>
				</a>
				<div id='already'>Already have an account? <a class="hvr-underline-from-left-blue" href="/Login">Login</a></div>
			</div>
		</form>
	</div>
</div>

