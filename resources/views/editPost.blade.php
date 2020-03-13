<!-- Displays a form for editting an existing job posting -->
@extends('layouts.appmaster') 

@section('content')
<form action="processEditPost" method="POST">
	{{ csrf_field() }}
    
    <div class="form-group">
		<input type="hidden" class="form-control" id="id" placeholder="ID" value="{{$postToEdit->getId()}}" name="id">
	</div>
    
	<div class="form-group">
		<label for="title">Title</label> 
		<input style="width: 30%" type="text" class="form-control" id="title" placeholder="Title" value="{{$postToEdit->getTitle()}}" name="title">
		{{$errors->first('title')}}
	</div>
	
	<div class="form-group">
		<label for="company">Company</label> 
		<input style="width: 30%" type="text" class="form-control" id="company" placeholder="Company" value="{{$postToEdit->getCompany()}}" name="company">
		{{$errors->first('company')}}
	</div>
	
	<div class="form-group">
		<label for="location">Location</label> 
		<input style="width: 30%" type="text" class="form-control" id="location" placeholder="Location" value="{{$postToEdit->getLocation()}}" name="location">
		{{$errors->first('location')}}
	</div>
	
	<div class="form-group">
		<label for="description">Description</label> 
		<input style="width: 30%" type="text" class="form-control" id="description" placeholder="Description" value="{{$postToEdit->getDescription()}}" name="description">
		{{$errors->first('description')}}
	</div>
	
	@for ($i = 1; $i < 5; $i++)
		@php
		$skill = null;
			$postSkill_array = $postToEdit->getPostSkill_array();
			if(isset($postSkill_array[$i - 1])){
				$postSkill = $postSkill_array[$i - 1];
				$skill = $postSkill->getSkill();
			}			
		@endphp
		
		@if(isset($skill))		
			<div class="form-group">
			<label for="skill{{$i}}">Skill {{$i}}</label> 
			<input style="width: 30%" type="text" class="form-control" id="skill{{$i}}" placeholder="Skill {{$i}}" value="{{$skill}}" name="skill{{$i}}">
			</div>
		@else
			<div class="form-group">
			<label for="skill{{$i}}">Skill {{$i}}</label> 
			<input style="width: 30%" type="text" class="form-control" id="skill{{$i}}" placeholder="Skill {{$i}}" value="" name="skill{{$i}}">
			</div>
		@endif
	
		@if($i == 1)
			{{$errors->first('skill1')}}
		@endif
		
	@endfor
	
	*Skill 2, 3, and 4 not required <br>
	
	<button type="submit" class="btn btn-dark">Save</button>

</form>
@endsection