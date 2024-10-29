@extends('admintheme::layouts.main')

@section('title', 'Manage Airwallet Accounts')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Airwallet Accounts</h3>
        <div class="card-tools">
            <a href="{{ route('admin.airwallet-accounts.create') }}" class="btn btn-success">Add New Account</a>
        </div>
    </div>
    <div class="card-body text-center overflow-auto">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Domain</th>
                    <th>Current Amount</th>
                    <th>Max Receive </th>
                    <th>Max Amount / Order</th>
                    <th>Client</th>
                    <th>Status</th>
                    <th>Withdrawn</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($airwalletAccounts as $account)
                    <tr>
                        <td class="align-middle">{{ $account->id }}</td>
                        <td class="align-middle">{{ $account->domain }}</td>
                        <td class="align-middle">{{ $account->max_receive_amount }} $</td>
                        <td class="align-middle">{{ $account->current_amount }} $</td>
                        <td class="align-middle">{{ $account->max_order_receive_amount }} $</td>
                        <td class="align-middle">
                            @foreach ($account->clients as $client)
                                <div style="white-space:nowrap">
                                    {{ $client->name }}
                                </div>
                                @if(!$loop->last)<hr> @else <div class="pb-3"></div> @endif
                            @endforeach    
                        </td>
                        <td class="align-middle">
                            <span class="badge {{ $account->getStatus() == 1 ? 'badge-success' : 'badge-danger' }}" style="background-color: {{ $account->getStatus() == 'Active' ? '#4CAF50' : '#d9534f' }};
                                                border-radius: 5px;">
                                {{ $account->getStatus()}}
                            </span>
                        </td>
                        <td class="align-middle">{{ $account->getWithdrawn() }}</td>
                        <td class="align-middle">
                            <a href="{{ route('admin.airwallet-accounts.edit', $account->id) }}"
                                class="btn btn-primary">Edit</a>
                            <form action="{{ route('admin.airwallet-accounts.destroy', $account->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-item btn btn-danger">Delete</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#popup-modal-{{ $account->id }}">Sell</button>
                            <div class="modal fade" id="popup-modal-{{ $account->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <p class="modal-title">Sell airwallet for domain:
                                                {{ $account->domain }}
                                            </p>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('admin.airwallet-moneys.sell', $account->id) }}"
                                                method="POST">
                                                @csrf
                                                <div class="mb-3 row">
                                                    <label for="money" class="col-sm-2 col-form-label">Money <span
                                                            style="color: red">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="number" step="0.01" class="form-control" id="money"
                                                            name="money" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label for="buyer_email" class="col-sm-2 col-form-label">Buyer Email
                                                        <span style="color: red">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="email" class="form-control" id="buyer_email"
                                                            name="buyer_email" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label for="buyer_name" class="col-sm-2 col-form-label">Buyer Name <span
                                                            style="color: red">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="buyer_name"
                                                            name="buyer_name" required>
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
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{$airwalletAccounts->links('admintheme::layouts.pagination')}}
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", (event) => {
            $('.remove-item').on('click', function () {
                return confirm('Are you sure?');
            });

            $('form').on('submit', function(e) {
                try {
                    const submitButton = $(this).find('button[type="submit"]');
                    submitButton.prop('disabled', true);
                    submitButton.html('<span>Submitting...</span>');
                } catch (error) {
                    console.error('Error during form submission:', error);
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endpush