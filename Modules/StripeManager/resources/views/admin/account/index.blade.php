@extends('admintheme::layouts.main')

@section('title', 'Manage Stripe Accounts')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stripe Accounts</h3>
            <div class="card-tools">
                <a href="{{ route('admin.stripe-accounts.create') }}" class="btn btn-success">Add New Account</a>
            </div>
        </div>
        <div class="card-body text-center overflow-auto">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Domain</th>
                        <th>Max Receive Amount</th>
                        <th>Current Amount</th>
                        <th>Max Order Receive Amount</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stripeAccounts as $account)
                        <tr>
                            <td>{{ $account->id }}</td>
                            <td>{{ $account->domain }}</td>
                            <td>{{ $account->max_receive_amount }}</td>
                            <td>{{ $account->current_amount }}</td>
                            <td>{{ $account->max_order_receive_amount }}</td>
                            <td>{{ $account->client ? $account->client->name : ''  }}</td>
                            <td>{{ $account->getStatus() }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                     <a href="{{ route('admin.stripe-accounts.edit', $account->id) }}" class="btn btn-primary me-1">Edit</a>
                                    <form action="{{ route('admin.stripe-accounts.destroy', $account->id) }}" method="POST" style="display:inline-block;">
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
            {{$stripeAccounts->links('admintheme::layouts.pagination')}}
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