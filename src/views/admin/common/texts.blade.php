@extends('betterfly::admin.common.layout')
@section('content')
    <main class="main">

        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Texts</li>
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
                            <div class="col-sm-6 col-md-6">
                                <div class="card text-white bg-warning">
                                    <div class="card-header text-center">
                                        Default Text
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="card text-white bg-success">
                                    <div class="card-header text-center">
                                        Translated On Language
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($words as $key => $value)
                            <div class="text-container row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="card">
                                        <div class="card-body translatable-key" data-key="{{ $key }}">
                                            {{ $key }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="card">
                                        <div class="p-0 card-body">
                                            <textarea rows="1">{{ $value }}</textarea>
                                            <div class="buttons-container">
                                                <button class="btn btn-sm btn-success m-2 text-save" type="button">
                                                    Save
                                                </button>
                                                <button class="btn btn-sm btn-warning m-2 text-cancel" type="button">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endforeach
                        <div class="row text-center">
                            <div class="col-12">
                                <button class="btn d-inline-block btn-square btn-success auto-translate" type="button">
                                    Auto Translate By Yandex API
                                </button>
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
      var textsRoute = "{{ route('translatable.index') }}";
      $('textarea').focus(function () {
        $('.text-container').removeClass('active');
        $('.text-container').find('textarea').attr('rows', '1');
        $(this).parents('.text-container').addClass('active');
        $(this).attr('rows', '4');
      });

      $('.text-cancel').click(function () {
        $(this).parents('.text-container').removeClass('active');
        $(this).parents('.text-container').find('textarea').attr('rows', '1');
      });


      $('.text-save').click(function () {
        var translVal = $(this).parents('.text-container').find('textarea').val();
        var translKey = $(this).parents('.text-container').find('.translatable-key').data('key');
        var data = new FormData;
        var self = $(this);
        data.append('key', translKey);
        data.append('value', translVal);

        $.ajax({
          url: textsRoute,
          method: "POST",
          data: data,
          contentType: false,
          processData: false,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            if (response.success) {
              self.parents('.text-container').removeClass('active');
              self.parents('.text-container').find('textarea').attr('rows', '1');
            }
          }
        });
      })


      $('.auto-translate').click(function () {
        var translVal = $(this).parents('.text-container').find('textarea').val();
        var translKey = $(this).parents('.text-container').find('.translatable-key').data('key');
        var data = new FormData;
        var self = $(this);
        data.append('key', translKey);
        data.append('value', translVal);

        $.ajax({
          url: textsRoute+'-auto-translate',
          method: "Get",
          data: data,
          contentType: false,
          processData: false,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            if (response.success) {
              location.reload();
            }
          }
        });
      })
    </script>
@endpush
