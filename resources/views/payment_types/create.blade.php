@extends('layouts.app')

@section('title', '| Add Payment Type')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Add Payment Type</h1>
    <hr>

    {{-- @include ('errors.list') --}}

    {{ Form::open(array('url' => 'payment_types')) }}

    <div class="form-group">
        {{ Form::label('payment_name', 'Payment Name') }}
        {{ Form::text('payment_name', '', array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection