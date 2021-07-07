@extends('admin.layouts.master')

@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="/dist/plugins/select2/select2.min.css">
    <style type="text/css">
        table.dynamic-add-row tbody td{
            padding: 8px 15px;
            font-size: 14px;
        }
    </style>
@endpush
@section('content')

{!! Form::model($warehouselist, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'method' => 'PATCH', 'route' => array(config('quickadmin.route').'.warehouselist.update', $warehouselist->id))) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-store"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.warehouselist.index', 'Warehouse / Store' , null) !!} / {{ $warehouselist->short_name }}</h3>

        <div class="btn-group ml-3">
            {!! Form::submit( 'Update' , array('class' => 'btn btn-primary')) !!}
            {!! link_to_route(config('quickadmin.route').'.warehouselist.index', 'Discard' , null, array('class' => 'btn btn-link ml-2')) !!}
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
            
            <div class="col-sm-10 col-lg-4">
                <div class="input-field">
                    {!! Form::label('short_name', 'Short Name*') !!}
                    {!! Form::text('short_name', old('short_name',$warehouselist->short_name), array('class'=>'form-control')) !!}
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-sm-10 col-lg-6">
                <div class="input-field">
                    {!! Form::label('name', 'Name*') !!}
                    {!! Form::text('name', old('name', $warehouselist->name), array('class'=>'form-control')) !!}
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-sm-10 col-lg-6">
                <div class="input-field">
                    {!! Form::label('address', 'Address') !!}
                    {!! Form::textarea('address', old('address', $warehouselist->address), array('class'=>'materialize-textarea form-control', 'rows' => '3')) !!}
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-sm-10 col-lg-4">
                <div class="input-field">
                    {!! Form::label('contact_person', 'Contact Person') !!}
                    {!! Form::text('contact_person', old('contact_person', $warehouselist->contact_person), array('class'=>'advancedAutoComplete form-control', 'autocomplete' => 'off')) !!}
                </div>
            </div>
            
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-3">
                <div class="input-field">
                    {!! Form::label('type', 'Type', ['class' => 'active']) !!}
                    {!! Form::select('type', $type, old('type', $warehouselist->type), ['class'=>'form-control selectpicker', 'title' => 'Select type..', 'data-live-search' => 'true']) !!}
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-10 col-lg-4">

                <div class="switch">
                    <label>
                        In-active
                        {!! Form::checkbox('status', 1, old('status', $warehouselist->status)) !!}
                        <span class="lever"></span>
                        Active
                    </label>
                </div>

            </div>
        </div>

    </div>
</div>

{!! Form::close() !!}

<div class="clear"></div>

<div class="card">
    <div class="card-header p-2">
        <h3 class="mt-1 ml-3">Personnel</h3>
    </div>
    <div class="card-header no-border">
        <div class="alert alert-success print-success-msg" style="display:none"></div>

        <table class="table table-hover table-sm dynamic-add-row" id="replenishments-products-table">
            <thead>
                <tr>
                    <th scope="col" class="text-left"></th>
                    <th scope="col" width="8%"></th>
                </tr>
            </thead>

            <tbody>
                @if(!is_null($warehouselist->personnel))
                @foreach($warehouselist->personnel as $personnel)
                    <tr>
                        <td> {{$personnel->user->name}} </td>
                        <td class="text-center"> <a href="#" class="removeRow btn btn-xs text-left waves-effect waves-light" data-id="{{$personnel->id}}"><i class="fas fa-trash-alt"></i></a> </td>
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

<table>
    <tbody>
        <tr id="table-row-to-clone" class="hidden">
            <td>
                <div class="input-field mt-0 mb-0">
                    {{-- {!! Form::text('product[name][]', old('product'), array('class'=>'form-control pt-0 mb-0 product', 'disabled'=> 'true')) !!} --}}
                    {!! Form::select('user', [], old('user'), array('class'=>'form-control pt-0 mb-0 product-select', 'disabled' => 'true')) !!}
                </div>
            </td>
            <td class="text-center">
                <a href="#" class="saveRow btn btn-xs text-left waves-effect waves-light"><i class="fas fa-save"></i></a>
                <a href="#" class="removeRow btn btn-xs text-left waves-effect waves-light"><i class="fas fa-trash-alt"></i></a>
            </td>
        </tr>
    </tbody>
</table>

@endsection

@section('javascript')
<script type="text/javascript" src="/dist/plugins/bootstrap-autocomplete/bootstrap-autocomplete.min.js"></script>
<script type="text/javascript" src="/dist/plugins/select2/select2.full.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        var counter = 0;

        M.textareaAutoResize($('#address'));
        AutoCompleteField.ajaxURL = '{{ route("api.users.search") }}';
        AutoCompleteField.init();


        $("#addrow").on("click", function () {
            var newRow = $("#table-row-to-clone").clone();
            newRow.removeAttr('class id');
            newRow.find('[disabled="true"]').removeAttr("disabled");

            $("#replenishments-products-table > tbody").append(newRow)
            newRow.find(".product-select").attr('id', 'productSelect' + counter);
            
            initializeSelect2( $('#productSelect' + counter) );
            $('#productSelect' + counter).focus();
            
            counter++;
        });


        $("#replenishments-products-table").on("click", ".removeRow", function (event) {
            event.preventDefault();
            var row = $(this).closest("tr");   


            var params = {
                url: "/inventory/warehouse/warehouselist/personnel_destroy/" + $(this).data('id'),
                data: {
                    id: $(this).data('id'),
                },
                type: 'DELETE',
                row: row,
                callback: function(params, response){
                    if($.isEmptyObject(response.error)){
                        $(".print-success-msg").text(response.success);
                        $(".print-success-msg").css('display','block');
                        params.row.remove();
                    }
                }
            }
            UTILS.ajaxRequest(params, formSubmit)

        });
        
        $("#replenishments-products-table").on("click", ".saveRow", function (event) {
            event.preventDefault();
            var row = $(this).closest("tr");       
            if( row.find(".product-select").val() ){
                var params = {
                    url: "{{ route('inventory.warehouselist.personnel.add') }}",
                    data: {
                        user_id: row.find(".product-select").val(),
                        warehouse_id: {{ $warehouselist->id }},
                    },
                    type: 'POST',
                    row: row
                };

                UTILS.ajaxRequest(params, formSubmit)
            }
        });

        
        function formSubmit(params, response){

            if($.isEmptyObject(response.error)){
                $(".print-success-msg").text(response.success);
                $(".print-success-msg").css('display','block');
                
                params.row.find(".product-select").parent().html(params.row.find(".product-select").text());
                params.row.find('.saveRow').remove();
                params.row.find('.removeRow').attr('data-id', response.data.id);
            }
        }

        function initializeSelect2(selectElementObj) {
            selectElementObj.select2({
                placeholder: 'Select User',
                ajax: {
                    url: '/api/users/search',
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