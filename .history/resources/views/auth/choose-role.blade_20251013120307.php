@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Are you a student?</h1>
    <p>Please choose your role to continue.</p>

    <div>
        <a href="{{ route('student.exists') }}" class="btn btn-primary">Yes, I'm a student</a>
        <a href="{{ route('nonstudent.select') }}" class="btn btn-secondary">No, I'm not a student</a>
    </div>
    <x-return-to-welcome />
</div>
@endsection
