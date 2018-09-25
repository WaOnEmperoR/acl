@extends('layouts.app')

@section('title', '| Edit Payment Session')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Edit {{$payment_session->payment_session_id}}</h1>
    <hr>
    {{-- @include ('errors.list') --}}

    {{ Form::model($payment_session, array('route' => array('payment_sessions.update', $payment_session->payment_session_id), 'method' => 'PUT')) }} {{-- Form model binding to automatically populate our fields with user data --}}

    <div class="form-group">
        {{ Form::label('payment_session_name', 'Payment Session Name') }}
        {{ Form::text('payment_session_name', null, array('class' => 'form-control')) }}
    </div>
    
    <div class="form-group">
        {{ Form::label('payment_start_date', 'Payment Start Date') }}
        {{ Form::text('payment_start_date', null, array('id' => 'payment_start_date', 'class' => 'form-control mydatepicker')) }}
    </div>

    <div class="form-group">
        {{ Form::label('payment_finish_date', 'Payment Finish Date') }}
        {{ Form::text('payment_finish_date', null, array('id' => 'payment_finish_date', 'class' => 'form-control mydatepicker')) }}
    </div>

    {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection

