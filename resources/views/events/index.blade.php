@extends('layouts.app')

@section('title', '| Events')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <h1><i class="fa fa-users"></i> Event Administration <a href="{{ route('events.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Event Name</th>
                    <th>Event Place</th>
                    <th>Event Start</th>
                    <th>Event Finish</th>
                    <th>Event Type</th>
                    <th>PIC</th>
                    <th>Operations</th>
                </tr>
            </thead>

            @php
                $counter = 1;
            @endphp
            <tbody>
                @foreach ($events as $event)
                <tr>
                    <td>{{ $counter }}</td>
                    <td>{{ $event->event_name }}</td>
                    <td>{{ $event->event_place }}</td>     
                    <td>{{ $event->event_start }}</td>
                    <td>{{ $event->event_finish }}</td>
                    <td>{{ $event->event_type }}</td>
                    <td>{{ $event->user_id }}</td>    
                    <td>{{ $event->created_at->format('F d, Y h:ia') }}</td>

                    <td>
                    <a href="{{ route('events.edit', $event->event_id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                    {!! Form::open(['method' => 'DELETE', 'route' => ['events.destroy', $event->event_id] ]) !!}
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

    <a href="{{ route('events.create') }}" class="btn btn-success">Add Event</a>

</div>

@endsection