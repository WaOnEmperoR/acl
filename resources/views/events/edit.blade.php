@extends('layouts.app')

@section('title', '| Edit Event')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Edit {{$event->event_id}}</h1>
    <hr>
    {{-- @include ('errors.list') --}}

    {{ Form::model($event, array('route' => array('events.update', $event->event_id), 'method' => 'PUT')) }} {{-- Form model binding to automatically populate our fields with user data --}}

    <div class="form-group">
        {{ Form::label('event_name', 'Event Name') }}
        {{ Form::text('event_name', null, array('class' => 'form-control')) }}
    </div>
    
    <div class="form-group">
        {{ Form::label('event_place', 'Event Place') }}
        {{ Form::text('event_place', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('event_start', 'Event Start') }}
        {{ Form::text('event_start', null, array('id' => 'event_start', 'class' => 'form-control mydatetimepicker')) }}
    </div>

    <div class="form-group">
        {{ Form::label('event_finish', 'Event Finish') }}
        {{ Form::text('event_finish', null, array('id' => 'event_finish', 'class' => 'form-control mydatetimepicker')) }}
    </div>

    <div class="form-group">
        {{ Form::label('user_id', 'Person In Charge') }}
        {{ Form::select('user_id', $users_list, null) }}
    </div>

    <div class="form-group">
        {{ Form::label('event_type_id', 'Event Type Name') }}
        {{ Form::select('event_type_id', $event_types_list, null) }}
    </div>

    {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection

