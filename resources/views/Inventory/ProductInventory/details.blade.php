@extends('admin.layouts.master')

@section('content')

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-sitemap"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route(config('quickadmin.route').'.inventory.productinventory.index', 'Inventory' , null) !!} / {{ $data['warehouse']->short_name }}</h3>
        
        <div class="btn-group ml-3">
            {!! link_to_route(config('quickadmin.route').'.inventory.productinventory.index', 'Back to list' , null, array('class' => 'btn btn-light')) !!}
        </div>
    </div>

</div>

<div class="clear"></div>


<div class="card">
    <div class="card-header no-border">
        
        <div class="row">
            <table class="ml-4 col-sm-12 col-lg-12" >
                <tr>
                    <th width="200"><strong>Warehouse / Store Name: </strong></th>
                    <th>{{ $data['warehouse']->name }}</th>
                </tr>
                <tr>
                    <th><strong>Address: </strong></th>
                    <th>{{ $data['warehouse']->address }}</th>
                </tr>
                <tr>
                    <th><strong>Contact Person: </strong></th>
                    <th>{{ $data['warehouse']->contact_person }}</th>
                </tr>
            </table>
        </div>

    </div>
</div>


<div class="card">
    <div class="card-header no-border">

        <table class="table table-striped table-hover datatable" id="datatable">
            <thead>
                <tr>
                    <th width="2%">Image</th>
                    <th width="20%">Name</th>
                    <th width="55%">Description</th>
                    <th width="15%">On-hand</th>
                    <th width="8%"></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data['products'] as $row)
                <tr>
                    <td>@if($row->image != '')<img src="{{ asset('uploads/thumb') . '/'.  $row->image }}">@endif</td>
                    <td>{{ $row->product_name }}</td>
                    <td>{{ $row->product_desc }}</td>
                    <td style="text-align: right;">{{ number_format($row->on_hand, 2, '.', ',') }}</td>
                    <td><a href="/inventory/productinventory/product_history/{{ $row->warehouse_id }}/{{ $row->product_id }}" class="btn btn-primary"> View History </a> </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>


<div class="clear"></div>


@endsection

@section('javascript')

@stop