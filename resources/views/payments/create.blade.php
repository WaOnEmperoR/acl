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
        {{ Form::label('text_file_proof', 'Transfer Text Proof') }}
        {{ Form::textarea('text_file_proof', null, array('class' => 'form-control', 'rows' => 4)) }}
    </div>

    <div class="form-group">
        {{ Form::label('verification_label', 'Verification Status') }}
        {!! '<br>' !!}        
        {{ Form::radio('verification_status', 'C', false) }}
        {{ Form::label('verification_status', 'Confirmed') }}
        {!! '<br>' !!}
        {{ Form::radio('verification_status', 'R', true) }}
        {{ Form::label('verification_status', 'Rejected') }}        
    </div>


    <div class="form-group">
        {{ Form::label('rejection_cause', 'Rejection Cause') }}
        {{ Form::textarea('rejection_cause', null, array('class' => 'form-control', 'rows' => 4)) }}
    </div>

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>

<script>
    $(function () {
        $("#payment_submitted").datepicker({
            onSelect: function (selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate() + 1);
                $("#payment_verified").datepicker("option", "minDate", dt);
            }
        });
        $("#payment_verified").datepicker({
            onSelect: function (selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate() - 1);
                $("#payment_submitted").datepicker("option", "maxDate", dt);
            }
        });

        $('input:radio[name=verification_status]').change(function() {
            if (this.value == 'C') {
                $("#rejection_cause").prop('disabled', true);
                $( "textarea#rejection_cause" ).val("");
            }
            else if (this.value == 'R') {
                $("#rejection_cause").prop('disabled', false);
            }
        });
    });
</script>

@endsection