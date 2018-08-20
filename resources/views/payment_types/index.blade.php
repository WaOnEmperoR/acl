@extends('layouts.app')

@section('title', '| Payment Types')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <h1><i class="fa fa-users"></i> Payment Type Administration <a href="{{ route('payment_types.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Payment Type Name</th>
                    <th>Date/Time Added</th>
                    <th>Operations</th>
                </tr>
            </thead>

            @php
                $counter = 1;
            @endphp
            <tbody>
                @foreach ($payment_types as $payment_type)
                <tr>
                    <td>{{ $counter }}</td>    
                    <td>{{ $payment_type->payment_name }}</td>
                    <td>{{ $payment_type->created_at->format('F d, Y h:ia') }}</td>

                    <td>
                    <a href="{{ route('payment_types.edit', $payment_type->payment_type_id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                    {!! Form::open(['method' => 'DELETE', 'route' => ['payment_types.destroy', $payment_type->payment_type_id] ]) !!}
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

    <a href="{{ route('payment_types.create') }}" class="btn btn-success">Add Payment Type</a>

</div>

@endsection