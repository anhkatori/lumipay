@extends('clientmanager::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('clientmanager.name') !!}</p>
@endsection
