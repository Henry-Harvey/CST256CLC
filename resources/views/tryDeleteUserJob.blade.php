<!-- This view displays a table with a single UserJob that may be deleted by pressing "yes". Pressing "no" brings the user back to the profile page -->
@extends('layouts.appmaster') @section('title', 'Try Delete Job')

@section('content')
<div class="container">
	<h2>Delete Job</h2>
</div>

<h5>Are you sure you want to delete this job?</h5>

<div>
	<table id="post" class="table">

		<thead>

			<tr>
				<th>Title</th>
				<th>Company</th>
				<th>Years</th>
			</tr>

		</thead>

		<tbody>

			<tr>
				<td>{{$jobToDelete->getTitle()}}</td>
				<td>{{$jobToDelete->getCompany()}}</td>
				<td>{{$jobToDelete->getYears()}}</td>
			</tr>		

		</tbody>

	</table>
	<form action="processDeleteUserJob" method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="idToDelete" value="{{$jobToDelete->getId()}}" />
		<button type="submit" class="btn btn-dark">Yes</button>
	</form>

	<a href="getProfile">No</a>
</div>
@endsection
