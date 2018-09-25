@extends('layouts.app')

@section('title', '| Payments')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <h1><i class="fa fa-users"></i> Payment Administration <a href="{{ route('payments.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Payment Submitted</th>
                    <th>Payment Verified</th>
                    <th>Payment verifier</th>
                    <th>Image File Proof</th>
                    <th>Text File Proof</th>
                    <th>Payment Session Name</th>
                    <th>Payment Type Name</th>
                    <th>User Name</th>
                    <th>Verification Status</th>
                    <th>Rejection Cause</th>
                    <th>Date/Time Added</th>
                    <th>Operations</th>
                </tr>
            </thead>

            @php
                $counter = 1;
            @endphp
            <tbody>
                @foreach ($payments as $payment)
                <tr>
                    <td>{{ $counter }}</td>
                    <td>{{ $payment->payment_submitted }}</td>
                    <td>{{ $payment->payment_verified }}</td>     
                    <td>{{ $payment->payment_verifier }}</td>
                    <td>{{ $payment->img_file_proof }}</td>
                    <td>{{ $payment->text_file_proof }}</td>
                    <td>{{ $payment->payment_session_name }}</td>
                    <td>{{ $payment->payment_type_name }}</td>     
                    <td>{{ $payment->user_name }}</td>
                    <td>{{ $payment->verification_status }}</td>
                    <td>{{ $payment->rejection_cause }}</td>    
                    <td>{{ $payment->created_at->format('F d, Y h:ia') }}</td>

                    <td>
                    <a href="{{ route('payments.edit', [$payment->payment_session_id, $payment->payment_type_id, $payment->user_id]) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                    {!! Form::open(['method' => 'DELETE', 'route' => ['payments.destroy', $payment->payment_session_id, $payment->payment_type_id, $payment->user_id ]]) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}

                    @php
                        $counter = $counter + 1;
                    @endphp

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <a href="{{ route('payments.create') }}" class="btn btn-success">Add payment</a>

</div>

@endsection