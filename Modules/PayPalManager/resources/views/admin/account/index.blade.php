@extends('admintheme::layouts.main')

@section('title', 'Manage PayPal Accounts')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">PayPal Accounts</h3>
            <div class="card-tools">
                <a href="{{ route('admin.paypal-accounts.create') }}" class="btn btn-success">Add New Account</a>
            </div>
        </div>
        <div class="card-body text-center overflow-auto">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Domain site fake</th>
                        <th>Max Receive Amount</th>
                        <th>Active Amount</th>
                        <th>Hold Amount</th>
                        <th>Max Order Amount</th>
                        <th>Proxy</th>
                        <th>Status</th>
                        <th>Client</th>
                        <th>Days Stopped</th>
                        <th>Payment Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paypalAccounts as $account)
                        <tr>
                            <td>{{ $account->id }}</td>
                            <td>{{ $account->email }}</td>
                            <td>{{ $account->domain_site_fake }}</td>
                            <td>{{ $account->max_receive_amount }}</td>
                            <td>{{ $account->active_amount }}</td>
                            <td>{{ $account->hold_amount }}</td>
                            <td>{{ $account->max_order_receive_amount }}</td>
                            <td>{{ $account->proxy }}</td>
                            <td>{{ $account->status ? $account->status->name : '' }}</td>
                            <td>{{ $account->client ? $account->client->name : '' }}</td>
                            <td>{{ $account->days_stopped }}</td>
                            <td>{{ $account->getPaymentMethod() }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.paypal-accounts.edit', $account->id) }}" class="btn btn-primary me-2">Edit</a>
                                    <form action="{{ route('admin.paypal-accounts.destroy', $account->id) }}" method="POST" style="display:inline-block;">
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
            {{$paypalAccounts->links('admintheme::layouts.pagination')}}
        </div>
    </div>
@endsection
@push('scripts')
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", (event) => {
    $('.remove-item').on('click', function(){
        return confirm('Are you sure?');
    })
});
</script>
@endpush