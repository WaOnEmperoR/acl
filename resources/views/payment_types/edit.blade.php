@extends('layouts.app')

@section('title', '| Edit Payment Type')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Edit {{$payment_type->payment_name}}</h1>
    <hr>
    {{-- @include ('errors.list') --}}

    {{ Form::model($payment_type, array('route' => array('payment_types.update', $payment_type->payment_type_id), 'method' => 'PUT')) }} {{-- Form model binding to automatically populate our fields with user data --}}

    <div class="form-group">
        {{ Form::label('payment_name', 'Payment Name') }}
        {{ Form::text('payment_name', null, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection

