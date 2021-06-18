@extends('admin.layouts.master') 
@section('content')

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-thumbs-up"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3">Approvers</h3>

    </div>
</div>

<div class="clear"></div>
<div class="card">
    <div class="card-header no-border">
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>Module Name</th>
                    </tr>
                </thead>

                <tbody>
                    <tr data-link="{{ route('admin.approvers.edit', array('replenishments')) }}">
                        <td>Replenishments</td>
                    </tr>
                    <tr data-link="{{ route('admin.approvers.edit', array('returns')) }}">
                        <td>Returns</td>
                    </tr>
                    <tr data-link="{{ route('admin.approvers.edit', array('deliveries')) }}">
                        <td>Deliveries</td>
                    </tr>
                    <tr data-link="{{ route('admin.approvers.edit', array('receiving')) }}">
                        <td>Receiving</td>
                    </tr>
                    <tr data-link="{{ route('admin.approvers.edit', array('inventoryadjustments')) }}">
                        <td>Inventory Adjustments</td>
                    </tr>
                    <tr data-link="{{ route('admin.approvers.edit', array('sales_transaction')) }}">
                        <td>Sales - Transactions</td>
                    </tr>
                    <tr data-link="{{ route('admin.approvers.edit', array('pullouts')) }}">
                        <td>Pull Outs</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection



@section('javascript')
<script type="text/javascript">

    $(document).ready(function () {

        $(".datatable").DataTable();
        $('.dataTables_length').addClass('bs-select');
        
        $('.dataTable').on('click', 'tbody tr', function() {
            var $this = $(this);
            window.location.replace($this.data('link'));
        });
        

    });

</script>
@stop