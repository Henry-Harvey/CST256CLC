<!-- This view displays a table with a single UserEducation that may be deleted by pressing "yes". Pressing "no" brings the user back to the profile page -->
@extends('layouts.appmaster') @section('title', 'Try Delete Education')

@section('content')
<div class="container">
	<h2>Delete Education</h2>
</div>

<h5>Are you sure you want to delete this education?</h5>

<div>
	<table id="post" class="table">

		<thead>

			<tr>
				<th>School</th>
				<th>Degree</th>
				<th>Years</th>
			</tr>

		</thead>

		<tbody>

			<tr>
				<td>{{$educationToDelete->getSchool()}}</td>
				<td>{{$educationToDelete->getDegree()}}</td>
				<td>{{$educationToDelete->getYears()}}</td>
			</tr>		

		</tbody>

	</table>
	<form action="processDeleteUserEducation" method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="idToDelete" value="{{$educationToDelete->getId()}}" />
		<button type="submit" class="btn btn-dark">Yes</button>
	</form>

	<a href="getProfile">No</a>
</div>
@endsection
