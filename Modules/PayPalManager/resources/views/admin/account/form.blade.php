@extends('admintheme::layouts.main')

@section('title', isset($paypalAccount) ? 'Edit PayPal Account' : 'Create PayPal Account')

@section('content')
    @if($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger mx-3">{{$error}}</div>
        @endforeach
    @endif
    <div class="card m-3">
        <div class="card-header px-5">
            <a href="{{url()->previous()}}" class="btn float-end btn-danger"> Back</a>
        </div>
        <form action="{{ isset($paypalAccount) ? route('admin.paypal-accounts.update', $paypalAccount->id) : route('admin.paypal-accounts.store') }}" method="POST">
            <div class="card-body p-5">
                @csrf
                @if(isset($paypalAccount))
                    @method('PUT')
                @endif

                <div class="form-group mb-3">
                    <label class="form-label" for="email">PayPal Email</label>
                    <input type="text" class="form-control @error('email')is-invalid @enderror" id="email" name="email" value="{{ old('email', isset($paypalAccount) ? $paypalAccount->email : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="password">PayPal Password</label>
                    <input type="text" class="form-control @error('password')is-invalid @enderror" id="password" name="password" value="{{ old('password', isset($paypalAccount) ? $paypalAccount->password : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="domain_site_fake">Domain Site Fake</label>
                    <input type="text" class="form-control @error('domain_site_fake')is-invalid @enderror" id="domain_site_fake" name="domain_site_fake" value="{{ old('domain_site_fake', isset($paypalAccount) ? $paypalAccount->domain_site_fake : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="max_receive_amount">Max Receive Amount</label>
                    <input type="number" step="0.01" class="form-control @error('max_receive_amount')is-invalid @enderror" id="max_receive_amount" name="max_receive_amount" value="{{ old('max_receive_amount', isset($paypalAccount) ? $paypalAccount->max_receive_amount : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="active_amount">Active Amount</label>
                    <input type="number" step="0.01" class="form-control @error('active_amount')is-invalid @enderror" id="active_amount" name="active_amount" value="{{ old('active_amount', isset($paypalAccount) ? $paypalAccount->active_amount : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="hold_amount">Hold Amount</label>
                    <input type="number" step="0.01" class="form-control @error('hold_amount')is-invalid @enderror" id="hold_amount" name="hold_amount" value="{{ old('hold_amount', isset($paypalAccount) ? $paypalAccount->hold_amount : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="max_order_receive_amount">Max Order Amount</label>
                    <input type="number" step="0.01" class="form-control @error('max_order_receive_amount')is-invalid @enderror" id="max_order_receive_amount" name="max_order_receive_amount" value="{{ old('max_order_receive_amount', isset($paypalAccount) ? $paypalAccount->max_order_receive_amount : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="proxy">Proxy</label>
                    <input type="text" class="form-control @error('proxy')is-invalid @enderror" id="proxy" name="proxy" value="{{ old('proxy', isset($paypalAccount) ? $paypalAccount->proxy : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="days_stopped">Stop Receiving Days</label>
                    <input type="number" class="form-control @error('days_stopped')is-invalid @enderror" id="days_stopped" name="days_stopped" value="{{ old('days_stopped', isset($paypalAccount) ? $paypalAccount->days_stopped : '') }}" >
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="status_id">Status</label>
                    <select class="form-control @error('status_id')is-invalid @enderror" id="status_id" name="status_id" >
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" {{ isset($paypalAccount) && $paypalAccount->status_id == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="client_id">Client</label>
                    <select class="form-control @error('client_id')is-invalid @enderror" id="client_id" name="client_id" >
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ isset($paypalAccount) && $paypalAccount->client_id == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control @error('description')is-invalid @enderror" id="description" name="description">{{ old('description', isset($paypalAccount) ? $paypalAccount->description : '') }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="payment_method">Payment Type</label>
                    <select class="form-control @error('payment_method')is-invalid @enderror" id="payment_method" name="payment_method" >
                        @foreach ($paymentMethods as $key => $name)
                            <option value="{{$key}}" {{ old($key, isset($paypalAccount) ? $paypalAccount->payment_method : '') == $key ? 'selected' : '' }}>{{$name}}</option>
                        @endforeach
                    </select>
                </div>
    
            </div>
            <div class="card-footer p-3 px-5">
                <button type="submit" class="btn btn-primary float-end">{{ isset($paypalAccount) ? 'Update Account' : 'Create Account' }}</button>
            </div>
        </form>
    </div>
@endsection