@extends('admintheme::layouts.main')

@section('title', 'Manage Airwallet Money')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Airwallet Money</h3>
        <div class="card-tools">
            <button class="btn btn-secondary" id="toggle-search-form">Show/Hide Search Form</button>
        </div>
    </div>
    <div class="card-body text-center overflow-auto">
        <div id="search-form" style="display: none;margin-bottom: 20px">
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label style="float:left" for="domain">Domain :</label>
                        <input type="text" name="domain" class="form-control"
                            value="{{ request()->get('domain') }}">
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
                    <th>Airwallet Domain</th>
                    <th>Status</th>
                    <th>Money</th>
                    <th>Buyer Email</th>
                    <th>Buyer Name</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentPage = $airwalletMoneys->currentPage();
                    $perPage = $airwalletMoneys->perPage();
                @endphp
                @foreach ($airwalletMoneys as $airwalletMoney)
                                <tr>
                                    <td class="align-middle">
                                        {{ ($currentPage - 1) * $perPage + $loop->iteration }}
                                    </td>
                                    <td class="align-middle">{{ $airwalletMoney->account_id }}</td>
                                    <td class="align-middle">{{ $airwalletMoney->domain }}</td>
                                    <td class="align-middle">                            
                                        <span class="badge {{ !$airwalletMoney->status ? 'badge-success' : 'badge-danger' }}" style="background-color: {{ !$airwalletMoney->status ? '#d9534f' : '#4CAF50' }};
                                        border-radius: 5px;">
                                            {{ !$airwalletMoney->status ? 'Hold' : 'Activate' }}
                                        </span>
                                    </td>
                                    <td class="align-middle">{{ $airwalletMoney->money }}</td>
                                    <td class="align-middle">{{ $airwalletMoney->buyer_email }}</td>
                                    <td class="align-middle">{{ $airwalletMoney->buyer_name }}</td>
                                    <td class="align-middle">{{ $airwalletMoney->created_at }}</td>
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                            data-bs-target="#popup-modal-{{ $airwalletMoney->id }}" {{ $airwalletMoney->status == 1 ? 'disabled' : '' }}>Edit</button>
                                    </td>
                                </tr>
                                <div class="modal fade" id="popup-modal-{{ $airwalletMoney->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <p class="modal-title">Edit airwallet money for domain: {{ $airwalletMoney->domain }}</p>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('admin.airwallet-moneys.update', $airwalletMoney->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" id="account-id" value={{$airwalletMoney->id}} name="account-id"
                                                        hidden>
                                                    <div class="mb-3 row">
                                                        <label for="money" class="col-sm-2 col-form-label">Money <span
                                                                style="color: red">*</span></label>
                                                        <div class="col-sm-10">
                                                            <input type="number" step="0.01" class="form-control" id="money"
                                                                name="money" value="{{ $airwalletMoney->money }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label for="buyer_email" class="col-sm-2 col-form-label">Buyer Email <span
                                                                style="color: red">*</span></label>
                                                        <div class="col-sm-10">
                                                            <input type="email" class="form-control" id="buyer_email" name="buyer_email"
                                                                value="{{ $airwalletMoney->buyer_email }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label for="buyer_name" class="col-sm-2 col-form-label">Buyer Name <span
                                                                style="color: red">*</span></label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="buyer_name" name="buyer_name"
                                                                value="{{ $airwalletMoney->buyer_name }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                                                        <div class="col-sm-2">
                                                            <div class="form-check form-switch d-flex align-items-center ms-4">
                                                                <input class="form-check-input" type="checkbox" id="status" style="font-size: x-large;float: inherit;" name=" status" {{ $airwalletMoney->status == 1 ? 'checked' : '' }}
                                                                    {{ $airwalletMoney->status == 1 ? 'disabled' : '' }}>
                                                                <label class="form-check-label mt-1 ms-2" id="status-label"
                                                                    for="status">{{ucfirst($airwalletMoney->getStatus())}}</label>
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
        {{$airwalletMoneys->links('admintheme::layouts.pagination')}}
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
        const statusInput = document.getElementById('status');
        const statusLabel = document.getElementById('status-label');

        statusInput.addEventListener('change', () => {
            if (statusInput.checked) {
                statusLabel.textContent = 'Activate';
            } else {
                statusLabel.textContent = 'Hold';
            }
        });

        if (statusInput.checked) {
            statusLabel.textContent = 'Activate';
        } else {
            statusLabel.textContent = 'Hold';
        }
    </script>
@endpush