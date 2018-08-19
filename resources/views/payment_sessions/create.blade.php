@extends('layouts.app')

@section('title', '| Add Payment Session')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Add Payment Session</h1>
    <hr>

    {{-- @include ('errors.list') --}}

    {{ Form::open(array('url' => 'payment_sessions')) }}

    <div class="form-group">
        {{ Form::label('payment_session_name', 'Payment Session Name') }}
        {{ Form::text('payment_session_name', '', array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('payment_start_date', 'Payment Start Date') }}
        {{ Form::text('payment_start_date', '', array('id' => 'payment_start_date', 'class' => 'form-control mydatepicker')) }}
    </div>

    <div class="form-group">
        {{ Form::label('payment_finish_date', 'Payment Finish Date') }}
        {{ Form::text('payment_finish_date', '', array('id' => 'payment_finish_date', 'class' => 'form-control mydatepicker')) }}
    </div>

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection