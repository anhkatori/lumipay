@extends('admintheme::layouts.main')

@section('title', 'Client Manager')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Client Manager</h3>
            <div class="card-tools">
                <a href="{{ route('admin.clients.create') }}" class="btn btn-success">Add New Account</a>
            </div>
        </div>
        <div class="card-body text-center overflow-auto">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Merchant ID</th>
                        <th>Public key</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $account)
                        <tr>
                            <td>{{ $account->id }}</td>
                            <td>{{ $account->name }}</td>
                            <td>{{ $account->username }}</td>
                            <td>{{ $account->email }}</td>
                            <td>{{ $account->phone }}</td>
                            <td>{{ $account->address }}</td>
                            <td>{{ $account->merchant_id }}</td>
                            <td>{{ $account->public_key }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.clients.edit', $account->id) }}" class="btn btn-primary me-1">Edit</a>
                                    <form action="{{ route('admin.clients.destroy', $account->id) }}" method="POST" style="display:inline-block;">
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
            {{$clients->links('admintheme::layouts.pagination')}}
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