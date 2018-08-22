@extends('layouts.app')

@section('title', '| Add Event')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Add Event</h1>
    <hr>

    {{-- @include ('errors.list') --}}

    {{ Form::open(array('url' => 'events')) }}

    <div class="form-group">
        {{ Form::label('event_name', 'Event Name') }}
        {{ Form::text('event_name', '', array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('event_place', 'Event Place') }}
        {{ Form::text('event_place', '', array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('event_start', 'Event Start') }}
        {{ Form::text('event_start', '', array('id' => 'event_start', 'class' => 'form-control mydatetimepicker')) }}
    </div>

    <div class="form-group">
        {{ Form::label('event_finish', 'Event Finish') }}
        {{ Form::text('event_finish', '', array('id' => 'event_finish', 'class' => 'form-control mydatetimepicker')) }}
    </div>

    <div class="form-group">
        {{ Form::label('user_id', 'Person In Charge') }}
        {{ Form::select('user_id', $users_list, null) }}
    </div>

    <div class="form-group">
        {{ Form::label('event_type_id', 'Event Type Name') }}
        {{ Form::select('event_type_id', $event_types_list, null) }}
    </div>

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection