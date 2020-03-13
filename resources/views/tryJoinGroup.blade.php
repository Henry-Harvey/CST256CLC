<!-- This view displays a warning message for joining a group -->
@extends('layouts.appmaster') 
@section('title', 'Try Join Group')

@section('content')
<div class="container">
	<h2>Join Group</h2>
</div>
<div>
<h5>Are you sure you want to join "{{$group->getTitle()}}"?</h5>

	<form action="processJoinGroup" method="POST">
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
@endsection
