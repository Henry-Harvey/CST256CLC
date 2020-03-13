<!-- Displays a form for editting a user's job -->
@extends('layouts.appmaster') 

@section('content')
<form action="processEditUserJob" method="POST">
	{{ csrf_field() }}
	
    <div class="form-group">
		<input type="hidden" class="form-control" id="id" placeholder="ID" value="{{$userJobToEdit->getId()}}" name="id">
	</div>
    
	<div class="form-group">
		<label for="title">Title</label> 
		<input style="width: 30%" type="text" class="form-control" id="title" placeholder="Title" value="{{$userJobToEdit->getTitle()}}" name="title">
		{{$errors->first('title')}}
	</div>
	
	<div class="form-group">
		<label for="company">Company</label> 
		<input style="width: 30%" type="text" class="form-control" id="company" placeholder="Company" value="{{$userJobToEdit->getCompany()}}" name="company">
		{{$errors->first('company')}}
	</div>
	
	<div class="form-group">
		<label for="years">Years</label> 
		<input style="width: 30%" type="text" class="form-control" id="years" placeholder="Years" value="{{$userJobToEdit->getYears()}}" name="years">
		{{$errors->first('years')}}
	</div>
		
	<button type="submit" class="btn btn-dark">Save</button>

</form>
@endsection