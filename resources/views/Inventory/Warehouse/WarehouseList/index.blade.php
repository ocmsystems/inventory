@extends('admin.layouts.master')

@section('content')
<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-list"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3">Warehouse / Store List</h3>
        <div class="ml-3">
            {!! link_to_route(config('quickadmin.route').'.warehouselist.create', 'Create' , null, array('class' => 'btn btn-primary')) !!}
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
                        <th width="20%">Name</th>
                        <th>Address</th>
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
    <script type="text/javascript">
        $(document).ready(function () {
        
            DataTableListing.ajaxURL = "{{ route('.warehouselist.listing') }}";
            DataTableListing.options.columns = [
                { "data": "name" },
                { "data": "address" },
            ];

            DataTableListing.init();

            $('.dataTable').on('click', 'tbody tr', function() {
                var $this = $(this);
                window.location.replace($this.data('link'));
            });
        });
    </script>
@stop