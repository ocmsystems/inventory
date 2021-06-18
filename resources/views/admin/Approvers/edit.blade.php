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

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-thumbs-up"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3">{!! link_to_route(config('quickadmin.route').'admin.approvers.index', 'Manage Approvers' , null) !!} / {{ $data['module_name'] }}</h3>

        {!! link_to_route(config('quickadmin.route').'admin.approvers.index', 'Back to List' , null, array('class' => 'btn btn-light ml-3')) !!}
    </div>
</div>

<div class="clear"></div>

<div class="card">
    <div class="card-header no-border">
            <div class="alert alert-success print-success-msg" style="display:none"></div>

            <table class="table table-hover table-sm dynamic-add-row" id="replenishments-products-table">
                <thead>
                    <tr>
                        <th scope="col" class="text-left">Approver list</th>
                        <th scope="col" width="8%"></th>
                    </tr>
                </thead>

                <tbody>
                    @if(!is_null($data['list']))
                    @foreach($data['list'] as $item)
                        <tr>
                            <td> {{$item->user->name}} </td>
                            <td class="text-center"> <a href="#" class="removeRow btn btn-xs text-left waves-effect waves-light" data-id="{{$item->id}}"><i class="fas fa-trash-alt"></i></a> </td>
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

<script type="text/javascript" src="/dist/plugins/select2/select2.full.js"></script>


<script type="text/javascript">
    $(document).ready(function(){
        var counter = 0;

        $("#addrow").on("click", function () {
            cloneRow();
        });


        $("#replenishments-products-table").on("click", ".removeRow", function (event) {
            event.preventDefault();
            var row = $(this).closest("tr");   


            var params = {
                url: "/admin/approvers/destroy/" + $(this).data('id'),
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
                    url: "{{ route('admin.approvers.store') }}",
                    data: {
                        user_id: row.find(".product-select").val(),
                        module: '{{ $data['module'] }}'
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
        function cloneRow(item){

            var newRow = $("#table-row-to-clone").clone();
            newRow.removeAttr('class id');
            newRow.find('[disabled="true"]').removeAttr("disabled");
            if(typeof item != 'undefined'){
                newRow.find('.product-select').append('<option value="' + item.id + '">'+ item.text +'</option>');
            }
            $("#replenishments-products-table > tbody").append(newRow)
            newRow.find(".product-select").attr('id', 'productSelect' + counter);
            
            initializeSelect2( $('#productSelect' + counter) );
            $('#productSelect' + counter).focus();
            
            counter++;
        }
        function initializeSelect2(selectElementObj) {
            selectElementObj.select2({
                placeholder: 'Select User',
                templateResult: function(state){
                    if (!state.id) {
                        return state.text;
                    }
                    if(state.type == 'role'){
                        return $('<b>-- ' + state.text + ' --</b>');
                    }
                    return state.text;
                },
                ajax: {
                    url: '/api/users/get_grouped',
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
                if(e.params.data.type == 'role'){
                    var params = {
                        url: '/api/users/search/',
                        data: {'role': e.params.data.id},
                        type: 'GET'
                    };

                    UTILS.ajaxRequest(params, function(params, data){
                        if(data.results.length > 0){
                            parentObj.remove();
                            for(var ctr = 0; ctr < data.results.length; ctr++){
                                var item = data.results[ctr];
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