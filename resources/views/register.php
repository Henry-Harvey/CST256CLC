<html lang="en">
<head>
<title>Register</title>
</head>

<body>

	<form action="register" method="POST">
		<input type="hidden" name="_token" value="<?php echo csrf_token()?>" />

		<h2>Register</h2>

		<table>
			<tr>
				<td>First Name:</td>
				<td><input type="text" name="firstname" /></td>
			</tr>

			<tr>
				<td>Last Name:</td>
				<td><input type="text" name="lastname" /></td>
			</tr>

			<tr>
				<td>Username:</td>
				<td><input type="text" name="username" /></td>
			</tr>

			<tr>
				<td>Password:</td>
				<td><input type="password" name="password" /></td>
			</tr>

			<tr>
				<td colspan="2" align="center"><input type="submit" value="Submit" />
				</td>
			</tr>

		</table>

	</form>

</body>

</html>