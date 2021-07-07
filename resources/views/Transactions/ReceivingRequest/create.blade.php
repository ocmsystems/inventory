@extends('admin.layouts.master')

@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="/dist/plugins/select2/select2.min.css">
@endpush
@section('content')

{!! Form::open(array('route' => config('quickadmin.route').'.receivingrequest.store', 'id' => 'form-with-validation', 'class' => 'form-horizontal')) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-shipping-fast"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.receivingrequest.index', 'Receiving Request' , null) !!} / New</h3>

        <div class="btn-group ml-3">
            {!! Form::submit( 'Save as Draft' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}
            {!! Form::submit( 'Save as Requested' , array('name' => 'status', 'class' => 'btn btn-primary ml-2')) !!}
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


<div class="card">
    <div class="card-header no-border">
        <div class="row">

            <div class="col-sm-10 col-lg-2 offset-lg-4">
                <div class="input-field">
                    {!! Form::label('requested_date', 'Request Date') !!}
                    {!! Form::text('requested_date', old('requested_date', date("M d, Y")), array('class'=>'form-control datetimepicker')) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10 col-lg-4">
                <div class="input-field">
                    {!! Form::label('project_name', 'Project Name') !!}
                    {!! Form::text('project_name', old('project_name'), array('class'=>'form-control')) !!}
                    
                </div>
            </div>
            <div class="col-sm-10 col-lg-3">
                <div class="input-field">
                    {!! Form::label('project_number', 'Project Number') !!}
                    {!! Form::text('project_number', old('project_number'), array('class'=>'form-control')) !!}
                    
                </div>
            </div>
        </div>
                
        <div class="row">
            <div class="col-sm-10 col-lg-4">
                <div class="input-field">
                    {!! Form::label('client_name', 'Client Name') !!}
                    {!! Form::text('client_name', old('client_name'), array('class'=>'form-control')) !!}
                    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-10 col-lg-4">
                {!! Form::label('receiving_type', 'Receiving Type') !!}
                <br>
                <label class="mr-3">
                    <input name="receiving_type" type="radio" value="10" />
                    <span>Pick-up from Client</span>
                </label>
                <label>
                    <input name="receiving_type" type="radio" value="20" />
                    <span>Received in warehouse</span>
                </label>
            </div>
        </div>

        <div class="row mb-3 mt-2">
            <div class="col-md-12 col-lg-4">
                <div class="input-field">
                    {!! Form::label('warehouselist_id', 'Receive From', array('class' => 'active')) !!}
                    {!! Form::select('warehouselist_id', $warehouselist, old('warehouselist_id'), array('class'=>'form-control selectpicker', 'title' => 'Select Warehouse')) !!}
                </div>
            </div>

            <div class="col-md-12 col-lg-4">
                <div class="input-field">
                    {!! Form::label('destination_id', 'Deliver To', array('class' => 'active')) !!}
                    {!! Form::select('destination_id', $warehouselist, old('destination_id'), array('class'=>'form-control selectpicker', 'title' => 'Select Warehouse')) !!}
                </div>
            </div>
            
        </div>
        <div class="row mb-3 mt-2">

            <div class="col-md-12 col-lg-3">
                <div class="input-field">
                    {!! Form::label('businessunit_id', 'Business Unit', array('class' => 'active')) !!}
                    {!! Form::select('businessunit_id', $businessunit, old('businessunit_id'), array('class'=>'form-control selectpicker', 'title' => 'Select Business Unit')) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-8">
                <div class="input-field">
                    {!! Form::label('notes', 'Notes') !!}
                    {!! Form::textarea('notes', old('notes'), array('class'=>'materialize-textarea form-control', 'rows' => '2', 'style'=>'padding-top: 0px', 'id'=>'notes')) !!}
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
                            <th scope="col" width="2%"></th>
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


<div class="card">
    <div class="card-header no-border">

        <div class="row">
            <div class="col-sm-10 col-lg-4">
                
                <div class="input-field">
                    {!! Form::label('prepared_by', 'Prepared By') !!}
                    {!! Form::text('prepared_by_name', old('prepared_by_name', auth()->user()->name), array('class'=>'form-control', 'readonly')) !!}
                    {!! Form::hidden('prepared_by', old('prepared_by', auth()->user()->id), array('class'=>'form-control')) !!}
                </div>

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