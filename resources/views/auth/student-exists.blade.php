@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Do you have an existing account?</h1>
    <div>
        <a href="{{ route('login') }}" class="btn btn-primary">Yes — Login</a>
        <a href="{{ route('register') }}" class="btn btn-secondary">No — Sign up</a>
    </div>
    <x-return-to-welcome />
</div>
@endsection
