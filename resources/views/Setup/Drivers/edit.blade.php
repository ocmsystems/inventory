@extends('admin.layouts.master')

@section('content')
{!! Form::model($drivers, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'method' => 'PATCH', 'route' => array(config('quickadmin.route').'.drivers.update', $drivers->id))) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-users-cog"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.drivers.index', 'Drivers' , null) !!}</h3>

        <div class="btn-group ml-3">
            {!! Form::submit('Update' , array('class' => 'btn btn-primary')) !!}
            {!! link_to_route(config('quickadmin.route').'.drivers.index', 'Discard' , null, array('class' => 'btn btn-link')) !!}
        </div>
    </div>
</div>

<div class="clear"></div>


<div class="card">
    <div class="card-header no-border">


        <div class="row">
    <div class="col-sm-10 col-lg-4">
        <div class="input-field">
            {!! Form::label('name', 'Name*') !!}
            {!! Form::text('name', old('name',$drivers->name), array('class'=>'form-control')) !!}
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-10">
        <label>
            {!! Form::checkbox('status', 1, $drivers->status == 1) !!}
            <span>Show on List</span>

        </label>
        
    </div>
</div>

    </div>
</div>

{!! Form::close() !!}

@endsection