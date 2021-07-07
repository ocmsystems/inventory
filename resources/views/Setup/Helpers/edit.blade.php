@extends('admin.layouts.master')

@section('content')
{!! Form::model($helpers, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'method' => 'PATCH', 'route' => array(config('quickadmin.route').'.helpers.update', $helpers->id))) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-people-carry"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.helpers.index', 'Helpers' , null) !!}</h3>

        <div class="btn-group ml-3">
            {!! Form::submit('Update' , array('class' => 'btn btn-primary')) !!}
            {!! link_to_route(config('quickadmin.route').'.helpers.index', 'Discard' , null, array('class' => 'btn btn-link')) !!}
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
            {!! Form::text('name', old('name',$helpers->name), array('class'=>'form-control')) !!}
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-10">
        <label>
            {!! Form::checkbox('status', 1, $helpers->status == 1) !!}
            <span>Show on List</span>

        </label>
        
    </div>
</div>

    </div>
</div>

{!! Form::close() !!}

@endsection