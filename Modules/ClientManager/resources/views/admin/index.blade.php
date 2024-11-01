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
                {{-- @if(request()->get('only-trashed'))
                    <a href="{{ route('admin.clients.index') }}" class="btn btn-primary">Account Manager</a>
                @else
                    <a href="{{ route('admin.clients.index', ['only-trashed' => 1]) }}" class="btn btn-danger">Account Removed</a>
                @endif --}}
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
                        <th>Merchant ID</th>
                        <th>Private key</th>
                        {{-- <th><a class="text-decoration-none" href="{{route('admin.clients.index', ['status' => request()->get('status') == 'asc' ? 'desc' : 'asc' ])}}">Status</a></th> --}}
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $account)
                        <tr>
                            <td class="align-middle">{{ $account->id }}</td>
                            <td class="align-middle">{{ $account->name }}</td>
                            <td class="align-middle">{{ $account->username }}</td>
                            <td class="align-middle">{{ $account->email }}</td>
                            <td class="align-middle">{{ $account->merchant_id }}</td>
                            <td class="align-middle">{{ $account->private_key }}</td>
                            <td class="align-middle">
                                <span class="badge {{ $account->status == 0 ? 'badge-success' : 'badge-danger' }}"
                                    style="background-color: {{ $account->status == 0 ? '#d9534f' : '#4CAF50' }};
                                                border-radius: 5px;">
                                    {{ $account->status == 0 ? 'Inactive' : 'active' }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex ">
                                    <a href="{{ route('admin.clients.edit', $account->id) }}" class="btn btn-primary me-1">Edit</a>
                                    @if($account->deleted_at)
                                        <form action="{{ route('admin.clients.restore', $account->id) }}" method="POST" >
                                            @csrf
                                            <button type="submit" class="btn btn-info text-white me-1">Restore</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.clients.destroy', $account->id) }}" method="POST" >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="remove-item btn btn-danger me-1">Delete</button>
                                        </form>
                                    @endif
                                    @if($account->status)
                                        <form action="{{ route('admin.clients.status', $account->id) }}" method="POST" >
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Inactive</button>
                                            <input type="hidden" name="status" value="0">
                                        </form>
                                    @else
                                        <form action="{{ route('admin.clients.status', $account->id) }}" method="POST" >
                                            @csrf
                                            <input type="hidden" name="status" value="1">
                                            <button type="submit" class="btn btn-primary">Active</button>
                                        </form>
                                    @endif

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