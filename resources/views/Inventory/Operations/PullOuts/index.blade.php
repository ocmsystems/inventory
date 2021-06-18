@extends('admin.layouts.master')

@section('content')

<div class="header-title">

    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-database"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3">Pull Outs</h3>
        <div class="btn-group ml-3">
            {!! link_to_route(config('quickadmin.route').'.pullouts.create', 'Create' , null, array('class' => 'btn btn-primary')) !!}
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
                        <th width="12%">Prepared By</th>
                        <th>Store Location</th>
                        <th width="15%">Pull Out Date</th>
                        <th width="8%">Status</th>

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
        DataTableListing.ajaxURL = "{{ route('.pullouts.listing') }}";
        DataTableListing.options.columns = [
            { "data": "transaction_number" },
            { "data": "prepared_by_name" },
            { "data": "warehouse_name" },
            { "data": "prepared_by_name" },
            { "data": "status" }
        ];

        DataTableListing.init();

        $('.dataTable').on('click', 'tbody tr', function() {
            var $this = $(this);
            window.location.replace($this.data('link'));
        });
    </script>
@stop