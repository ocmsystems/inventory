@extends('admin.layouts.master')

@section('content')

<div class="header-title">

    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-truck"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3">Deliveries</h3>
        <div class="btn-group ml-3">
            {!! link_to_route(config('quickadmin.route').'.deliveries.create', 'Create' , null, array('class' => 'btn btn-primary')) !!}
        </div>
    </div>

</div>

<div class="clear"></div>


<div class="card">
    <div class="card-header no-border">
        <div class="table-responsive">
            <table class="table table-striped table-hover datatable" id="datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Delivery Date</th>
                        <th>Source Warehouse</th>
                        <th>Destination Warehouse</th>
                        <th>Contact Person</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>

        </div>
    </div>
</div>

@endsection

@section('javascript')
    <script type"text/javascript">
        DataTableListing.ajaxURL = "{{ route('.deliveries.listing') }}";
        DataTableListing.options.columns = [
            { "data": "transaction_number" },
            { "data": "delivery_date" },
            { "data": "source_warehouse_name" },
            { "data": "destination_warehouse_name" },
            { "data": "contact_person" },
            { "data": "status" },
        ];

        DataTableListing.init();

        $('.dataTable').on('click', 'tbody tr', function() {
            var $this = $(this);
            window.location.replace($this.data('link'));
        });
    </script>
@stop