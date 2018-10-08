@extends('layouts.app')

@section('title', '| Edit Payment')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Edit {{$payment->payment_session_id}}, {{$payment->payment_type_id}}, {{$payment->user_id}}</h1>
    <hr>
    {{-- @include ('errors.list') --}}

    {{ Form::model($payment, array('route' => array('payments.update', $payment->payment_session_id, $payment->payment_type_id, $payment->user_id), 'method' => 'PUT', 'files'=>true)) }} {{-- Form model binding to automatically populate our fields with user data --}}

    {{ Form::hidden('payment_session_id', $payment->payment_session_id) }}
    {{ Form::hidden('payment_type_id', $payment->payment_type_id) }}
    {{ Form::hidden('user_id', $payment->user_id) }}

    <div class="form-group">
        {{ Form::label('user_name', 'Payment Sender') }}
        {{ Form::text('user_name', $payment->username, ['class' => 'form-control', 'readonly' => 'true']) }}
    </div>

    <div class="form-group">
        {{ Form::label('payment_type_name', 'Payment Type') }}
        {{ Form::text('payment_type_name', $payment->payment_type_name, ['class' => 'form-control', 'readonly' => 'true']) }}
    </div>

    <div class="form-group">
        {{ Form::label('payment_session_name', 'Payment Session') }}
        {{ Form::text('payment_session_name', $payment->payment_session_name, ['class' => 'form-control', 'readonly' => 'true']) }}
    </div>

    <div class="form-group">
        {{ Form::label('payment_submitted', 'Payment Submitted Date') }}
        {{ Form::text('payment_submitted', null, array('id' => 'payment_submitted', 'class' => 'form-control mydatepicker')) }}
    </div>

    <div class="form-group">
        {{ Form::label('payment_verified', 'Payment Verified Date') }}
        {{ Form::text('payment_verified', null, array('id' => 'payment_verified', 'class' => 'form-control mydatepicker')) }}
    </div>

    <div class="form-group">
        {!! Form::label('transfer_image', 'Transfer Image Proof') !!}
        {!! Form::file('transfer_image') !!}
        <?php
            $my_image = base64_encode($payment->img_file_proof);
            echo '<img src="data:image/jpeg;base64,' . $my_image . '"/>';
        ?>
    </div>

    <div class="form-group">
        {{ Form::label('text_file_proof', 'Transfer Text Proof') }}
        {{ Form::text('text_file_proof', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('verification_status', 'Verification Status') }}
        {{ Form::radio('verification_status', 'C', false) }}
        {{ Form::radio('verification_status', 'R', true) }}
    </div>

    <div class="form-group">
        {{ Form::label('rejection_cause', 'Rejection Cause') }}
        {{ Form::text('rejection_cause', null, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}

</div>

@endsection

