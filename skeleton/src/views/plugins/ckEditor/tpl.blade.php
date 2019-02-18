at_symbolpush('css')
<link href="../vendor/betterfly/plugins/ckeditor/ckEditorSamples.css" rel="stylesheet">
at_symbolendpush

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
    loadScript(['../vendor/betterfly/plugins/ckeditor/ckeditor.js'], onload);
    function onload() {
      loadScript(['../vendor/betterfly/plugins/ckeditor/adapters/jquery.js'], function(){
        $( "textarea#{{ $plugin_id }}" ).ckeditor();
      });
    }
</script>
at_symbolendpush