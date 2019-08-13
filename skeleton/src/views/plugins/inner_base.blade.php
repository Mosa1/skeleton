at_symbolextends('betterfly::admin.common.layout')

at_symbolsection('content')
@php
    $routeStr = $routeType == 'update' ?  'route("'.$moduleRoute.'.update",['.($cfg->parentModule ? '$'.str_singular($cfg->parentModule).'->id,' : '').'$data->id])' : 'route("'.$moduleRoute.'.store"'.($cfg->parentModule ? ',[$'.str_singular($cfg->parentModule).'->id]': '').')';
@endphp

<main class="main">

    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="print_start route('dashboard') print_end">Dashboard</a>
        </li>
        @if(!$cfg->editModeOnly)
            <li class="breadcrumb-item">
                <a href="print_start {!! 'route("'.$moduleRoute.'.index"'.($cfg->parentModule ? ',$'.str_singular($cfg->parentModule).'->id' : '').')' !!} print_end">{{ $moduleName }}</a>
            </li>
        @endif
        <li class="breadcrumb-item active">{{ $moduleName }}</li>
    </ol>

    at_symbolif(\Session::get('status'))
    <div class="container-fluid">
        <div id="ui-view">
            <div class="alert alert-success" role="alert"> print_start \Session::get('status') print_end</div>
        </div>
    </div>
    at_symbolendif
    <div class="container-fluid">
        <div id="ui-view">
            <div>
                <div class="animated fadeIn">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong>{{ $moduleName }}</strong>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal"
                                          action="print_start {!! $routeStr !!} print_end" method="post"
                                          enctype="multipart/form-data">
                                        @if($routeType == 'update')
                                            at_symbolmethod('PUT')
                                        @endif
                                        at_symbolcsrf
                                        {plugins}
                                        <input type="hidden" value="{{$requestNameSpace}}" name="request_name_space">
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
at_symbolendsection