at_symbolphp
if(isset($data)){
$value = json_decode($data->{{$fieldName}});
$value = key_exists('{{$fieldName}}',old()) ? json_decode(old('{{$fieldName}}')) : $value;
} else{
$value = json_decode(old('{{ $fieldName }}'));
}
at_symbolendphp

<div class="row">
    <label class="col-md-3 col-form-label" for="{{$plugin_id}}">{{ $properties->title }}</label>

    <div class="col-md-9 p-0" id="{{$plugin_id}}">
        <input hidden type="text" name="{{ $fieldName }}" class="{{$plugin_id}}_input">
        <div class="col-md-12 "><button type="button" id="btnAdd-{{$plugin_id}}" class="btn btn-primary">Add section</button></div>
        at_symbolforeach($value as $key => $items)
            <div class="group col-md-12 row mt-3 mb-3">
                @foreach($properties->plugins as $key => $subPlugin)
                    <div class="col-md-{{ $subPlugin->colNumber }}">
                        @include('betterfly::plugins.' . $subPlugin->pluginName . '.tpl',["properties" => ["subPlugin" => true,"title" => $subPlugin->title,"value" => '$items->{'.$key.'}' ],"fieldName" => ''])
                    </div>

                @endforeach
                <div class="col-md-2 mt-2">
                    <button type="button" class="btn btn-danger btnRemove">Remove</button>
                </div>
            </div>
        at_symbolendforeach
    </div>
</div>
at_symbolpush('scripts')
<script>
    loadScript(['../vendor/betterfly/plugins/multifield/jquery.multifield.min.js'], load{{$plugin_id}});

    function load{{$plugin_id}}() {
        $('#{{$plugin_id}}').multifield({
            section: '.group',
            btnAdd:'#btnAdd-{{$plugin_id}}',
            btnRemove:'.btnRemove',
        });

        $('form .btn-sm.btn-success').click(function(e){
            e.preventDefault();
            var data = {};
            for (i = 0; i < $('#{{$plugin_id}} .group').length; i++) {
                var parentEl = $($('#{{$plugin_id}} .group')[i]);
                var inputs = parentEl.find('.form-control');
                data[i] = {};
                $.each(inputs, function( k, v ) {
                    data[i][k] = $(v).val()
                });
            }
            data = JSON.stringify(data);
            $('.{{$plugin_id}}_input').val(data);

            $(this).parents('form').submit()
        })
    }
</script>
at_symbolendpush