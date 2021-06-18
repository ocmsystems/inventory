@extends('admin.layouts.master') 
@section('content')
<div class="clear"></div>

<section class="content">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Permission settings</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Manage Users</a></li>
                        <li class="breadcrumb-item active">Data Sets</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary card-outline">
                    <div class="card-header" data-widget="collapse" style="cursor: pointer;">
                        <h3 class="card-title">Sites</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div id="sourceContainer">
                            <select id="sources" name="sources[]" multiple="multiple" style="display: none;"></select>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary card-outline">
                    <div class="card-header" data-widget="collapse" style="cursor: pointer;">
                        <h3 class="card-title">Sections</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div id="sourceContainer">
                            <select id="sections" name="sections[]" multiple="multiple" style="display: none;"></select>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary card-outline">
                    <div class="card-header" data-widget="collapse" style="cursor: pointer;">
                        <h3 class="card-title">Authors</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div id="sourceContainer">
                            <select id="authors" name="authors[]" multiple="multiple" style="display: none;"></select>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>
</section>
@endsection
 
@section('javascript')

<script type="text/javascript" src="/dist/plugins/multiselect/jquery.quicksearch.js"></script>

<script type="text/javascript" src="/js/sections.js"></script>
<script type="text/javascript" src="/js/authors.js"></script>
<script type="text/javascript" src="/js/sources.js"></script>

<script type="text/javascript">

    var user_id =  {{$user->id}};
    $(document).ready(function(){

        var params= { type: 'GET', url: '/api/sources/user_permissions', data: {user_id: user_id}, callback: SOURCES.buildMultiselect, container: '#sources' };
        SOURCES.init(params);
        AUTHORS.init({ type: 'GET', url: '/api/authors/user_permissions', data: {user_id: user_id}, callback: AUTHORS.buildMultiSelect, container: '#authors' });
        SECTIONS.init({ type: 'GET', url: '/api/sections/user_permissions', data: {user_id: user_id}, callback: SECTIONS.buildMultiSelect, container: '#sections' });

    });

</script>

@endsection