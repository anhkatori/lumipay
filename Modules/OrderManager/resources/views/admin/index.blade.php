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
    </div>
    <div class="card-body text-center overflow-auto">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Request ID</th>
                    <th>Amount</th>
                    <th>Email</th>
                    <th>IP</th>
                    <th>Description</th>
                    <!-- <th>Cancel URL</th>
                    <th>Return URL</th>
                    <th>Notify URL</th> -->
                    <th>Method</th>
                    <th>Status</th>
                    <th>Method Account</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->request_id }}</td>
                        <td>{{ $order->amount }}</td>
                        <td>{{ $order->email }}</td>
                        <td>{{ $order->ip }}</td>
                        <td>{{ $order->description }}</td>
                        <!-- <td>{{ $order->cancel_url }}</td>
                        <td>{{ $order->return_url }}</td>
                        <td>{{ $order->notify_url }}</td> -->
                        <td>{{ $order->method }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->method_account }}</td>
                        <td>
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
        });
    </script>
@endpush