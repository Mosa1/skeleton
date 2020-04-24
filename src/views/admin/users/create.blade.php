@extends('betterfly::admin.common.layout')

@section('content')

    <main class="main">

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route("users.index") }}">Users</a>
            </li>
            <li class="breadcrumb-item active">User</li>
        </ol>

        @if(\Session::get('status'))
            <div class="container-fluid">
                <div id="ui-view">
                    <div class="alert alert-success" role="alert"> {{ \Session::get('status') }}</div>
                </div>
            </div>
        @endif
        <div class="container-fluid">
            <div id="ui-view">
                <div>
                    <div class="animated fadeIn">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Users</strong>
                                    </div>
                                    <div class="card-body">
                                        <form class="form-horizontal"
                                              action="{{ route("users.store") }}" method="post"
                                              enctype="multipart/form-data">
                                            @csrf
                                            @php
                                                if(isset($data)){
                                                    $value = $data->name;
                                                    $value = key_exists('name',old()) ? old('name') : $value;
                                                } else{
                                                    $value = old('name');
                                                }
                                            @endphp


                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label" for="text-input">Name</label>
                                                <div class="col-md-9">
                                                    <input  required class="form-control" value="{{ $value }}" type="text" name="name" placeholder="Name">
                                                    @if($errors->get('name'))
                                                        <br>
                                                        <div class="alert alert-danger" role="alert">{{ $errors->first('name') }}</div>
                                                    @endif
                                                </div>
                                            </div>@php
                                                if(isset($data)){
                                                    $value = $data->email;
                                                    $value = key_exists('email',old()) ? old('email') : $value;
                                                } else{
                                                    $value = old('email');
                                                }
                                            @endphp


                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label" for="text-input">Email</label>
                                                <div class="col-md-9">
                                                    <input  required class="form-control" value="{{ $value }}" type="text" name="email" placeholder="Email">
                                                    @if($errors->get('email'))
                                                        <br>
                                                        <div class="alert alert-danger" role="alert">{{ $errors->first('email') }}</div>
                                                    @endif
                                                </div>
                                            </div>@php
                                                if(isset($data)){
                                                    $value = $data->password;
                                                    $value = key_exists('password',old()) ? old('password') : $value;
                                                } else{
                                                    $value = old('password');
                                                }
                                            @endphp

                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label" for="password-input">Password</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="password" name="password" placeholder="Password">
                                                    @if($errors->get('password'))
                                                        <br>
                                                        <div class="alert alert-danger" role="alert">{{ $errors->first('password') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label" for="password-input">Confirm Password</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="password" name="c_password" placeholder="Confirm Password">
                                                    @if($errors->get('c_password'))
                                                        <br>
                                                        <div class="alert alert-danger" role="alert">{{ $errors->first('c_password') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <input class="form-control" type="hidden" name="is_super" value="1" placeholder="Super Admin">



                                            <input type="hidden" value="\App\Modules\Users\UsersRequest" name="request_name_space">
                                            <div class=" text-right">
                                                <button class="btn btn-sm btn-success btn-primary" type="submit">
                                                    <i class="fa fa-dot-circle-o"></i> Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
