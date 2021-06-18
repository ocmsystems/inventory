@extends('admin.layouts.master')

@section('content')

{!! Form::open(array('route' => config('quickadmin.route').'admin.positions.store', 'id' => 'form-with-validation', 'class' => 'form-horizontal')) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-database"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'admin.positions.index', 'Positions' , null) !!} / New</h3>

        <div class="btn-group ml-3">
            {!! Form::submit( 'Create' , array('class' => 'btn btn-primary')) !!}
            {!! link_to_route(config('quickadmin.route').'admin.positions.index', 'Discard' , null, array('class' => 'btn btn-link')) !!}
        </div>
    </div>
</div>

<div class="clear"></div>


<div class="row">
    <div class="col-sm-10 col-sm-offset-2">
        @if ($errors->any())
        	<div class="alert alert-danger">
        	    <ul>
                    {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
                </ul>
        	</div>
        @endif
    </div>
</div>


<div class="card">
    <div class="card-header no-border">
    
        <div class="row">

            <div class="col-sm-10 col-lg-3">
                <div class="input-field">
                    {!! Form::label('name', 'Name*') !!}
                    {!! Form::text('name', old('name'), array('class'=>'form-control')) !!}
                    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-5">
                <div class="input-field">
                    {!! Form::label('description', 'Description') !!}
                    {!! Form::textarea('notes', old('notes'), array('class'=>'materialize-textarea form-control', 'rows' => '2', 'style'=>'padding-top: 0px')) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                <label>
                    {!! Form::checkbox('status', 1, false) !!}
                    <span>Status</span>

                </label>
            </div>
        </div>
    </div>
</div>


{!! Form::close() !!} 

@endsection