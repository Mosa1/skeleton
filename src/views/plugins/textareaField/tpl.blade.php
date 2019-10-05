@php
    $defaultCfg = [
        'title'=>'text',
        'required' => false,
        'disable' => false,
        'subPlugin'=> false,
        'value' => false,
        'type' => 'text'
    ];
    $cfg = (object)array_merge($defaultCfg,(array)$properties);
@endphp

@if(!$cfg->subPlugin)
at_symbolphp
    if(isset($data)){
        $value = $data->{{$fieldName}};
        $value = key_exists('{{$fieldName}}',old()) ? old('{{$fieldName}}') : $value;
    } else{
        $value = old('{{ $fieldName }}');
    }
at_symbolendphp
@endif
@if($cfg->value)
    at_symbolphp
    $value = {!! $cfg->value !!}
    at_symbolendphp
@endif

<div class="form-group row">
    @if(!$cfg->subPlugin)
    <label class="col-md-3 col-form-label" for="text-input">{{ $cfg->title }}
    @endif
    <div class="col-md-{{ $cfg->subPlugin ? 12 : 9 }}">
        <textarea rows="5" cols="50" class="form-control" name="{{ $fieldName }}" placeholder="{{ $cfg->title }}">print_start $value print_end</textarea>
        at_symbolif($errors->get('{{ $fieldName }}'))
            <br>
            <div class="alert alert-danger" role="alert">print_start $errors->first('{{ $fieldName }}') print_end</div>
        at_symbolendif
    </div>
</div>