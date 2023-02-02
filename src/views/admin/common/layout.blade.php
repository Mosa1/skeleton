<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CMS">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keyword" content="">
    <title>CMS</title>
    <base href="{{ URL::to('/') }}/">
    <!-- Icons-->
    <link href="{{ asset('vendor/betterfly/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/betterfly/css/simple-line-icons.css') }}" rel="stylesheet">
    <!-- Icons-->
    <link href="{{ asset('vendor/betterfly/css/style-min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/betterfly/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/betterfly/css/general.css') }}" rel="stylesheet">
    @stack('css')
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    {{--<a class="navbar-brand" href="#">--}}
    {{--<img class="navbar-brand-full" src="img/brand/logo.svg" width="89" height="25" alt="CoreUI Logo">--}}
    {{--<img class="navbar-brand-minimized" src="img/brand/sygnet.svg" width="30" height="30" alt="CoreUI Logo">--}}
    {{--</a>--}}
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="nav navbar-nav d-md-down-none">
        <li class="nav-item px-3">
            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
        </li>
        {{--@if(Auth::user()->hasRole('super-admin'))--}}
        <li class="nav-item px-3">
            <a class="nav-link" href="{{ route('users.index') }}">Users</a>
        </li>
        {{--@endif--}}
        <li class="nav-item px-3">
            <a class="nav-link" href="{{ route('users.edit',Auth()->user()->id) }}">Settings</a>
        </li>
    </ul>
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link px-4" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
               aria-expanded="false">
                <img class="img-avatar" src="{{ asset('vendor/betterfly/img/avatar.png') }}" alt="">
                {{ \Illuminate\Support\Facades\Auth::user()->email }}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-center">
                    <strong>Account</strong>
                </div>
                {{--<a class="dropdown-item" href="#">--}}
                {{--<i class="fa fa-bell-o"></i> Updates--}}
                {{--<span class="badge badge-info">42</span>--}}
                {{--</a>--}}
                {{--<a class="dropdown-item" href="#">--}}
                {{--<i class="fa fa-envelope-o"></i> Messages--}}
                {{--<span class="badge badge-success">42</span>--}}
                {{--</a>--}}
                {{--<a class="dropdown-item" href="#">--}}
                {{--<i class="fa fa-tasks"></i> Tasks--}}
                {{--<span class="badge badge-danger">42</span>--}}
                {{--</a>--}}
                {{--<a class="dropdown-item" href="#">--}}
                {{--<i class="fa fa-comments"></i> Comments--}}
                {{--<span class="badge badge-warning">42</span>--}}
                {{--</a>--}}
                {{--<div class="dropdown-header text-center">--}}
                {{--<strong>Settings</strong>--}}
                {{--</div>--}}
                {{--<a class="dropdown-item" href="#">--}}
                {{--<i class="fa fa-user"></i> Profile</a>--}}
                {{--<a class="dropdown-item" href="#">--}}
                {{--<i class="fa fa-wrench"></i> Settings</a>--}}
                {{--<a class="dropdown-item" href="#">--}}
                {{--<i class="fa fa-usd"></i> Payments--}}
                {{--<span class="badge badge-secondary">42</span>--}}
                {{--</a>--}}
                {{--<a class="dropdown-item" href="#">--}}
                {{--<i class="fa fa-file"></i> Projects--}}
                {{--<span class="badge badge-primary">42</span>--}}
                {{--</a>--}}
                {{--<div class="dropdown-divider"></div>--}}
                {{--<a class="dropdown-item" href="#">--}}
                {{--<i class="fa fa-shield"></i> Lock Account</a>--}}
                <a class="dropdown-item" href="{{ route('betterfly.logout') }}">
                    <i class="fa fa-lock"></i> Logout</a>
            </div>
        </li>
        @if(config('translatable') && config('translatable.locales'))
            <li class="nav-item row">
                @foreach(config('translatable.locales') as $locale)
                    @if(is_array($locale)) @continue @endif
                    <a class="nav-link mr-2" href="{{ route('admin.setLocale',$locale) }}">
                        <button class="btn btn-sm btn-pill btn-primary {{ \App::getLocale() == $locale ? "btn-success" : ''}}">
                            <img src="{{ asset('vendor/betterfly/img/lang_'.$locale.'.png') }}">
                        </button>
                    </a>
                @endforeach
            </li>
        @endif
    </ul>
    {{--<button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" data-toggle="aside-menu-lg-show">--}}
    {{--<span class="navbar-toggler-icon"></span>--}}
    {{--</button>--}}
    <button class="navbar-toggler aside-menu-toggler d-lg-none" type="button" data-toggle="aside-menu-show">
        <span class="navbar-toggler-icon"></span>
    </button>
</header>
<div class="app-body">
    <div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="nav-icon icon-speedometer"></i> Dashboard
                    </a>
                </li>
                <li class="nav-title">Modules</li>

                @include('admin.common.menu')

                <li class="divider"></li>
                <li class="nav-title">Extras</li>
                <li class="nav-item nav-dropdown">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('translatable.index') }}" target="_top">
                        <i class="nav-icon icon-star"></i> Translations</a>
                </li>
            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>


    @yield('content')

</div>
<footer class="app-footer">
    <div>
        <span>&copy; {{ date('Y') }} .</span>
    </div>
</footer>
<script src="{{ asset('vendor/betterfly/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('vendor/betterfly/js/popper.min.js') }}"></script>
<script src="{{ asset('vendor/betterfly/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/betterfly/js/pace.min.js') }}"></script>
<script src="{{ asset('vendor/betterfly/js/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('vendor/betterfly/js/coreui.min.js') }}"></script>
<script src="{{ asset('vendor/betterfly/js/Modal.js') }}"></script>
<script src="{{ asset('vendor/betterfly/js/ScriptsLoader.js') }}"></script>

@stack('scripts')
<script>
  var filesRoute = "{{ route('file.index') }}";
  var ajaxValidation = "{{ route('ajax-validation') }}";
  var csrf = $('meta[name="csrf-token"]').attr('content');
  $(function () {
    $('.remove-item').click(function () {
      var action = $(this).data().url;
      var row = $(this).parents('tr');
      var self = $(this);
      Modal.show({
        yesClass: 'btn-danger',
        body: 'You are going to delete this Item, do you want to continue?',
        yes: 'Delete',
        callback: function (btn) {
          Modal.hide();

          if (btn === 'yes') {
            $.ajax({
              url: action,
              type: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': csrf
              },
              success: function (result) {
                if (result.message === 'Successfully deleted') {
                  if (self.parents('li').length) {
                    self.closest('li').remove();
                  } else {
                    table.row(row).remove().draw();
                  }
                }
              }
            });
          }
        }
      });
    });

    $('.checkbox-plugin').change(function () {
      var val = $(this).is(':checked') ? 1 : 0;
      var checkbox = $('input[name="' + $(this).attr('for') + '"]');
      checkbox.val(val)
    });


    $('.visibility').change(function () {
      var action = $(this).data().url;
      var csrf = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
        url: action,
        type: 'PATCH',
        data: {visibility: $(this).is(':checked') ? 1 : 0},
        headers: {
          'X-CSRF-TOKEN': csrf
        },
        success: function (result) {

        }
      });
    });
  });
</script>
<div class="copied">
    <button class="btn btn-pill btn-danger" type="button">
        <i class="fa fa-lightbulb-o"></i>&nbsp;Copied
    </button>
</div>
</body>
</html>
