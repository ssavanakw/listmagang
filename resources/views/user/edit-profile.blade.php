@extends('layouts.dashboard')

@section('content')
    <h2>Edit Profil Anda</h2>
    <form action="{{ route('user.updateProfile') }}" method="POST">
        @csrf
        
    </form>
@endsection

