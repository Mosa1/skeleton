at_symbolphp
    if(isset($data)){
        $coords =  $data->{{$fieldName}} !== null ? $data->{{$fieldName}} : json_encode(["lat" => 41.725334,"lng" => 44.761534,"zoom" => 10]);
    }else{
        $coords = json_encode(["lat" => 41.725334,"lng" => 44.761534,"zoom" => 10]);
    }

at_symbolendphp

<div class="form-group row">
    <label class="col-md-3 col-form-label" for="text-input">{{ $properties->title }}</label>
    <div class="col-md-9">
        <div id="{{$plugin_id}}" style="min-height: 400px;"></div>
        <input type="hidden" value='print_start_allow_chars $coords print_end_allow_chars' name="{{ $fieldName }}">
    </div>
    at_symbolif($errors->get('{{ $fieldName }}'))
    <br>
    <br>
    <div class="alert alert-danger" role="alert">print_start $errors->first('{{ $fieldName }}') print_end</div>
    at_symbolendif
</div>


at_symbolpush('scripts')
<script>
  loadCss('../vendor/betterfly/plugins/mapPlugin/leaflet.css');

  loadScript(['../vendor/betterfly/plugins/mapPlugin/leaflet.js'], onload);

  function onload() {
    var coords = JSON.parse('print_start_allow_chars $coords print_end_allow_chars');
    var lat = coords.lat;
    var lng = coords.lng;
    var zoom = coords.zoom;

    var options = {
      center: [lat, lng],
      zoom: zoom
    };

    var map = L.map('{{ $plugin_id }}', options);
    var markerGroup = L.layerGroup().addTo(map);

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: 'OSM'}).addTo(map);
    var myIcon = L.icon({
      iconUrl: '{{ asset('vendor/betterfly/img/marker.png') }}',
    });
    L.marker([lat, lng], {icon: myIcon}).addTo(markerGroup);

    function setMarker(e) {
      markerGroup.clearLayers();
      var lat = e.latlng.lat;
      var lng = e.latlng.lng;
      var zoom = map.getZoom();

      var inputData =  JSON.stringify({lat: lat,lng:lng,zoom:zoom });
      $('input[name="{{ $fieldName }}"]').val(inputData);
      L.marker([lat, lng], {icon: myIcon}).addTo(markerGroup);
    }

    map.on('click', setMarker)
  }
</script>
at_symbolendpush