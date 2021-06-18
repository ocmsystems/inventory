@extends('admin.layouts.master')

@section('content')


<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-sitemap"></i>
        </div>
    </div>

    <h3 class="float-left mt-2 ml-3">Product Inventory</h3>
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

<div class="clear"></div>


@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function () {
    
        DataTableListing.ajaxURL = "{{ route('.warehouselist.listing') }}";
        DataTableListing.options.columns = [
            { "data": "name" },
            { "data": "address" },
        ];
        DataTableListing.options["fnRowCallback"] = function ( nRow, aData, iDisplayIndex ) {
            nRow.setAttribute('data-link',  '/inventory/productinventory/view/' + aData['id']);
            return nRow;
        }

        DataTableListing.init();

        $('.dataTable').on('click', 'tbody tr', function() {
            var $this = $(this);
            window.location = $this.data('link');
        });

    });
</script>

@stop