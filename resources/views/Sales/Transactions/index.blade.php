@extends('admin.layouts.master')

@section('content')

<div class="header-title">

    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3">Transactions</h3>
        <div class="btn-group ml-3">
            {!! link_to_route(config('quickadmin.route').'.transactions.create', 'Create' , null, array('class' => 'btn btn-primary')) !!}
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
                        <th width="12%">Transaction Number</th>
                        <th width="15%">Transaction Date</th>
                        <th width="33%">Store</th>
                        <th width="17%">Sales Person</th>
                        <th width="13%">Amount</th>
                        <th width="10%">Status</th>
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
        DataTableListing.ajaxURL = "{{ route('.transactions.listing') }}";
        DataTableListing.options.columns = [
            { "data": "transaction_number" },
            { "data": "transaction_date" },
            { "data": "warehouse_name" },
            { "data": "prepared_by_name" },
            { "data": "amount" },
            { "data": "status" },
        ];

        DataTableListing.init();

        $('.dataTable').on('click', 'tbody tr', function() {
            var $this = $(this);
            window.location.replace($this.data('link'));
        });
    </script>
@stop