@extends('admintheme::layouts.main')

@section('title', 'Order Manager')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Order Manager</h3>
        <div class="card-tools">
            <button class="btn btn-secondary" id="toggle-search-form">Show/Hide Search Form</button>
        </div>
    </div>
    <div class="card-body text-center overflow-auto">
        <div id="search-form" style="margin-bottom: 20px">
            <form action="" method="GET">
                <div class="row">
                    <div class="row">

                        <div class="col-md-2">
                            <label style="float:left" for="request_id">Order Id:</label>
                            <input type="text" name="request_id" class="form-control"
                                value="{{ request()->get('request_id') }}">
                        </div>
                        <div class="col-md-2">
                            <label style="float:left" for="email">Email:</label>
                            <input type="text" name="email" class="form-control" value="{{ request()->get('email') }}">
                        </div>
                        <div class="col-md-2">
                            <label style="float:left" for="method_account">Method Account:</label>
                            <input type="text" name="method_account" class="form-control"
                                value="{{ request()->get('method_account') }}">
                        </div>
                        <div class="col-md-2">
                            <label style="float:left" for="status">Status:</label>
                            <select class="form-control" name="status">
                                @foreach ($availableStatuses as $status => $label)
                                    <option 
                                        @if (request()->get('status') === $status) 
                                            selected
                                        @endif 
                                        value="{{$status}}"
                                    >
                                    @php
                                        echo $label
                                    @endphp 
                                    </option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class="col-md-4" style="text-align:left">
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
                    <th>ID</th>
                    <th>Order ID</th>
                    <th>Amount</th>
                    <th>Email</th>
                    <th>IP</th>
                    <th>Method</th>
                    <th>Method Account</th>
                    <th>Method Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                                <tr>
                                    <td class="align-middle">{{ $order->id }}</td>
                                    <td class="align-middle">{{ $order->request_id }}</td>
                                    <td class="align-middle">{{ round($order->amount, 2) }}$</td>
                                    <td class="align-middle">{{ $order->email }}</td>
                                    <td class="align-middle">{{ $order->ip }}</td>
                                    <td class="align-middle">
                                        {{ [
                        'PAYPAL' => 'Paypal',
                        'CREDIT_CARD' => 'Stripe',
                        'CREDIT_CARD_2' => 'Airwallet',
                    ][$order->method] ?? 'Unknown' }}
                                    </td>
                                    
                                    <td class="align-middle">
                                        @if($order->method == 'PAYPAL')
                                            {{ $order->paypalAccount->email ?? '' }}
                                        @elseif($order->method == 'CREDIT_CARD')
                                            {{ $order->stripeAccount->domain ?? '' }}
                                        @elseif($order->method == 'CREDIT_CARD_2')
                                            {{ $order->airwalletAccount->domain ?? '' }}
                                        @else
                                            {{ $order->method_account }}
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($order->method == 'PAYPAL')
                                            {{ $order->paypalAccount->payment_method ?? '' }}
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge {{ $order->status == 'processing' ? 'badge-success' : 'badge-danger' }}"
                                            style="background-color: {{ $order->status == 'processing' ? '#4c8faf' : '#4CAF50' }};
                                                                            border-radius: 5px;">
                                            {{ $order->getOrderStatusLabel($order->status)}}
                                        </span>
                                    </td>
                                    <td class="align-middle row">
                                        
                                            <form class="col-sm-4" action="{{ route('admin.ordermanager.destroy', $order->id) }}" method="POST"
                                                style="display:inline-flex;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="remove-item btn btn-danger">Delete</button>
                                            </form>
                                            @if ($order->status == 'dispute')
                                                <form class="col-sm-4" action="{{ route('admin.ordermanager.closeDispute', $order->id) }}" method="POST"
                                                    style="display:inline-flex;">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="dispute-item btn btn-warning">Close Dispute</button>
                                                </form>
                                            @endif
                                            
                                            @if ($order->status == 'processing')
                                                <form class="col-sm-4" action="{{ route('admin.ordermanager.dispute', $order->id) }}" method="POST"
                                                    style="display:inline-flex;">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="dispute-item btn btn-warning">Dispute</button>
                                                </form>
                                            @endif
                                            <div class="col-sm-4" style="display:inline-flex;">
                                                <button type="button" class="view-btn btn btn-primary" data-bs-toggle="modal" data-bs-target="#popup-order-{{ $order->id }}">
                                                    View
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                @endforeach
            </tbody>

        </table>

        
        @foreach ($orders as $order)
        <div class="modal fade" id="popup-order-{{ $order->id }}" tabindex="-1" aria-labelledby="View Order" aria-hidden="true">
            <div class="modal-dialog">
                <table class="table table-bordered table-hover table-popup"  >
                    <thead>
                        <tr><td colspan="2"><h4>Order details</h4></td></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <td class="align-middle">{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <th>Order ID</th>
                            <td class="align-middle">{{ $order->request_id }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td class="align-middle">{{ round($order->amount, 2) }}$</td> 
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td class="align-middle">{{ $order->email }}</td>
                        </tr>
                        <tr>
                            <th>IP</th>
                            <td class="align-middle">{{ $order->ip }}</td>
                        </tr>
                        <tr>
                            <th>Additional</th>
                            <td class="align-middle">{{ $order->addtional }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td class="align-middle">{{ $order->description }}</td>
                        </tr>
                        <tr>
                            <th>Method</th>
                            <td class="align-middle">
                                {{ [
                'PAYPAL' => 'Paypal',
                'CREDIT_CARD' => 'Stripe',
                'CREDIT_CARD_2' => 'Airwallet',
            ][$order->method] ?? 'Unknown' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Method Account</th>
                            <td class="align-middle">
                                @if($order->method == 'PAYPAL')
                                    {{ $order->paypalAccount->email ?? '' }}
                                @elseif($order->method == 'CREDIT_CARD')
                                    {{ $order->stripeAccount->domain ?? '' }}
                                @elseif($order->method == 'CREDIT_CARD_2')
                                    {{ $order->airwalletAccount->domain ?? '' }}
                                @else
                                    {{ $order->method_account }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td class="align-middle">
                                <span class="badge {{ $order->status == 'processing' ? 'badge-success' : 'badge-danger' }}"
                                    style="background-color: {{ $order->status == 'processing' ? '#4c8faf' : '#4CAF50' }};
                                                                    border-radius: 5px;">
                                    {{ $order->status}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td class="align-middle">
                                {{(new DateTime($order->created_at))->format('d-m-Y H:i');}}
                             
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2 >
                                <button type="button" class="col-sm-4 close-item btn btn-primary">Close</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
    <div class="card-footer clearfix">
        {{$orders->links('admintheme::layouts.pagination')}}
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
                $('#search-form').slideToggle(300);
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

            $('.view-btn').on('click', function() {
                let id = $(this).data('bs-target');
                    $(id).toggleClass('show')
            })
        });
    </script>

@endpush