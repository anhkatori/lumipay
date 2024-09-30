@extends('admintheme::layouts.main')

@section('title', isset($airwalletAccount) ? 'Edit Airwallet Account' : 'Create Airwallet Account')

@section('content')
@if($errors->any())
    @foreach ($errors->ll() as $error)
        <div class="alert alert-danger mx-3">{{$error}}</div>
    @endforeach
@endif

<div class="card mx-3 mt-3">
    <div class="card-header px-5">
        <a href="{{url()->previous()}}" class="btn float-end btn-danger"> Back</a>
    </div>
    <form
        action="{{ isset($airwalletAccount) ? route('admin.airwallet-accounts.update', $airwalletAccount->id) : route('admin.airwallet-accounts.store') }}"
        method="POST">
        <div class="card-body p-5">
            @csrf
            @if(isset($airwalletAccount))
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-12 col-sm-3">
                    <div class="form-group mb-3">
                        <label class="form-label" for="domain">Domain</label>
                        <input type="text" class="form-control @error('domain') is-invalid @enderror" id="domain" name="domain"
                            value="{{ old('domain', isset($airwalletAccount) ? $airwalletAccount->domain : '') }}">
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="form-group mb-3">
                        <label class="form-label" for="max_receive_amount">Max Receive Amount</label>
                        <input type="number" step="0.01" class="form-control @error('max_receive_amount') is-invalid @enderror"
                            id="max_receive_amount" name="max_receive_amount"
                            value="{{ old('max_receive_amount', isset($airwalletAccount) ? $airwalletAccount->max_receive_amount : '') }}">
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="form-group mb-3">
                        <label class="form-label" for="max_order_receive_amount">Max Order Amount</label>
                        <input type="number" step="0.01"
                            class="form-control @error('max_order_receive_amount') is-invalid @enderror"
                            id="max_order_receive_amount" name="max_order_receive_amount"
                            value="{{ old('max_order_receive_amount', isset($airwalletAccount) ? $airwalletAccount->max_order_receive_amount : '') }}">
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="form-group mb-3">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                            @foreach (\Modules\AirwalletManager\App\Models\AirwalletAccount::STATUSES as $key => $status)
                                <option value="{{$key}}" {{ old('status', isset($airwalletAccount) ? $airwalletAccount->status : '') == $key ? 'selected' : '' }}>{{$status}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-12 @if(!isset($airwalletAccount)) d-none @endif">
                    <div class="form-group mb-3">
                        <label class="form-label" for="current_amount">Current Amount</label>
                        <input type="number" step="0.01" class="form-control @error('current_amount') is-invalid @enderror"
                            id="current_amount" name="current_amount"
                            value="{{ old('current_amount', isset($airwalletAccount) ? $airwalletAccount->current_amount : 0) }}">
                    </div>
                </div>
                <div class="col-12 col-sm-12">
                    <div class="form-group mb-3">
                        <label class="form-label" for="client_ids">Clients</label>
                        <select class="form-control @error('client_ids')is-invalid @enderror" id="client_ids"
                            name="client_ids[]" multiple>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ isset($airwalletAccount) && in_array($client->id, explode(',', $airwalletAccount->client_ids)) ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer p-3 px-5">
            <button type="submit"
                class="float-end btn btn-primary">{{ isset($airwalletAccount) ? 'Update Account' : 'Create Account' }}</button>
        </div>
    </form>
</div>
@endsection