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
        'title'=>'Select Option',
        'multiple' => false,
        'required' => false,
        'options'  => [],
        'dataLoaderMethod' => false
    ];
    $cfg = (object)array_merge($defaultCfg,(array)$properties);

@endphp

<div class="form-group row">
    <label class="col-md-3 col-form-label" for="{{$plugin_id}}">{{ $properties->title }}</label>
    <div class="col-md-9">
        <select {{ $cfg->required ? 'required' : '' }} class="dd col-lg-12 form-control" id="{{$plugin_id}}"
                name="{{ $fieldName.($cfg->multiple ? '[]' : '') }}" {{ $cfg->multiple ? 'multiple': '' }}>
            @if(!$cfg->multiple)
                <option value="" class="default" selected>Select Option</option>
            @endif
            at_symbolphp
                $options = {{ $cfg->dataLoaderMethod ? $cfg->dataLoaderMethod.'()' : '[]' }}
            at_symbolendphp
            at_symbolforeach($options as $option)
                <option print_start $value == $option->{{$properties->optionValue }} ? 'selected' : '' print_end value="print_start $option->{{$properties->optionValue }} print_end">print_start $option->{{$properties->optionName }} print_end</option>
            at_symbolendforeach
        </select>
        at_symbolif($errors->get('{{ $fieldName }}'))
            <br>
            <br>
            <div class="alert alert-danger" role="alert">print_start $errors->first('{{ $fieldName }}') print_end</div>
        at_symbolendif
    </div>
</div>


at_symbolpush('scripts')

<script>

  loadCss(['../vendor/betterfly/plugins/selectPlugin/select2.min.css']);
  loadScript(['../vendor/betterfly/plugins/selectPlugin/select2.min.js'], onload);

  function onload() {
    $("select#{{$plugin_id}}").select2();
  }

</script>

at_symbolendpush