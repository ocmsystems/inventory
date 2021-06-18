@extends('admin.layouts.master')

@section('content')

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-exchange-alt"></i>
        </div>
    </div>

    
    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route( config('quickadmin.route').'.inventory.productinventory.index', 'Inventory' , null) !!} / {{ $warehouse->short_name }} / {{ $product->name }} </h3>
        
        <div class="btn-group ml-3">
            {!! link_to_route(config('quickadmin.route').'.inventory.productinventory.index', 'Back to list' , null, array('class' => 'btn btn-light')) !!}
        </div>
    </div>

</div>

<div class="clear"></div>


<div class="card">
    <div class="card-header no-border">
        
        <div class="row">
            <table class="ml-1 col-sm-12 col-lg-12" >
                <tr>
                    <th width="120"><strong>Store</strong></th>
                    <th width="10">:</th>
                    <td width="300">{{ $warehouse->name }}</td>
                    
                    
                    <th width="120"><strong>Product </strong></th>
                    <th width="10">:</th>
                    <td>{{ $product->name }}</td>
                </tr>
                <tr>
                    <th><strong>Address </strong></th>
                    <th width="10">:</th>
                    <td>{{ $warehouse->address }}</td>

                    <th width="120"><strong>Description </strong></th>
                    <th width="10">:</th>
                    <td>{{ $product->description }}</td>


                </tr>
                <tr>
                    <th><strong>Contact Person </strong></th>
                    <th width="10">:</th>
                    <td>{{ $warehouse->contact_person }}</td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>

                    
                    <th>On-hand</th>
                    <th>:</th>
                    <th> {{ number_format($product->on_hand($warehouse->id), 2) }} </th>
                </tr>
            </table>
        </div>

    </div>
</div>

<div class="clear"></div>


<div class="card">
    <div class="card-header no-border">

        
        <div class="table-responsive">

            <table class="table table-hover datatable compact">
                <thead>
                    <tr>
                        {{-- <th width="5%"></th> --}}
                        <th>Reference</th>
                        <th width="5%" class="text-center">Quantity</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="15%" class="text-center">Date Time inserted</th>
                    </tr>
                </thead>

                <tbody>
                    @if(count($data['list']) > 0)
                    @foreach($data['list'] as $inventory_item)
                    <?php
                        $reference = '--------';
                        $status = '--------';
                        if(!empty($inventory_item->relLink())){
                            $reference = '<a href="' . $inventory_item->relLink()->link() . '" target="_blank">' . $inventory_item->relLink()->transaction_number . '</a>';
                            $status = $inventory_item->relLink()->statusValue();
                        }
                    ?>
                    <tr>

                        <td><?php echo $reference; ?> </td>
                        <td class="text-right"> {{ number_format($inventory_item->actual_qty, 2) }} </td>
                        <td class="text-center"> {{ $status }} </td>
                        <td class="text-center"> {{ date("M d, Y h:i A", strtotime($inventory_item->insert_datetime)) }} </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>

        </div>

    </div>
</div>

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.datatable').DataTable({
                ordering: false
            });
        });
    </script>
@stop