@extends('admin.layouts.master')

@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="/dist/plugins/select2/select2.min.css">
@endpush

@section('content')

{!! Form::open(array('route' => array(config('quickadmin.route').'.receiving.update', $receiving->id), 'id' => 'form-with-validation', 'method' => 'PATCH', 'class' => 'form-horizontal')) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-handshake"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.receiving.index', 'Receiving' , null) !!} / {{ $receiving->transaction_number }}</h3>

        <div class="btn-group ml-3">
            {!! Form::submit( 'Save as Draft' , array('name' => 'status', 'class' => 'btn btn-primary')) !!}
            {!! Form::submit( 'Submit to Validate' , array('name' => 'status', 'class' => 'btn btn-primary ml-1')) !!}

            {!! link_to_route(config('quickadmin.route').'.receiving.index', 'Discard' , null, array('class' => 'btn btn-link')) !!}
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

            <div class="col-sm-10 col-lg-3">
                <div class="input-field">
                    {!! Form::label('source_document', 'Source Document', array('class'=>'active')) !!}
                    {{-- {!! Form::text('source_document', old('source_document'), array('id'=>'source_document', 'class'=>'form-control', 'autocomplete' => 'off')) !!} --}}
                </div>
                <div class="input-field mt-3">
                    {!! Form::select('delivery_id', [$receiving->delivery_id => $receiving->delivery->transaction_number], old('delivery_id'), array('class'=>'form-control pt-0 mb-0', 'id' => 'delivery_id')) !!}
                    {!! Form::hidden('source_document', old('source_document', $receiving->source_document), array('class'=>'form-control')) !!}
                </div>
            </div>

            <div class="col-sm-10 col-lg-3 offset-lg-2">
                <div class="input-field">
                    {!! Form::label('received_date', 'Receive Date') !!}
                    {!! Form::text('received_date', old('received_date', date("M d, Y", strtotime($receiving->received_date)) ), array('class'=>'form-control datepicker')) !!}
                    
                </div>
            </div>

        </div>

        
        <div class="row">

            <div class="col-sm-10 col-lg-4">
                <div class="input-field">{!! Form::label('receiving_warehouse', 'Receiving Location*', array('class'=>'active')) !!}</div>
                {!! Form::select('receiving_warehouse', $warehouselist, old('receiving_warehouse', $receiving->receiving_warehouse->id), array('class'=>'form-control selectpicker', 'disabled', 'title' => ' ', 'data-live-search' => 'true')) !!}
                {!! Form::hidden('receiving_warehouse_id', old('receiving_warehouse_id', $receiving->receiving_warehouse_id), array('class'=>'form-control', 'id' => 'receiving_warehouse_id')) !!}
            </div>
        

            <div class="col-sm-10 col-lg-3 offset-lg-1">
                <div class="input-field">
                    {!! Form::label('delivery_date', 'Delivery Date') !!}
                    {!! Form::text('delivery_date', old('delivery_date', $receiving->delivery->delivery_date), array('class'=>'form-control', 'readonly', 'id' => 'delivery_date')) !!}
                    
                </div>
            </div>

        </div>

        
        <div class="row">

            <div class="col-sm-10 col-lg-4">
                <div class="input-field">
                    <div class="input-field">
                        {!! Form::label('source_warehouse', 'Source Location*', array('class'=>'active')) !!}
                    </div>
                    {!! Form::select('source_warehouse', $warehouselist, old('source_warehouse', $receiving->delivery->source_warehouse_id), array('class'=>'form-control selectpicker', 'title' => ' ', 'disabled', 'data-live-search' => 'true')) !!}
                    {!! Form::hidden('source_warehouse_id', old('source_warehouse_id', $receiving->delivery->source_warehouse_id), array('class'=>'form-control', 'id' => 'source_warehouse_id')) !!}
                    
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
                                            <td>
                                                <div class="input-field mt-0 mb-0">
                                                    {!! Form::number('details[qty]['.$details->product_id.']['.$details->delivery_details_id.']', old('product', $details->received_qty), array('placeholder' => '0', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode >= 48', 'min' => '0', 'step' => '1')) !!}
                                                    {!! Form::hidden('details[id]['.$details->product_id.']['.$details->delivery_details_id.']', old('product', $details->id)) !!}
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
            <div class="col-sm-10 col-lg-8">
                
                <div class="input-field">
                    {!! Form::label('notes', 'Notes') !!}
                    {!! Form::textarea('notes', old('notes', $receiving->notes), array('class'=>'materialize-textarea form-control', 'rows' => '2', 'style'=>'padding-top: 5px')) !!}
                </div>

            </div>
        </div>

        
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

        $('#delivery_id').select2({
            ajax: {
                url: '{{ route("api.deliveries.search") }}',
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {  results: data.results };
                }
            }
        }).on('select2:select', function (e) {
            var data = e.params.data;

            $("#source_document").val(data.transaction_number);

            $('#receiving_warehouse').selectpicker('val', data.destination_warehouse_id);
            $('#receiving_warehouse_id').val(data.destination_warehouse_id);

            $('#source_warehouse').selectpicker('val', data.source_warehouse_id);
            $('#source_warehouse_id').val(data.source_warehouse_id);

            $('#delivery_date').val( moment(data.delivery_date, 'YYYY-MM-DD').format('MMM DD, YYYY') );
            $('label[for="delivery_date"]').addClass('active');
            
            $('#receiving-products-table tbody').html('');

            $.each(data.details, function(i, item){

                var qty = item.approved_qty - item.delivered_qty;
                var html = '';
                html += '<tr>';
                    html += '<td>' + item.product.name + '</td>';
                    html += '<td class="text-right pr-1">' + item.approved_qty + '.00</td>';
                    html += '<td class="text-right pr-1">' + item.delivered_qty + '</td>';
                    html += '<td>';
                        html += '<div class="input-field mt-0 mb-0">';
                            html += ' <input class="form-control pt-0 mb-0 text-right" name="details[qty][' + item.product_id + '][' + item.id + ']" type="number" step="1" min="0" onkeypress="return event.charCode >= 48" value="' + qty + '"> ';
                        html += '</div>';
                    html += '</td>';
                    
                html += '</tr>';

                $('#receiving-products-table tbody').append(html);

            });

            // $("#source_Warehouse_id").val(data.source_Warehouse_id  );
            console.log(data);
        });;

    });
</script>
@stop

