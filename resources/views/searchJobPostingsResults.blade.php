<!-- Displays a table with all found job postings -->
@extends('layouts.appmaster') 
@section('title', 'Job Postings Results')

@section('content') 
<div class="container">
	<h2>Job Postings Results</h2>
</div>

<div>
	<table class="display">
		<thead>
			<tr>
				<th>Title</th>
				<th>Company</th>
				<th>Location</th>
				<th>Description</th>
				<th></th>				
			</tr>
		</thead>
		<tbody>
			@foreach ($foundPosts as $post)
			<tr>
				<td>{{$post->getTitle()}}</td>
				<td>{{$post->getCompany()}}</td>
				<td>{{$post->getLocation()}}</td>
				<td>{{$post->getDescription()}}</td>	
				<td>
					<form action="getJobPost" method="POST">
						{{ csrf_field() }}						 
						<input type="hidden" name="idToShow" value= "{{$post->getId()}}" />
						<button type="submit" class="btn btn-dark">View</button>
					</form>
				</td>							
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
