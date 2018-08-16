@extends('layouts.app')

@section('title', '| Edit Master Event')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Edit {{$master_event->type_event_name}}</h1>
    <hr>
    {{-- @include ('errors.list') --}}

    {{ Form::model($master_event, array('route' => array('master_events.update', $master_event->master_event_id), 'method' => 'PUT')) }} {{-- Form model binding to automatically populate our fields with user data --}}

    <div class="form-group">
        {{ Form::label('type_event_name', 'Type Event Name') }}
        {{ Form::text('type_event_name', null, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection

