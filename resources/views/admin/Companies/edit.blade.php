@extends('admin.layouts.master')

@section('content')

{!! Form::open( array( 'route' => array('admin.companies.update', $companies->id), 'class' => 'form-horizontal', 'id' => 'form-with-validation', 'method' => 'PATCH')) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-database"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'admin.companies.index', 'Company' , null) !!}</h3>

        <div class="btn-group ml-3">
            {!! Form::submit('Update' , array('class' => 'btn btn-primary')) !!}
            {!! link_to_route(config('quickadmin.route').'admin.companies.index', 'Discard' , null, array('class' => 'btn btn-link ml-2')) !!}
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
                    {!! Form::text('name', old('name',$companies->name), array('class'=>'form-control')) !!}
                    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-5">
                <div class="input-field">
                    {!! Form::label('description', 'Description') !!}
                    {!! Form::textarea('description', old('description',$companies->description), array('class'=>'materialize-textarea form-control', 'rows' => '2', 'style'=>'padding-top: 0px')) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                <label>
                    {!! Form::checkbox('status', 1, $companies->status == 1) !!}
                    <span>Status</span>
                </label>
            </div>
        </div>
    </div>

</div>

{!! Form::close() !!}

@endsection