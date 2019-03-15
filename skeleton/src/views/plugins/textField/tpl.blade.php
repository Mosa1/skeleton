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
        'title'=>'TextField',
        'required' => false,
        'disable' => false,
    ];
    $cfg = (object)array_merge($defaultCfg,(array)$properties);

@endphp

<div class="form-group row">
    <label class="col-md-3 col-form-label" for="text-input">{{ $properties->title }}</label>
    <div class="col-md-9">
        <input {{ $mode === 'edit' && $cfg->disable ? 'disabled' : '' }} {{ $cfg->required ? 'required' : '' }} class="form-control" value="print_start $value print_end" type="text" name="{{ $fieldName }}" placeholder="{{ $cfg->title }}">
        at_symbolif($errors->get('{{ $fieldName }}'))
            <br>
            <div class="alert alert-danger" role="alert">print_start $errors->first('{{ $fieldName }}') print_end</div>
        at_symbolendif
    </div>
</div>