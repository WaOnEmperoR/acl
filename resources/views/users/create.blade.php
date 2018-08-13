@extends('layouts.app')

@section('title', '| Add User')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Add User</h1>
    <hr>

    {{-- @include ('errors.list') --}}

    {{ Form::open(array('url' => 'users', 'files'=>true)) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', '', array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {!! Form::label('avatar', 'Profile Avatar') !!}
        {!! Form::file('avatar') !!}
    </div>

    <div class="form-group">
        {{ Form::label('gender', 'Gender') }}
        {{ Form::select('gender', array('M' => 'Male', 'F' => 'Female'), 'M') }}
    </div>

    <div class="form-group">
        {{ Form::label('birth_date', 'Birth Date') }}
        {{ Form::text('birth_date', '', array('id' => 'birth_date', 'class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('address', 'Address') }}
        {{ Form::textarea('address',null,['class'=>'form-control', 'rows' => 3, 'cols' => 40]) }}
    </div>

    <div class='form-group'>
        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>

        @endforeach
    </div>

    <div class="form-group">
        {{ Form::label('password', 'Password') }}<br>
        {{ Form::password('password', array('class' => 'form-control')) }}

    </div>

    <div class="form-group">
        {{ Form::label('password', 'Confirm Password') }}<br>
        {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

    </div>

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection