@extends('layouts.global')
@section('title', 'Dashboaard')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Selamat Datang') }}</div>
                <div class="card-body">
                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
@endsection
