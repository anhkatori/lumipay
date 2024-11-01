@extends('admintheme::layouts.main')

@section('title', isset($client) ? 'Edit Client' : 'Create Client')

@section('content')
    <div class="container-fluid">
        @if($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger mx-3">{{$error}}</div>
            @endforeach
        @endif
        <div class="card ">
            <div class="card-header ">
                <a href="{{url()->previous()}}" class="btn float-end btn-danger"> Back</a>
            </div>
            <form action="{{ isset($client) ? route('admin.clients.update', $client->id) : route('admin.clients.store') }}" method="POST">
                <div class="card-body ">
                    @csrf
                    @if(isset($client))
                        @method('PUT')
                    @endif

                    <div class="form-group mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($client) ? $client->name : '') }}" >
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="username">User Name</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', isset($client) ? $client->username : '') }}" >
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
                    <div class="form-group mb-3">
                        <label class="form-label" for="address">Address</label>
                        <input type="text" step="0.01" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', isset($client) ? $client->address : '') }}" >
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-group d-flex align-items-center gap-3">
                            <label class="form-label mb-0" for="status">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input " style="height: 30px;width: 60px;" type="checkbox" id="status" name="status" {{ old('status', isset($client) &&  $client->status  ? 'checked' : '') }}>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="invoice_description">Invoice Description</label>
                        <textarea class="form-control @error('invoice_description') is-invalid @enderror" name="invoice_description" id="invoice_description">{{ old('invoice_description', isset($client) ? $client->invoice_description : '') }}</textarea>
                    </div>
                </div>
                <div class="card-footer p-3 ">
                    <button type="submit" class="float-end btn btn-primary">{{ isset($client) ? 'Update Client' : 'Create Client' }}</button>
                </div>
            </form>
        </div>
        @if(isset($client))
            <div class="row mt-4">
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header"><span class="">Paypal Accounts:</span></div>
                        <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($paypalAccounts as $paypalAccount)
                                <li class="list-group-item"><a href="{{route('admin.paypal-accounts.edit', ['paypal_account' => $paypalAccount])}}">{{$paypalAccount->domain_site_fake}}</a></li>
                            @endforeach
                        </ul>
                        </div>
                        <div class="card-footer clearfix">
                            {{$paypalAccounts->appends(['stripe_account' => $stripeAccounts->currentPage(),'airwallet_account' => $airwalletAccounts->currentPage() ])->links('admintheme::layouts.pagination')}}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header"><span class="">Stripe Accounts:</span></div>
                        <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($stripeAccounts as $stripeAccount)
                                <li class="list-group-item"><a href="{{route('admin.stripe-accounts.edit', ['stripe_account' => $stripeAccount])}}">{{$stripeAccount->domain}}</a></li>
                            @endforeach
                        </ul>
                        </div>
                        <div class="card-footer clearfix">
                            {{$stripeAccounts->appends(['papal_account' => $paypalAccounts->currentPage(), 'airwallet_account' => $airwalletAccounts->currentPage() ])->links('admintheme::layouts.pagination')}}
                        </div>                        
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header"><span class="">Airwallet Accounts:</span></div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach($airwalletAccounts as $airwalletAccount)
                                    <li class="list-group-item"><a href="{{route('admin.airwallet-accounts.edit', ['airwallet_account' => $airwalletAccount])}}">{{$airwalletAccount->domain}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer clearfix">
                            {{$airwalletAccounts->appends(['stripe_account' => $stripeAccounts->currentPage(), 'papal_account' => $paypalAccounts->currentPage() ])->links('admintheme::layouts.pagination')}}
                        </div>                        
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
