at_symbolextends('betterfly::admin.common.layout')


at_symbolpush('css')
<style>
    body.dragging, body.dragging * {
        cursor: move !important;
    }

    .dragged {
        position: absolute;
        opacity: 0.5;
        z-index: 2000;
    }
    ol.sortable li{
        list-style: none;
    }
    ol.sortable li.placeholder {
        position: relative;
        height: 42px;
        background: #fff;
        border: 1px dashed #0088cc;
        margin-bottom: 7px;
        /** More li styles **/
    }

    ol.sortable li.placeholder.mjs-nestedSortable-error {
        background: #fbe3e4;
    }

    ol.sortable li > div{
        display: block;
        border: 1px solid #cccccc;
        color: #0088cc;
        cursor: pointer;
        background: #eeeeee;
        line-height: 42px;
        padding: 5px 13px;
        box-sizing: border-box;
    }

    ol.sortable li > div:after{
        content: "";
        display: block;
        clear: both;
    }

    ol.sortable li  a{
        float: right;
    }

    ol.sortable li ol {
        margin-top: 10px;
    }

</style>
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
                    <div class="col-lg-12 p-0">
                        <div class="card">
                            <div class="card-header">
                                @if(!property_exists($cfg->indexPlugin[0],'addBtn') || $cfg->indexPlugin[0]->addBtn)
                                    <div class="col-xl-12 text-right">
                                        <a href="print_start route('{{ str_plural(strtolower($moduleName)) }}.create') print_end"
                                           class="btn btn-square btn-success active"
                                           type="button"
                                           aria-pressed="true">{{ $addBtnText }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <ol class="sortable p-0 text" data-url="{{ route('update-order',$moduleName) }}">
                                    at_symbolphp
                                        $traverse = function ($data) use (&$traverse){
                                            $tpl = '';
                                            foreach ($data as $item) {
                                                $tpl .= '<li id="item_'.$item->id.'"">
                                                <div class="mb-2">'
                                                    @if(property_exists($cfg->indexPlugin[0],'displayFields'))
                                                        @foreach($cfg->indexPlugin[0]->displayFields as $field)
                                                            .$item->{{$field}}.' | '
                                                        @endforeach
                                                    @else
                                                        .$item->name
                                                    @endif
                                                    .'
                                                    <a data-url="'.route("{{ str_plural(strtolower($moduleName)).'.delete' }}",$item->id).'" class="btn btn-danger remove-item p-2" href="javascript:;">
                                                      <i class="fa fa-trash-o"></i>
                                                    </a>
                                                    <a class="btn btn-info p-2 mr-2" href="'.route("{{ str_plural(strtolower($moduleName)).'.edit' }}",$item->id).'">
                                                      <i class="fa fa-edit"></i>
                                                    </a>
                                                </div>';
                                                if($item->children->count()){
                                                    $childTpl = '<ol>';
                                                    $childTpl .= $traverse($item->children);
                                                    $childTpl .= '</ol>';
                                                }else{
                                                    $childTpl = '';
                                                }

                                                $tpl .= $childTpl;
                                                $tpl .= '</li>';

                                            }

                                            return $tpl;
                                        };
                                        echo $traverse($data);
                                    at_symbolendphp
                                </ol>
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
  loadScript('../vendor/betterfly/js/jquery.ui-min.js', sortableListLoaded);

  function sortableListLoaded() {

    loadScript('../vendor/betterfly/plugins/sortableList/sortableList.js', function () {

      var list = $("ol.sortable").nestedSortable({
        forcePlaceholderSize: true,
        handle: 'div',
        helper: 'clone',
        items: 'li',
        opacity: .6,
        placeholder: 'placeholder',
        tolerance: 'pointer',
        toleranceElement: '> div',
        maxLevels: {{ property_exists($cfg->indexPlugin[0],'depth') ? $cfg->indexPlugin[0]->depth : 2 }},
        update: function (event, ui) {
          var serialized = $("ol.sortable").nestedSortable('toHierarchy', {startDepthCount: 0});
          var action = $("ol.sortable").data('url');
          $.ajax({
            url: action,
            type: 'POST',
            data: {data: serialized},
            headers: {
              'X-CSRF-TOKEN': csrf
            },
            success: function (result) {
              if (result.message === 'Successfully deleted') {

              }
            }
          });
        }
      });
    });
  }
</script>
at_symbolendpush
