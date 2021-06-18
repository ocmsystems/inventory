<!-- Navbar -->
<nav class="main-header navbar navbar-expand bg-theme">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>

    </ul>

    <!-- SEARCH FORM -->
    {{-- <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form> --}}

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown user-menu">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" data-slide="true" href="#">
                <img src="/img/profile.png" class="user-image img-circle elevation-2" alt="User Image">
                <span class="d-none d-md-inline">
                    {{auth()->user()->name!=null ? auth()->user()->name : "Administrator"}}</span>
            </a>
            
        </li>

    </ul>
</nav>
<!-- /.navbar -->