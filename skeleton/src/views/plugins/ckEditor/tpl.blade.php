at_symbolphp
if(isset($data)){
$value = $data->{{$fieldName}};
$value = key_exists('{{$fieldName}}',old()) ? old('{{$fieldName}}') : $value;
} else{
$value = old('{{ $fieldName }}');
}
at_symbolendphp

<div class="form-group row">
    <label class="col-md-3 col-form-label" for="text-input">{{ $properties->title }}</label>
    <div class="col-md-9">
        <textarea id="{{$plugin_id}}" name="{{ $fieldName }}">
            print_start $value print_end
        </textarea>
        at_symbolif($errors->get('{{ $fieldName }}'))
        <br>
        <div class="alert alert-danger" role="alert">print_start $errors->first('{{ $fieldName }}') print_end</div>
        at_symbolendif
    </div>
</div>

at_symbolpush('scripts')


<script>
  loadCss('../vendor/betterfly/plugins/ckeditor/ckEditorSamples.css');

  loadScript(['../vendor/betterfly/plugins/ckeditor/ckeditor.js'], load{{$plugin_id}});

  function load{{$plugin_id}}() {
    loadScript(['../vendor/betterfly/plugins/ckeditor/adapters/jquery.js'], function () {
      $("textarea#{{ $plugin_id }}").ckeditor({
        extraPlugins: "image2",
        filebrowserBrowseUrl: "print_start route('ckfinder_browser') print_end",
        filebrowserUploadUrl: "print_start route('ckfinder_connector') print_end?command=QuickUpload&type=Files"
      });
    });
  }
</script>
at_symbolendpush