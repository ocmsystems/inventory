<!-- Main Sidebar Container -->
<aside id="leftPane" class="main-sidebar sidebar-light-info elevation-4 splitter-panel">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <!-- <img src="/img/logo_tigerph.png" alt="@lang('global.app_name')" class="brand-image img-circle" > -->
        <p class="brand-text font-weight-bold text-center">
            {{-- {{config('app.name')}} --}}
            <!-- <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="100" height="24" viewBox="0 0 142.000000 39.000000" preserveAspectRatio="xMidYMid meet">
                <g transform="translate(0.000000,39.000000) scale(0.100000,-0.100000)" fill="#fff" stroke="none">
                    <path d="M0 344 l0 -46 55 4 55 3 0 -152 0 -153 55 0 55 0 0 149 0 150 53 3 52 3 3 43 3 42 -165 0 -166 0 0 -46z"/>
                    <path d="M340 195 l0 -195 55 0 55 0 0 195 0 195 -55 0 -55 0 0 -195z"/>
                
                    <path d="M519 364 c-21 -23 -24 -36 -27 -135 -5 -132 2 -174 33 -205 22 -22 32 -24 120 -24 88 0 98 2 120 25 20 19 25 34 25 75 0 42 3 50 19 50 15 0 19 -10 23 -52 4 -40 11 -57 32 -75 25 -21 37 -23 137 -23 l109 0 0 45 0 45 -85 0
                                -85 0 0 30 0 30 75 0 75 0 0 45 0 45 -75 0 -75 0 0 35 0 35 80 0 80 0 0 40 0
                                40 -135 0 -135 0 0 -75 0 -75 -70 0 -70 0 0 -75 0 -75 -45 0 -46 0 3 108 3
                                107 88 3 87 3 0 39 0 40 -118 0 c-114 0 -119 -1 -143 -26z"/>
                
                    <path d="M1130 195 l0 -195 50 0 50 0 0 156 0 155 38 -3 c34 -3 37 -6 37 -33 0 -27 -4 -30 -32 -33 -32 -3 -33 -5 -33 -47 0 -43 0 -44 38 -47 l37 -3 3 -72 3 -73 49 0 50 0 0 82 c0 60 -4 86 -16 99 -12 13 -13 22 -5 38 19 35 13 115
                                -10 145 -20 25 -23 26 -140 26 l-119 0 0 -195z"/>
                </g>
            </svg> -->

            Inventory
                
        </p>
    </a>


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
      
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a class="nav-link {!! classActiveSegment(1, 'dashboard') !!}" href="{{ route(config('quickadmin.route').'.dashboard.index') }}">
                        <i class="fas fa-chart-line"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                @foreach($menus as $menu) 
                    @if($menu->menu_type != 2 && is_null($menu->parent_id)) 

                        @if(Auth::user()->role->canAccessMenu($menu))
                            @if( strtolower($menu->name) != 'dashboard' )
                                <li class="nav-item">
                                    <a class="nav-link {!! classActiveSegment(1, strtolower($menu->name)) !!}" href="{{ route(config('quickadmin.route').'.'.strtolower($menu->name).'.index') }}">
                                        <i class="fas {{ $menu->icon }}"></i>
                                        <p>{{ $menu->title }}</p>
                                    </a>
                                </li>
                            @endif
                        @endif 
                    @else 
                        @if(Auth::user()->role->canAccessMenu($menu) && !is_null($menu->children()->first()) && is_null($menu->parent_id))
                            <li class="nav-item @if(count($menu['children']) > 0) has-treeview @endif {!! classActivePath(1, strtolower($menu->name)) !!}">
                                <a href="#" class="nav-link {!! classActiveSegment(1, strtolower($menu->name)) !!}"> 
                                    <i class="fas {{ $menu->icon }}"></i>
                                    <p>{{ $menu->title }}  
                                        @if(count($menu['children']) > 0) 
                                            <i class="right fa fa-angle-left"></i> 
                                        @endif 
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach($menu['children'] as $child) 
                                        @if(Auth::user()->role->canAccessMenu($child))
                                            <li class="nav-item has-treeview {!! classActivePath(2, strtolower($child->name)) !!}">
                                                
                                                <a 
                                                    class="nav-link {!! classActiveSegment(2, strtolower($child->name)) !!}" 
                                                    @if(count($child['children']) == 0) 
                                                        href="{{ '/' . strtolower($menu->name) . '/' . strtolower($child->name) }}"
                                                    @endif
                                                >
                                                
                                                    <i class="fas {{ $child->icon }}"></i>
                                                    <p>{{ $child->title  }}
                                                        @if(count($child['children']) > 0) 
                                                            <i class="right fa fa-angle-left"></i> 
                                                        @endif 
                                                    </p>
                                                </a>

                                                @if(!is_null($child->children()->first()))
                                                    <ul class="nav nav-treeview">
                                                        @foreach($child['children'] as $grandchild)
                                                            @if(Auth::user()->role->canAccessMenu($grandchild))
                                                            <li class="nav-item ">
                                                                @if($grandchild->menu_type == 1)
                                                                <a href="{{ route( '.'.strtolower($grandchild->name).'.index') }}" class="nav-link {!! classActiveSegment(3, strtolower($grandchild->name)) !!}">
                                                                @else
                                                                <a href="{{ route( '.'.strtolower($menu->name).'.'.strtolower($child->name).'.'.strtolower($grandchild->name).'.index') }}" class="nav-link {!! classActiveSegment(3, strtolower($grandchild->name)) !!}">
                                                                @endif
                                                                    <i class="fas {{ $grandchild->icon }}"></i>
                                                                    <p>{{ $grandchild->title  }}</p>
                                                                </a>
                                                            </li>
                                                            @endif 
                                                        @endforeach
                                                    </ul>
                                                @endif

                                            </li>
                                        @endif 
                                    @endforeach
                                </ul>
                            </li>
                        @endif 
                    @endif 
                @endforeach

                @if(Auth::user()->role_id == config('quickadmin.defaultRole'))
                <li class="nav-item has-treeview {!! classActivePath(1,'admin') !!}">
                    <a href="#" class="nav-link {!! classActiveSegment(1, 'admin') !!}">
                        <i class="fa fa-cogs"></i>
                        <p>Admin <i class="right fa fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- <li class="nav-item">
                            <a href="{{ url('admin/menu') }}" class="nav-link {!! classActiveSegment(2, 'menu') !!}">
                                <i class="fa fa-list"></i>
                                <p>Manage Menu</p>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a href="{{ url('admin/positions') }}" class="nav-link {!! classActiveSegment(2, 'positions') !!}">
                                <i class="fa fa-address-card"></i>
                                <p>Manage Positions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/companies') }}" class="nav-link {!! classActiveSegment(2, 'companies') !!}">
                                <i class="fa fa-building"></i>
                                <p>Manage Companies</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/users') }}" class="nav-link {!! classActiveSegment(2, 'users') !!}">
                                <i class="fa fa-users"></i>
                                <p>Manage Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/roles') }}" class="nav-link {!! classActiveSegment(2, 'roles') !!}">
                                <i class="fa fa-gavel"></i>
                                <p>Manage Roles</p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="{{ url('admin/approvers') }}" class="nav-link {!! classActiveSegment(2, 'approvers') !!}">
                                <i class="fa fa-thumbs-up"></i>
                                <p>Manage Approvers</p>
                            </a>
                        </li> --}}
                        {{-- <li class="nav-item">
                            <a href="{{ url('admin/permissions') }}" class="nav-link {!! classActiveSegment(2, 'permissions') !!}">
                                <i class="fa fa-gavel"></i>
                                <p>Manage Permissions</p>
                            </a>
                        </li> --}}
                        {{-- <li class="nav-item">
                            <a href="{{ url('admin/actions') }}" class="nav-link  {!! classActiveSegment(2, 'actions') !!}">
                                <i class="fa fa-users"></i>
                                <p>Audit trail</p>
                            </a>
                        </li> --}}
                    </ul>
                </li>
                @endif 

                <li>
                    <a href="{{ url('logout') }}" class="nav-link">
                        <i class="fa fa-sign-out fa-fw"></i>
                        <p>{{ trans('quickadmin::admin.partials-sidebar-logout') }}</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

</aside>