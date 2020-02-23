<!-- Displays a user's profile -->
@extends('layouts.appmasterLoggedIn') @section('content')

<div class="profile-card">

	<!-- Background color -->
	<div class="card-up indigo lighten-1"></div>

	<!-- Avatar -->
	<!-- <div class="avatar mx-auto white">
    <img src="https://mdbootstrap.com/img/Photos/Avatars/img%20%2810%29.jpg" class="rounded-circle"
      alt="woman avatar">
  </div> -->

	<!-- Content -->
	<div class="card-body">
		<!-- Name -->
		<h4 class="card-title">{{$user->getFirst_name()}}
			{{$user->getLast_name()}}</h4>
		<hr>

		<p>Location</p>
		<p>
			<i>{{$user->getLocation()}}</i>
		</p>
		<p>Summary</p>
		<p>
			<i>{{$user->getSummary()}}</i>
		</p>

		@if(Session::get('sp')->getUser_id() == $user->getId()) 
			<a href="getEditProfile">Edit Profile</a> 
		@endif
	</div>
</div>

<div class="user-resume">
	<h3>Job History</h3>

	@if(Session::get('sp')->getUser_id() == $user->getId()) 
	<a href="createUserJob">Add Job History</a>
	@endif
	<table id="userJobs" class="table">
		<thead>
			<tr>
				<th>Title</th>
				<th>Company</th>
				<th>Years</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($userJob_array as $userJob)
			<tr>
				<td>{{$userJob->getTitle()}}</td>
				<td>{{$userJob->getCompany()}}</td>
				<td>{{$userJob->getYears()}}</td>
				<td>
					<form action="getEditUserJob" method="POST">
						{{ csrf_field() }}
						 
						<input type="hidden" name="idToEdit" value= "{{$userJob->getId()}}" />
						<button type="submit" class="btn btn-dark">Edit</button>

					</form>
				</td>
				<td>
					<form action="getTryDeleteUserJob" method="POST">
						{{ csrf_field() }}
						 
						<input type="hidden" name="idToDelete" value= "{{$userJob->getId()}}" />
						<button type="submit" class="btn btn-dark">Delete</button>

					</form>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

	<h3>Skills</h3>

	@if(Session::get('sp')->getUser_id() == $user->getId()) 
	<a href="createUserSkill">Add Skills</a>
	@endif
	<table id="userSkills" class="table">
		<thead>
			<tr>
				<th>Skill</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($userSkill_array as $userSkill)
			<tr>
				<td>{{$userSkill->getSkill()}}</td>
				<td>
					<form action="getEditUserSkill" method="POST">
						{{ csrf_field() }}
						 
						<input type="hidden" name="idToEdit" value= "{{$userSkill->getId()}}" />
						<button type="submit" class="btn btn-dark">Edit</button>

					</form>
				</td>
				<td>
					<form action="getTryDeleteUserSkill" method="POST">
						{{ csrf_field() }}
						 
						<input type="hidden" name="idToDelete" value= "{{$userSkill->getId()}}" />
						<button type="submit" class="btn btn-dark">Delete</button>

					</form>
				</td>
			</tr>			
			@endforeach
		</tbody>
	</table>

	<h3>Education</h3>
	
	@if(Session::get('sp')->getUser_id() == $user->getId()) 
	<a href="createUserEducation">Add Education</a>
	@endif
	<table id="userEducation" class="table">
		<thead>
			<tr>
				<th>School</th>
				<th>Degree</th>
				<th>Years</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($userEducation_array as $userEducation)
			<tr>
				<td>{{$userEducation->getSchool()}}</td>
				<td>{{$userEducation->getDegree()}}</td>
				<td>{{$userEducation->getYears()}}</td>
				<td>
					<form action="getEditUserEducation" method="POST">
						{{ csrf_field() }}
						 
						<input type="hidden" name="idToEdit" value= "{{$userEducation->getId()}}" />
						<button type="submit" class="btn btn-dark">Edit</button>

					</form>
				</td>
				<td>
					<form action="getTryDeleteUserEducation" method="POST">
						{{ csrf_field() }}
						 
						<input type="hidden" name="idToDelete" value= "{{$userEducation->getId()}}" />
						<button type="submit" class="btn btn-dark">Delete</button>

					</form>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>



@endsection
