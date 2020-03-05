<!-- Displays a table with all users. The admin can click "suspend" or "delete" to perform those actions -->
@extends('layouts.appmasterLoggedIn') 
@section('title', 'Groups')

@section('content') 
<div class="container">
	<h2>All Groups</h2>
</div>

<a href="getNewGroup">Create new</a>

<div>
	<table id="users" class="display">

		<thead>

			<tr>
				<th>Title</th>
				<th>Description</th>
				<th>Members</th>
				<th></th>
			</tr>

		</thead>

		<tbody>

			@foreach ($allGroups as $group)
			<tr>
				<td>{{$group->getTitle()}}</td>
				<td>{{$group->getDescription()}}</td>
				<td>{{count($group->getMembers_array())}}</td>				
				<td>
					<form action="getGroup" method="POST">
						{{ csrf_field() }}
						 
						<input type="hidden"
							name="idToDisplay" value= "{{$group->getId()}}" />
						<button type="submit" class="btn btn-dark">View</button>
					</form>
				</td>			
			</tr>
			@endforeach

		</tbody>

	</table>
</div>
@endsection
