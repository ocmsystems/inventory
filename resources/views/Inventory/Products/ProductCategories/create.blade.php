@extends('admin.layouts.master')

@section('content')
{!! Form::open(array('route' => config('quickadmin.route').'.productcategories.store', 'id' => 'form-with-validation', 'class' => 'form-horizontal')) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-cart-plus"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.productcategories.index', 'Product Categories' , null) !!} / New</h3>

        <div class="btn-group ml-3">
            {!! Form::submit( 'Create', array('class' => 'btn btn-primary')) !!}
            
            {!! link_to_route(config('quickadmin.route').'.productcategories.index', 'Back to List' , null, array('class' => 'btn btn-light ml-1')) !!}
        </div>
    </div>
</div>

<div class="clear"></div>

<div class="card">
    <div class="card-header no-border">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
            </ul>
        </div>
    @endif

        <div class="form-group">

            <div class="row">
                <div class="col-sm-10 col-lg-3">
                    <div class="input-field">
                        {!! Form::label('name', 'Name*') !!}
                        {!! Form::text('name', old('name'), array('class'=>'form-control')) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10 col-lg-5">
                    <div class="input-field">
                        {!! Form::label('description', 'Description') !!}
                        {!! Form::textarea('description', old('description'), array('class'=>'materialize-textarea form-control', 'rows' => 3)) !!}
                    </div>
                </div>
            </div>

            
            <div class="row mt-3">
                <div class="col-sm-10 col-lg-3">
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