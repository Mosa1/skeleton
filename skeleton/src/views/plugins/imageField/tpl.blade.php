at_symbolphp
    if(isset($data)){
        $images =  json_decode($data->{{$fieldName}}) ? json_decode($data->{{$fieldName}}) : [$data->{{$fieldName}}];
        $value =  $data->{{$fieldName}};
    }else{
        $value = 'none';
        $images = [];
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
        <input type="hidden" name="{{ $fieldName }}" value="print_start $value print_end">
        <br>
        <br>
        at_symbolforeach($images as $key => $image)
            <div class="preview-container">
                <img class="old" src="print_start $image print_end" height="150">
                <a data-index="print_start $key print_end" href="javascript:;" class="remove-image">
                    <i class="fa fa-close"></i>
                </a>
            </div>
        at_symbolendforeach
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
      folder: "{{ $cfg->folder }}",
      thumbs: [@foreach($cfg->thumbs as $thumb){!! json_encode($thumb) !!},@endforeach],
      required: {{ $cfg->required ? 'true' : 'false' }}
    });
  }
</script>
at_symbolendpush