@include('admin.partials.header')
<div class="container-fluid">
    <div class="row">
        <div id="login" class="home-with-bg col-sm-12">
            <div class="bg-login-register"></div>
            <div class="login-form">

                <div class="login-title">
                    <h1>Inventory</h1>
                    <h3>"Tagline here"</h3>
                </div>
                


                <div class="login-form-container">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>{{ trans('quickadmin::auth.whoops') }}</strong> {{ trans('quickadmin::auth.some_problems_with_input') }}
                            <br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="form-horizontal"
                        role="form"
                        method="POST"
                        action="{{ url('login') }}">
                        <input type="hidden"
                            name="_token"
                            value="{{ csrf_token() }}">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-field">
                                        {!! Form::label('email', trans('quickadmin::auth.login-email')) !!}
                                        <input type="email"
                                            class="form-control"
                                            name="email"
                                            value="{{ old('email') }}">

                                    </div>
                                </div>
                            
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-field">
                                        {!! Form::label('password', trans('quickadmin::auth.login-password')) !!}

                                        <input type="password"
                                            class="form-control"
                                            name="password">
                                    </div>
                                </div>
                                <div class="col-md-12 text-left mt-0"><a href="#">forgot password</a></div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit"
                                            class="btn btn-primary"
                                            style="width: 100%">
                                        {{ trans('quickadmin::auth.login-btnlogin') }}
                                    </button>
                                </div>

                                <div class="col-md-12 mt-2">
                                    <a  href="{{url('register')}}"
                                        class="btn btn-primary"
                                        style="width: 100%">
                                        Sign up
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="login-footer">
                    <div>logistics@email.com</div>
                    <div>+63 2 88888888</div>
                    <div><a href="#">www.logistics.com</a></div>
                </div>

            </div>


        </div>
    </div>
</div>
@include('admin.partials.javascripts') @yield('javascript')
@include('admin.partials.footer')
