@extends('admin.layouts.master')

@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="/dist/plugins/select2/select2.min.css">
@endpush

@section('content')

{!! Form::open(array('route' => config('quickadmin.route').'.deliveries.store', 'id' => 'form-with-validation', 'class' => 'form-horizontal')) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-truck"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.deliveries.index', 'Deliveries' , null) !!} / New</h3>

        <div class="btn-group ml-3">
            {!! Form::submit( 'Save as Draft' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}
            {!! Form::submit( 'Submit for Approval' , array('name' => 'status', 'class' => 'btn btn-primary ml-1')) !!}
            
            {!! link_to_route(config('quickadmin.route').'.deliveries.index', 'Discard' , null, array('class' => 'btn btn-link')) !!}
        </div>
    </div>
</div>

<div class="clear"></div>


<div class="card">
    <div class="card-header no-border">

        <div class="col-lg-7 col-sm-12">

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
                        {!! Form::label('contact_person', 'Contact Person') !!}
                        {!! Form::text('contact_person', old('contact_person'), array('class'=>'form-control')) !!}
                        
                    </div>
                </div>
                
                <div class="col-sm-10 col-lg-4 offset-lg-2">
                    <div class="input-field">
                        {!! Form::label('delivery_date', 'Delivery Date', array('class' => 'active')) !!}
                        {!! Form::text('delivery_date', old('delivery_date', date("M d, Y")), array('class'=>'form-control datepicker')) !!}
                    </div>
                </div>
                
            </div>

            <div class="row">

                <div class="col-sm-10 col-lg-6">
                    <div class="input-field">
                        <div class="input-field">
                            {!! Form::label('source_warehouse_id', 'Source Location*', array('class'=>'active')) !!}
                        </div>
                        {!! Form::select('source_warehouse_id', $warehouselist, old('source_warehouse_id'), array('class'=>'form-control selectpicker', 'title' => 'Choose location..', 'data-live-search' => 'true')) !!}
                        
                    </div>
                </div>
                

                <div class="col-sm-10 col-lg-4">
                    <div class="input-field">
                        {!! Form::label('source_document', 'Source Document') !!}
                        {!! Form::text('source_document', old('source_document'), array('class'=>'form-control advancedAutoComplete', 'autocomplete' => 'off')) !!}
                        
                        <a href="javascript:;" class="btn btn-link lookup"><i class="fas fa-binoculars"></i></a>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-10 col-lg-6">
                    <div class="input-field">{!! Form::label('destination_warehouse_id', 'Destination Location*', array('class'=>'active')) !!}</div>
                    {!! Form::select('destination_warehouse_id', $warehouselist, old('destination_warehouse_id'), array('class'=>'form-control selectpicker', 'title' => 'Choose location..', 'data-live-search' => 'true')) !!}
                </div>

            </div>
            

            <div class="row mt-5">

                <div class="col-sm-12 col-lg-12">

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
                                    
                                    <table class="table table-hover table-sm dynamic-add-row" id="replenishments-products-table">
                                        <thead>
                                            <tr>
                                                <th scope="col" width="80%">Product</th>
                                                <th scope="col" width="10%">Demand</th>
                                                <th scope="col">Approved</th>
                                                <th scope="col" width="2%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="table-row-to-clone" class="hidden">
                                                <td>
                                                    <div class="input-field mt-0 mb-0">
                                                        {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                                                        {!! Form::select('product[name][]', [], old('destination_warehouse_id'), array('class'=>'form-control pt-0 mb-0 product-select', 'disabled' => 'true')) !!}
                                                        {!! Form::hidden('product[id][]', old('product'), array('disabled'=> 'true')) !!}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-field mt-0 mb-0">
                                                        {!! Form::number('product[request_qty][]', old('product'), array('placeholder' => '0', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode >= 48', 'disabled'=> 'true', 'min' => '0', 'step' => '1')) !!}
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td>
                                                    <a href="#" class="removeRow btn btn-xs text-left waves-effect waves-light"><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
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

                        <div class="tab-pane" id="adtl-info-attr" role="tabpanel" aria-labelledby="adtl-info-tab-attr">

                            <div class="row">
                                <div class="col-sm-10 col-lg-12">

                                    <div class="input-field">{!! Form::label('priority', 'Priority', array('class'=>'active')) !!}</div>
                                    <div class="radio-buttons-wrapper">
                                        <p>
                                            <label>
                                                <input class="with-gap" name="priority" type="radio" value="1" checked />
                                                <span>Normal</span>
                                            </label>

                                            
                                            <label>
                                                <input class="with-gap" name="priority" type="radio" value="2" checked />
                                                <span>Urgent</span>
                                            </label>
                                            
                                            <label>
                                                <input class="with-gap" name="priority" type="radio" value="3" checked />
                                                <span>Very Urgent</span>
                                            </label>
                                        </p>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-10 col-lg-4">
                                    <div class="input-field">
                                        {!! Form::label('prepared_by', 'Prepared By') !!}
                                        {!! Form::text('prepared_by_name', old('prepared_by_name', auth()->user()->name), array('class'=>'form-control', 'readonly')) !!}
                                        {!! Form::hidden('prepared_by', old('prepared_by', auth()->user()->id), array('class'=>'form-control')) !!}
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="tab-pane" id="notes-attr" role="tabpanel" aria-labelledby="notes-tab-attr">

                            <div class="row">
                                <div class="col-sm-12 col-lg-8">
                                    <div class="input-field">
                                        {!! Form::textarea('notes', old('notes'), array('class'=>'materialize-textarea form-control', 'rows' => '2', 'style'=>'padding-top: 0px')) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
            
            </div>
        </div>
        
        <div class="offset-lg-1 col-lg-4 col-sm-12">
            <div id="warehouse_inventory" style="max-height: 500px;"></div>
        </div>
    </div>
</div>

{!! Form::close() !!} 

@endsection



@section('javascript')

<script type="text/javascript" src="/dist/plugins/select2/select2.full.js"></script>
<script type="text/javascript" src="/dist/plugins/bootstrap-autocomplete/bootstrap-autocomplete.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        var counter = 0;

        

        AutoCompleteField.ajaxURL = '{{ route("api.replenishments.search") }}';
        AutoCompleteField.init();


        $('.advancedAutoComplete').on('autocomplete.select', function(e, item){
            if(item.details.length > 0){
                $("#replenishments-products-table tbody tr").not(':first').remove();
                
                $('#destination_warehouse_id').selectpicker('val', item.destination_warehouse_id);
                $.each(item.details, function(i, val){
                    cloneRow(val);
                });
            }
        });
        
        $('#destination_warehouse_id').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
            UTILS.ajaxRequest({url: '/api/productinventory/get', data: {'wid': clickedIndex}, 'type': 'POST' }, function(params, data){
                console.info(data);

                var html = '';
                if(typeof data.products != 'undefined'){
                    html += '<h4 class="mb-4">Current Stocks for '+data.warehouse.name+'</h4>';
                    for(var i = 0; i < data.products.length; i++){
                        var item = data.products[i];

                        var bg_class = '';

                        if(item.on_hand <= item.reorder_quantity){
                            bg_class = 'bg-warning';
                        }

                        if(item.on_hand <= item.critical_quantity){
                            bg_class = 'bg-danger';
                        }
                        html += '<div class="row mb-2 p-3 '+bg_class+'">';
                            html += '<div class="col-lg-8">';
                                html += '<b>' + item.product_name + ' ' + item.product_desc + '</b>';
                            html += '</div>';
                            html += '<div class="col-lg-4">';
                                html += item.on_hand;
                            html += '</div>';
                        html += '</div>';
                    }
                }
                $("#warehouse_inventory").html(html);
            });
        });

        
        $("#addrow").on("click", function () {
            cloneRow();
        });

        $("#replenishments-products-table").on("click", ".removeRow", function (event) {
            $(this).closest("tr").remove();       
        });


        function cloneRow(item){
            var newRow = $("#table-row-to-clone").clone();
            newRow.removeAttr('class id');
            newRow.find('[disabled="true"]').removeAttr("disabled");
            
            if(typeof item != 'undefined'){
                newRow.find('.product-select').append('<option value="' + item.product_id + '">'+ item.product.name +'</option>')
                newRow.find('input[name="product[request_qty][]"]').val(item.approved_qty);
            }
            $("#replenishments-products-table > tbody").append(newRow)
            newRow.find(".product-select").attr('id', 'productSelect' + counter);
            
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