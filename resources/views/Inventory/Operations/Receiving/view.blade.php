@extends('admin.layouts.master')

@section('content')

{!! Form::open(array('route' => array(config('quickadmin.route').'.receiving.update', $receiving->id), 'method' => 'PATCH', 'id' => 'form-with-validation', 'class' => 'form-horizontal')) !!}

<div class="header-title">
        <div class="card">
            <div class="card-header no-border">
                <i class="fas fa-handshake"></i>
            </div>
        </div>
    
        <div class="float-left">
            <h3 class="mt-2 ml-3"> 
                {!! link_to_route(config('quickadmin.route').'.receiving.index', 'Receiving' , null) !!} / 
                @if ($receiving->status == 1)
                Approval / 
                @endif
                {{$receiving->transaction_number}}

                
                
                @if ($receiving->status == 2)
                    {!! HTML::tag('span', $receiving->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-success text-sm']) !!}
                @elseif ($receiving->status == 10)
                    {!! HTML::tag('span', $receiving->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-danger text-sm']) !!}
                @endif

            </h3>
    
            @if ($receiving->status == 1 && in_array('receiving', auth()->user()->approval_modules()))
            <div class="btn-group ml-3">
                {!! Form::submit( 'Mark as Received' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}
                {!! Form::submit( 'Decline' , array('name' => 'status', 'class' => 'btn btn-danger ml-1')) !!}
            </div>
            @endif

            {!! link_to_route(config('quickadmin.route').'.receiving.index', 'Back to list' , null, array('class' => 'btn btn-light ml-3')) !!}
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
    
                <div class="col-sm-10 col-lg-3">
                    <div class="input-field">
                        {!! Form::label('source_document', 'Source Document', array('class'=>'active')) !!}
                        {{-- {!! Form::text('source_document', old('source_document'), array('id'=>'source_document', 'class'=>'form-control', 'autocomplete' => 'off')) !!} --}}
                    </div>
                    <div class="input-field mt-3">
                        {!! Form::text('source_document', old('source_document', $receiving->source_document), array('class'=>'form-control', 'disabled')) !!}
                        {!! Form::hidden('delivery_id', old('delivery_id', $receiving->delivery_id), array('class'=>'form-control')) !!}
                    </div>
                </div>
    
                <div class="col-sm-10 col-lg-3 offset-lg-2">
                    <div class="input-field">
                        {!! Form::label('received_date', 'Receive Date') !!}
                        {!! Form::text('received_date', old('received_date', date("M d, Y", strtotime($receiving->received_date)) ), array('class'=>'form-control', 'disabled')) !!}

                        {!! Form::hidden('received_date', old('received_date', date("M d, Y", strtotime($receiving->received_date)) ), array('class'=>'form-control')) !!}
                    </div>
                </div>
    
            </div>
    
            
            <div class="row">
    
                <div class="col-sm-10 col-lg-4">
                    <div class="input-field">
                        {!! Form::label('receiving_warehouse', 'Receiving Location*', array('class'=>'active')) !!}
                        {!! Form::text('receiving_warehouse_id', old('receiving_warehouse_id', $receiving->receiving_warehouse->name), array('class'=>'form-control', 'id' => 'receiving_warehouse_id', 'disabled')) !!}
                    </div>
                </div>
            
    
                <div class="col-sm-10 col-lg-3 offset-lg-1">
                    <div class="input-field">
                    {!! Form::label('delivery_date', 'Delivery Date') !!}
                    {!! Form::text('delivery_date', old('delivery_date', date("M d, Y", strtotime($receiving->delivery->delivery_date)) ), array('class'=>'form-control', 'id' => 'delivery_date', 'disabled')) !!}
                    </div>
                </div>
    
            </div>
    
            
            <div class="row">
    
                <div class="col-sm-10 col-lg-4">
                    <div class="input-field">
                        <div class="input-field">
                            {!! Form::label('source_warehouse', 'Source Location*', array('class'=>'active')) !!}
                        </div>
                        {!! Form::text('source_warehouse_id', old('source_warehouse_id', $receiving->delivery->source_warehouse->name), array('class'=>'form-control', 'id' => 'source_warehouse_id', 'disabled')) !!}
                        
                    </div>
                </div>
            </div>
    
    
            
            <div class="row mt-2">
    
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
                                    
                                    <table class="table table-hover table-sm dynamic-add-row" id="receiving-products-table">
                                        <thead>
                                            <tr>
                                                <th scope="col" width="70%">Product</th>
                                                <th scope="col" width="10%">Requested</th>
                                                <th scope="col" width="10%">Delivered</th>
                                                <th scope="col" width="10%">Received</th>
                                            </tr>
                                        </thead>
    
                                        <tbody>
                                        @if(!is_null($receiving['details']))
                                        @foreach($receiving['details'] as $details)
                                            <tr>
                                                <td>{{ $details->product->name }}</td>
                                                <td class="text-right pr-1">{{ $details->dr_details->approved_qty }}</td>
                                                <td class="text-right pr-1">{{ $details->dr_details->delivered_items() }}</td>
                                                <td class="text-right pr-1">{{ $details->received_qty }}</td>
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
                <div class="col-sm-10 col-lg-8">
                    
                    <div class="input-field">
                        {!! Form::label('notes', 'Notes') !!}
                        {!! Form::textarea('notes', old('notes', $receiving->notes), array('class'=>'md-textarea form-control', 'rows' => '2', 'style'=>'padding-top: 5px', 'disabled')) !!}
                    </div>
    
                </div>
            </div>
    
            
            <div class="row">
                <div class="col-sm-10 col-lg-4">
                    
                    <div class="input-field">
                        {!! Form::label('prepared_by', 'Prepared By') !!}
                        {!! Form::text('prepared_by_name', old('prepared_by_name', $receiving->prepared_by_user->name), array('class'=>'form-control', 'disabled')) !!}
                    </div>
    
                </div>
            </div>
    
        </div>
    </div>
    
    {!! Form::close() !!} 
    
    @endsection
    
    
    
    @section('javascript')
    @stop
    