@extends('layouts.appmaster') 
@section('title', 'Account Registration')

@section('content')
<h2>Registration Form</h2>

<form action="processRegister" method="POST">
	<input type="hidden" name="_token" value="<?php echo csrf_token()?>" />

	<div class="form-group">
		<label for="username">Username</label> <input type="text"
			class="form-control" id="username" placeholder="Username"
			name="username">
	</div>

	<div class="form-group">
		<label for="password">Password</label> <input type="text"
			class="form-control" id="password" placeholder="Password"
			name="password">
	</div>

	<div class="form-group">
		<label for="firstname">First Name</label> <input type="text"
			class="form-control" id="firstname" placeholder="First Name"
			name="firstname">
	</div>

	<div class="form-group">
		<label for="lastname">Last Name</label> <input type="text"
			class="form-control" id="lastname" placeholder="Last Name"
			name="lastname">
	</div>
	
	<div class="form-group">
		<label for="location">Location</label> <input type="text"
			class="form-control" id="location" placeholder="Location"
			name="location">
	</div>
	
	<div class="form-group">
		<label for="summary">Summary</label> <input type="text"
			class="form-control" id="summary" placeholder="Summary"
			name="summary">
	</div>

	<button type="submit" class="btn btn-dark">Submit</button>

</form>

<a href="register">Register</a>
@endsection