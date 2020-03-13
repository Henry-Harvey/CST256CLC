<!-- Displays a form for creating a new group -->
@extends('layouts.appmaster') 
@section('title', 'New Group')

@section('content')

<h2>New Group</h2>

<!-- Form takes in login info and uses http post to persist it to the controller -->
<form action="processCreateGroup" method="POST">
		{{ csrf_field() }}

	<div class="form-group">
		<label for="title">Title</label> 
		<input style="width: 30%" type="text" class="form-control" id="title" placeholder="Title" name="title">
		{{$errors->first('title')}}
	</div>
	
	<div class="form-group">
		<label for="company">Description</label> 
		<input style="width: 30%" type="text" class="form-control" id="description" placeholder="Description" name="description">
		{{$errors->first('description')}}
	</div>			
	
	<button type="submit" class="btn btn-dark">Submit</button>

</form>

@endsection
