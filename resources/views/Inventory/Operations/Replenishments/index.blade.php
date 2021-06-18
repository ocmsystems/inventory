@extends('admin.layouts.master')

@section('content')

<div class="header-title">

    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-cart-plus"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3">Replenishments</h3>
        <div class="btn-group ml-3">
            {!! link_to_route(config('quickadmin.route').'.replenishments.create', 'Create' , null, array('class' => 'btn btn-primary')) !!}
        </div>
    </div>

</div>

<div class="clear"></div>

<div class="card">
    <div class="card-header no-border">

        <div id="datatable_overlay"></div>
        <div class="table-responsive">
            <table class="table table-hover datatable compact" id="datatable">
                <thead>
                    <tr>
                        <th width="6%">#</th>
                        <th width="12%">Contact Person</th>
                        <th>Destination Location</th>
                        <th width="15%">Scheduled Date</th>
                        <th width="12%">Prepared by</th>
                        <th width="8%">Status</th>
                        {{-- <th width="5%"></th> --}}
                    </tr>
                </thead>

                <tbody>
                </tbody>


            </table>

        </div>
    </div>
</div>

    

@endsection

@section('javascript')
    <script type"text/javascript">
        $(document).ready(function(){

            DataTableListing.ajaxURL = "{{ route('.replenishments.listing') }}";
            DataTableListing.options.columns = [
                { "data": "transaction_number" },
                { "data": "contact_person" },
                { "data": "destination_warehouse_name" },
                { "data": "scheduled_date" },
                { "data": "prepared_by_name" },
                { "data": "status" }
            ];

            DataTableListing.init();

            $('.dataTable').on('click', 'tbody tr', function() {
                var $this = $(this);
                window.location.replace($this.data('link'));
            });
            
        });
    </script>
@stop