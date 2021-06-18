@extends('admin.layouts.master')

@section('content')

{!! Form::model($transactions, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'method' => 'PATCH', 'route' => array(config('quickadmin.route').'.transactions.update', $transactions->id))) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3">{!! link_to_route(config('quickadmin.route').'.transactions.index', 'Transactions' , null) !!} / 
                @if ($transactions->status == 1)
                Approval / 
                @endif
                {{$transactions->transaction_number}}
                
                @if ($transactions->status == 2)
                    {!! HTML::tag('span', $transactions->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-success text-sm']) !!}
                @elseif ($transactions->status == 10)
                    {!! HTML::tag('span', $transactions->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-danger text-sm']) !!}
                @endif</h3>

        @if ($transactions->status == 1 && in_array('sales_transaction', auth()->user()->approval_modules()))
            <div class="btn-group ml-3">
                {!! Form::submit( 'Verify' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}
                {!! Form::submit( 'Decline' , array('name' => 'status', 'class' => 'btn btn-danger ml-1')) !!}
            </div>
        @endif
            {!! link_to_route(config('quickadmin.route').'.transactions.index', 'Back to list' , null, array('class' => 'btn btn-light ml-3')) !!}
    </div>
</div>

<div class="clear"></div>

<div class="card">
    <div class="card-header no-border">

        <div class="row">

            <div class="col-lg-4 col-sm-12">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <div class="input-field" style="margin-top: -9px;">
                            <div class="input-field">
                                {!! Form::label('warehouse', 'Store Location*', array('class'=>'active')) !!}
                            </div>
                            {!! Form::text('warehouse', old('warehouse', $transactions->warehouse->name ), array('class'=>'form-control')) !!}
                            {!! Form::hidden('warehouse_id', old('warehouse_id', $transactions->warehouse_id ), array('class'=>'form-control')) !!}
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-10 col-lg-8">
                        <div class="input-field">
                            {!! Form::label('barcode', 'Barcode') !!}
                            {!! Form::text('barcode', old('barcode', $transactions->product->barcode), array('class'=>'form-control', 'readonly')) !!}
                            {!! Form::hidden('product_id', old('product_id', $transactions->product_id), array('class'=>'form-control')) !!}
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-6 col-sm-12">

                <div class="row">
                    <div class="col-sm-10 col-lg-4">
                        <div class="input-field">
                            {!! Form::label('transaction_date', 'Transaction Date', array('class' => 'active')) !!}
                            {!! Form::text('transaction_date', old('transaction_date', date("M d, Y", strtotime($transactions->transaction_date)) ), array('class'=>'form-control', 'readonly')) !!}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-10 col-lg-4">
                        <div class="input-field">
                            {!! Form::label('prepared_by', 'Prepared By', array('class' => 'active')) !!}
                            {!! Form::text('prepared_by', old('prepared_by', $transactions->prepared_by_user->name), array('class'=>'form-control', 'readonly')) !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
    </div>
</div>



<div class="card">
    <div class="card-header no-border">
        <div class="row">

            <div class="col-4 col-lg-1">
                <div class="row">
                    <div class="col-xs-4 col-lg-12"><img src="{{ asset('uploads') }}/{{ $transactions->product->image }}" style="width: 100%;"/></div>
                </div>
            </div>



            <div class="col col-lg-8" style="font-size: 15px;">
                
                <div class="row">
                    <div class="col-sm-10 col-lg-6">
                        <div class="input-field"><label class="active">SKU</label><span class="text-bold">{{ $transactions->product->sku }}</span></div>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-sm-10 col-lg-6">
                        <div class="input-field"><label class="active">Name</label><span class="text-bold">{{ $transactions->product->name }}</span>
                            @if($transactions->discount !== 0)
                                <span class="bg-success" style="font-size: 10px;">On sale</span>
                            @endif
                        </div>
                    </div>
                </div>

                
            
                <div class="row">
                    <div class="col-sm-10 col-lg-6">
                        <div class="input-field"><label class="active">Description</label><span class="text-bold">{{ $transactions->product->description }}</span></div>
                    </div>
                </div>

            
            <div class="row">
                <div class="col-sm-10 col-lg-6">
                    <div class="input-field"><label class="active">Price</label><span class="text-bold">{{ $transactions->original_price }}</span></div>
                </div>
            </div>
            
            @if($transactions->discount !== 0 && $transactions->discount != '')

                <div class="row">
                    <div class="col-sm-10 col-lg-6">
                        <div class="input-field"><label class="active">Discount</label><span class="text-bold">{{ $transactions->discount }}%</span></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-10 col-lg-6">
                        <div class="input-field"><label class="active">Discounted Price</label><span class="text-bold">{{ $transactions->amount }}</span></div>
                    </div>
                </div>

            @endif

        </div>
    </div>
</div>

{!! Form::close() !!}

@endsection