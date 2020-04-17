<!-- Displays a table with all users. The admin can click "suspend" or "delete" to perform those actions -->
@extends('layouts.appmaster') 
@section('title', 'Admin')

@section('content') 
<div class="container">
	<h2>Admin | All Users</h2>
</div>

<div>
	<table id="users" class="table">

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
				<th>View</th>
				<th>Suspend</th>
				<th>Delete</th>
			</tr>

		</thead>

		<tbody>

			@foreach ($allUsers_array as $user)
			<tr>
				<td>{{$user->getId()}}</td>
				<td>{{$user->getFirst_name()}}</td>
				<td>{{$user->getLast_name()}}</td>
				<td>{{$user->getLocation()}}</td>
				<td>{{$user->getSummary()}}</td>
				<td>{{$user->getRole()}}</td>
				<td>{{$user->getCredentials()->getUsername()}}</td>
				<td>{{$user->getCredentials()->getPassword()}}</td>
				<td>
					<form action="getOtherProfile" method="POST">
						{{ csrf_field() }}					 
						<input type="hidden" name="idToShow" value= "{{$user->getId()}}" />
						<button type="submit" class="btn btn-dark">View</button>
					</form>
				</td>
				<td>
				@if (Session::get('sp')->getUser_id() != $user->getId())
					<form action="getTryToggleSuspension" method="POST">
						{{ csrf_field() }}					
							<input type="hidden" name="idToToggle" value= "{{$user->getId()}}" />
				@if ($user->getCredentials()->getSuspended() == 0)
						<button type="submit" class="btn btn-dark">Suspend</button>
				@else
						<button type="submit" class="btn btn-dark">Unsuspend</button>
				@endif
					</form>
				@endif
				</td>
				<td>
				@if (Session::get('sp')->getUser_id() != $user->getId())
					<form action="getTryDeleteUser" method="POST">
						{{ csrf_field() }}
						 
						<input type="hidden" name="idToDelete" value= "{{$user->getId()}}" />
						<button type="submit" class="btn btn-dark">Delete</button>

					</form>
				</td>
				@endif
			</tr>
			@endforeach

		</tbody>

	</table>
</div>
@endsection
