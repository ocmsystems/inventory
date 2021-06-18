@extends('admin.layouts.master')

@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="/dist/plugins/select2/select2.min.css">
@endpush
@section('content')
{!! Form::model($inventoryadjustments, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'method' => 'PATCH', 'route' => array(config('quickadmin.route').'.inventoryadjustments.update', $inventoryadjustments->id))) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-boxes"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.inventoryadjustments.index', 'Inventory Adjustments' , null) !!}  / {{ $inventoryadjustments->transaction_number }}</h3>

        <div class="btn-group ml-3">
                {!! Form::submit( 'Save as Draft' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}
                {!! Form::submit( 'Submit for Approval' , array('name' => 'status', 'class' => 'btn btn-primary ml-2')) !!}
                {!! link_to_route(config('quickadmin.route').'.inventoryadjustments.index', 'Discard' , null, array('class' => 'btn btn-link')) !!}
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

            <div class="col-sm-10 col-lg-5">
                <div class="input-field">
                    <div class="input-field">
                        {!! Form::label('warehouse_id', 'Source Location*', array('class'=>'active')) !!}
                    </div>
                    {!! Form::select('warehouse_id', $warehouselist, old('warehouse_id', $inventoryadjustments->warehouse_id), array('class'=>'form-control selectpicker', 'title' => 'Choose location..', 'data-live-search' => 'true')) !!}
                    
                </div>
            </div>


            <div class="col-sm-10 col-lg-2 mt-2">
                <div class="input-field">
                    {!! Form::label('date', 'Date*') !!}
                    {!! Form::text('date', old('date', date('M d, Y', strtotime($inventoryadjustments->date))), array('class'=>'form-control datepicker')) !!}
                </div>
            </div>
                
        </div>

        <div class="row">

            <div class="col-sm-10 col-lg-4">
                <div class="input-field">
                    {!! Form::label('contact_person', 'Contact Person') !!}
                    {!! Form::text('contact_person', old('contact_person', $inventoryadjustments->contact_person), array('class'=>'form-control')) !!}
                    
                </div>
            </div>
            
            <div class="col-sm-10 col-lg-3 offset-lg-1">
                <div class="input-field">
                    {!! Form::label('source_document', 'Source Document') !!}
                    {!! Form::text('source_document', old('source_document', $inventoryadjustments->source_document), array('class'=>'form-control')) !!}
                    
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
                                            <th scope="col" width="2%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!is_null($inventoryadjustments->details))
                                        @foreach ($inventoryadjustments->details as $details)
                                            <tr>
                                                <td>
                                                    <div class="input-field mt-0 mb-0">
                                                        {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                                                        {!! Form::select('product[name][]', [$details->product->id => $details->product->name], old('product_id'), array('class'=>'form-control pt-0 mb-0 product-select')) !!}
                                                    </div>
                                                </td>             
                                                <td class="text-right"><span class="onHandItem"> {{ $details->product->on_hand($inventoryadjustments->warehouse_id) }} </span></td>
                                                <td>
                                                    <div class="input-field mt-0 mb-0">
                                                        {!! Form::number('product[adjustment_qty][]', old('product', $details->adjusted_quantity), array('placeholder' => '0', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode >= 48', 'min' => '0', 'step' => '1')) !!}
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" class="removeRow btn btn-xs text-left waves-effect waves-light"><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @endif
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="3" style="text-align: left;">
                                                {{ link_to('#', 'Add a line', array('id' => 'addrow', 'class' => 'btn btn-xs text-left')) }}
                                            </td>
                                        </tr>
                                    </tfoot>

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
                    {!! Form::textarea('notes', old('notes', $inventoryadjustments->notes), array('class'=>'materialize-textarea form-control', 'rows' => '2', 'style'=>'padding-top: 0px')) !!}
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
    
    <table class="hidden">
        
        <tbody>
            <tr id="table-row-to-clone" class="hidden">
                <td>
                    <div class="input-field mt-0 mb-0">
                        {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                        {!! Form::select('product[name][]', [], old('destination_warehouse_id'), array('class'=>'form-control pt-0 mb-0 product', 'disabled' => 'true')) !!}
                        {!! Form::hidden('product[id][]', old('product'), array('disabled'=> 'true')) !!}
                    </div>
                </td>
                <td class="text-right"><span class="onHandItem"></span></td>
                <td>
                    <div class="input-field mt-0 mb-0">
                        {!! Form::number('product[adjustment_qty][]', old('product'), array('placeholder' => '0', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode >= 48', 'disabled'=> 'true', 'min' => '0', 'step' => '1')) !!}
                    </div>
                </td>
                <td>
                    <a href="#" class="removeRow btn btn-xs text-left waves-effect waves-light"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
        </tbody>
    
    </table>
    
    {!! Form::close() !!} 
    
    @endsection
    
    
    @section('javascript')
    
    <script type="text/javascript" src="/dist/plugins/select2/select2.full.js"></script>
    <script type="text/javascript" src="/dist/plugins/bootstrap-autocomplete/bootstrap-autocomplete.min.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            var counter = {{ count($inventoryadjustments['details']) }};
            
            initializeSelect2( $('.product-select') );

            $("#warehouse_id").change(function(){
                $("#replenishments-products-table > tbody").html('');
            });
            $("#addrow").on("click", function () {
                if( typeof $("#warehouse_id").val() != 'undefined' && $("#warehouse_id").val() != ''){
                    cloneRow();
                }else{
                    alert("You must select a store first.");
                }
            });
    
            $("#replenishments-products-table").on("click", ".removeRow", function (event) {
                $(this).closest("tr").remove();       
            });
    
            
            function cloneRow(){
                var newRow = $("#table-row-to-clone").clone();
                newRow.removeAttr('class id');
                newRow.find('[disabled="true"]').removeAttr("disabled");
                
                $("#replenishments-products-table > tbody").append(newRow)
                newRow.find(".product").attr('id', 'productSelect' + counter);
                
                initializeSelect2( $('#productSelect' + counter) );
    
                $('#productSelect' + counter).focus();
                
                counter++;
            }
    
    
    
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
                                type: 'inventory',
                                warehouse: $("#warehouse_id").val(),
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
                }).on('select2:select', function (e) {
                // Do something
                    $(this).parent().parent().parent().find("span.onHandItem").text(parseFloat(e.params.data.on_hand));       
                });;
            }
        });
    </script>
    
    @stop