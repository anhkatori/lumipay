@extends('admintheme::layouts.main')

@section('title', isset($paypalAccount) ? 'Edit PayPal Account' : 'Create PayPal Account')
@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger mx-3">{{$error}}</div>
    @endforeach
@endif

<div class="card m-3">
    <div class="card-header px-5">
        <a href="{{ route('admin.paypal-accounts.index') }}" class="btn float-end btn-danger"> Back</a>
    </div>
    <form
        action="{{ isset($paypalAccount) ? route('admin.paypal-accounts.update', $paypalAccount->id) : route('admin.paypal-accounts.store') }}"
        method="POST">
        <div class="card-body p-5">
            @csrf
            @if(isset($paypalAccount))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label" for="email">PayPal Email</label>
                        <input type="text" class="form-control @error('email')is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', isset($paypalAccount) ? $paypalAccount->email : '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label" for="password">PayPal Password</label>
                        <input type="text" class="form-control @error('password')is-invalid @enderror" id="password"
                            name="password"
                            value="{{ old('password', isset($paypalAccount) ? utf8_decode($paypalAccount->password) : '') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label" for="email">PayPal Client Key</label>
                        <input type="text" class="form-control @error('proxy')is-invalid @enderror" id="client_key"
                            name="client_key"
                            value="{{ old('client_key', isset($paypalAccount) ? $paypalAccount->client_key : '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label" for="password">PayPal Secret Key</label>
                        <input type="text" class="form-control @error('proxy')is-invalid @enderror" id="secret_key"
                            name="secret_key"
                            value="{{ old('secret_key', isset($paypalAccount) ? $paypalAccount->secret_key : '') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="form-label" for="seller">Seller</label>
                        <input type="text" class="form-control @error('seller')is-invalid @enderror" id="seller"
                            name="seller"
                            value="{{ old('seller', isset($paypalAccount) ? $paypalAccount->seller : '') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="form-label" for="domain_site_fake">Domain Site Fake</label>
                        <input type="text" class="form-control @error('domain_site_fake')is-invalid @enderror"
                            id="domain_site_fake" name="domain_site_fake"
                            value="{{ old('domain_site_fake', isset($paypalAccount) ? $paypalAccount->domain_site_fake : '') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label" for="proxy">Proxy</label>
                        <input type="text" class="form-control @error('proxy')is-invalid @enderror" id="proxy"
                            name="proxy" value="{{ old('proxy', isset($paypalAccount) ? $paypalAccount->proxy : '') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-3">
                        <label class="form-label" for="domain_status">Domain Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input @error('domain_status')is-invalid @enderror"
                                style="height: 30px;width: 60px;" type="checkbox" id="domain_status"
                                name="domain_status" {{ old('domain_status', isset($paypalAccount) ? $paypalAccount->domain_status : '0') ? 'checked' : '' }}>
                            <label style="margin: 5px;" id="domain-status-label" class="form-check-label"
                                for="domain_status">
                                {{ old('domain_status', isset($paypalAccount) ? $paypalAccount->domain_status : '0') ? 'ON' : 'OFF' }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="{{ isset($paypalAccount->id) ? 'col-md-3' : 'd-none' }}">
                    <div class="form-group mb-3">
                        <label class="form-label" for="active_amount">Active Amount</label>
                        <input type="number" step="0.01"
                            class="form-control @error('active_amount')is-invalid @enderror" id="active_amount"
                            name="active_amount"
                            value="{{ old('active_amount', isset($paypalAccount) ? $paypalAccount->active_amount : '0') }}">
                    </div>
                </div>
                <div class="{{ isset($paypalAccount->id) ? 'col-md-3' : 'd-none' }}">
                    <div class="form-group mb-3">
                        <label class="form-label" for="hold_amount">Hold Amount</label>
                        <input type="number" step="0.01" class="form-control @error('hold_amount')is-invalid @enderror"
                            id="hold_amount" name="hold_amount"
                            value="{{ old('hold_amount', isset($paypalAccount) ? $paypalAccount->hold_amount : '0') }}">
                    </div>
                </div>
                <div class="{{ isset($paypalAccount->id) ? 'col-md-3' : 'col-md-6' }}">
                    <div class="form-group mb-3">
                        <label class="form-label" for="max_receive_amount">Max Receive Amount</label>
                        <input type="number" step="0.01"
                            class="form-control @error('max_receive_amount')is-invalid @enderror"
                            id="max_receive_amount" name="max_receive_amount"
                            value="{{ old('max_receive_amount', isset($paypalAccount) ? $paypalAccount->max_receive_amount : '') }}">
                    </div>
                </div>
                <div class="{{ isset($paypalAccount->id) ? 'col-md-3' : 'col-md-6' }}">
                    <div class="form-group mb-3">
                        <label class="form-label" for="max_order_receive_amount">Max Order Amount</label>
                        <input type="number" step="0.01"
                            class="form-control @error('max_order_receive_amount')is-invalid @enderror"
                            id="max_order_receive_amount" name="max_order_receive_amount"
                            value="{{ old('max_order_receive_amount', isset($paypalAccount) ? $paypalAccount->max_order_receive_amount : '') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label" for="site_client">Site Client</label>
                        <input type="text" class="form-control @error('site_client')is-invalid @enderror"
                            id="site_client" name="site_client"
                            value="{{ old('site_client', isset($paypalAccount) ? $paypalAccount->site_client : '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label" for="client_ids">Clients</label>
                        <select class="form-control @error('client_ids')is-invalid @enderror" id="client_ids"
                            name="client_ids[]" multiple>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ isset($paypalAccount) && in_array($client->id, explode(',', $paypalAccount->client_ids)) ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="form-label" for="proxy">Remover</label>
                        <input type="text" class="form-control @error('proxy')is-invalid @enderror" id="remover"
                            name="remover"
                            value="{{ old('remover', isset($paypalAccount) ? $paypalAccount->remover : '') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="form-label" for="payment_method">Payment Type</label>
                        <select class="form-control @error('payment_method')is-invalid @enderror" id="payment_method"
                            name="payment_method">
                            @foreach ($paymentMethods as $key => $name)
                                <option value="{{$key}}" {{ old($key, isset($paypalAccount) ? $paypalAccount->payment_method : '') == $key ? 'selected' : '' }}>{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="form-label" for="status_id">Status</label>
                        <select class="form-control @error('status_id')is-invalid @enderror" id="status_id"
                            name="status_id">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}" {{ isset($paypalAccount) && $paypalAccount->status_id == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="form-label" for="xmdt_status">Status XMDT</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input @error('xmdt_status')is-invalid @enderror"
                                style="height: 30px;width: 60px;" type="checkbox" id="xmdt_status" name="xmdt_status" {{ isset($paypalAccount) && $paypalAccount->xmdt_status ? 'checked' : '' }}>
                            <label style="margin:5px" data-toggle="button">
                                <i class="fas fa-toggle-on"></i>
                                <i class="fas fa-toggle-off"></i>
                                <span
                                    id="xmdt-status-label">{{ isset($paypalAccount) && $paypalAccount->xmdt_status ? 'ON' : 'OFF' }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control @error('description')is-invalid @enderror" id="description"
                            name="description">{{ old('description', isset($paypalAccount) ? $paypalAccount->description : '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer p-3 px-5">
            <button type="submit"
                class="btn btn-primary float-end">{{ isset($paypalAccount) ? 'Update Account' : 'Create Account' }}</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('domain_status').addEventListener('change', function () {
        if (this.checked) {
            document.getElementById('domain-status-label').innerText = 'ON';
        } else {
            document.getElementById('domain-status-label').innerText = 'OFF';
        }
    });
    document.getElementById('xmdt_status').addEventListener('change', function () {
        if (this.checked) {
            document.getElementById('xmdt-status-label').innerText = 'ON';
        } else {
            document.getElementById('xmdt-status-label').innerText = 'OFF';
        }
    });
</script>

@endsection