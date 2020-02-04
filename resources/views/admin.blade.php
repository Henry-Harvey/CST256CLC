@extends('layouts.appmasterAdmin') 
@section('title', 'Admin')

@section('content') 
<div class="container">
	<h2>Admin | All Users</h2>
</div>

<div>
	<table id="users" class="display">

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
				<th>Suspend</th>
				<th>Delete</th>
			</tr>

		</thead>

		<tbody>

			@foreach ($allUsers as $user)
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
				@if (Session::get('user_id') != $user->getId())
					<form action="processToggleSuspendUser" method="POST">
						<input type="hidden" name="_token"
							value="<?php echo csrf_token()?>" /> <input type="hidden"
							name="idToToggle" value= "{{$user->getId()}}" />
				@if ($user->getCredentials()->getSuspended() == 0)
						<button type="submit" class="btn btn-dark">Suspend</button>
				@else
						<button type="submit" class="btn btn-dark">Unsuspend</button>
				@endif
					</form>
				@endif
				</td>
				<td>
				@if (Session::get('user_id') != $user->getId())
					<form action="processTryDeleteUser" method="POST">
						<input type="hidden" name="_token"
							value="<?php echo csrf_token()?>" /> 
						<input type="hidden"
							name="idToDelete" value= "{{$user->getId()}}" />
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