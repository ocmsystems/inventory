@extends('admin.layouts.master')

@section('content')

{!! Form::open(array('route' => config('quickadmin.route').'.warehouselist.store', 'id' => 'form-with-validation', 'class' => 'form-horizontal')) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-store"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.warehouselist.index', 'Warehouse / Store' , null) !!} / New</h3>

        <div class="btn-group ml-3">
            {!! Form::submit( 'Create' , array('class' => 'btn btn-primary')) !!}
            {!! link_to_route(config('quickadmin.route').'.warehouselist.index', 'Discard' , null, array('class' => 'btn btn-link ml-2')) !!}
        </div>
    </div>
</div>

<div class="clear"></div>


<div class="card">
    <div class="card-header no-border">
    
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

        <div class="row">
            
            <div class="col-sm-10 col-lg-4">
                <div class="input-field">
                    {!! Form::label('short_name', 'Short Name*') !!}
                    {!! Form::text('short_name', old('short_name'), array('class'=>'form-control')) !!}
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-sm-10 col-lg-6">
                <div class="input-field">
                    {!! Form::label('name', 'Name*') !!}
                    {!! Form::text('name', old('name'), array('class'=>'form-control')) !!}
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-sm-10 col-lg-6">
                <div class="input-field">
                    {!! Form::label('address', 'Address') !!}
                    {!! Form::textarea('address', old('address'), array('class'=>'materialize-textarea form-control', 'rows' => '3')) !!}
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-sm-10 col-lg-4">
                <div class="input-field">
                    {!! Form::label('contact_person', 'Contact Person') !!}
                    {!! Form::text('contact_person', old('contact_person'), array('class'=>'advancedAutoComplete form-control', 'autocomplete' => 'off')) !!}
                </div>
            </div>
            
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-3">
                <div class="input-field">
                    {!! Form::label('type', 'Type', ['class' => 'active']) !!}
                    {!! Form::select('type', $type, old('type'), ['class'=>'form-control selectpicker', 'title' => 'Select type..', 'data-live-search' => 'true']) !!}
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-10 col-lg-4">

                <div class="switch">
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
{!! Form::close() !!}

@endsection

@section('javascript')
<script type="text/javascript" src="/dist/plugins/bootstrap-autocomplete/bootstrap-autocomplete.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){

        M.textareaAutoResize($('#address'));
        AutoCompleteField.ajaxURL = '{{ route("api.users.search") }}';
        AutoCompleteField.init();
    });
</script>
@stop