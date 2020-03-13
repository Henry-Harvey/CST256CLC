<!-- This view displays a warning message for leaving a group -->
@extends('layouts.appmaster') 
@section('title', 'Try Leave Group')

@section('content')
@if(Session::get('sp')->getUser_id() != $group->getOwner_id()) 
<div class="container">
	<h2>Leave Group</h2>
</div>
<div>
<h5>Are you sure you want to leave "{{$group->getTitle()}}"?</h5>

	<form action="processLeaveGroup" method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="groupid" value= "{{$group->getId()}}" />
		<button type="submit" class="btn btn-dark">Yes</button>
	</form>

	<form action="getGroup" method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="idToDisplay" value= "{{$group->getId()}}" />
		<button type="submit" class="btn btn-dark">No</button>
	</form>
</div>
@else
<div class="container">
	<h2>You may not leave the group because you created it. You may delete it</h2>
</div>
@endif
@endsection
