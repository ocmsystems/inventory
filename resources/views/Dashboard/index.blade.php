@extends('admin.layouts.master')

@push('styles')
    <!-- Select2 -->
@endpush
@section('content')

<div class="header-title">  
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> Dashboard</h3>
    </div>
</div>
<div class="clear"></div>

<div id="dashboard">

    @if(auth()->user()->role->title == 'Administrator')
        @include('Dashboard.admin_view')
    @elseif(auth()->user()->role->title == 'Project Manager')
        @include('Dashboard.pm_view')
    @endif

</div>

@endsection

@section('javascript')
<script type="text/javascript" src="/dist/plugins/chart.js/Chart.js"></script>
<script type="text/javascript" src="/dist/plugins/select2/select2.full.js"></script>
<script>
    
    $(document).ready(function(){
        $('.table').on('click', 'tbody tr', function() {
            var $this = $(this);
            if( typeof $this.data('link') != 'undefined'){
                window.location.replace($this.data('link'));
            }
        });

    });
    
</script>
@endsection
