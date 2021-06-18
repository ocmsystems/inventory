@extends('admin.layouts.master')


@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="/dist/plugins/fullcalendar/packages/core/main.min.css">
    <link rel="stylesheet" href="/dist/plugins/fullcalendar/packages/daygrid/main.min.css">
    <link rel="stylesheet" href="/dist/plugins/fullcalendar/packages/timegrid/main.min.css">
    <link rel="stylesheet" href="/dist/plugins/fullcalendar/packages/list/main.min.css">

    <link rel="stylesheet" href="/dist/plugins/select2/select2.min.css">
    <style type="text/css">
        .select2-dropdown {
            z-index: 10060;
        }

        .modal-open .select2-close-mask {
            z-index: 10055;
        }
    </style>
@endpush

@section('content')

<div class="header-title">

    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-percent"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3">Discounts</h3>
        <div class="btn-group ml-3">
            {{-- {!! link_to_route(config('quickadmin.route').'.discounts.create', 'Create' , null, array('class' => 'btn btn-primary')) !!} --}}
            <button data-target="calendarModal" class="btn modal-trigger btn-primary">Add new</button>
        </div>
    </div>

</div>

<div class="clear"></div>


<div class="card">
    <div class="card-header no-border">
        <div class="row">
            <div class="col-sm-12 col-lg-10 offset-lg-1">
                <div class="alert alert-success print-success-msg" style="display:none"></div>

                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>


<div id="calendarModal" class="modal fade" role="dialog">
    <form id="discountForm" method="POST">

        <div class="modal-header">
            <h4 class="modal-title">Create new event</h4>
            <a href="#!" class="modal-close waves-effect waves-green btn btn-light" style="font-size: 20px;padding: 2px 8px;height: auto;line-height: 20px;">&times;</a>
            {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button> --}}

        </div>
        <div class="modal-body">
            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>
            <div class="form-group">
                
                <div class="row">
                    <div class="col-sm-10 col-lg-8">
                        <div class="input-field">
                            {!! Form::label('title', 'Title*') !!}
                            {!! Form::text('title', old('title'), array('class'=>'form-control')) !!}
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-10 col-lg-5">
                        <div class="input-field">
                            {!! Form::label('warehouse_id', 'Destination Location*', array('class'=>'active')) !!}
                            {!! Form::select('warehouse_id', $warehouselist, old('warehouse_id'), array('class'=>'form-control selectpicker', 'title' => 'Choose location..', 'data-live-search' => 'true')) !!}
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-10 col-lg-6">
                        <div class="input-field">
                            {!! Form::label('dates', 'Discount Period*', array('class'=> 'active')) !!}
                        </div>
                        {!! Form::text('dates', old('dates'), array('class'=>'form-control date_and_time_picker')) !!}
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-sm-10 col-lg-3">
                        <div class="input-field">
                            {!! Form::label('end_datetime', 'Ends at*', array('class'=> 'active')) !!}
                        </div>
                        {!! Form::text('end_datetime', old('end_datetime'), array('class'=>'form-control date_and_time_picker')) !!}
                    </div>
                </div> --}}

                
                <div class="row mt-5">

                    <div class="col-sm-10 col-lg-12">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="product-tab" data-toggle="tab" href="#product-attr" role="tab" aria-controls="product" aria-selected="true">Details</a>
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
                                                    <th scope="col" class="text-left">Product</th>
                                                    <th scope="col" width="15%">Original Price</th>
                                                    <th scope="col" width="15%">Discount Type</th>
                                                    <th scope="col" width="15%">Discount</th>
                                                    <th scope="col" width="15%">Discounted Price</th>
                                                    <th scope="col" width="2%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
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
                
                <div class="row mt-3">
                    <div class="col-sm-10 col-lg-5">
                        <div class="input-field">
                            {!! Form::label('status', 'Status', array('class'=> 'active')) !!}
                            
                            <div class="switch mt-2 mb-2">
                                <label>
                                    In-active
                                    {!! Form::checkbox('status', 1, old('status')) !!}
                                    <span class="lever"></span>
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn btn-light float-left">Discard</a>
            {!! Form::submit( 'Submit' , array('name' => 'submit', 'id'=> 'submit', 'class' => 'btn btn-primary ml-1')) !!}
            {!! Form::submit( 'Update' , array('name' => 'update', 'id'=> 'update', 'class' => 'btn btn-primary ml-1')) !!}
        </div>
    </form>
</div><!-- /.modal-dialog -->

