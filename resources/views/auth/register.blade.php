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

                    @if (session('success'))
                        <div class="text-success text-center mb-3">
                            {{ session('success') }}
                        </div>
                    @endif 
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
                        <form action="{{ route('register') }}" method="post">

                        @csrf

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-field">
                                        {!! Form::label('name', 'Full Name') !!}
                                        <input type="text"
                                            class="form-control"
                                            name="name"
                                            value="{{ old('name') }}">
                                    </div>
                                </div>
                            </div>


                            <div class="row mb-3 mt-2">
                                <div class="col-md-12">
                                    <div class="input-field">
                                        {!! Form::label('company', 'Company', array('class' => 'active')) !!}
                                        {!! Form::select('company', $companies, old('company'), array('class'=>'form-control selectpicker', 'title' => 'Select Company')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 mt-2">
                                <div class="col-md-12">
                                    <div class="input-field">
                                        {!! Form::label('position', 'Position', array('class' => 'active')) !!}
                                        {!! Form::select('position', $positions, old('position'), array('class'=>'form-control selectpicker', 'title' => 'Select Position')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="input-field">
                                        {!! Form::label('email', 'Email Address') !!}
                                        <input type="email"
                                            class="form-control"
                                            name="email"
                                            value="{{ old('email') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="input-field">
                                        {!! Form::label('id_number', 'ID Number') !!}
                                        <input type="text"
                                            class="form-control"
                                            name="id_number"
                                            value="{{ old('id_number') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="input-field">
                                        {!! Form::label('mobile_number', 'Mobile Number') !!}
                                        <input type="text"
                                            class="form-control"
                                            name="mobile_number"
                                            value="{{ old('mobile_number') }}">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit"
                                            class="btn btn-primary"
                                            style="width: 100%">
                                        Sign Up
                                    </button>
                                </div>

                                <div class="col-md-12 mt-2">
                                    <a href="{{route('login')}}" class="text-center">I already have an account</a>
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
