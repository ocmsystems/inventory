@extends('admin.layouts.master')


@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="/dist/plugins/select2/select2.min.css">
@endpush

@section('content')

{!! Form::open( array( 'route' => array(config('quickadmin.route').'.returns.update', $returns->id), 'id' => 'form-with-validation', 'method' => 'PATCH', 'class' => 'form-horizontal')) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.returns.index', 'Returns' , null) !!} / {{$returns->transaction_number}}</h3>

        <div class="btn-group ml-3">
            {!! Form::submit( 'Save as Draft' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}
            {!! Form::submit( 'Submit for Approval' , array('name' => 'status', 'class' => 'btn btn-primary ml-1')) !!}
            
            {!! link_to_route(config('quickadmin.route').'.returns.index', 'Discard' , null, array('class' => 'btn btn-link ml-1')) !!}
        </div>
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
                        {!! Form::label('prepared_by', 'Prepared By') !!}
                        {!! Form::text('prepared_by_name', old('prepared_by_name', auth()->user()->name), array('class'=>'form-control', 'readonly')) !!}
                        {!! Form::hidden('prepared_by', old('prepared_by', auth()->user()->id), array('class'=>'form-control')) !!}
                    </div>

                </div>

                <div class="col-sm-10 col-lg-2 offset-lg-2">
                    <div class="input-field">
                        {!! Form::label('datetime_prepared', 'Prepared Date') !!}
                        {!! Form::text('datetime_prepared', old('datetime_prepared', date("F d, Y", strtotime($returns->datetime_prepared)) ), array('class'=>'form-control datetimepicker')) !!}
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-10 col-lg-4">
                    <div class="input-field">
                        {!! Form::label('destination_warehouse_id', 'Destination Location*', array('class'=>'active')) !!}
                        {!! Form::select('destination_warehouse_id', $warehouselist, old('destination_warehouse_id', $returns->destination_warehouse_id), array('class'=>'form-control selectpicker', 'title' => 'Choose location..', 'data-live-search' => 'true')) !!}
                    </div>
                </div>

                <div class="col-sm-10 col-lg-2 offset-lg-1">
                    <div class="input-field">
                        {!! Form::label('source_document', 'Source Document') !!}
                        {!! Form::text('source_document', old('source_document', $returns->source_document), array('class'=>'form-control')) !!}
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
                          <a class="nav-link" id="notes-tab-attr" data-toggle="tab" href="#notes-attr" role="tab" aria-controls="notes-attr"
                            aria-selected="false">Notes</a>
                        </li>
                    </ul>
                    
                    
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="product-attr" role="tabpanel" aria-labelledby="product-tab-attr">
                            <div class="row">
                                <div class="col-sm-10 col-lg-12">
                                    
                                    <table class="table table-hover table-sm dynamic-add-row" id="returns-products-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Product</th>
                                                <th scope="col" width="13%">Qty</th>
                                                <th scope="col" width="5%"></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr id="table-row-to-clone" class="hidden">
                                                <td>
                                                    <div class="input-field mt-0 mb-0">
                                                        {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                                                        {!! Form::select('product[name][]', [], old('products'), array('class'=>'form-control pt-0 mb-0 product', 'disabled' => 'true')) !!}
                                                        {!! Form::hidden('product[id][]', old('product'), array('disabled'=> 'true')) !!}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-field mt-0 mb-0">
                                                        {!! Form::number('product[request_qty][]', old('product'), array('placeholder' => '0', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode >= 48', 'disabled'=> 'true', 'min' => '0', 'step' => '1')) !!}
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" class="removeRow btn btn-xs text-left waves-effect waves-light"><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>

                                    @if(!is_null($returns['details']))
                                        
                                        @foreach($returns['details'] as $details)
                                            <tr>
                                                <td>
                                                    <div class="input-field mt-0 mb-0">
                                                        {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                                                        {!! Form::select('product[name][]', [$details->product->id => $details->product->name], old('destination_warehouse_id'), array('class'=>'form-control pt-0 mb-0 product-select')) !!}
                                                        {!! Form::hidden('product[id][]', old('product'), array('disabled'=> 'true')) !!}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-field mt-0 mb-0">
                                                        {!! Form::number('product[request_qty][]', old('product', $details->qty), array('placeholder' => '0', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode >= 48', 'min' => '0', 'step' => '1')) !!}
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
                        <div class="tab-pane" id="notes-attr" role="tabpanel" aria-labelledby="notes-tab-attr">

                            <div class="row">
                                <div class="col-sm-12 col-lg-8">
                                    <div class="input-field">
                                        {!! Form::textarea('notes', old('notes', $returns->notes), array('class'=>'materialize-textarea form-control', 'rows' => '2', 'style'=>'padding-top: 0px', 'id'=>'notes')) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
                
            </div>


            <div class="clear mt-3"></div>

                <div class="col-sm-10 col-lg-8">
                    

                </div>
            </div>

            <div class="clear mt-3"></div>





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
        var counter = {{ count($returns['details']) }};

        M.textareaAutoResize($('#notes'));

        initializeSelect2( $('.product-select') );

        $("#addrow").on("click", function () {
            var newRow = $("#table-row-to-clone").clone();
            newRow.removeAttr('class id');
            newRow.find('[disabled="true"]').removeAttr("disabled");

            $("#returns-products-table > tbody").append(newRow);

            newRow.find(".product").attr('id', 'productSelect' + counter);
            
            initializeSelect2( $('#productSelect' + counter) );

            $('#productSelect' + counter).focus();
            
            counter++;
        });



        $("#returns-products-table").on("click", ".removeRow", function (event) {
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