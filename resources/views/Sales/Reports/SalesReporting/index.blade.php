@extends('admin.layouts.master')

@section('content')

<div class="header-title">

    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-4 ml-3">Sales Reporting</h3>
    </div>

</div>

<div class="clear"></div>

<div class="row">
    <div class="col-lg-6 col-sm-12">

        <div class="card">
            <div class="card-header pt-3">
                <h4>Daily Sales</h4>
            </div>

            <div class="card-body">

                {!! Form::open(array('route' => 'reports.salesreporting.generate', 'id' => 'form-with-validation', 'class' => 'form-horizontal', 'target'=>'_blank')) !!}

                <div class="row">

                    <div class="col-sm-10 col-lg-8">
                        <div class="input-field">
                            <div class="input-field">
                                {!! Form::label('warehouse_id', 'Source Location*', array('class'=>'active')) !!}
                            </div>
                            {!! Form::select('warehouse_id', $warehouselist, old('warehouse_id'), array('class'=>'form-control selectpicker', 'data-live-search' => 'true')) !!}
                        </div>
                    </div>

                </div>

                @if(auth()->user()->role->title == 'Administrator')

                <div class="row">
                    <div class="col-sm-10 col-lg-8">
                        <div class="input-field">
                            <div class="input-field">
                                {!! Form::label('user_id', 'Promodizer', array('class'=>'active')) !!}
                            </div>
                            {!! Form::select('user_id', $userlist, old('user_id'), array('class'=>'form-control selectpicker', 'title' => 'Choose user..', 'data-size' => "5", 'data-live-search' => 'true')) !!}
                        </div>
                    </div>

                </div>

                @endif

                <div class="row">
                    
                    <div class="col-sm-10 col-lg-8">
                        {!! Form::label('date', 'Transaction Date') !!}
                        {!! Form::text('date', old('date', date("M d, Y") ), array('class'=>'form-control datepicker')) !!}

{{-- 
                        <div class="input-field">
                            {!! Form::label('date', 'Date', array('class' => 'active')) !!}
                            <div id="reportrange" style="padding: 8px 10px;">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                            {!! Form::hidden('date', old('date') ) !!}
                        </div> --}}
                    </div>
                    
                </div>
                

                <div class="btn-group mt-2">
                    {!! Form::submit( 'Generate' , array('name' => 'daily', 'class' => 'btn btn-primary')) !!}
                </div>

                {!! Form::close() !!} 
                
            </div>
        </div>
    </div>

    @if(auth()->user()->role->title == 'Administrator')
    <div class="col-lg-6 col-sm-12">

        <div class="card">
            <div class="card-header pt-3">
                <h4>Periodic Sales</h4>
            </div>

            <div class="card-body">

                {!! Form::open(array('route' => 'reports.salesreporting.generate_periodic', 'id' => 'form-with-validation', 'class' => 'form-horizontal', 'target' => '_blank')) !!}

                <div class="row">

                    <div class="col-sm-10 col-lg-8">
                        <div class="input-field">
                            <div class="input-field">
                                {!! Form::label('warehouse_id', 'Source Location*', array('class'=>'active')) !!}
                            </div>
                            {!! Form::select('warehouse_id', $warehouselist, old('warehouse_id'), array('class'=>'form-control selectpicker', 'title' => 'Choose location..', 'data-live-search' => 'true')) !!}
                        </div>
                    </div>

                </div>

                <div class="row">
                    
                    <div class="col-sm-10 col-lg-8">

                        <div class="input-field">
                            {!! Form::label('daterange', 'Date', array('class' => 'active')) !!}
                            <div id="reportrange" style="padding: 8px 10px;">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                            {!! Form::hidden('daterange', old('daterange') ) !!}
                        </div>
                    </div>
                    
                </div>

                {{-- <div class="row mb-2">
                    <div class="col-sm-10 col-lg-8">
                        <label for="todate">
                            {!! Form::checkbox('todate', 1, 1) !!}
                            <span>To Date Amount</span>
                        </label>
                    </div>
                </div> --}}

                <div class="btn-group mt-2">
                    {!! Form::submit( 'Generate' , array('name' => 'periodic', 'class' => 'btn btn-primary')) !!}
                </div>

                {!! Form::close() !!} 
                
            </div>
        </div>

    </div>

    @endif
</div>

    


@endsection

@section('javascript')

<script type="text/javascript">
    $(function() {

        var start = moment().startOf('month');
        var end = moment().endOf('month');

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#daterange').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        }


        $('#date').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'MMM DD, YYYY'
            },
        });


        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

    });
</script>
@stop