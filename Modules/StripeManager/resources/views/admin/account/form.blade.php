@extends('admintheme::layouts.main')

@section('title', isset($stripeAccount) ? 'Edit Stripe Account' : 'Create Stripe Account')

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
        <form action="{{ isset($stripeAccount) ? route('admin.stripe-accounts.update', $stripeAccount->id) : route('admin.stripe-accounts.store') }}" method="POST">
            <div class="card-body p-5">
                @csrf
                @if(isset($stripeAccount))
                    @method('PUT')
                @endif

                <div class="form-group mb-3">
                    <label class="form-label" for="domain">Domain</label>
                    <input type="text" class="form-control @error('domain') is-invalid @enderror" id="domain" name="domain" value="{{ old('domain', isset($stripeAccount) ? $stripeAccount->domain : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="max_receive_amount">Max Receive Amount</label>
                    <input type="number" step="0.01" class="form-control @error('max_receive_amount') is-invalid @enderror" id="max_receive_amount" name="max_receive_amount" value="{{ old('max_receive_amount', isset($stripeAccount) ? $stripeAccount->max_receive_amount : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="current_amount">Current Amount</label>
                    <input type="number" step="0.01" class="form-control @error('current_amount') is-invalid @enderror" id="current_amount" name="current_amount" value="{{ old('current_amount', isset($stripeAccount) ? $stripeAccount->current_amount : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="max_order_receive_amount">Max Order Amount</label>
                    <input type="number" step="0.01" class="form-control @error('max_order_receive_amount') is-invalid @enderror" id="max_order_receive_amount" name="max_order_receive_amount" value="{{ old('max_order_receive_amount', isset($stripeAccount) ? $stripeAccount->max_order_receive_amount : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" >
                        @foreach (\Modules\StripeManager\App\Models\StripeAccount::STATUSES as $key => $status)
                            <option value="{{$key}}" {{ old('status', isset($stripeAccount) ? $stripeAccount->status : '') == $key ? 'selected' : '' }}>{{$status}}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="card-footer p-3 px-5">
                <button type="submit" class="float-end btn btn-primary">{{ isset($stripeAccount) ? 'Update Account' : 'Create Account' }}</button>
            </div>
        </form>
    </div>
@endsection
