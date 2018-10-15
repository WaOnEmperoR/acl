@extends('layouts.app')

@section('title', '| Payments')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <h1><i class="fa fa-users"></i> Payment Administration <a href="{{ route('payments.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="payments_table">
            <thead>
                <tr>
                    <th>Payment Submitted</th>
                    <th>Payment Verified</th>
                    <th>Payment verifier</th>
                    <th>Payment Session ID</th>
                    <th>Payment ID</th>
                    <th>User ID</th>
                </tr>
            </thead>
        </table>
    </div>

    <a href="{{ route('payments.create') }}" class="btn btn-success">Add payment</a>

</div>

<script>
    $(function() {
        $('#payments_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ url('payments/paymentsData') }}',
            columns: [
                { data: 'payment_submitted', name: 'payment_submitted' },
                { data: 'payment_verified', name: 'payment_verified' },
                { data: 'payment_verifier', name: 'payment_verifier' },
                { data: 'payment_session_id', name: 'payment_session_id' },
                { data: 'payment_type_id', name: 'payment_type_id' },
                { data: 'user_id', name: 'user_id' },
            ]
        });
    });
</script>
@endsection


