<!-- Displays a form for editting a user's education -->
@extends('layouts.appmaster') 

@section('content')
<form action="processEditUserEducation" method="POST">
	{{ csrf_field() }}
	
    <div class="form-group">
		<input type="hidden" class="form-control" id="id" placeholder="ID" value="{{$userEducationToEdit->getId()}}" name="id">
	</div>
    
	<div class="form-group">
		<label for="title">School</label> 
		<input style="width: 30%" type="text" class="form-control" id="school" placeholder="School" value="{{$userEducationToEdit->getSchool()}}" name="school">
		{{$errors->first('school')}}
	</div>
	
	<div class="form-group">
		<label for="company">Degree</label> 
		<input style="width: 30%" type="text" class="form-control" id="degree" placeholder="Degree" value="{{$userEducationToEdit->getDegree()}}" name="degree">
		{{$errors->first('degree')}}
	</div>
	
	<div class="form-group">
		<label for="years">Years</label> 
		<input style="width: 30%" type="text" class="form-control" id="years" placeholder="Years" value="{{$userEducationToEdit->getYears()}}" name="years">
		{{$errors->first('years')}}
	</div>
		
	<button type="submit" class="btn btn-dark">Save</button>

</form>
@endsection