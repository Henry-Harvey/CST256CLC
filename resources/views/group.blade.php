<!-- Displays a group -->
@extends('layouts.appmasterLoggedIn') 
@section('content')

<div class="profile-card">

	<div class="card-up indigo lighten-1"></div>

	<div class="card-body">
		<h4 class="card-title">{{$group->getTitle()}}</h4>
		<br>

		<p>Description</p>
		<p>
			<i>{{$group->getDescription()}}</i>
		</p>


		@if(Session::get('sp')->getUser_id() == $group->getOwner_id()) 
			<form action="getEditGroup" method="POST">
					{{ csrf_field() }}						 
					<input type="hidden" name="idToEdit" value= "{{$group->getId()}}" />
					<button type="submit" class="btn btn-dark">Edit</button>
			</form>
			<form action="getTryDeleteGroup" method="POST">
					{{ csrf_field() }}					 
					<input type="hidden" name="idToDelete" value= "{{$group->getId()}}" />
					<button type="submit" class="btn btn-dark">Delete</button>
			</form>
		@else
			@if($userIsMember)
				<form action="getTryLeaveGroup" method="POST">
					{{ csrf_field() }}						 
					<input type="hidden" name="groupid" value= "{{$group->getId()}}" />				
					<button type="submit" class="btn btn-dark">Leave</button>			
				</form>
			@else
				<form action="getTryJoinGroup" method="POST">
					{{ csrf_field() }}						 
					<input type="hidden" name="groupid" value= "{{$group->getId()}}" />				
					<button type="submit" class="btn btn-dark">Join</button>		
				</form>
			@endif
		@endif
	</div>
</div>

<div>
	<h5>Members</h5>
	<table id="members" class="display">
		<thead>
			<tr>
				<th>First Name</th>
				<th>Last Name</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($group->getMembers_array() as $member)
			<tr>
				<td>{{$member->getFirst_name()}}</td>
				<td>{{$member->getLast_name()}}</td>							
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

@endsection
