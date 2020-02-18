
@extends('layouts.appmasterLoggedIn')

@section('content')

<h2>New User Job</h2>

<form action="processCreateUserJob" method="POST">
{{ csrf_field() }}

<!-- <div class="form-group">
<img src="https://mdbootstrap.com/img/Photos/Avatars/img%20%2810%29.jpg" class="rounded-circle">
</div> -->

<div class="form-group">
<label for="title">Title</label>
<input style="width: 30%" type="text" class="form-control" id="title" placeholder="Title" name="title">
{{$errors->first('title')}}
</div>

<div class="form-group">
<label for="company">Company</label>
<input style="width: 30%" type="text" class="form-control" id="company" placeholder="Company" name="company">
{{$errors->first('company')}}
</div>

<div class="form-group">
<label for="years">Years</label>
<input style="width: 30%" type="text" class="form-control" id="years" placeholder="Years" name="years">
{{$errors->first('years')}}
</div>

<button type="submit" class="btn btn-dark">Save</button>

</form>
@endsection