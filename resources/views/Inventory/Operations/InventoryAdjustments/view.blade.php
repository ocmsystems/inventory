@extends('admin.layouts.master')

@section('content')
{!! Form::open(array('route' => array(config('quickadmin.route').'.inventoryadjustments.update', $inventoryadjustments->id), 'method' => 'PATCH', 'id' => 'form-with-validation', 'class' => 'form-horizontal')) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-boxes"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.inventoryadjustments.index', 'Inventory Adjustments' , null) !!}  / 
                @if ($inventoryadjustments->status == 1)
                Approval / 
                @endif
                {{$inventoryadjustments->transaction_number}}</h3>

        @if ($inventoryadjustments->status == 1 && in_array('inventoryadjustments', auth()->user()->approval_modules()))
        <div class="btn-group ml-3">
            {!! Form::submit( 'Validate' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}
            {!! Form::submit( 'Decline' , array('name' => 'status', 'class' => 'btn btn-danger ml-1')) !!}
        </div>
        @endif
        {!! link_to_route(config('quickadmin.route').'.inventoryadjustments.index', 'Back to list' , null, array('class' => 'btn btn-light ml-3')) !!}

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

            <div class="col-sm-10 col-lg-5">
                <div class="input-field">
                    <div class="input-field">
                        {!! Form::label('warehouse_id', 'Source Location*', array('class'=>'active')) !!}
                        {!! Form::text('warehouse_id', old('warehouse_id', $inventoryadjustments->warehouse->name), array('class'=>'form-control ', 'readonly' )) !!}
                    </div>
                </div>
            </div>


            <div class="col-sm-10 col-lg-2">
                <div class="input-field">
                    {!! Form::label('date', 'Date*') !!}
                    {!! Form::text('date', old('date', date('M d, Y', strtotime($inventoryadjustments->date))), array('class'=>'form-control', 'readonly')) !!}
                </div>
            </div>
                
        </div>

        <div class="row">

            <div class="col-sm-10 col-lg-4">
                <div class="input-field">
                    {!! Form::label('contact_person', 'Contact Person') !!}
                    {!! Form::text('contact_person', old('contact_person', $inventoryadjustments->contact_person), array('class'=>'form-control', 'readonly')) !!}
                    
                </div>
            </div>
            
            <div class="col-sm-10 col-lg-3 offset-lg-1">
                <div class="input-field">
                    {!! Form::label('source_document', 'Source Document') !!}
                    {!! Form::text('source_document', old('source_document', $inventoryadjustments->source_document), array('class'=>'form-control', 'readonly')) !!}
                    
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-10 col-lg-2">
                <div class="input-field">
                    <div class="input-field">
                        {!! Form::label('type', 'Type*', array('class'=>'active')) !!}
                    </div>
                    {!! Form::select('type', [1 => 'Current', 2 => 'Beginning'], old('warehouse_id', $inventoryadjustments->type), array('class'=>'form-control selectpicker', 'disabled')) !!}
                    
                </div>
            </div>
        </div>

        <div class="row mt-5">

            <div class="col-sm-10 col-lg-8">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="product-tab" data-toggle="tab" href="#product-attr" role="tab"
                            aria-controls="product" aria-selected="true">Details</a>
                    </li>
                </ul>
                
                
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="product-attr" role="tabpanel" aria-labelledby="product-tab-attr">
                        <div class="row">
                            <div class="col-sm-10 col-lg-12">
                                
                                <table class="table table-hover table-sm dynamic-add-row" id="replenishments-products-table">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="70%" class="text-left">Product</th>
                                            <th scope="col" width="14%">On-Hand</th>
                                            <th scope="col" width="14%">Actual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!is_null($inventoryadjustments->details))
                                        @foreach ($inventoryadjustments->details as $details)
                                            <tr>
                                                <td>
                                                    <div class="input-field mt-0 mb-0">
                                                        {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                                                        {!! Form::text('product[name][]', old('product_id', $details->product->name), array('class'=>'form-control pt-0 mb-0 product-select', 'disabled')) !!}
                                                    </div>
                                                </td>             
                                                <td class="text-right"><span class="onHandItem"> {{ $details->product->on_hand($inventoryadjustments->warehouse_id) }} </span></td>
                                                <td>
                                                    <div class="input-field mt-0 mb-0">
                                                        {!! Form::number('product[adjustment_qty][]', old('product', $details->adjusted_quantity), array('placeholder' => '0', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode >= 48', 'min' => '0', 'step' => '1', 'disabled')) !!}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @endif
                                    </tbody>

                                </table>

                            </div>
                        </div>
                    </div>

                </div>


            </div>
            
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-8">
                <div class="input-field">
                    {!! Form::label('notes', 'Notes') !!}
                    {!! Form::textarea('notes', old('notes', $inventoryadjustments->notes), array('class'=>'materialize-textarea form-control', 'rows' => '2', 'style'=>'padding-top: 0px', 'readonly')) !!}
                </div>
            </div>
        </div>


        <div class="row">

            <div class="col-sm-10 col-lg-4">
                <div class="input-field">
                    {!! Form::label('prepared_by_name', 'Prepared By*') !!}
                    {!! Form::text('prepared_by_name', old('prepared_by_name', auth()->user()->name), array('class'=>'form-control', 'readonly')) !!}
                    {!! Form::hidden('prepared_by', old('prepared_by', auth()->user()->id), array('class'=>'form-control', 'readonly')) !!}
                </div>
            </div>

        </div>

    </div>
</div>


{!! Form::close() !!} 
@endsection
    
@section('javascript')
@stop