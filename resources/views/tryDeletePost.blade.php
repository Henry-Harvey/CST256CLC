<!-- This view displays a table with a single Job posting that may be deleted by pressing "yes". Pressing "no" brings the user back to the allJobPostings page -->
@extends('layouts.appmaster') @section('title', 'Try Delete Post')

@section('content')
<div class="container">
	<h2>Delete Post</h2>
</div>

<h5>Are you sure you want to delete this post?</h5>

<div>
	<table id="post" class="table">

		<thead>

			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Company</th>
				<th>Location</th>
				<th>Description</th>
			</tr>

		</thead>

		<tbody>

			<tr>
			<!-- $postToDelete comes from AccountController.onTryDeletePost() -->
				<td>{{$postToDelete->getId()}}</td>
				<td>{{$postToDelete->getTitle()}}</td>
				<td>{{$postToDelete->getCompany()}}</td>
				<td>{{$postToDelete->getLocation()}}</td>
				<td>{{$postToDelete->getDescription()}}</td>

			</tr>
			
			<tr>
				<td colspan="5">
				Skills: 
				@foreach ($postToDelete->getPostSkill_array() as $skill)
					{{$skill->getSkill()}} | 
				@endforeach
				</td>
			</tr>

		</tbody>

	</table>
	<form action="processDeletePost" method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="idToDelete" value="{{$postToDelete->getId()}}" />
		<button type="submit" class="btn btn-dark">Yes</button>
	</form>

	<a href="jobPostings">No</a>
</div>
@endsection
