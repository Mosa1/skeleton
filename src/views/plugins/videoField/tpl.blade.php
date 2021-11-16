at_symbolphp
    if(isset($data)){
        $files =  json_decode($data->{{$fieldName}}) ? json_decode($data->{{$fieldName}}) : ($data->{{$fieldName}} && $data->{{$fieldName}} !== 'none' ?  [$data->{{$fieldName}}]: []);
        $value =  $data->{{$fieldName}};
    }else{
        $value = null;
        $files = [];
    }

at_symbolendphp
@php
    $defaultCfg = [
        'folder' => 'files',
        'title'=>'File',
        'mimeTypes' => ['mp4','webm','ogg'],
        'maxCount' => 1,
        'thumbs' => [],
        'crop'   => false,
        'required' => false
    ];
    $cfg = (object)array_merge($defaultCfg,(array)$properties);
    $multiple = $cfg->maxCount > 1;

@endphp

<div class="form-group row">
    <label class="col-md-3 col-form-label" for="{{$plugin_id}}">{{ $properties->title }}</label>
    <div class="col-md-9">
        <input for="{{ $fieldName }}" name="{{ $plugin_id }}" id="{{$plugin_id}}" type="file">
        <input type="hidden" name="{{ $fieldName }}" print_start $value ? "value=".$value : "value=none" print_end>
        <br>
        <br>
        at_symbolforeach($files as $key => $file)
            <div class="preview-container">
                <video width="300" height="140" data-scr="print_start 'storage/uploads/{{ $cfg->folder }}/'.$file print_end" controls>
                    <source src="print_start 'storage/uploads/{{ $cfg->folder }}/'.$file print_end">
                </video>
                <a data-index="print_start $key print_end" href="javascript:;" class="remove-image">
                    <i class="fa fa-close"></i>
                </a>
            </div>
        at_symbolendforeach
    </div>
</div>
at_symbolpush('scripts')
<script>
  loadCss(['print_start asset("vendor/betterfly/plugins/filePlugin/filePlugin.css") print_end']);
  loadScript(['print_start asset("vendor/betterfly/plugins/filePlugin/filePlugin.js") print_end'], load{{$plugin_id}});

  function load{{$plugin_id}}() {
    $('input#{{$plugin_id}}').filePlugin({
      maxCount: {{ $cfg->maxCount }},
      mimeTypes: [@foreach($cfg->mimeTypes as $type)"{{ $type }}",@endforeach],
      folder: "{{ $cfg->folder }}",
      thumbs: [@foreach($cfg->thumbs as $thumb){!! json_encode($thumb) !!},@endforeach],
      required: {{ $cfg->required ? 'true' : 'false' }}
    });
  }
</script>
at_symbolendpush
