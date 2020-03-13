<!-- This view displays a table with a single user that may be deleted by pressing "yes". Pressing "no" brings the user back to the allUsers page -->
@extends('layouts.appmaster') @section('title', 'Try Delete')

@section('content')
<div class="container">
	<h2>Admin | Delete User</h2>
</div>

<h5>Are you sure you want to delete this user?</h5>

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
			<!-- $userToDelete comes from AccountController.onTryDeleteUser() -->
				<td>{{$userToDelete->getId()}}</td>
				<td>{{$userToDelete->getFirst_name()}}</td>
				<td>{{$userToDelete->getLast_name()}}</td>
				<td>{{$userToDelete->getLocation()}}</td>
				<td>{{$userToDelete->getSummary()}}</td>
				<td>{{$userToDelete->getRole()}}</td>
				<td>{{$userToDelete->getCredentials()->getUsername()}}</td>
				<td>{{$userToDelete->getCredentials()->getPassword()}}</td>

			</tr>

		</tbody>

	</table>
	<form action="processDeleteUser" method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="idToDelete" value="{{$userToDelete->getId()}}" />
		<button type="submit" class="btn btn-dark">Yes</button>
	</form>

	<a href="admin">No</a>
</div>
@endsection