<table style="display:none">
    <tbody>
        <tr id="table-row-to-clone" class="hidden">
                <td>
                    <div class="input-field mt-0 mb-0">
                        {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                        {!! Form::select('product[name][]', [], old('product_name'), array('class'=>'form-control pt-0 mb-0 product-select', 'disabled' => 'true')) !!}
                    </div>
                </td>
                <td class="text-right">
                    <span class="origPrice"></span>
                </td>
                <td>
                    <div class="input-field mt-0 mb-0">
                        <select name="product[discount_type][]" class="discount-type-form form-control">
                            <option value="1">Percentage</option>
                            <option value="2">Mark Down</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="input-field mt-0 mb-0">
                        {!! Form::text('product[discount][]', old('product_discount'), array('placeholder' => '0', 'class'=>'discount-form form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)', 'disabled'=> 'true')) !!}
                    </div>
                </td>
                <td class="text-right"> <span class="discountedPrice"></span> </td>
                <td> <a href="#" class="removeRow btn btn-xs text-left waves-effect waves-light"><i class="fas fa-trash-alt"></i></a> </td>
            </tr>
    </tbody>
</table>
@endsection

@section('javascript')

<script type="text/javascript" src="/dist/plugins/fullcalendar/packages/core/main.min.js"></script>
<script type="text/javascript" src="/dist/plugins/fullcalendar/packages/interaction/main.min.js"></script>
<script type="text/javascript" src="/dist/plugins/fullcalendar/packages/daygrid/main.min.js"></script>
<script type="text/javascript" src="/dist/plugins/fullcalendar/packages/timegrid/main.min.js"></script>
<script type="text/javascript" src="/dist/plugins/fullcalendar/packages/list/main.min.js"></script>

<script type="text/javascript" src="/dist/plugins/select2/select2.full.js"></script>
<script type="text/javascript" src="/dist/plugins/select2/select2.optgroupSelect.js"></script>
<script type"text/javascript">
    var counter = 0;
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.modal');
        $("#update").hide();
        
        $('#calendarModal').modal({
            dismissible: true,
            onCloseEnd: function(){
                // alert("asdasd");
                $("#discountForm").find("input[type=text], textarea").val("");
                $("#replenishments-products-table > tbody").html('');
                $("#status").prop( "checked", false );
                $("#update").hide();
                $("#submit").show();
            }
        });


        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'Asia/Taipei',
            plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
            selectable: true,
            allDayDefault: false,
            editable: true,
            eventLimit: true, 
            select: function(info, test) {
                $('.modal').modal('open');
                // $('.modal').find('#start_datetime').val(moment(info.startStr).format('MMM DD, YYYY hh:mm A'));
                // $('.modal').find('#end_datetime').val(moment(info.endStr).format('MMM DD, YYYY') + ' 11:59 PM');
                
                $('#dates').data('daterangepicker').setStartDate( moment(info.startStr).format('MMM DD, YYYY hh:mm A') );
                $('#dates').data('daterangepicker').setEndDate( moment(info.endStr).subtract(1, 'days').format('MMM DD, YYYY' + ' 11:59 PM' ) );
            },
            eventClick: function(info, element) {
                // Display the modal and set the values to the event values.
                $("#discountForm input#title").val(info.event.title);
                $("#discountForm label[for='title']").addClass('active');
                $('#discountForm input#dates').val(  );

                
                $('#dates').data('daterangepicker').setStartDate(moment(info.event.extendedProps.start_datetime).format('MMM DD, YYYY hh:mm A'));
                $('#dates').data('daterangepicker').setEndDate(moment(info.event.extendedProps.end_datetime).format('MMM DD, YYYY hh:mm A'));

                $('#discountForm #warehouse_id').val(info.event.extendedProps.warehouse_id);
                $('#warehouse_id').selectpicker('refresh');

                $("#update").show();
                $("#update").data('id', info.event.id);
                $("#submit").hide();
                if(info.event.extendedProps.status == 1){
                    $("#status").prop( "checked", true );
                }else{
                    $("#status").prop( "checked", false );
                }

                if(typeof info.event.extendedProps.details != 'undefined'){
                    for(var ctr = 0; ctr < info.event.extendedProps.details.length; ctr++){
                        cloneRow(info.event.extendedProps.details[ctr]);
                    }
                }

                $('.modal').modal('open');
            },
            eventSources: [
                {
                    url: '/api/discounts/get',
                    textColor: '#fff',
                    startParam: null, //resetting default fullcalendar parameter
                    endParam: null, //resetting default fullcalendar parameter
                    type: 'GET',
                    cache:false,
                    data: function() {
                        var fecha = $('#calendar').fullCalendar('getDate');
                        return {
                            month: fecha.getMonth() + 1,
                            year: fecha.getFullYear()
                        }
                    }
                }
            ],
        });

        calendar.render();


        $("#addrow").on("click", function () {
            cloneRow();
        });

        $("#replenishments-products-table").on("keyup", ".discount-form", function(){
            var grandParentTR = $(this).parent().parent().parent();
            var discountType = grandParentTR.find('select.discount-type-form').val();
            var price = ( isNaN( parseFloat(grandParentTR.find("span.origPrice").text()) ) ? 0 : parseFloat(grandParentTR.find("span.origPrice").text()) );


            if(discountType == 1){
                var discount = ( isNaN( parseFloat($(this).val()) ) ? 0 : parseFloat($(this).val()) / 100 );
                var dPrice = price - (price * discount);
            }else if(discountType == 2){
                var discount = ( isNaN( parseFloat($(this).val()) ) ? 0 : parseFloat($(this).val()) );
                var dPrice = price - discount;
            }

            var discountedPrice = dPrice.toFixed(2);
            grandParentTR.find("span.discountedPrice").text(discountedPrice);
        });

        $("#replenishments-products-table").on("change", ".discount-type-form", function(){
            var grandParentTR = $(this).parent().parent().parent();
            var discountType = $(this).val();
            var price = ( isNaN( parseFloat(grandParentTR.find("span.origPrice").text()) ) ? 0 : parseFloat(grandParentTR.find("span.origPrice").text()) );
 
            if(discountType == 1){
                var discount = ( isNaN( parseFloat(grandParentTR.find('.discount-form').val()) ) ? 0 : parseFloat(grandParentTR.find('.discount-form').val()) / 100 );
                var dPrice = price - (price * discount);
            }else if(discountType == 2){
                var discount = ( isNaN( parseFloat(grandParentTR.find('.discount-form').val()) ) ? 0 : parseFloat(grandParentTR.find('.discount-form').val()) );
                var dPrice = price - discount;
            }

            var discountedPrice = dPrice.toFixed(2);
            grandParentTR.find("span.discountedPrice").text(discountedPrice);
        });


        $("#submit").click(function(e){
            e.preventDefault();
            var params = {
                url: '/api/discounts/add',
                data: $("#discountForm").serialize(),
                type: 'POST'
            };
            $("#discountForm :input").prop("disabled", true);

            UTILS.ajaxRequest(params, formSubmit)
        });
        
        $("#update").click(function(e){
            e.preventDefault();
            var params = {
                url: '/api/discounts/update/' + $(this).data('id'),
                data: $("#discountForm").serialize(),
                type: 'POST'
            };
            $("#discountForm :input").prop("disabled", true);

            UTILS.ajaxRequest(params, formSubmit)
        });

        
        $("#replenishments-products-table").on("click", ".removeRow", function (event) {
            $(this).closest("tr").remove();       
        });

        function formSubmit(params, response){
            if($.isEmptyObject(response.error)){
                $(".print-success-msg").text(response.success);
                
                calendar.refetchEvents();

                $('.modal').modal('close');
                $(".print-success-msg").css('display','block');
            }else{
                printErrorMsg(response.error);
            }
            $("#discountForm :input").prop("disabled", false);
        }

        function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }

        function cloneRow(item){
            var newRow = $("#table-row-to-clone").clone();
            newRow.removeAttr('class id');
            newRow.find('[disabled="true"]').removeAttr("disabled");
            
            if(typeof item != 'undefined'){
                newRow.find('select.discount-type-form').val(item.discount_type);
                newRow.find('.product-select').append('<option value="' + item.product_id + '">'+ item.product.name +'</option>');
                newRow.find('input[name="product[discount][]"]').val(item.discount);
                newRow.find('span.origPrice').html(item.original_price);
                newRow.find('span.discountedPrice').html(item.discounted_price);
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
                dropdownParent: $('#calendarModal'),
                templateResult: function(state){
                    if (!state.id) {
                        return state.text;
                    }
                    if(state.type == 'cat'){
                        return $('<b>-- ' + state.text + ' --</b>');
                    }
                    return state.text;
                },
                // templateSelection: function(state){
                //     if (!state.id) {
                //         return state.text;
                //     }
                //     // if( state.type != 'cat'){
                //     //     return state.text;
                //     // }
                // },
                ajax: {
                    url: '/api/products/get_grouped',
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
            }).on('select2:select', function (e) {
            // Do something
                var parentObj = $(this).parent().parent().parent();
                if(e.params.data.type == 'cat'){
                    var params = {
                        url: '/api/products/get/',
                        data: {'cat': e.params.data.id},
                        type: 'GET'
                    };

                    UTILS.ajaxRequest(params, function(params, data){
                        if(data.results.length > 0){
                            parentObj.remove();
                            for(var ctr = 0; ctr < data.results.length; ctr++){
                                var item = data.results[ctr];
                                console.info(item);
                                cloneRow(item);
                            }
                        }
                    });
                }else{
                    parentObj.find("span.origPrice").text(parseFloat(e.params.data.price));       
                    parentObj.find("span.discountedPrice").text(parseFloat(e.params.data.price));
                }

            });
        }

    });

</script>

@stop