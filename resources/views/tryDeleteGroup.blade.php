<!-- This view displays a table with a single Job posting that may be deleted by pressing "yes". Pressing "no" brings the user back to the allJobPostings page -->
@extends('layouts.appmasterLoggedIn') 
@section('title', 'Try Delete Group')

@section('content')
@if(Session::get('sp')->getUser_id() == $groupToDelete->getOwner_id()) 
<div class="container">
	<h2>Delete Group</h2>
</div>
<div>
<h5>Are you sure you want to delete "{{$groupToDelete->getTitle()}}"?</h5>

	<form action="processDeleteGroup" method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="idToDelete" value= "{{$groupToDelete->getId()}}" />
		<button type="submit" class="btn btn-dark">Yes</button>
	</form>

	<form action="getGroup" method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="idToDisplay" value= "{{$groupToDelete->getId()}}" />
		<button type="submit" class="btn btn-dark">No</button>
	</form>
</div>
@else
<div class="container">
	<h2>You may not delete the group because you do not own it</h2>
</div>
@endif
@endsection
