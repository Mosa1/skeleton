at_symbolphp
    if(isset($data)){
        $value = $data->{{$fieldName}};
        $value = key_exists('{{$fieldName}}',old()) ? old('{{$fieldName}}') : $value;
    } else{
        $value = old('{{ $fieldName }}');
    }
at_symbolendphp

<div class="form-group row">
    <div class="input-group date">
    <label class="col-md-3 col-form-label" for="date-input">{{ $properties->title }}</label>
    <div class="col-md-9">
        <div class="input-group"><div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div><input type="text" class="form-control datetimepicker" value="print_start $value print_end" name="{{ $fieldName }}"></div>
        at_symbolif($errors->get('{{ $fieldName }}'))
            <br>
            <div class="alert alert-danger" role="alert">print_start $errors->first('{{ $fieldName }}') print_end</div>
        at_symbolendif
    </div>
    </div>
</div>

at_symbolpush('scripts')
    <script src="../vendor/betterfly/plugins/dateField/jquery-ui.min.js"></script>
    <script src="../vendor/betterfly/plugins/dateField/jquery-ui-timepicker-addon.min.js"></script>

<script>
  $( function() {
    $('.datetimepicker').datetimepicker({ dateFormat:'yy-mm-dd' });
  });
</script>
at_symbolendpush

