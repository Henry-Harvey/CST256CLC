<!-- Displays a form for editting an existing group -->
@extends('layouts.appmaster') 

@section('content')
<form action="processEditGroup" method="POST">
	{{ csrf_field() }}
    
    <div class="form-group">
		<input type="hidden" class="form-control" id="id" placeholder="ID" value="{{$groupToEdit->getId()}}" name="id">
	</div>
    
	<div class="form-group">
		<label for="title">Title</label> 
		<input style="width: 30%" type="text" class="form-control" id="title" placeholder="Title" value="{{$groupToEdit->getTitle()}}" name="title">
		{{$errors->first('title')}}
	</div>
	
	<div class="form-group">
		<label for="description">Description</label> 
		<input style="width: 30%" type="text" class="form-control" id="description" placeholder="Description" value="{{$groupToEdit->getDescription()}}" name="description">
		{{$errors->first('description')}}
	</div>
	
	<div class="form-group">
		<input type="hidden" class="form-control" id="owner_id" placeholder="Owner_id" value="{{$groupToEdit->getOwner_id()}}" name="owner_id">
	</div>
	
	<button type="submit" class="btn btn-dark">Save</button>

</form>
@endsection