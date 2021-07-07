@extends('admin.layouts.master')

@section('content')
{!! Form::model($projects, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'method' => 'PATCH', 'route' => array(config('quickadmin.route').'.projects.update', $projects->id))) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-database"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.projects.index', 'Projects' , null) !!}</h3>

        <div class="btn-group ml-3">
            {!! Form::submit('Update' , array('class' => 'btn btn-primary')) !!}
            {!! link_to_route(config('quickadmin.route').'.projects.index', 'Discard' , null, array('class' => 'btn btn-link')) !!}
        </div>
    </div>
</div>

<div class="clear"></div>


<div class="card">
    <div class="card-header no-border">


        <div class="row">
    <div class="col-sm-10 col-lg-4">
        <div class="input-field">
            {!! Form::label('name', 'Project Name*') !!}
            {!! Form::text('name', old('name',$projects->name), array('class'=>'form-control')) !!}
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-10 col-lg-4">
        <div class="input-field">
            {!! Form::label('number', 'Project Number') !!}
            {!! Form::text('number', old('number',$projects->number), array('class'=>'form-control')) !!}
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-10">
        <label>
            {!! Form::checkbox('status', 1, $projects->status == 1) !!}
            <span>Is Active</span>

        </label>
        
    </div>
</div>

    </div>
</div>

{!! Form::close() !!}

@endsection