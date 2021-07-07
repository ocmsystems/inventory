@extends('admin.layouts.master')

@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="/dist/plugins/select2/select2.min.css">
@endpush
@section('content')
{!! Form::model($receivingrequest, array('files' => true, 'class' => 'form-horizontal', 'id' => 'form-with-validation', 'method' => 'PATCH', 'route' => array(config('quickadmin.route').'.receivingrequest.update', $receivingrequest->id))) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-shipping-fast"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> 
            {!! link_to_route(config('quickadmin.route').'.receivingrequest.index', 'Receiving Request' , null) !!} / {{ $receivingrequest->transaction_number }}
        
            @if ($receivingrequest->status == 1)
                {!! HTML::tag('span', $receivingrequest->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-info text-sm']) !!}
            @elseif ($receivingrequest->status == 2)
                {!! HTML::tag('span', $receivingrequest->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-info text-sm']) !!}
            @elseif ($receivingrequest->status == 3)
                {!! HTML::tag('span', $receivingrequest->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-success text-sm']) !!}
            @elseif ($receivingrequest->status == 4)
                {!! HTML::tag('span', $receivingrequest->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-danger text-sm']) !!}
            @elseif ($receivingrequest->status == 5)
                {!! HTML::tag('span', $receivingrequest->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-success text-sm']) !!}
            @endif
        </h3>

        <div class="btn-group ml-3">
            @if($receivingrequest->status == 1)
            
                {!! Form::submit( 'Mark as Scheduled' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}

            @elseif($receivingrequest->status == 2)
            
                {!! Form::submit( 'Pick Up Successful' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}
                {!! Form::submit( 'Pick Up Unsuccessful' , array('name' => 'status', 'class' => 'btn btn-danger ml-2')) !!}
            
            @elseif($receivingrequest->status == 3)
            
                {!! Form::submit( 'Mark as Received' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}
                {!! Form::submit( 'Save Only' , array('name' => 'status', 'class' => 'btn btn-danger ml-2')) !!}
        
            @endif

            {!! link_to_route(config('quickadmin.route').'.receivingrequest.index', 'Discard' , null, array('class' => 'btn btn-link ml-2')) !!}
        </div>
    </div>
</div>

<div class="clear"></div>

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

@if($receivingrequest->status == 5)
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                    <div class="input-field">
                        <div>Receiving Form Copy: {{$receivingrequest->receiving_form_file}}</div>
                    </div>
                </div>
            </div>

            <table class="table table-hover table-sm dynamic-add-row mt-4" id="request-products-table">
                <thead>
                    <tr>
                        <th scope="col" class="text-left" width="35%">Item Name</th>
                        <th scope="col" width="13%">Brand</th>
                        <th scope="col" width="13%">Category</th>
                        <th scope="col" width="13%">SKU</th>
                        <th scope="col" width="13%">Requested Qty</th>
                        <th scope="col" width="11%">Delivered Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!is_null($receivingrequest['details']))
                        @foreach($receivingrequest['details'] as $details)
                            <tr>
                                <td>
                                    <div class="input-field mt-0 mb-0">
                                        {!! $details->product->name !!}
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: center;">
                                    {!! $details->requested_qty !!}
                                </td>
                                <td style="text-align: center;">
                                    {!! $details->received_qty !!}
                                </td>
                            </tr>
                        @endforeach

                    @endif

                </tbody>

            </table>
        </div>
    </div>

@endif
@if($receivingrequest->status == 3)
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                    <div class="file-field input-field">
                        <div class="btn btn-primary" style="line-height: 18px;height: auto;">
                            <span>Receiving Form</span>
                            {!! Form::file('filename') !!}
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Upload your excel file..">
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-hover table-sm dynamic-add-row mt-4" id="request-products-table">
                <thead>
                    <tr>
                        <th scope="col" class="text-left" width="35%">Item Name</th>
                        <th scope="col" width="13%">Brand</th>
                        <th scope="col" width="13%">Category</th>
                        <th scope="col" width="13%">SKU</th>
                        <th scope="col" width="13%">Requested Qty</th>
                        <th scope="col" width="11%">Delivered Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!is_null($receivingrequest['details']))
                                        
                        @foreach($receivingrequest['details'] as $details)
                            <tr>
                                <td>
                                    <div class="input-field mt-0 mb-0">
                                        {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                                        {!! Form::text('product[name][]', old('client_name', $details->product->name), array('class'=>'form-control', 'disabled')) !!}
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="input-field mt-0 mb-0">
                                        {!! Form::number('product[request_qty][]', old('product', $details->requested_qty), array('placeholder' => '0', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode >= 48', 'min' => '0', 'step' => '1', 'disabled')) !!}
                                    </div>
                                </td>
                                <td>
                                    <div class="input-field mt-0 mb-0">
                                        {!! Form::number('details[qty]['.$details->product_id.']', old('product', $details->received_qty), array('placeholder' => '0', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode >= 48', 'min' => '0', 'step' => '1')) !!}
                                        {!! Form::hidden('details[id]['.$details->product_id.']', old('product', $details->id) ) !!}
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    @endif

                </tbody>

            </table>

            <br>
        </div>
    </div>
@endif


<div class="card">
    <div class="card-header">
        @if($receivingrequest->status == 1)
            <div class="row">
                <div class="col-sm-10 col-lg-2 offset-lg-4">
                    <div class="input-field">
                        {!! Form::label('scheduled_date', 'Schedule Date') !!}
                        {!! Form::text('scheduled_date', old('scheduled_date', $receivingrequest->scheduled_date ? date("M d, Y", strtotime($receivingrequest->scheduled_date)) : date("M d, Y") ), array('class'=>'form-control datetimepicker')) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-3">
                    <div class="input-field">
                        {!! Form::label('driver_id', 'Driver', array('class' => 'active')) !!}
                        {!! Form::select('driver_id', $drivers, old('businessunit_id', $receivingrequest->driver_id), array('class'=>'form-control selectpicker', 'title' => 'Select Driver')) !!}
                    </div>
                </div>

                <div class="col-md-12 col-lg-3 offset-lg-1">
                    <div class="input-field">
                        {!! Form::label('helper_id', 'Helper', array('class' => 'active')) !!}
                        {!! Form::select('helper_id', $helpers, old('businessunit_id', $receivingrequest->helper_id), array('class'=>'form-control selectpicker', 'title' => 'Select Helper')) !!}
                    </div>
                </div>
            </div>
            <br>
        @endif
        @if($receivingrequest->status >= 2)
            <div class="row">
                <div class="col-sm-10 col-lg-2 offset-lg-4">
                    <div class="input-field">
                        {!! Form::label('scheduled_date', 'Schedule Date') !!}
                        {!! Form::text('scheduled_date', old('scheduled_date', $receivingrequest->scheduled_date ? date("M d, Y", strtotime($receivingrequest->scheduled_date)) : date("M d, Y") ), array('class'=>'form-control datetimepicker', 'disabled')) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-3">
                    <div class="input-field">
                        {!! Form::label('driver_id', 'Driver', array('class' => 'active')) !!}
                        {!! Form::select('driver_id', $drivers, old('businessunit_id', $receivingrequest->driver_id), array('class'=>'form-control selectpicker', 'title' => 'Select Driver', 'disabled')) !!}
                    </div>
                </div>

                <div class="col-md-12 col-lg-3 offset-lg-1">
                    <div class="input-field">
                        {!! Form::label('helper_id', 'Helper', array('class' => 'active')) !!}
                        {!! Form::select('helper_id', $helpers, old('businessunit_id', $receivingrequest->helper_id), array('class'=>'form-control selectpicker', 'title' => 'Select Helper', 'disabled')) !!}
                    </div>
                </div>
            </div>
            <br>
        @endif
        
    </div>
</div>


<div class="card">
    <div class="card-header no-border">
        <div class="row">

            <div class="col-sm-10 col-lg-2 offset-lg-4">
                <div class="input-field">
                    {!! Form::label('requested_date', 'Request Date') !!}
                    {!! Form::text('requested_date', old('requested_date', date("M d, Y", strtotime($receivingrequest->requested_date))), array('class'=>'form-control datetimepicker', 'disabled')) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10 col-lg-4">
                <div class="input-field">
                    {!! Form::label('project_name', 'Project Name') !!}
                    {!! Form::text('project_name', old('project_name', $receivingrequest->project_name), array('class'=>'form-control', 'disabled')) !!}
                    
                </div>
            </div>
            <div class="col-sm-10 col-lg-3">
                <div class="input-field">
                    {!! Form::label('project_number', 'Project Number') !!}
                    {!! Form::text('project_number', old('project_number', $receivingrequest->project_number), array('class'=>'form-control', 'disabled')) !!}
                    
                </div>
            </div>
        </div>
                
        <div class="row">
            <div class="col-sm-10 col-lg-4">
                <div class="input-field">
                    {!! Form::label('client_name', 'Client Name') !!}
                    {!! Form::text('client_name', old('client_name', $receivingrequest->client_name), array('class'=>'form-control', 'disabled')) !!}
                    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-10 col-lg-4">
                {!! Form::label('receiving_type', 'Receiving Type') !!}
                <br>
                <label class="mr-3">
                    <input name="receiving_type" type="radio" value="10" <?php echo $receivingrequest->receiving_type == 10 ? 'checked':'';?> disabled/>
                    <span>Pick-up from Client</span>
                </label>
                <label>
                    <input name="receiving_type" type="radio" value="20" <?php echo $receivingrequest->receiving_type == 20 ? 'checked':'';?> disabled/>
                    <span>Received in warehouse</span>
                </label>
            </div>
        </div>

        <div class="row mb-3 mt-2">
            <div class="col-md-12 col-lg-4">
                <div class="input-field">
                    {!! Form::label('warehouselist_id', 'Receive From', array('class' => 'active')) !!}
                    {!! Form::select('warehouselist_id', $warehouselist, old('warehouselist_id', $receivingrequest->warehouselist_id), array('class'=>'form-control selectpicker', 'title' => 'Select Warehouse', 'disabled')) !!}
                </div>
            </div>

            <div class="col-md-12 col-lg-3">
                <div class="input-field">
                    {!! Form::label('businessunit_id', 'Business Unit', array('class' => 'active')) !!}
                    {!! Form::select('businessunit_id', $businessunit, old('businessunit_id', $receivingrequest->businessunit_id), array('class'=>'form-control selectpicker', 'title' => 'Select Business Unit', 'disabled')) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-8">
                <div class="input-field">
                    {!! Form::label('notes', 'Notes') !!}
                    {!! Form::textarea('notes', old('notes', $receivingrequest->notes), array('class'=>'materialize-textarea form-control', 'rows' => '2', 'style'=>'padding-top: 0px', 'id'=>'notes', 'disabled')) !!}
                </div>
            </div>
        </div>

    </div>
</div>

<div class="card">
    <div class="card-header no-border">

        <div class="row">
            <div class="col-sm-10 col-lg-12">
                
                <table class="table table-hover table-sm dynamic-add-row" id="request-products-table">
                    <thead>
                        <tr>
                            <th scope="col" class="text-left" width="40%">Item Name</th>
                            <th scope="col" width="15%">Brand</th>
                            <th scope="col" width="15%">Category</th>
                            <th scope="col" width="15%">SKU</th>
                            <th scope="col" width="13%">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="table-row-to-clone" class="hidden">
                            <td width="40%">
                                <div class="input-field mt-0 mb-0">
                                    {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                                    {!! Form::select('product[name][]', [], old(''), array('class'=>'form-control pt-0 mb-0 product-select', 'disabled' => 'true')) !!}
                                    {!! Form::hidden('product[id][]', old(''), array('disabled'=> 'true')) !!}
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <div class="input-field mt-0 mb-0">
                                    {!! Form::number('product[request_qty][]', old('product'), array('placeholder' => '0', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode >= 48', 'disabled'=> 'true', 'min' => '0', 'step' => '1')) !!}
                                </div>
                            </td>
                            <td>
                                <a href="#" class="removeRow btn btn-xs text-left waves-effect waves-light"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>


                        @if(!is_null($receivingrequest['details']))
                                            
                            @foreach($receivingrequest['details'] as $details)
                                <tr>
                                    <td>
                                        <div class="input-field mt-0 mb-0">
                                            {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                                            {!! Form::text('product[name][]', old('client_name', $details->product->name), array('class'=>'form-control', 'disabled')) !!}
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <div class="input-field mt-0 mb-0">
                                            {!! Form::number('product[request_qty][]', old('product', $details->requested_qty), array('placeholder' => '0', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode >= 48', 'min' => '0', 'step' => '1', 'disabled')) !!}
                                        </div>
                                    </td>
                                    <td>
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

{!! Form::close() !!} 

@endsection


@section('javascript')

<script type="text/javascript" src="/dist/plugins/select2/select2.full.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        var counter = 0;

        M.textareaAutoResize($('#notes'));

        $("#addrow").on("click", function () {
            var newRow = $("#table-row-to-clone").clone();
            newRow.removeAttr('class id');
            newRow.find('[disabled="true"]').removeAttr("disabled");

            $("#request-products-table > tbody").append(newRow)
            newRow.find(".product-select").attr('id', 'productSelect' + counter);
            
            initializeSelect2( $('#productSelect' + counter) );

            $('#productSelect' + counter).focus();
            
            counter++;
        });

        initializeSelect2( $('.product-select') );
    
        $("#request-products-table").on("click", ".removeRow", function (event) {
            $(this).closest("tr").remove();       
        });




    });


    function initializeSelect2(selectElementObj) {
            selectElementObj.select2({
                placeholder: 'Select Product',
                language: {
                noResults: function() {
                    return '{!! link_to_route('.productlist.create', 'Add New Product' , null, array('class' => 'btn btn-primary', 'target' => '_blank')) !!}';
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
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


</script>

@stop