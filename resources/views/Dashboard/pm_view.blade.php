
<div class="row">
    <div class="col-sm-12 col-lg-9">
        <div class="card">
            <div class="card-header p-3">
                <h4 class="card-title">Receiving Requests</h4>
            </div>

            <div class="card-body p-2" style="display: block;">
                <div class="table-responsive" style="max-height: 250px;">

                    <table class="table table-striped table-hover datatable" id="datatable">
                        <thead>
                            <tr>
                                <th>Transaction #</th>
                                <th>Project Name</th>
                                <th>Receive From</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>

            </div>

        </div>
    </div>
</div>

@section('javascript')
    <script type"text/javascript">
        DataTableListing.ajaxURL = "{{ route('.receivingrequest.listing') }}";
        DataTableListing.options.bLengthChange = false;
        DataTableListing.options.bFilter = false;

        DataTableListing.options.columns = [
            { "data": "transaction_number" },
            { "data": "project_name" },
            { "data": "receive_from" },
            { "data": "status" },
        ];

        DataTableListing.init();

        $('.dataTable').on('click', 'tbody tr', function() {
            var $this = $(this);
            window.location.replace($this.data('link'));
        });
    </script>
@stop