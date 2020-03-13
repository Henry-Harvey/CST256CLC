@extends('layouts.appmaster')
@section('title', 'Appied')

@section('content')
	<h2>Applied</h2>
	<p>You have applied to be a {{$post->getTitle()}}</p>
@endsection