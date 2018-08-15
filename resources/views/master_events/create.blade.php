@extends('layouts.app')

@section('title', '| Add Master Event')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Add Master Event</h1>
    <hr>

    {{-- @include ('errors.list') --}}

    {{ Form::open(array('url' => 'master_events')) }}

    <div class="form-group">
        {{ Form::label('type_event_name', 'Type Event Name') }}
        {{ Form::text('type_event_name', '', array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection