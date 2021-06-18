@extends('admin.layouts.master') 
@section('content')


<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-gavel"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3">Roles</h3>
        <div class="btn-group ml-3">
        {!! link_to_route('admin.roles.create', 'Create' , null, array('class' => 'btn btn-primary')) !!}
        </div>
    </div>
</div>

<div class="clear"></div>

<div class="card">
    <div class="card-header no-border">
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>{{ trans('quickadmin::admin.roles-index-title') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->title }}</td>
                        <td>
                            {!! link_to_route('admin.roles.edit', trans('quickadmin::admin.roles-index-edit'), [$role->id], ['class' => 'btn btn-xs btn-info']) !!} 
                            
                            {!! Form::open(['style' => 'display: inline-block;', 'method' => 'DELETE', 'onsubmit' => 'return confirm(\'' . trans('quickadmin::admin.roles-index-are_you_sure') . '\');', 'route' => ['admin.roles.destroy', $role->id]]) !!}
                            {!! Form::submit(trans('quickadmin::admin.roles-index-delete'), ['class' => 'btn btn-xs btn-danger']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection