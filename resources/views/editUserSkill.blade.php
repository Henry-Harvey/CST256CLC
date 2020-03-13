<!-- Displays a form for editting a user's skill -->
@extends('layouts.appmaster') 

@section('content')
<form action="processEditUserSkill" method="POST">
	{{ csrf_field() }}
	
    <div class="form-group">
		<input type="hidden" class="form-control" id="id" placeholder="ID" value="{{$userSkillToEdit->getId()}}" name="id">
	</div>
    
	<div class="form-group">
		<label for="skill">Skill</label> 
		<input style="width: 30%" type="text" class="form-control" id="skill" placeholder="Title" value="{{$userSkillToEdit->getSkill()}}" name="skill">
		{{$errors->first('skill')}}
	</div>
		
	<button type="submit" class="btn btn-dark">Save</button>

</form>
@endsection