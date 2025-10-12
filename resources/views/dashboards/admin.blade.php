@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Supervisor (Admin) Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}</p>
</div>
@endsection
