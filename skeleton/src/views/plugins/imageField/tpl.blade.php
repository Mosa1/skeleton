at_symbolphp
    if(isset($data)){
        $value = $data->{{$fieldName}};
        $value = key_exists('{{$fieldName}}',old()) ? old('{{$fieldName}}') : $value;
    } else{
        $value = old('{{ $fieldName }}');
    }

at_symbolendphp
@php
    $multiple = property_exists($properties,'maxCount') && $properties->maxCount > 1 ? true : false;
@endphp

<div class="form-group row">
    <label class="col-md-3 col-form-label" for="{{$plugin_id}}">{{ $properties->title }}</label>
    <div class="col-md-9">
        <input id="{{$plugin_id}}"  type="file" {{ $multiple ? 'multiple' : '' }} name="{{ $multiple ? $fieldName.'[]' : $fieldName }}">
        at_symbolif($errors->get('{{ $fieldName }}'))
            <br>
            <div class="alert alert-danger" role="alert">print_start $errors->first('{{ $fieldName }}') print_end</div>
        at_symbolendif
    </div>
</div>