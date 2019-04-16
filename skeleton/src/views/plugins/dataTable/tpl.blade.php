at_symbolextends('betterfly::admin.common.layout')


at_symbolpush('css')
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
                                @if($cfg->excelExport)
                                    <form method="POST" action="print_start route('excel-export') print_end">
                                        <input type="hidden"  name="data" value='print_start_allow_chars json_encode($data) print_end_allow_chars'>
                                        <input type="hidden"  name="name" value='{{ property_exists($cfg->excelExport,'name') ? $cfg->excelExport->name : 'file' }}'>
                                        <input type="hidden"  name="format" value='{{ property_exists($cfg->excelExport,'format') ? $cfg->excelExport->format : 'xlsx' }}'>
                                        at_symbolcsrf
                                        <button type="submit"
                                                class="btn btn-warning btn-ladda-progress excel-export ladda-button"
                                                data-style="expand-right"><span
                                                    class="ladda-label">Generate Excel</span><span
                                                    class="ladda-spinner"></span>
                                            <div class="ladda-progress" style="width: 114px;"></div>
                                        </button>
                                    </form>
                                @endif
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
                                            @if(property_exists($cfg,'setVisibility') && $cfg->setVisibility)
                                                <th>Visibility</th>
                                            @endif
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        at_symbolforeach($data as $key => $item)
                                        <tr role="row"
                                            class="print_start ($key + 1 % 2) == 0 ? 'odd' : 'even' print_end">

                                            @foreach($cfg->indexPlugin[0]->cols as $col)

                                                @if(property_exists($col,'renderer'))
                                                    @if($col->renderer == 'photo')
                                                        <td class="align-middle">
                                                            <img class="datatable-image"
                                                                 src="print_start $item->{{$col->value}} print_end">
                                                        </td>
                                                    @endif
                                                @else
                                                    <td class="align-middle">print_start
                                                        strip_tags($item->{{$col->value}}) print_end
                                                    </td>
                                                @endif
                                            @endforeach


                                            @if(property_exists($cfg,'setVisibility') && $cfg->setVisibility)
                                                <td class="text-center align-middle">
                                                    <label class="switch switch-label switch-success">
                                                        <input data-url="print_start route('set-visibility',['{{ str_plural($moduleName) }}',$item->id]) print_end"
                                                               class="switch-input visibility" type="checkbox"
                                                               print_start $item->visibility ? 'checked' : '' print_end>
                                                        <span class="switch-slider" data-checked="On"
                                                              data-unchecked="Off"></span>
                                                    </label>
                                                </td>
                                            @endif

                                            <td class="text-center align-middle">
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
                                    <div class="text-right">
                                        <div class="d-inline-block">print_start method_exists($data,'links') ? $data->links() : '' print_end</div>
                                    </div>
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
<script>
  loadCss('../vendor/betterfly/plugins/dataTable/dataTables.bootstrap4.min.css');
  @if($cfg->excelExport)
  loadCss('../vendor/betterfly/css/lada.css');
  loadScript(
      [
        '../vendor/betterfly/js/spin.min.js',
        '../vendor/betterfly/js/ladda.min.js',
      ], ladaLoaded
  );

  function ladaLoaded() {
    Ladda.bind('.btn-ladda-progress', {
      callback: function callback(instance) {
        var progress = 0;
        var interval = setInterval(function () {
          progress = Math.min(progress + Math.random() * 0.1, 1);
          instance.setProgress(progress);
          if (progress === 1) {
            instance.stop();
            clearInterval(interval);
          }
        }, 50);
      }
    });
  }
  @endif

  loadScript('../vendor/betterfly/plugins/dataTable/jquery.dataTables.js', dataTableLoaded);

  function dataTableLoaded() {
    loadScript('../vendor/betterfly/plugins/dataTable/dataTables.bootstrap4.js', bootstrapLoaded);

    function bootstrapLoaded() {
      table = $('#datatable').DataTable({
        "paging": print_start method_exists($data,'links') ? 'false,' : 'true,' print_end
        "columnDefs": [
                <?php foreach($cfg->indexPlugin[0]->cols as $key => $col){ ?>
          {
            "searchable": <?= property_exists($col, 'searchable') && $col->searchable ? 'true' : 'false' ?> ,
            "targets": <?= $key ?>,
            "sortable": <?= property_exists($col, 'sortable') && $col->sortable ? 'true' : 'false'  ?>
          },
            <?php } ?>
        ]
      });
    }
  }
</script>
at_symbolendpush
