@extends('admintheme::layouts.main')

@section('title', 'Manage PayPal Accounts')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<style>
    hr {
        color: #787878;
        width: 70%;
        text-align: center;
        margin: 10px auto;
    }
</style>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">PayPal Accounts</h3>
        <div class="card-tools">
            <a href="{{ route('admin.paypal-accounts.create') }}" class="btn btn-success">Add New Account</a>
            <button class="btn btn-secondary" id="toggle-search-form">Show/Hide Search Form</button>
        </div>
    </div>
    <div class="card-body text-center overflow-auto">
        <div id="search-form" style="display: none;margin-bottom: 20px">
            <form action="" method="GET">
                <div class="row">
                    <div class="row">
                        <div class="col-md-3">
                            <label style="float:left" for="email">Email:</label>
                            <input type="text" name="email" class="form-control" value="{{ request()->get('email') }}">
                        </div>
                        <div class="col-md-3">
                            <label style="float:left" for="proxy">Proxy:</label>
                            <input type="text" name="proxy" class="form-control" value="{{ request()->get('proxy') }}">
                        </div>
                        <div class="col-md-3">
                            <label style="float:left" for="status">Status:</label>
                            <select name="status" class="form-control">
                                <option value="">All</option>
                                @if(isset($statuses))
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->name }}" {{ request()->get('status') == $status->name ? 'selected' : '' }}>{{ $status->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label style="float:left" for="domain_site_fake">Domain Site Fake:</label>
                            <input type="text" name="domain_site_fake" class="form-control"
                                value="{{ request()->get('domain_site_fake') }}">
                        </div>
                        <div class="col-md-3">
                            <label style="float:left" for="client">Client:</label>
                            <select name="client[]" class="form-control" multiple>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" @if(in_array($client->id, $selectedClients)) selected
                                    @endif>{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label style="float:left" for="payment_type">Payment Type:</label>
                            <select name="payment_type" class="form-control">
                                <option value="">All</option>
                                @if(isset($paymentTypes))
                                    @foreach($paymentTypes as $key => $paymentType)
                                        <option value="{{ $key }}" {{ request()->get('payment_type') == $key ? 'selected' : '' }}>
                                            {{ $paymentType }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3" style="text-align:left">
                            <button type="submit" class="btn btn-primary pull-right"
                                style="margin-top:23px">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Acc Paypal</th>
                    <th>Domain - Client</th>
                    <th>Domain Status</th>
                    <th>Activate + Hold / Max Receive</th>
                    <th>Max Amount/Order</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Status XMDT</th>
                    <th>Stop Days</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paypalAccounts as $account)
                    <tr>
                        <td class="align-middle">
                            {{ $account->email }} 
                            @if($account->password)| @endif 
                            {{ utf8_decode($account->password) }}
                            <hr> {{ $account->seller }}
                            @if($account->proxy)| @endif
                            {{ $account->proxy }}
                        </td>
                        <td class="align-middle">{{ $account->domain_site_fake }}
                            <hr> {{ $account->site_client }}
                        </td>
                        <td class="align-middle">
                            <span class="badge {{ $account->domain_status ? 'badge-success' : 'badge-danger' }}" style="background-color: {{ $account->domain_status ? '#4CAF50' : '#d9534f' }};
                                         border-radius: 5px;">
                                {{ $account->domain_status ? 'ON' : 'OFF' }}
                            </span>
                        </td>
                        <td class="align-middle">({{ $account->active_amount }} + {{ $account->hold_amount }}) /
                            {{ $account->max_receive_amount }}
                        </td>
                        <td class="align-middle">{{ $account->max_order_receive_amount }}</td>
                        <td class="align-middle">
                            @if($account->status && $account->status->name == 'Work')
                                <span class="badge"
                                    style="background-color: #4CAF50;border-radius: 5px;">{{ $account->status->name }}</span>
                            @elseif($account->status && $account->status->name == 'Pending')
                                <span class="badge"
                                    style="background-color: #4c8faf;border-radius: 5px;">{{ $account->status->name }}</span>
                            @else
                                <span class="badge"
                                    style="background-color: #d9534f;border-radius: 5px;">{{ $account->status->name }}</span>
                            @endif
                        </td>
                        <td class="align-middle">{{ $account->getPaymentMethod() }}</td>
                        <td class="align-middle">
                            <span class="badge {{ !$account->xmdt_status ? 'badge-success' : 'badge-danger' }}" style="background-color: {{ !$account->xmdt_status ? '#4CAF50' : '#d9534f' }};
                                 border-radius: 5px;">
                                {{ !$account->xmdt_status ? 'OFF' : 'XMDT(' . floor(now()->diffInDays($account->xmdt_status)) . 'd)' }}
                            </span>
                        </td>
                        <td class="align-middle">
                            {{ $account->days_stopped ? floor(now()->diffInDays($account->days_stopped)) . ' days' : '' }}
                        </td>
                        <td class="align-middle">
                            <div class="d-flex">
                                <a href="{{ route('admin.paypal-accounts.edit', $account->id) }}"
                                    class="btn btn-primary me-2">Edit</a>
                                <form action="{{ route('admin.paypal-accounts.destroy', $account->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove-item btn btn-danger me-2">Delete</button>
                                </form>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#popup-modal-{{ $account->id }}">Sell</button>
                            </div>
                        </td>
                    </tr>
                    <div class="modal fade" id="popup-modal-{{ $account->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p class="modal-title">Sell paypal for email:
                                        {{ $account->email }}
                                    </p>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.paypal-accounts.sell') }}" method="POST">
                                        @csrf
                                        <input type="text" id="account-id" value={{$account->id}} name="account-id" hidden>
                                        <div class="mb-3 row">
                                            <label for="money" class="col-sm-2 col-form-label">Money <span
                                                    style="color: red">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="number" step="0.01" class="form-control" id="money"
                                                    name="money" required>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="buyer_email" class="col-sm-2 col-form-label">Buyer Email <span
                                                    style="color: red">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="buyer_email" name="buyer_email"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="buyer_name" class="col-sm-2 col-form-label">Buyer Name <span
                                                    style="color: red">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="buyer_name" name="buyer_name"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{$paypalAccounts->links('admintheme::layouts.pagination')}}
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", (event) => {
            $('.remove-item').on('click', function () {
                return confirm('Are you sure?');
            })
            $('#toggle-search-form').on('click', function () {
                $('#search-form').slideToggle(500);
            });
            var form = $('#search-form');
            var inputs = form.find('input, select');
            form.hide();
            inputs.each(function () {
                if ($(this).val() !== '') {
                    form.show();
                    return false;
                }
            });
        });
    </script>
@endpush