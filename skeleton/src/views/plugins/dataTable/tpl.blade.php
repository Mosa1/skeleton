at_symbolextends('betterfly::admin.common.layout')


at_symbolpush('css')
<link href="../vendor/betterfly/plugins/dataTable/dataTables.bootstrap4.min.css" rel="stylesheet">
at_symbolendpush

at_symbolsection('content')
@php
    $addBtnText = property_exists($cfg->indexPlugin[0],'addBtn') && is_object($cfg->indexPlugin[0]->addBtn) && property_exists($cfg->indexPlugin[0]->addBtn,'text') ? $cfg->indexPlugin[0]->addBtn->text : 'add New';
@endphp
<main class="main">

    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="print_start route('dashboard') print_end">Dashboard</a>
        </li>
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
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-edit"></i> {{ $moduleName }}
                            <div class="card-header-actions">
                                <a class="card-header-action" href="https://datatables.net" target="_blank">
                                    <small class="text-muted">docs</small>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(!property_exists($cfg->indexPlugin[0],'addBtn') || $cfg->indexPlugin[0]->addBtn)
                                <div class="col-xl-12 text-right">
                                    <a href="print_start route('{{ str_plural(strtolower($moduleName)) }}.create') print_end"
                                       class="btn btn-square btn-success active col-xl-1 mb-3"
                                       type="button"
                                       aria-pressed="true">{{ $addBtnText }}
                                    </a>
                                </div>
                            @endif
                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div class="col-sm-12">
                                    <table id="datatable"
                                           class="table table-striped table-bordered datatable dataTable no-footer datatable dataTable">
                                        <thead>
                                        <tr>
                                            @foreach($cfg->indexPlugin[0]->cols as $col)
                                                <th>{{ $col->name }}</th>
                                            @endforeach
                                            @if(property_exists($cfg->indexPlugin[0],'setStatus') && $cfg->indexPlugin[0]->setStatus)
                                                <th>Status</th>
                                            @endif
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        at_symbolforeach($data as $key => $item)
                                        <tr role="row"
                                            class="print_start ($key + 1 % 2) == 0 ? 'odd' : 'even' print_end">

                                            @foreach($cfg->indexPlugin[0]->cols as $col)
                                                <td class="">print_start strip_tags($item->{{$col->value}}) print_end</td>
                                            @endforeach


                                            @if(property_exists($cfg->indexPlugin[0],'setStatus') && $cfg->indexPlugin[0]->setStatus)
                                                <td class="text-center">
                                                    <label class="switch switch-label switch-success">
                                                        <input class="switch-input" type="checkbox" checked="">
                                                        <span class="switch-slider" data-checked="On"
                                                              data-unchecked="Off"></span>
                                                    </label>
                                                </td>
                                            @endif

                                            <td class="text-right">
                                                <a class="btn btn-info"
                                                   href="print_start route('{{ str_plural(strtolower($moduleName)) }}.edit',$item->id) print_end">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                @if(!property_exists($cfg->indexPlugin[0],'removeBtn') || $cfg->indexPlugin[0]->removeBtn)
                                                    <a data-url="print_start route('{{ str_plural(strtolower($moduleName)) }}.delete',$item->id) print_end"
                                                       class="btn btn-danger remove-item" href="javascript:;">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        at_symbolendforeach
                                        </tbody>
                                    </table>
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

at_symbolpush('scripts')
<script src="../vendor/betterfly/plugins/dataTable/jquery.dataTables.js"></script>
<script src="../vendor/betterfly/plugins/dataTable/dataTables.bootstrap4.js"></script>
<script>
  var table = $('#datatable').DataTable({
    "columnDefs": [
        <?php foreach($cfg->indexPlugin[0]->cols as $key => $col){ ?>
            {
                "searchable": <?= property_exists($col,'searchable') && $col->searchable  ? 'true' : 'false' ?> ,
                "targets": <?= $key ?>,
                "sortable": <?= property_exists($col,'sortable') && $col->sortable  ? 'true' : 'false'  ?>
            },
        <?php } ?>
    ]
  });
</script>
at_symbolendpush
