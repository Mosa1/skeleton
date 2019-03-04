at_symbolphp
    if(isset($data)){
        $value = $data->{{$fieldName}};
        $value = key_exists('{{$fieldName}}',old()) ? old('{{$fieldName}}') : $value;
    } else{
        $value = old('{{ $fieldName }}');
    }

at_symbolendphp
@php
    $defaultCfg = [
        'folder' => 'files',
        'title'=>'Image',
        'mimeTypes' => ['jpg','jpeg','png','svg'],
        'maxCount' => 1,
        'required' => false,
        'thumbs' => [],
        'crop'   => false
    ];
    $cfg = (object)array_merge($defaultCfg,(array)$properties);
    $multiple = $cfg->maxCount > 1;
@endphp

<div class="form-group row">
    <label class="col-md-3 col-form-label" for="{{$plugin_id}}">{{ $properties->title }}</label>
    <div class="col-md-9">
        <input for="{{ $fieldName }}" name="{{ $plugin_id }}" id="{{$plugin_id}}" type="file">
        <input type="hidden" name="{{ $fieldName }}" value="none">
        <br>
        <br>
    </div>
</div>
at_symbolpush('scripts')
<script>
  loadCss(['../vendor/betterfly/plugins/imagePlugin/imagePlugin.css']);
  loadScript(['../vendor/betterfly/plugins/imagePlugin/imagePlugin.js'], onload);

  function onload() {
    $('input#{{$plugin_id}}').imagePlugin({
      maxCount: {{ $cfg->maxCount }},
      types: [@foreach($cfg->mimeTypes as $type)"{{ $type }}",@endforeach],
      required: {{ $cfg->required ? 'true' : 'false' }}
    });
  }
</script>
at_symbolendpush