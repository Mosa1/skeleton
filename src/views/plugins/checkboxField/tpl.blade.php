at_symbolphp
    if(isset($data)){
        $value = $data->{{$fieldName}};
        $value = key_exists('{{$fieldName}}',old()) ? old('{{$fieldName}}') : $value;
    } else{
        $value = old('{{ $fieldName }}');
    }
at_symbolendphp


<div class="form-group row">
    <label class="col-md-3 col-form-label" for="checkbox-input">{{ $properties->title }}</label>
    <div class="col-md-9">
        <label class="switch switch-label switch-outline-success-alt">
            <input class="switch-input checkbox-plugin" for="{{ $fieldName }}" type="checkbox" print_start $value ? 'checked' : '' print_end>
            <span class="switch-slider" data-checked="On" data-unchecked="Off"></span>
        </label>
        <input type="hidden" name="{{ $fieldName }}" value="print_start $value print_end">
    </div>
    at_symbolif($errors->get('{{ $fieldName }}'))
    <br>
    <div class="alert alert-danger" role="alert">print_start $errors->first('{{ $fieldName }}') print_end</div>
    at_symbolendif
</div>