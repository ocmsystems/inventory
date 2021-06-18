@extends('admin.layouts.master')

@section('content')
{!! Form::open(['route' => 'admin.users.store', 'class' => 'form-horizontal']) !!}


<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route('admin.users.index', 'Users' , null) !!} / New</h3>

        <div class="btn-group ml-3">
            {!! Form::submit( 'Create' , array('class' => 'btn btn-primary')) !!}
            
            {!! link_to_route('admin.users.index', 'Discard' , null, array('class' => 'btn btn-link ml-1')) !!}
        </div>
    </div>
</div>

<div class="clear"></div>

<div class="card" style="width: 50%;">
    <div class="card-header no-border">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    {!! implode('', $errors->all('
                    <li class="error">:message</li>
                    ')) !!}
                </ul>
            </div>
        @endif
        
        
        <div class="form-group">

            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="input-field">
                        {!! Form::label('name', trans('quickadmin::admin.users-create-name')) !!}
                        {!! Form::text('name', old('name'), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="input-field">
                        {!! Form::label('email', trans('quickadmin::admin.users-create-email')) !!}
                        {!! Form::email('email', old('email'), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="input-field">
                        {!! Form::label('password', trans('quickadmin::admin.users-create-password')) !!}
                        {!! Form::password('password', ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="input-field">
                        {!! Form::label('role_id', trans('quickadmin::admin.users-create-role'), ['class' => 'active']) !!}        
                        {!! Form::select('role_id', $roles, old('role_id'), ['class'=>'form-control selectpicker', 'title' => 'Select role..', 'data-live-search' => 'true']) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-lg-8">
                    <div class="input-field">
                        {!! Form::label('status', 'Status', array('class'=> 'active')) !!}
                        
                        <div class="switch mt-2 mb-2">
                            <label>
                                In-active
                                {!! Form::checkbox('status', 1, old('status')) !!}
                                <span class="lever"></span>
                                Active
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{!! Form::close() !!}

@endsection


