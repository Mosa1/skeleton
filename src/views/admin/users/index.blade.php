@extends('betterfly::admin.common.layout')
@section('content')
    <main class="main">

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Users</li>
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
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-edit"></i> Users
                                <div class="card-header-actions">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-xl-12 text-right">
                                    <a href="{{ route('users.create') }}"
                                       class="btn btn-square btn-success active col-xl-1 mb-3"
                                       type="button"
                                       aria-pressed="true">add new User
                                    </a>
                                </div>
                                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="col-sm-12">
                                        <table id="datatable"
                                               class="table table-striped table-bordered datatable dataTable no-footer datatable dataTable">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data as $key => $item)
                                                <tr role="row"
                                                    class="{{ ($key + 1 % 2) == 0 ? 'odd' : 'even' }}">


                                                    <td class="align-middle">{{
                                                        strip_tags($item->name) }}
                                                    </td>

                                                    <td class="align-middle">{{
                                                        strip_tags($item->email) }}
                                                    </td>

                                                    <td class="text-center align-middle">
                                                        <a class="btn btn-info"
                                                           href="{{ route('users.edit',$item->id) }}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>

                                                        <a data-url="{{ route('users.delete',$item->id) }}"
                                                           class="btn btn-danger remove-item" href="javascript:;">
                                                            <i class="fa fa-trash-o"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
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
@endsection


@push('scripts')
    <script>
      loadCss('../vendor/betterfly/plugins/dataTable/dataTables.bootstrap4.min.css');

      loadScript('../vendor/betterfly/plugins/dataTable/jquery.dataTables.js', dataTableLoaded);

      function dataTableLoaded() {
        loadScript('../vendor/betterfly/plugins/dataTable/dataTables.bootstrap4.js', bootstrapLoaded);

        function bootstrapLoaded() {
          table = $('#datatable').DataTable({
            "columnDefs": [
              {
                "searchable": true ,
                "targets": 0,
                "sortable": true
              },
              {
                "searchable": true,
                "targets": 1,
                "sortable": true
              },
            ]
          });
        }
      }
    </script>
@endpush
