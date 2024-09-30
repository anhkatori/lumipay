@extends('admintheme::layouts.main')

@section('title', 'Block IP Manager')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Blocked IPs</h3>
        <div class="card-tools">
        </div>
    </div>
    <div class="card-body text-center overflow-auto">
        <form action="{{ route('admin.blocked-ip.store') }}" method="POST" class="d-flex mb-3">
            @csrf
            <div class="form-group mr-2 w-25 d-flex justify-content-between">
                <label for="ip_ban" class="mr-2 w-25">IP Ban <span class="text-danger">*</span></label>
                <input type="text" class="form-control w-75" id="ip_ban" name="ip_ban" required>
            </div>
            <div class="form-group mr-2 w-25 d-flex justify-content-between">
                <label for="sort_ip" class="mr-2 w-25">Sort IP <span class="text-danger">*</span></label>
                <input type="text" class="form-control w-75" id="sort_ip" name="sort_ip" required>
            </div>
            <button type="submit" class="btn btn-success mx-5">Create</button>
        </form>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>IP Ban</th>
                    <th>Sort IP</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <form action="{{ route('admin.blocked-ip.index') }}" method="GET">
                        <td class="align-middle"></td>
                        <td class="align-middle">
                            <input type="text" class="form-control" id="search_ip_ban" name="search_ip_ban"
                                value="{{ request()->input('search_ip_ban') }}" placeholder="Search IP Ban">
                        </td>
                        <td class="align-middle"></td>
                        <td class="align-middle">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </td>
                    </form>
                </tr>
                @foreach ($blockedIps as $blockedIp)
                    <tr>
                        <td class="align-middle">{{ $blockedIp->id }}</td>
                        <td class="align-middle">{{ $blockedIp->ip_ban }}</td>
                        <td class="align-middle">{{ $blockedIp->sort_ip }}</td>
                        <td class="align-middle">
                            <form action="{{ route('admin.blocked-ip.destroy', $blockedIp->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-item btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{$blockedIps->links('admintheme::layouts.pagination')}}
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", (event) => {
            $('.remove-item').on('click', function () {
                return confirm('Are you sure?');
            })
        });
    </script>
@endpush