@extends('admin.layouts.master')

@section('content')

{!! Form::model($productlist, array('files' => true, 'class' => 'form-horizontal', 'id' => 'form-with-validation', 'method' => 'PATCH', 'route' => array(config('quickadmin.route').'.productlist.update', $productlist->id))) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-boxes"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.productlist.index', 'Product List' , null) !!} / {{$productlist->name}}</h3>

        <div class="btn-group ml-3">
            {!! Form::submit( 'Update', array('class' => 'btn btn-primary')) !!}
            
            {!! link_to_route(config('quickadmin.route').'.productlist.index', 'Back to List' , null, array('class' => 'btn btn-light ml-1')) !!}
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

        <div id="kv-avatar-errors-1" class="center-block" style="width:800px;display:none"></div>
        <div class="form-group">

            <div class="row">

                <div class="col-sm-10 col-lg-2 mb-5">
                    
                    <div class="kv-avatar-single">
                        <div class="file-loading">
                            {!! Form::file('image', array('id'=>'image')) !!}
                        </div>
                        <div class="kv-avatar-hint text-center" style="line-height: 10px;">
                            <small>Select file < 300 KB</small>
                        </div>
                        {!! Form::hidden('image_w', 4096) !!}
                        {!! Form::hidden('image_h', 4096) !!}
                    </div>
                </div>

                <div class="col-sm-6 col-lg-6">

                    <div class="row">
                        <div class="col-sm-12 col-lg-5">
                            <div class="input-field">
                                {!! Form::label('sku', 'SKU') !!}
                                {!! Form::text('sku', old('sku',$productlist->sku), array('class'=>'form-control')) !!}
                            </div>
                        </div>
                    </div>
                    
                        
                    <div class="row">
                        <div class="col-sm-12 col-lg-7">
                            <div class="input-field">
                                {!! Form::label('name', 'Name*') !!}
                                {!! Form::text('name', old('name',$productlist->name), array('class'=>'form-control')) !!}
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12 col-lg-7">
                            <div class="input-field">
                                {!! Form::label('productcategories_id', 'Product Category*', array('class'=>'active')) !!}
                                {!! Form::select('productcategories_id', $productcategories, old('productcategories_id',$productlist->productcategories_id), array('class'=>'form-control selectpicker no-autoinit', 'title' => 'Choose category..', 'data-live-search' => 'true')) !!}
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-sm-12 col-lg-11">
                            <div class="input-field">
                                {!! Form::label('description', 'Description') !!}
                                {!! Form::textarea('description', old('description',$productlist->description), array('class'=>'materialize-textarea form-control', 'rows' => 3)) !!}
                            </div>
                        </div>
                    </div>

    
                    <div class="row">
                        <div class="col-sm-12 col-lg-7">
                            <div class="input-field">
                                {!! Form::label('variant', 'Variant*') !!}
                                {!! Form::text('variant', old('variant',$productlist->variant), array('class'=>'form-control')) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-lg-6">
                            <div class="input-field">
                                {!! Form::label('barcode', 'Barcode') !!}
                                {!! Form::text('barcode', old('barcode',$productlist->barcode), array('class'=>'form-control')) !!}
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-10 col-lg-7">
                            <div class="input-field">
                                {!! Form::label('status', 'Status', array('class'=> 'active')) !!}
                                
                                <div class="switch mt-2 mb-2">
                                    <label>
                                        In-active
                                        {!! Form::checkbox('status', 1, old('status', $productlist->status)) !!}
                                        <span class="lever"></span>
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
    
                </div>

                <div class="col-sm-6 col-lg-4">

                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <div class="row">

                                <div class="col-6 col-sm-7 col-lg-7">
                                    <div class="input-field">
                                        {!! Form::label('price', 'Price') !!}
                                        {!! Form::text('price', old('price', $productlist->price), array('placeholder' => '0.00', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)', 'min' => '0')) !!}
                                    </div>
                                </div>

                                <div class="col-6 col-sm-7 col-lg-7">
                                    <div class="input-field">
                                        {!! Form::label('cost', 'Cost') !!}
                                        {!! Form::text('cost', old('cost', $productlist->cost), array('placeholder' => '0.00', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)', 'min' => '0')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12 col-lg-12">

                            <div class="row">
                                <div class="col-6 col-sm-7 col-lg-7">
                                    <div class="input-field">
                                        {!! Form::label('reorder_quantity', 'Reorder Quantity') !!}
                                        {!! Form::text('reorder_quantity', old('reorder_quantity', $productlist->reorder_quantity), array('placeholder' => '0.00', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)', 'min' => '0')) !!}
                                    </div>
                                </div>

                                <div class="col-6 col-sm-7 col-lg-7">
                                    <div class="input-field">
                                        {!! Form::label('critical_quantity', 'Critical Quantity') !!}
                                        {!! Form::text('critical_quantity', old('critical_quantity', $productlist->critical_quantity), array('placeholder' => '0.00', 'class'=>'form-control pt-0 mb-0 text-right', 'onkeypress' => 'return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)', 'min' => '0')) !!}
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>

                        </div>
                    </div>
    
                     


                </div>

            </div>
    
        </div>
    </div>
</div>


{!! Form::close() !!}

@endsection

@section('javascript')

<script type="text/javascript">
    $(document).ready(function(){
        $("#image").fileinput({
            previewSettings:{
                image: {width: "200px", height: "auto", 'max-width': "200px", 'max-height': "100%"},
            },
            overwriteInitial: true,
            maxFileSize: 300,
            showClose: false,
            showCaption: false,
            showBrowse: false,
            
            zoomIcon: '',
            removeFromPreviewOnError: true,
            browseOnZoneClick: true,
            removeLabel: '',
            removeIcon: '<i class="fas fa-remove"></i>',
            removeTitle: 'Cancel or reset changes',
            elErrorContainer: '#kv-avatar-errors-1',
            msgErrorClass: 'alert alert-block alert-danger',
            defaultPreviewContent: '<img src="{{ asset('uploads') }}/{{ $productlist->image }}" style="width: 100%; height: auto; max-width: 200px; max-height: 100%;" /> <h6 class="text-muted">Click to Update</h6>',
            layoutTemplates: {main2: '{preview} {remove} {browse}', indicator: ''},
            allowedFileExtensions: ["jpg", "png", "gif"]
        });

    });
</script>

@stop