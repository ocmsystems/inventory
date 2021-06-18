@extends('admin.layouts.master')

@section('content')

<div class="header-title">

    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-database"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3">Physical Count</h3>
        {{-- <div class="btn-group ml-3">
            {!! link_to_route(config('quickadmin.route').'.physicalcount.create', 'Create' , null, array('class' => 'btn btn-primary')) !!}
        </div> --}}
    </div>

</div>

<div class="clear"></div>

<div class="row">
    <div class="col-lg-6 col-sm-12">

        <div class="card">
            <div class="card-header pt-3">
                <h4>Physical Count</h4>
            </div>

            <div class="card-body">
                    {!! Form::open(array('route' => 'reports.physicalcount.generate', 'id' => 'form-with-validation', 'class' => 'form-horizontal', 'target' => '_blank')) !!}

                    <div class="row">
    
                        <div class="col-sm-10 col-lg-8">
                            <div class="input-field">
                                <div class="input-field">
                                    {!! Form::label('warehouse_id', 'Source Location*', array('class'=>'active')) !!}
                                </div>
                                {!! Form::select('warehouse_id', $warehouselist, old('warehouse_id'), array('class'=>'form-control selectpicker', 'title' => 'Choose location..', 'data-live-search' => 'true')) !!}
                            </div>
                        </div>
    
                    </div>
    
                    {{-- <div class="row mb-2">
                        <div class="col-sm-10 col-lg-8">
                            <label for="todate">
                                {!! Form::checkbox('todate', 1, 1) !!}
                                <span>To Date Amount</span>
                            </label>
                        </div>
                    </div> --}}
    
                    <div class="btn-group mt-2">
                        {!! Form::submit( 'Generate' , array('name' => 'periodic', 'class' => 'btn btn-primary')) !!}
                    </div>
    
                    {!! Form::close() !!} 
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
@stop