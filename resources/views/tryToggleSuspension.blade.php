<!-- This view displays a table with a single user whose suspension may be toggled by pressing "yes". Pressing "no" brings the user back to the allUsers page -->
@extends('layouts.appmaster') @section('title', 'Try Toggle Suspension')

@section('content')
<div class="container">
	<h2>Admin | Toggle User Susupension</h2>
</div>

<h5>Are you sure you want to toggle the suspension on this user?</h5>

<div>
	<table id="user" class="table">

		<thead>

			<tr>
				<th>ID</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Location</th>
				<th>Summary</th>
				<th>Role</th>
				<th>Username</th>
				<th>Password</th>
			</tr>

		</thead>

		<tbody>

			<tr>
				<td>{{$userToToggle->getId()}}</td>
				<td>{{$userToToggle->getFirst_name()}}</td>
				<td>{{$userToToggle->getLast_name()}}</td>
				<td>{{$userToToggle->getLocation()}}</td>
				<td>{{$userToToggle->getSummary()}}</td>
				<td>{{$userToToggle->getRole()}}</td>
				<td>{{$userToToggle->getCredentials()->getUsername()}}</td>
				<td>{{$userToToggle->getCredentials()->getPassword()}}</td>

			</tr>

		</tbody>

	</table>
	<form action="processToggleSuspension" method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="idToToggle" value="{{$userToToggle->getId()}}" />
		<button type="submit" class="btn btn-dark">Yes</button>
	</form>

	<a href="admin">No</a>
</div>
@endsection
