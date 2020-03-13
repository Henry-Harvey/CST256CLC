<!-- Displays a form for a creating new user skill -->
@extends('layouts.appmaster')

@section('content')

<h2>New User Skill</h2>

<form action="processCreateUserSkill" method="POST">
{{ csrf_field() }}

<div class="form-group">
<label for="skill">Skill</label>
<input style="width: 30%" type="text" class="form-control" id="skill" placeholder="Skill" name="skill">
{{$errors->first('title')}}
</div>

<button type="submit" class="btn btn-dark">Save</button>

</form>
@endsection