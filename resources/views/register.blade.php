<!-- This view displays a form that may be filled out and submitted, resulting in a new user being created with the filled in information -->
@extends('layouts.appmasterLoggedOut') 
@section('title', 'Account Registration')

@section('content')

<h2>Registration Form</h2>

<!-- Form takes in user info and uses http post to persist it to the controller -->
<form action="processRegister" method="POST">
	{{ csrf_field() }}

	<div class="form-group">
		<label for="username">Username</label> 
		<input type="text" style="width: 30%" class="form-control" id="username" placeholder="Username" name="username">
		{{$errors->first('username')}}
	</div>

	<div class="form-group">
		<label for="password">Password</label> 
		<input type="text" style="width: 30%" class="form-control" id="password" placeholder="Password" name="password">
		{{$errors->first('password')}}
	</div>

	<div class="form-group"> 
		<label for="firstname">First Name</label> 
		<input type="text" style="width: 30%" class="form-control" id="firstname" placeholder="First Name" name="firstname">
		{{$errors->first('firstname')}}
	</div>

	<div class="form-group">
		<label for="lastname">Last Name</label> 
		<input type="text" style="width: 30%" class="form-control" id="lastname" placeholder="Last Name" name="lastname">
		{{$errors->first('lastname')}}
	</div>
	
	<div class="form-group">
		<label for="location">Location</label> 
		<input type="text" style="width: 30%" class="form-control" id="location" placeholder="Location" name="location">
		{{$errors->first('location')}}
	</div>
	
	<div class="form-group">
		<label for="summary">Summary</label> 
		<input type="text" style="width: 30%" class="form-control" id="summary" placeholder="Summary" name="summary">
		{{$errors->first('summary')}}
	</div>

	<button type="submit" class="btn btn-dark">Submit</button>

</form>

Already have an account? Click <a href="login">here</a> to log in

@endsection
