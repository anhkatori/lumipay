@extends('admintheme::layouts.main')

@section('title', 'Blocked Email Manager')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Blocked Emails</h3>
        <div class="card-tools">
            <a href="{{ route('admin.blocked-email.create') }}" class="btn btn-success">Add New Blocked Email</a>
            <button class="btn btn-secondary" id="toggle-search-form">Show/Hide Search Form</button>
        </div>
    </div>
    <div class="card-body text-center overflow-auto">
        <div id="search-form" style="display: none;margin-bottom: 20px">
            <form action="{{ route('admin.blocked-email.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label style="float:left" for="email">Email:</label>
                        <input type="text" name="email" class="form-control" value="{{ request()->get('email') }}">
                    </div>
                    <div class="col-md-4">
                        <label style="float:left" for="status_lock">Locked Status:</label>
                        <select name="status_lock" class="form-control">
                            <option value="" {{ request()->get('status_lock') == '' ? 'selected' : '' }}>All</option>
                            <option value="1" {{ request()->get('status_lock') == '1' ? 'selected' : '' }}>Locked</option>
                            <option value="0" {{ request()->get('status_lock') == '0' ? 'selected' : '' }}>Not Locked
                            </option>
                        </select>
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
                    <th>Id</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Money Account</th>
                    <th>Money Bonus</th>
                    <th>Status Lock</th>
                    <th>Status Delete</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blockedEmails as $blockedEmail)
                    <tr>
                        <td>{{ $blockedEmail->id }}</td>
                        <td>{{ $blockedEmail->email }}</td>
                        <td>{{ $blockedEmail->name }}</td>
                        <td>{{ $blockedEmail->money_account }}</td>
                        <td>{{ $blockedEmail->money_bonus }}</td>
                        <td>
                            @if($blockedEmail->status_lock == 1)
                                <span class="text-danger">Locked</span>
                            @else
                                <span class="text-muted">Not Locked</span>
                            @endif
                        </td>
                        <td>
                            @if($blockedEmail->status_delete == 1)
                                <span class="text-primary">Okie</span>
                            @else
                                <span class="text-muted">None</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.blocked-email.edit', $blockedEmail->id) }}"
                                class="btn btn-primary">Edit</a>
                            <form action="{{ route('admin.blocked-email.destroy', $blockedEmail->id) }}" method="POST"
                                style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $blockedEmails->links() }}
    </div>
    <div class="card-footer clearfix">
        {{$blockedEmails->links('admintheme::layouts.pagination')}}
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