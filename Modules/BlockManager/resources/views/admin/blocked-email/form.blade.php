@extends('admintheme::layouts.main')

@section('title', isset($blockedEmail) ? 'Edit Blocked Email' : 'Create Blocked Email')

@section('content')
    @if($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger mx-3">{{$error}}</div>
        @endforeach
    @endif

    <div class="card mx-3 mt-3">
        <div class="card-header px-5">
            <a href="{{url()->previous()}}" class="btn float-end btn-danger"> Back</a>
        </div>
        <form
            action="{{ isset($blockedEmail) ? route('admin.blocked-email.update', $blockedEmail->id) : route('admin.blocked-email.store') }}"
            method="POST">
            <div class="card-body p-5">
                @csrf
                @if(isset($blockedEmail))
                    @method('PUT')
                @endif

                <div class="form-group mb-3">
                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                        value="{{ old('email', isset($blockedEmail) ? $blockedEmail->email : '') }}" required>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name', isset($blockedEmail) ? $blockedEmail->name : '') }}" required>
                </div>


                <div class="form-group mb-3">
                    <div class="row">
                        <div class="col-md-6 d-flex">
                            <label class="form-check-label" for="status_delete">Delete</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status_delete" name="status_delete" style="font-size: 30px;margin-left: -15px;margin-top: 0px;"
                                    {{ old('status_delete', isset($blockedEmail) ? $blockedEmail->status_delete : 0) == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer p-3 px-5">
                <button type="submit"
                    class="float-end btn btn-primary">{{ isset($blockedEmail) ? 'Update Blocked Email' : 'Create Blocked Email' }}</button>
            </div>
        </form>
    </div>
@endsection