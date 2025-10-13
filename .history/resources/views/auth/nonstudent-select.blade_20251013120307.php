@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Select your role</h1>
    <p>If you are not a student, choose whether you are a Supervisor (Admin) or Super Admin.</p>

    <div>
        <a href="{{ route('login') }}?role=admin" class="btn btn-primary">Supervisor (Admin)</a>
        <a href="{{ route('login') }}?role=super_admin" class="btn btn-secondary">Super Admin</a>
    </div>
    <x-return-to-welcome />
</div>
@endsection
