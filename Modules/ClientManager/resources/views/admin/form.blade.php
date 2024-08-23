@extends('admintheme::layouts.main')

@section('title', isset($client) ? 'Edit Client' : 'Create Client')

@section('content')
    @if($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger mx-3">{{$error}}</div>
        @endforeach
    @endif
    <div class="card mx-3">
        <div class="card-header px-5">
            <a href="{{url()->previous()}}" class="btn float-end btn-danger"> Back</a>
        </div>
        <form action="{{ isset($client) ? route('admin.clients.update', $client->id) : route('admin.clients.store') }}" method="POST">
            <div class="card-body p-5">
                @csrf
                @if(isset($client))
                    @method('PUT')
                @endif

                <div class="form-group mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($client) ? $client->name : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input type="text" step="0.01" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', isset($client) ? $client->email : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="phone">Phone</label>
                    <input type="text" step="0.01" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', isset($client) ? $client->phone : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="address">Address</label>
                    <input type="text" step="0.01" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', isset($client) ? $client->address : '') }}" >
                </div>
            </div>
            <div class="card-footer p-3 px-5">
                <button type="submit" class="float-end btn btn-primary">{{ isset($client) ? 'Update Client' : 'Create Client' }}</button>
            </div>
        </form>
    </div>
@endsection
