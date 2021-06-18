@extends('admin.layouts.master')


@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="/dist/plugins/select2/select2.min.css">
@endpush

@section('content')


{!! Form::open( array( 'route' => array(config('quickadmin.route').'.pullouts.update', $pullouts->id), 'id' => 'form-with-validation', 'method' => 'PATCH', 'class' => 'form-horizontal')) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-cart-plus"></i>
        </div>
    </div>
    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.pullouts.index', 'Pull Outs' , null) !!} / 
            @if ($pullouts->status == 1)
            Approval / 
            @endif
            {{$pullouts->transaction_number}}

            
            @if ($pullouts->status == 2) 
                {!! HTML::tag('span', $pullouts->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-success text-sm']) !!}
            @elseif ($pullouts->status == 10)
                {!! HTML::tag('span', $pullouts->statusValue(), ['class' => 'pl-1 pr-1 mb-2 bg-danger text-sm']) !!}
            @endif


        </h3>
        @if ($pullouts->status == 1 && in_array('pullouts', auth()->user()->approval_modules()) )
        <div class="btn-group ml-3">
            {!! Form::submit( 'Approve' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}
            {!! Form::submit( 'Decline' , array('name' => 'status', 'class' => 'btn btn-danger ml-1')) !!}
        </div>
        @endif
        @if ($pullouts->status == 2 && in_array('pullouts', auth()->user()->approval_modules()) )
            {!! Form::submit( 'Cancel' , array('name' => 'status', 'class' => 'btn btn-warning ml-3')) !!}
        @endif
        {!! link_to_route(config('quickadmin.route').'.pullouts.index', 'Back to list' , null, array('class' => 'btn btn-light ml-3')) !!}
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
                        {!! Form::text('contact_person', old('contact_person', $pullouts->prepared_by_user->name), array('class'=>'form-control', 'readonly' => true)) !!}
                    </div>
                </div>


                <div class="col-sm-10 col-lg-2 offset-lg-2">
                    <div class="input-field">
                        {!! Form::label('datetime_prepared', 'Prepared Date', ['class' => 'active']) !!}
                        {!! Form::text('datetime_prepared', old('datetime_prepared', date("M d, Y", strtotime($pullouts->datetime_prepared))) , array('class'=>'form-control', 'readonly' => true)) !!}
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-10 col-lg-4">
                    <div class="input-field">
                        {!! Form::label('warehouse_id', 'Store Location*', array('class'=>'active')) !!}
                        {!! Form::text('warehouse_id', old('warehouse_id', $pullouts->warehouse->name), array('class'=>'form-control', 'readonly' => true)) !!}
                        {!! Form::hidden('warehouse_id', old('warehouse_id', $pullouts->warehouse_id), array('class'=>'form-control')) !!}
                    </div>
                </div>


                <div class="col-sm-10 col-lg-2 offset-lg-1">
                    <div class="input-field">
                        {!! Form::label('pullout_date', 'Pull Out Date') !!}
                        {!! Form::text('pullout_date', old('pullout_date', date("M d, Y", strtotime($pullouts->pullout_date))), array('class'=>'form-control datetimepicker')) !!}
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-10 col-lg-4"></div>

                <div class="col-sm-10 col-lg-2 offset-lg-1">
                    <div class="input-field">
                        {!! Form::label('source_document', 'Source Document') !!}
                        {!! Form::text('source_document', old('source_document', $pullouts->source_document), array('class'=>'form-control', 'readonly' => true)) !!}
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
                                    
                                    <table class="table table-hover table-sm dynamic-add-row" id="pullouts-products-table">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-left" width="50%">Product</th>
                                                    <th scope="col" width="10%">Quantity</th>
                                                    <th scope="col" width="35%">Note</th>
                                                    <th scope="col" width="5%">Action</th>
                                                </tr>
                                            </thead>

                                        <tbody>

                                    @if(!is_null($pullouts['details']))
                                        
                                        @foreach($pullouts['details'] as $details)
                                            <tr>
                                                <td>
                                                    <div class="input-field mt-0 mb-0">
                                                        {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                                                        {{ $details->product->name }}
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="input-field mt-0 mb-0">
                                                        {{ $details->qty }}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="input-field mt-0 mb-0">
                                                        {{ $details->note }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($pullouts->status == 2 && in_array('pullouts', auth()->user()->approval_modules()) )
                                                        {!! Form::submit( 'Cancel Item' , array('name' => 'status', 'class' => 'btn btn-warning ml-3')) !!}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endif

                                        </tbody>

                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="notes-attr" role="tabpanel" aria-labelledby="notes-tab-attr">

                            <div class="row">
                                <div class="col-sm-12 col-lg-8">
                                    <div class="input-field">
                                        {{ $pullouts->notes }}
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
        var counter = {{ count($pullouts['details']) }};
        
        initializeSelect2( $('.product-select') );

        $("#addrow").on("click", function () {
            var newRow = $("#table-row-to-clone").clone();
            newRow.removeAttr('class id');
            newRow.find('[disabled="true"]').removeAttr("disabled");

            $("#pullouts-products-table > tbody").append(newRow);

            newRow.find(".product").attr('id', 'productSelect' + counter);
            initializeSelect2( $('#productSelect' + counter) );

            $('#productSelect' + counter).focus();
            
            counter++;
        });

        $('#scheduled_date').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'MMM DD, YYYY hh:mm A'
            },
            minDate: moment()  
        });


        $("#pullouts-products-table").on("click", ".removeRow", function (event) {
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