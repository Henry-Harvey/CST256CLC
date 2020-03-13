<!-- Displays a user's profile -->
@extends('layouts.appmaster') @section('content')

<div class="profile-card">

	<div class="card-up indigo lighten-1"></div>

	<div class="card-body">
		<h4 class="card-title">{{$post->getTitle()}}</h4>

		<p>Company</p>
		<p>
			<i>{{$post->getTitle()}}</i>
		</p>
		<p>Location</p>
		<p>
			<i>{{$post->getLocation()}}</i>
		</p>
		<p>Description</p>
		<p>
			<i>{{$post->getDescription()}}</i>
		</p>

		<table id="postSkills" class="table">
			<thead>
				<tr>
					<th>Skills</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($post->getPostSkill_array() as $skill)
				<tr>
					<td>{{$skill->getSkill()}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		
		<form action="processApply" method="POST">
			{{ csrf_field() }}
			<input type="hidden" name="id" value="{{$post->getId()}}" />
			<button type="submit" class="btn btn-dark">Apply</button>
	</form>
	</div>
</div>

@endsection
