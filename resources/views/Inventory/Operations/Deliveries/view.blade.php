@extends('admin.layouts.master')


@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="/dist/plugins/select2/select2.min.css">
@endpush

@section('content')


{!! Form::open( array( 'route' => array(config('quickadmin.route').'.deliveries.update', $deliveries->id), 'id' => 'form-with-validation', 'method' => 'PATCH', 'class' => 'form-horizontal')) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-cart-plus"></i>
        </div>
    </div>
    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.deliveries.index', 'Deliveries' , null) !!} / 
            @if ($deliveries->status == 1)
            Approval / 
            @endif
            {{$deliveries->transaction_number}}

            
            @if ($deliveries->status == 2)
                {!! HTML::tag('span', $deliveries->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-info text-sm']) !!}
            @elseif ($deliveries->status == 3)
                {!! HTML::tag('span', $deliveries->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-success text-sm']) !!}
            @elseif ($deliveries->status == 10)
                {!! HTML::tag('span', $deliveries->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-danger text-sm']) !!}
            @endif

        </h3>

        
        @if ($deliveries->status == 1 && in_array('deliveries', auth()->user()->approval_modules()))
        <div class="btn-group ml-3">
            {!! Form::submit( 'Approve' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}
            {!! Form::submit( 'Decline' , array('name' => 'status', 'class' => 'btn btn-danger ml-1')) !!}
        </div>
        @endif
        {!! link_to_route(config('quickadmin.route').'.deliveries.index', 'Back to list' , null, array('class' => 'btn btn-light ml-3')) !!}
    </div>
</div>

<div class="clear"></div>


<div class="card">
    <div class="card-header no-border">

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
            </ul>
        </div>
        @endif


        <div class="form-group">

            <div class="row">

                <div class="col-sm-10 col-lg-3">
                    <div class="input-field">
                        {!! Form::label('contact_person', 'Contact Person*') !!}
                        {!! Form::text('contact_person', old('contact_person', $deliveries->contact_person), array('class'=>'form-control', 'readonly' => true)) !!}
                    </div>
                </div>


                <div class="col-sm-10 col-lg-2 offset-lg-2">
                    <div class="input-field">
                        {!! Form::label('scheduled_date', 'Scheduled Date') !!}
                        {!! Form::text('scheduled_date', old('scheduled_date', date("M d, Y", strtotime($deliveries->delivery_date)) ), array('class'=>'form-control', 'readonly' => true)) !!}
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-10 col-lg-4">
                    <div class="input-field">
                        {!! Form::label('source_warehouse_id', 'Source Location*', array('class'=>'active')) !!}
                        {!! Form::text('source_warehouse', old('source_warehouse', $deliveries->source_warehouse->name), array('class'=>'form-control', 'readonly' => true)) !!}
                        {!! Form::hidden('source_warehouse_id', old('source_warehouse_id', $deliveries->source_warehouse_id), array('class'=>'form-control')) !!}
                        
                    </div>
                </div>

                <div class="col-sm-10 col-lg-2 offset-lg-1">
                    <div class="input-field">
                        {!! Form::label('source_document', 'Source Document') !!}
                        {!! Form::text('source_document', old('source_document', $deliveries->source_document), array('class'=>'form-control', 'readonly' => true)) !!}
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-10 col-lg-4">
                    <div class="input-field">
                        {!! Form::label('destination_warehouse_id', 'Destination Location*', array('class'=>'active')) !!}
                        {!! Form::text('destination_warehouse', old('scheduled_date', $deliveries->destination_warehouse->name), array('class'=>'form-control', 'readonly' => true)) !!}
                        {!! Form::hidden('destination_warehouse_id', old('destination_warehouse_id', $deliveries->destination_warehouse_id), array('class'=>'form-control')) !!}
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
                        <li class="nav-item">
                          <a class="nav-link" id="adtl-info-tab-attr" data-toggle="tab" href="#adtl-info-attr" role="tab" aria-controls="adtl-info-attr"
                            aria-selected="false">Additional Info</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="notes-tab-attr" data-toggle="tab" href="#notes-attr" role="tab" aria-controls="notes-attr"
                            aria-selected="false">Notes</a>
                        </li>
                    </ul>
                    
                    
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="product-attr" role="tabpanel" aria-labelledby="product-tab-attr">
                            <div class="row">
                                <div class="col-sm-10 col-lg-12">
                                    
                                    <table class="table table-hover table-sm dynamic-add-row" id="deliveries-products-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Product</th>
                                                <th class="text-center" scope="col" width="8%">Demand</th>
                                                <th class="text-center" scope="col" width="13%">Approved</th>

                                                @if($deliveries->status == 2 || $deliveries->status == 3)
                                                <th width="8%">Delivered</th>
                                                @endif                                            
                                            </tr>
                                        </thead>

                                        <tbody>

                                    @if(!is_null($deliveries['details']))
                                        
                                        @foreach($deliveries['details'] as $details)
                                            <tr>
                                                <td>
                                                    <div class="input-field mt-0 mb-0">
                                                        {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                                                        {{ $details->product->name }}
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="input-field mt-0 mb-0">
                                                        {{ $details->requested_qty }}
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="input-field mt-0 mb-0">
                                                        @if($deliveries->status < 2)
                                                        {!! Form::number('details[approved_qty]['.$details->id.']', old('product'), array('placeholder' => '0', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode >= 48', 'min' => '0', 'step' => '1')) !!}
                                                        @else
                                                        {{ $details->approved_qty }}
                                                        @endif
                                                    </div>
                                                </td>

                                                @if($deliveries->status == 2 || $deliveries->status == 3)
                                                <td class="text-right">
                                                    {{ $details->delivered_items() }}
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach

                                    @endif

                                        </tbody>

                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="adtl-info-attr" role="tabpanel" aria-labelledby="adtl-info-tab-attr">

                            <div class="row">
                                <div class="col-sm-10 col-lg-12">

                                    <div class="input-field"> {!! Form::label('priority', 'Priority', array('class'=>'active')) !!} </div>
                                    <div class="radio-buttons-wrapper">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="defaultInline1" name="priority" value="1"  @if($deliveries->priority == 1) checked @endif />
                                            <label class="custom-control-label" for="defaultInline1">Normal</label>
                                        </div>
                                        <!-- Default inline 2-->
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="defaultInline2" name="priority" value="2"  @if($deliveries->priority == 2) checked @endif />
                                            <label class="custom-control-label" for="defaultInline2">Urgent</label>
                                        </div>
                                        <!-- Default inline 3-->
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="defaultInline3" name="priority" value="3" @if($deliveries->priority == 3) checked @endif />
                                            <label class="custom-control-label" for="defaultInline3">Very Urgent</label>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-10 col-lg-4">
                                    
                                    <div class="input-field">
                                        {!! Form::label('prepared_by', 'Prepared By') !!}
                                        {!! Form::text('prepared_by_name', old('prepared_by_name', $deliveries->prepared_by_user->name), array('class'=>'form-control', 'readonly')) !!}
                                        {!! Form::hidden('prepared_by', old('prepared_by', $deliveries->prepared_by), array('class'=>'form-control')) !!}
                                    </div>

                                </div>
                            </div>
    
                        </div>
                        <div class="tab-pane" id="notes-attr" role="tabpanel" aria-labelledby="notes-tab-attr">

                            <div class="row">
                                <div class="col-sm-12 col-lg-8">
                                    <div class="input-field">
                                        {{ $deliveries->notes }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
                
            </div>

        </div>

    </div>
</div>

<div class="clear"></div>

{!! Form::close() !!}

@endsection


@section('javascript')

<script type="text/javascript" src="/dist/plugins/select2/select2.full.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        var counter = {{ count($deliveries['details']) }};
        
        initializeSelect2( $('.product-select') );

        $("#addrow").on("click", function () {
            var newRow = $("#table-row-to-clone").clone();
            newRow.removeAttr('class id');
            newRow.find('[disabled="true"]').removeAttr("disabled");

            $("#deliveries-products-table > tbody").append(newRow);

            newRow.find(".product").attr('id', 'productSelect' + counter);
            initializeSelect2( $('#productSelect' + counter) );

            $('#productSelect' + counter).focus();
            
            counter++;
        });

        $('#scheduled_date').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'MMM DD, YYYY'
            },
            minDate: moment()  
        });


        $("#deliveries-products-table").on("click", ".removeRow", function (event) {
            $(this).closest("tr").remove();       
        });


        function initializeSelect2(selectElementObj) {
            selectElementObj.select2({
                placeholder: 'Select Product',
                ajax: {
                    url: '/api/products/get',
                    dataType: "json",
                    type: 'GET',
                    delay: 250,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    data: function (params) {
                        var query = {
                            search: params.term,
                            type: 'public'
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data.results
                        };
                    }
                }
            });
        }
    });
</script>

@stop