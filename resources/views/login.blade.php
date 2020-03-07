<!-- This view displays a form that may be filled out and submitted, resulting in a the user logging in and setting the session or failing login -->
@extends('layouts.appmasterLoggedOut') 
@section('title', 'Account Login')

@section('content')

<h2 style="color: #FFFFFF">Login Form</h2>

<!-- Form takes in login info and uses http post to persist it to the controller -->
<form id="form1" class="border border-light p-5" action="processLogin" method="POST">
		{{ csrf_field() }}

	<div class="form-group">
		<label for="username">Username</label> 
		<input style="width: 50%" type="text" class="form-control" id="username" placeholder="Username" name="username">
		{{$errors->first('username')}}
	</div>

	<div class="form-group">
		<label for="password">Password</label> 
		<input style="width: 50%" type="password" class="form-control" id="password" placeholder="Password" name="password">
		{{$errors->first('password')}}
	</div>

	<button type="submit" class="btn btn-dark">Submit</button>

</form>
	
	<p style="color: #FFFFFF">Don't have an account? Click <a href="register">here</a> to register</p>

@endsection
