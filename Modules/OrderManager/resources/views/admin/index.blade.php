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
        <div id="search-form" style="display: none;margin-bottom: 20px">
            <form action="" method="GET">
                <div class="row">
                    <div class="row">
                        <div class="col-md-3">
                            <label style="float:left" for="request_id">Request Id:</label>
                            <input type="text" name="request_id" class="form-control"
                                value="{{ request()->get('request_id') }}">
                        </div>
                        <div class="col-md-3">
                            <label style="float:left" for="email">Email:</label>
                            <input type="text" name="email" class="form-control" value="{{ request()->get('email') }}">
                        </div>
                        <div class="col-md-3">
                            <label style="float:left" for="method_account">Method Account:</label>
                            <input type="text" name="method_account" class="form-control"
                                value="{{ request()->get('method_account') }}">
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
                    <th>ID</th>
                    <th>Request ID</th>
                    <th>Amount</th>
                    <th>Email</th>
                    <th>IP</th>
                    <th>Additional</th>
                    <th>Description</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Method Account</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                                <tr>
                                    <td class="align-middle">{{ $order->id }}</td>
                                    <td class="align-middle">{{ $order->request_id }}</td>
                                    <td class="align-middle">{{ $order->amount }}</td>
                                    <td class="align-middle">{{ $order->email }}</td>
                                    <td class="align-middle">{{ $order->ip }}</td>
                                    <td class="align-middle">{{ $order->addtional }}</td>
                                    <td class="align-middle">{{ $order->description }}</td>
                                    <td class="align-middle">
                                        {{ [
                        'PAYPAL' => 'Paypal',
                        'CREDIT_CARD' => 'Stripe',
                        'CREDIT_CARD_2' => 'Airwallet',
                    ][$order->method] ?? 'Unknown' }}
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge {{ $order->status == 'processing' ? 'badge-success' : 'badge-danger' }}"
                                            style="background-color: {{ $order->status == 'processing' ? '#4c8faf' : '#4CAF50' }};
                                                                            border-radius: 5px;">
                                            {{ $order->status}}
                                        </span>
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
                                        <div>
                                            <form action="{{ route('admin.ordermanager.destroy', $order->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="remove-item btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                @endforeach
            </tbody>

        </table>
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