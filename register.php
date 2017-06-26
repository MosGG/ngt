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
</style>
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
							<input type="text" maxlength="" name="submit[mmemberBusiness]" value="" placeholder="Business Name *">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<img src='/images/new/login-abn.png'>
							<input type="text" maxlength="" name="submit[mmemberABN]" value="" placeholder="ABN *">
						</td>
					</tr>
					<tr>
						<td>
						<img src='/images/new/login-email.png'>
						<input type="text" maxlength="" name="submit[mmemberNameF]" value="" placeholder="First Name *">
						</td>
						<td>
						<img src='/images/new/login-email.png'>
						<input type="text" maxlength="" name="submit[mmemberNameS]" value="" placeholder="Surname *">
						</td>
					</tr>
					<tr>
						<!-- <th align="right">Email:<b>*</b></th> -->
						<td colspan="2">
						<img src='/images/new/footer-email.png'>
						<input type="text" maxlength="" name="submit[mmemberEmail]" value="" placeholder="Email *">
						</td>
					</tr>
					<tr>
						<td>
						<img src='/images/new/footer-tel.png'>
						<input type="text" maxlength="" name="submit[mmemberPhone]" value="" placeholder="Phone">
						</td>
						<td>
						<img src='/images/new/login-mobile.png'>
						<input type="text" maxlength="" name="submit[mmemberMobilePhone]" value="" placeholder="Mobile">
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<img src='/images/new/footer-location.png'>
						<input type="text" maxlength="" name="submit[mmemberAddress]" value="" placeholder="Address">
						</td>
					</tr>
					<tr>
						<td>
						<img src='/images/new/footer-location.png'>
						<input type="text" maxlength="" name="submit[mmemberSuburb]" value="" placeholder="Suburb">
						</td>
						<td>
						<img src='/images/new/footer-location.png'>
						<input type="text" maxlength="" name="submit[mmemberPostcode]" value="" placeholder="Postcode">
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
						</td>
					</tr>
					
					</tbody>
					</table>
					<div id='tb-captcha'>
						<a href="javascript:updateimage();">
						<img src="http://www.newglobalmel.com.au/include/captcha.php/captcha.php" border="0" name="captcha" id="captcha" alt="Click for New Security Code" title="Click for New Security Code" data-pagespeed-url-hash="1193997884" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">
						</a>
						<input id='input-captcha' type="text" name="submit[mmemberCaptcha]" value="" placeholder="Enter the Security Code *">
						<input id='reg-submit' type="submit" name="submit[button]" value="Submit">
						<a href="javascript:updateimage();">
						<img id='cap-reload' title="Click for New Security Code" data-pagespeed-url-hash="1193997884" onload="pagespeed.CriticalImages.checkImageForCriticality(this);" src='images/new/reg-cap-reload.png'>
						</a>
						<div id='already'>Already have an account? <a class="hvr-underline-from-left-blue" href="/Login">Login</a></div>
					</div>

		</form>
	</div>
</div>

