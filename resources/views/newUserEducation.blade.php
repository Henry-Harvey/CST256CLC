<!-- Displays a form for a creating new user education -->
@extends('layouts.appmaster')

@section('content')

<h2>New User Education</h2>

<form action="processCreateUserEducation" method="POST">
{{ csrf_field() }}

<div class="form-group">
<label for="school">School</label>
<input style="width: 30%" type="text" class="form-control" id="school" placeholder="School" name="school">
{{$errors->first('school')}}
</div>

<div class="form-group">
<label for="degree">Degree</label>
<input style="width: 30%" type="text" class="form-control" id="degree" placeholder="Degree" name="degree">
{{$errors->first('degree')}}
</div>

<div class="form-group">
<label for="years">Years</label>
<input style="width: 30%" type="text" class="form-control" id="years" placeholder="Years" name="years">
{{$errors->first('years')}}
</div>

<button type="submit" class="btn btn-dark">Save</button>

</form>
@endsection