@extends('layouts.app')

@section('title', '| Edit User')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Edit {{$user->name}}</h1>
    <hr>
    {{-- @include ('errors.list') --}}

    {{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT', 'files'=>true)) }} {{-- Form model binding to automatically populate our fields with user data --}}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {!! Form::label('avatar', 'Profile Avatar') !!}
        {!! Form::file('avatar') !!}
        <?php 
            $my_image = base64_encode( $user->img_avatar );
            echo '<img src="data:image/jpeg;base64,'.$my_image.'"/>';
        ?>
    </div>
    
    <div class="form-group">
        {{ Form::label('gender', 'Gender') }}
        {{ Form::select('gender', array('M' => 'Male', 'F' => 'Female'), $user->gender) }}
    </div>

    <div class="form-group">
        {{ Form::label('birth_date', 'Birth Date') }}
        {{ Form::text('birth_date', null, array('id' => 'birth_date', 'class' => 'form-control mydatepicker')) }}
    </div>

    <div class="form-group">
        {{ Form::label('address', 'Address') }}
        {{ Form::textarea('address',null,['class'=>'form-control', 'rows' => 3, 'cols' => 40]) }}
    </div>

    <h5><b>Give Role</b></h5>

    <div class='form-group'>
        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id, $user->roles ) }}
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

