<!-- Displays a form for creating a new job posting -->
@extends('layouts.appmaster') 
@section('title', 'New Job Posting')

@section('content')

<h2>New Job Posting</h2>

<!-- Form takes in login info and uses http post to persist it to the controller -->
<form action="processCreatePost" method="POST">
		{{ csrf_field() }}

	<div class="form-group">
		<label for="title">Title</label> 
		<input style="width: 30%" type="text" class="form-control" id="title" placeholder="Title" name="title">
		{{$errors->first('title')}}
	</div>
	
	<div class="form-group">
		<label for="company">Company</label> 
		<input style="width: 30%" type="text" class="form-control" id="company" placeholder="Company" name="company">
		{{$errors->first('company')}}
	</div>
	
	<div class="form-group">
		<label for="company">Location</label> 
		<input style="width: 30%" type="text" class="form-control" id="location" placeholder="Location" name="location">
		{{$errors->first('location')}}
	</div>
	
	<div class="form-group">
		<label for="company">Description</label> 
		<input style="width: 30%" type="text" class="form-control" id="description" placeholder="Description" name="description">
		{{$errors->first('description')}}
	</div>
	
	

	@for ($i = 1; $i < 5; $i++)
	<div class="form-group">
		<label for="skill{{$i}}">Skill {{$i}}*</label> 
		<input style="width: 30%" type="text" class="form-control" id="skill{{$i}}" placeholder="Skill {{$i}}" name="skill{{$i}}">
	</div>
		@if($i == 1)
			{{$errors->first('skill1')}}
		@endif
	@endfor
	
	
	
	*Only 1 Skill is required to enter <br>

	<button type="submit" class="btn btn-dark">Submit</button>

</form>

@endsection
