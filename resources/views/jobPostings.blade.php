<!-- This view displays a table with all users. The admin can click "suspend" or "delete" to perform those actions -->
@extends('layouts.appmasterLoggedIn') 
@section('title', 'Job Postings')

@section('content') 
<div class="container">
	<h2>Job Postings</h2>
</div>

@if(Session::get('sp')->getRole() != 0)
<a href="createPost">Add</a>
@endif

<div>
	<table id="users" class="display">

		<thead>

			<tr>
				<th>Title</th>
				<th>Company</th>
				<th>Location</th>
				<th>Description</th>
				@if(Session::get('sp')->getRole() != 0)
				<th>Edit</th>
				<th>Delete</th>
				@endif
				
			</tr>

		</thead>

		<tbody>

			@foreach ($allPosts as $post)
			<tr>
				<td>{{$post->getTitle()}}</td>
				<td>{{$post->getCompany()}}</td>
				<td>{{$post->getLocation()}}</td>
				<td>{{$post->getDescription()}}</td>
				@if(Session::get('sp')->getRole() != 0)
				<td>
					<form action="getEditPost" method="POST">
						{{ csrf_field() }}
						 
						<input type="hidden" name="idToEdit" value= "{{$post->getId()}}" />
						<button type="submit" class="btn btn-dark">Edit</button>

					</form>
				</td>			
				<td>
					<form action="processTryDeletePost" method="POST">
						{{ csrf_field() }}
						 
						<input type="hidden" name="idToDelete" value= "{{$post->getId()}}" />
						<button type="submit" class="btn btn-dark">Delete</button>

					</form>
				</td>
				@endif
			</tr>
				
			<tr>
				<td colspan="4">
				Skills: 
				@foreach ($post->getPostSkill_array() as $skill)
					{{$skill->getSkill()}} | 
				@endforeach
				</td>
			</tr>
			
			@endforeach

		</tbody>

	</table>
</div>
@endsection
