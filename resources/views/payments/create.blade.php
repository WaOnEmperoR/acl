@extends('layouts.app')

@section('title', '| Add Payment')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Add Payment</h1>
    <hr>

    {{-- @include ('errors.list') --}}
    
    {{ Form::open(array('url' => 'payments', 'files'=>true)) }}

    <div class="form-group">
        {{ Form::label('user_id', 'Payment Sender') }}
        {{ Form::select('user_id', $users_list, null) }}
    </div>

    <div class="form-group">
        {{ Form::label('payment_type_id', 'Payment Type') }}
        {{ Form::select('payment_type_id', $payment_types_list, null) }}
    </div>

    <div class="form-group">
        {{ Form::label('payment_session_id', 'Payment Session') }}
        {{ Form::select('payment_session_id', $payment_sessions_list, null) }}
    </div>

    <div class="form-group">
        {{ Form::label('payment_submitted', 'Payment Submitted Date') }}
        {{ Form::text('payment_submitted', '', array('id' => 'payment_submitted', 'class' => 'form-control mydatepicker')) }}
    </div>

    <div class="form-group">
        {{ Form::label('payment_verified', 'Payment Verified Date') }}
        {{ Form::text('payment_verified', '', array('id' => 'payment_verified', 'class' => 'form-control mydatepicker')) }}
    </div>

    <div class="form-group">
        {!! Form::label('transfer_image', 'Transfer Image Proof') !!}
        {!! Form::file('transfer_image') !!}
    </div>

    <div class="form-group">
        {{ Form::label('transfer_text', 'Transfer Text Proof') }}
        {{ Form::text('transfer_text', '', array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('verification_status', 'Verification Status') }}
        {{ Form::radio('verification_status', 'C', false) }}
        {{ Form::radio('verification_status', 'R', true) }}
    </div>

    <div class="form-group">
        {{ Form::label('rejection_cause', 'Rejection Cause') }}
        {{ Form::text('rejection_cause', '', array('class' => 'form-control')) }}
    </div> 

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>

@endsection