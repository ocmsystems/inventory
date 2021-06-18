@extends('admin.layouts.master')

@section('content')

{!! Form::open(array('route' => config('quickadmin.route').'.transactions.store', 'id' => 'form-with-validation', 'class' => 'form-horizontal')) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.transactions.index', 'Transactions' , null) !!} / New</h3>

        <div class="btn-group ml-3">
            {!! Form::submit( 'Submit for validation' , array('class' => 'btn btn-primary', 'disabled')) !!}
            {!! link_to_route(config('quickadmin.route').'.transactions.index', 'Discard' , null, array('class' => 'btn btn-link')) !!}
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

            <div class="col-lg-4 col-sm-12">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <div class="input-field" style="margin-top: -9px;">
                            <div class="input-field">
                                {!! Form::label('warehouse_id', 'Store Location*', array('class'=>'active mt-2')) !!}
                            </div>
                            {!! Form::select('warehouse_id', $warehouselist, old('warehouse_id'), array('class'=>'form-control selectpicker', 'data-live-search' => 'true')) !!}
                            
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-10 col-lg-8">
                        <div class="input-field">
                            {!! Form::label('barcode', 'Barcode') !!}
                            {!! Form::text('barcode', old('barcode'), array('class'=>'form-control')) !!}
                            {!! Form::hidden('product_id', old('product_id'), array('class'=>'form-control')) !!}
                            
                            <a href="javascript:;" class="btn btn-link lookup" title="Scan Barcode"><i class="fas fa-camera"></i></a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-6 col-sm-12">

                <div class="row">
                    <div class="col-sm-10 col-lg-4">
                        <div class="input-field">
                            {!! Form::label('transaction_date', 'Transaction Date', array('class' => 'active')) !!}
                            {!! Form::text('transaction_date', old('transaction_date', date("M d, Y")), array('class'=>'form-control', 'readonly')) !!}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-10 col-lg-4">
                        <div class="input-field">
                            {!! Form::label('prepared_by', 'Prepared By', array('class' => 'active')) !!}
                            {!! Form::text('prepared_by', old('prepared_by', auth()->user()->name), array('class'=>'form-control', 'readonly')) !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
    </div>
</div>

<div id="product_details">

</div>

{!! Form::close() !!} 

<div id="interactive" class="viewport" style="position:fixed; width:100%; height:100%; top:0; left:0;z-index: 999;"></div>

@endsection


@section('javascript')

<script type="text/javascript" src="/dist/plugins/quagga/quagga.min.js"></script>
<script type="text/javascript" src="/js/pages/sales/transactions.js"></script>

<script type="text/javascript">
    var config = {
        route: {
            product_barcode: '{{ route('api.products.barcode') }}',
        }
    }
</script>

@stop
