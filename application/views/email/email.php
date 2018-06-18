<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Template</title>
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
</head>
<body style="font-family: 'Source Sans Pro', sans-serif; padding:0; margin:0;">
<table style="max-width: 750px; margin: 0px auto; width: 100% ! important; background: #F3F3F3; padding:30px 30px 30px 30px;" width="100% !important" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td style="background:#fff; padding:15px; text-align: center;"><img style="max-width: 125px; width: 100%;padding: 10px;" src="<?php echo base_url();?> backend_asset/custom/images/clubzlogo.png"></td>
	</tr>
	<tr>
		<td style="text-align: center; background: #009f95;">
			<table width="100%" border="0" cellpadding="30" cellspacing="0">
				<tr>
					<td>
						<h2 style="color: #fff; margin: 0 0 5px; text-transform: capitalize; font-size: 35px; font-weight: normal;">Welcome to  <span style="color: #fff;">ClubZ</span></h2>
						<!-- <p style="color: #fff; font-size: 16px; line-height: 28px;">Hello  <?php echo !empty($fullName) ? $fullName : ''; ?></p> -->
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="text-align: center;">
			<table width="100%" border="0" cellpadding="30" cellspacing="0" bgcolor="#fff">
				<tr>
					<td>
						<p style="text-align: center;color: #333; font-size: 16px; line-height: 28px;">Dear ClubZ Member,</p>
						<p style="text-align: center;color: #333; font-size: 16px; line-height: 28px;">We got a request of forgot password. As requested, here is your password.</p>
						<div><img style="max-width: 100px; width: 100%; margin-bottom:10px;" src="<?php echo base_url();?> backend_asset/custom/images/clubzkey.png"></div>
						<h3 style="color: #333; font-size: 28px; font-weight: normal; margin: 0; text-transform: capitalize;">Your Password</h3>
					<!-- 	<p style="color: #333; font-size: 16px; line-height: 28px;">We got a request of forgot password. As requested, here is your ClubZ password :</p> -->
						<h2 style="background: #009f95; color: #fff; margin: 15px 0 5px; font-size: 30px; display: inline-block; font-weight: normal; padding: 10px 15px;"><?php echo $PWD ? $PWD : ''; ?></h2>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="text-align: center;">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#fff">
				<tr>
					<td style="padding: 10px;background: #009f95;color: #fff;">Copyright@ClubZ</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>