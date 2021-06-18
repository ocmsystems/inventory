@extends('admin.layouts.master') 
@section('content')


{!! Form::open(['route' => ['admin.roles.update', $role->id], 'class' => 'form-horizontal', 'method' => 'PATCH']) !!}

<div class="header-title">
    <div class="card">
        <div class="card-header no-border">
            <i class="fas fa-cart-plus"></i>
        </div>
    </div>

    <div class="float-left">
        <h3 class="mt-2 ml-3"> {!! link_to_route('admin.roles.index', 'Roles' , null) !!} / {{$role->title}}</h3>

        <div class="btn-group ml-3">
            {!! Form::submit( 'Save' , array('class' => 'btn btn-primary')) !!}
            
            {!! link_to_route('admin.roles.index', 'Discard' , null, array('class' => 'btn btn-link ml-1')) !!}
        </div>
    </div>
</div>

<div class="clear"></div>


<div class="card">
    <div class="card-header no-border">
        
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                {!! implode('', $errors->all('
                <li class="error">:message</li>')) !!}
            </ul>
        </div>
        @endif


        <div class="form-group">
            {!! Form::label('title', trans('quickadmin::admin.roles-edit-title'), ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::text('title', old('title', $role->title), ['class'=>'form-control', 'placeholder'=> trans('quickadmin::admin.roles-edit-title_placeholder')]) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="clearfix"></div>
            <div style="height: 30px;"></div>

            {!! Form::label('menus', 'Menus', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
               
                <div class="col-sm-12 col-12">
                    <ul id="menu-roles">
                        @foreach($menus as $menu) 
                            @if($menu->menu_type != 2 && is_null($menu->parent_id)) 
                                @if( strtolower($menu->title) == 'dashboard')
                                    <li>
                                        <div class="input-group mb-1">
                                            <div class="btn-group-toggle">
                                                <label>
                                                {!! Form::checkbox('menus['.$menu->id.']', $menu->id, old('menus.'.$menu->id, $role->canAccessMenu($menu)), array('id' => 'menus-' . $menu->id, 'disabled' => 'disabled') ) !!}
                                                    <span> {{ $menu->title }} </span>
                                                </label>
                                                {{-- {!! Form::label('menus-' . $menu->id, $menu->title, ['class'=>'control-label']) !!} --}}
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @else   
                                @if(!is_null($menu->children()->first()) && is_null($menu->parent_id))
                                    <li>
                                        <div class="input-group mb-1">
                                            <div class="btn-group-toggle">
                                                {{-- <label class="btn btn-danger"> --}}
                                                    {{-- <input type="checkbox" name="menus[]" value="{{ $menu->id }}" autocomplete="off"> {{ $menu->title }} --}}

                                                    <label>
                                                        {!! Form::checkbox('menus['.$menu->id.']', $menu->id, old('menus.'.$menu->id, $role->canAccessMenu($menu)), array('id' => 'menus-' . $menu->id) ) !!}
                                                        <span> {{ $menu->title }} </span>
                                                    </label>

                                                    {{-- {!! Form::label('menus-' . $menu->id, $menu->title, ['class'=>'control-label']) !!} --}}

                                                {{-- </label> --}}
                                            </div>
                                        </div>

                                        @if(count($menu['children']) > 0) 
                                            <ul class="ml-4">
                                                @foreach($menu['children'] as $child) 
                                                    <li>
                                                        <div class="input-group mb-1">
                                                            <div class="btn-group-toggle">
                                                                {{-- <label class="btn btn-danger"> --}}
                                                                    {{-- <input type="checkbox" name="menus[]" value="{{ $menu->id }}" autocomplete="off"> {{ $child->title }} --}}
                                                                    <label>
                                                                        {!! Form::checkbox('menus['.$child->id.']', $child->id, old('menus.'.$child->id, $role->canAccessMenu($child)), array('id' => 'menus-' . $child->id) ) !!}
                                                                        <span> {{ $child->title }} </span>
                                                                    </label>
                                                                    {{-- {!! Form::label('menus-' . $child->id, $child->title, ['class'=>'control-label']) !!} --}}
                                                                    {{-- </label> --}}
                                                            </div>
                                                        </div>
                                                        
                                                        @if(count($child['children']) > 0) 
                                                            <ul>
                                                                @foreach($child['children'] as $grandchild) 
                                                                    <li>
                                                                        <div class="input-group mb-1">
                                                                            <div class="btn-group-toggle">
                                                                                {{-- <label class="btn btn-danger"> --}}
                                                                                    {{-- <input type="checkbox" name="menus[]" value="{{ $menu->id }}" autocomplete="off"> {{ $grandchild->title }} --}}
                                                                                <label>
                                                                                    {!! Form::checkbox('menus['.$grandchild->id.']', $grandchild->id, old('menus.'.$grandchild->id, $role->canAccessMenu($grandchild)), array('id' => 'menus-' . $grandchild->id) ) !!}
                                                                                    <span> {{ $grandchild->title }} </span>
                                                                                </label>
                                                                                    {{-- {!! Form::label('menus-' . $grandchild->id, $grandchild->title, ['class'=>'control-label']) !!} --}}
                                                                                {{-- </label> --}}
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                        
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>

                </div>
            </div>
        </div> <!-- end .form-group -->

    </div>
</div>

{!! Form::close() !!}

@endsection