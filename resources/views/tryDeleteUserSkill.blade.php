<!-- This view displays a table with a single UserSkill that may be deleted by pressing "yes". Pressing "no" brings the user back to the profile page -->
@extends('layouts.appmaster') @section('title', 'Try Delete Skill')

@section('content')
<div class="container">
	<h2>Delete Skill</h2>
</div>

<h5>Are you sure you want to delete this skill?</h5>

<div>
	<table id="post" class="table">

		<thead>

			<tr>
				<th>Skill</th>
			</tr>

		</thead>

		<tbody>

			<tr>
				<td>{{$skillToDelete->getSkill()}}</td>
			</tr>		

		</tbody>

	</table>
	<form action="processDeleteUserSkill" method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="idToDelete" value="{{$skillToDelete->getId()}}" />
		<button type="submit" class="btn btn-dark">Yes</button>
	</form>

	<a href="getProfile">No</a>
</div>
@endsection
