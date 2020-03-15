<!-- Displays a form for the user to fill out to search for a job post -->
@extends('layouts.appmaster') 
@section('title', 'Search Job Postings')

@section('content') 
<div class="container">
	<h2>Search Job Postings</h2>
</div>

<div>
	<form action="processSearchPosts" method="POST">
		{{ csrf_field() }}
		<div class="form-group">
			<label for="title">Title</label> 
			<input style="width: 30%" type="text" class="form-control" id="title" placeholder="Title" name="title">
		</div>
		<div class="form-group">
			<label for="description">Description</label> 
			<input style="width: 30%" type="text" class="form-control" id="description" placeholder="Description" name="description">
		</div>
		<button type="submit" class="btn btn-dark">Search</button>
	</form>
</div>
@endsection