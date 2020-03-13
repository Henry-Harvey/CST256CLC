<!-- Displays a form for editting a user's profile -->
@extends('layouts.appmaster') 

@section('content')
<form action="processEditProfile" method="POST">
	{{ csrf_field() }}
    
    <div class="form-group">
		<input type="hidden" class="form-control" id="id" placeholder="ID" value="{{$user->getId()}}" name="id">
	</div>
    
	<div class="form-group">
		<label for="firstname">First Name</label> 
		<input style="width: 30%" type="text" class="form-control" id="firstname" placeholder="First Name" value="{{$user->getFirst_name()}}" name="firstname">
		{{$errors->first('firstname')}}
	</div>
	
	<div class="form-group">
		<label for="lastname">Last Name</label> 
		<input style="width: 30%" type="text" class="form-control" id="lastname" placeholder="Last Name" value="{{$user->getLast_name()}}" name="lastname">
		{{$errors->first('lastname')}}
	</div>
	
	<div class="form-group">
		<label for="location">Location</label> 
		<input style="width: 30%" type="text" class="form-control" id="location" placeholder="Location" value="{{$user->getLocation()}}" name="location">
		{{$errors->first('location')}}
	</div>
	
	<div class="form-group">
		<label for="summary">Summary</label> 
		<input style="width: 30%" type="text" class="form-control" id="summary" placeholder="Summary" value="{{$user->getSummary()}}" name="summary">
		{{$errors->first('summary')}}
	</div>

	<div class="form-group">
		<input type="hidden" class="form-control" id="role" placeholder="role" value="{{$user->getRole()}}" name="role">
	</div>
	
	<div class="form-group">
		<input type="hidden" class="form-control" id="credentials_id" placeholder="credentials_id" value="{{$user->getCredentials_id()}}" name="credentials_id">
	</div>

	<button type="submit" class="btn btn-dark">Save</button>

</form>
@endsection