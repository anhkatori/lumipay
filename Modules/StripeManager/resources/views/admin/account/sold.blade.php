@extends('admintheme::layouts.main')

@section('title', 'Manage Stripe Money')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Stripe Money</h3>
        <div class="card-tools">
            <button class="btn btn-secondary" id="toggle-search-form">Show/Hide Search Form</button>
        </div>
    </div>
    <div class="card-body text-center overflow-auto">
        <div id="search-form" style="display: none;margin-bottom: 20px">
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label style="float:left" for="stripe_domain">Stripe Domain:</label>
                        <input type="text" name="stripe_domain" class="form-control"
                            value="{{ request()->get('stripe_domain') }}">
                    </div>
                    <div class="col-md-4">
                        <label style="float:left" for="buyer_email">Buyer Email:</label>
                        <input type="text" name="buyer_email" class="form-control"
                            value="{{ request()->get('buyer_email') }}">
                    </div>
                    <div class="col-md-4" style="text-align:left">
                        <button type="submit" class="btn btn-primary pull-right" style="margin-top:23px">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Account ID</th>
                    <th>Stripe Domain</th>
                    <th>Status</th>
                    <th>Money</th>
                    <th>Buyer Email</th>
                    <th>Buyer Name</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stripeMoneys as $stripeMoney)
                                <tr>
                                    <td class="align-middle">
                                        {{ ($stripeMoneys->currentPage() - 1) * $stripeMoneys->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="align-middle">{{ $stripeMoney->account_id }}</td>
                                    <td class="align-middle">{{ $stripeMoney->stripe_domain }}</td>
                                    <td class="align-middle">                            
                                        <span class="badge {{ !$stripeMoney->status ? 'badge-success' : 'badge-danger' }}" style="background-color: {{ !$stripeMoney->status ? '#d9534f' : '#4CAF50' }};
                                        border-radius: 5px;">
                                            {{ !$stripeMoney->status ? 'Hold' : 'Activate' }}
                                        </span>
                                    </td>
                                    <td class="align-middle">{{ $stripeMoney->money }}</td>
                                    <td class="align-middle">{{ $stripeMoney->buyer_email }}</td>
                                    <td class="align-middle">{{ $stripeMoney->buyer_name }}</td>
                                    <td class="align-middle">{{ $stripeMoney->created_at }}</td>
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                            data-bs-target="#popup-modal-{{ $stripeMoney->id }}" {{ $stripeMoney->status == 1 ? 'disabled' : '' }}>Edit</button>
                                    </td>
                                </tr>
                                <div class="modal fade" id="popup-modal-{{ $stripeMoney->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <p class="modal-title">Edit stripe money for email: {{ $stripeMoney->stripe_email }}</p>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('admin.stripe-moneys.sold-update', $stripeMoney->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" id="account-id" value={{$stripeMoney->id}} name="account-id"
                                                        hidden>
                                                    <div class="mb-3 row">
                                                        <label for="money" class="col-sm-2 col-form-label">Money <span
                                                                style="color: red">*</span></label>
                                                        <div class="col-sm-10">
                                                            <input type="number" step="0.01" class="form-control" id="money"
                                                                name="money" value="{{ $stripeMoney->money }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label for="buyer_email" class="col-sm-2 col-form-label">Buyer Email <span
                                                                style="color: red">*</span></label>
                                                        <div class="col-sm-10">
                                                            <input type="email" class="form-control" id="buyer_email" name="buyer_email"
                                                                value="{{ $stripeMoney->buyer_email }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label for="buyer_name" class="col-sm-2 col-form-label">Buyer Name <span
                                                                style="color: red">*</span></label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="buyer_name" name="buyer_name"
                                                                value="{{ $stripeMoney->buyer_name }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label for="status" class="col-sm-2 col-form-label">Activate</label>
                                                        <div class="col-sm-2">
                                                            <div class="form-check form-switch d-flex align-items-center ms-4">
                                                                <input class="form-check-input" type="checkbox" id="status" style="font-size: x-large;float: inherit;" name="status" {{ $stripeMoney->status == 1 ? 'checked' : '' }}
                                                                    {{ $stripeMoney->status == 1 ? 'disabled' : '' }}>
                                                            </div>
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
        {{$stripeMoneys->links('admintheme::layouts.pagination')}}
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", (event) => {
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