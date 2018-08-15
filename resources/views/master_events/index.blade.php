@extends('layouts.app')

@section('title', '| Master Events')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <h1><i class="fa fa-users"></i> Master Event Administration <a href="{{ route('master_events.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Name of Event Type</th>
                    <th>Date/Time Added</th>
                    <th>Operations</th>
                </tr>
            </thead>

            @php
                $counter = 1;
            @endphp
            <tbody>
                @foreach ($master_events as $master_event)
                <tr>
                    <td>{{ $counter }}</td>    
                    <td>{{ $master_event->type_event_name }}</td>
                    <td>{{ $master_event->created_at->format('F d, Y h:ia') }}</td>

                    <td>
                    <a href="{{ route('master_events.edit', $master_event->master_event_id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                    {!! Form::open(['method' => 'DELETE', 'route' => ['master_events.destroy', $master_event->master_event_id] ]) !!}
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

    <a href="{{ route('master_events.create') }}" class="btn btn-success">Add Master Event</a>

</div>

@endsection