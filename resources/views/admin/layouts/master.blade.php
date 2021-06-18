@include('admin.partials.header')
@include('admin.partials.topbar')

<div class="clearfix"></div>

<div class="wrapper" id="app">
    @include('admin.partials.sidebar')

    <div id="rightPane" class="content-wrapper splitter-panel">

        {{-- <div class="content-header">
            <h3 class="page-title">
                {{ preg_replace('/([a-z0-9])?([A-Z])/','$1 $2',str_replace('Controller','',explode("@",class_basename(app('request')->route()->getAction()['controller']))[0])) }}
        </h3>
    </div> --}}

        <section class="content">
            @if (Session::has('message'))
            <div class="note note-info">
                <p>{{ Session::get('message') }}</p>
            </div>
            @endif

            @yield('content')
        </section>

    </div>
</div>

<div class="scroll-to-top" style="display: none;">
    <i class="fa fa-arrow-up"></i>
</div>
@include('admin.partials.javascripts') @yield('javascript')
@include('admin.partials.footer')