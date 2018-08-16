@extends('layouts.app')

@section('title', '| Payment Sessions')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <h1><i class="fa fa-users"></i> Payment Session Administration <a href="{{ route('payment_sessions.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Payment Start Date</th>
                    <th>Payment Finish Date</th>
                    <th>Date/Time Added</th>
                    <th>Operations</th>
                </tr>
            </thead>

            @php
                $counter = 1;
            @endphp
            <tbody>
                @foreach ($payment_sessions as $payment_session)
                <tr>
                    <td>{{ $counter }}</td>    
                    <td>{{ $payment_session->payment_start_date }}</td>
                    <td>{{ $payment_session->payment_finish_date }}</td>
                    <td>{{ $payment_session->created_at->format('F d, Y h:ia') }}</td>

                    <td>
                    <a href="{{ route('payment_sessions.edit', $payment_session->payment_session_id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                    {!! Form::open(['method' => 'DELETE', 'route' => ['payment_sessions.destroy', $payment_session->payment_session_id] ]) !!}
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

    <a href="{{ route('payment_sessions.create') }}" class="btn btn-success">Add Payment Session</a>

</div>

@endsection