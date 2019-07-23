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
    ];
    $cfg = (object)array_merge($defaultCfg,(array)$properties);

@endphp
<div class="form-group row">
    <label class="col-md-3 col-form-label" for="text-input">{{ $properties->title }}</label>
    <input type="hidden" value="print_start $value print_end" name="{{ $fieldName }}">
    <div class="col-md-9">
            <div id="wrapper">
                <header id="header">
                    <nav id="nav" class="clearfix">
                        <ul>
                            <li id="rectangle"><a href="#">rectangle</a></li>
                            <li id="circle"><a href="#">circle</a></li>
                            <li id="polygon"><a href="#">polygon</a></li>
                            <li id="edit"><a href="#">edit</a></li>
                            <li id="clear"><a href="#">clear</a></li>
                            <li id="show_help"><a href="#">?</a></li>
                        </ul>
                    </nav>
                    <div id="coords"></div>
                    <div id="debug"></div>
                </header>
                <div id="image_wrapper">
                    <div id="image" class="draw">
                        <img src="" alt="#" id="img"/>
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.2" baseProfile="tiny" id="svg">
                            <g>
                                print_start_allow_chars $value  print_end_allow_chars
                            </g>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Get image form -->
            <div id="get_image_wrapper">
                <div id="get_image">
                    <span title="close" class="close_button"></span>
                    <div id="loading">Loading</div>
                    <div id="file_reader_support">
                        <label>Drag an image</label>
                        <div id="dropzone">
                            <span class="clear_button" title="clear">x</span>
                            <img src="print_start 'storage/uploads/{{ $cfg->parentData->folder }}/'.${{ $cfg->parentData->variableName }}->{{ $cfg->parentData->fieldName }} print_end" alt="preview" id="sm_img"/>
                        </div>
                        <b>or</b>
                    </div>
                    <button id="button">OK</button>
                </div>
            </div>

            <!-- Help block -->
            <div id="overlay"></div>
            <div id="help">
                <span class="close_button" title="close"></span>
                <div class="txt">
                    <section>
                        <h2>Main</h2>
                        <p><span class="key">F5</span> &mdash; reload the page
                            and
                            display the form for loading image again</p>
                        <p><span class="key">S</span> &mdash; save map params in
                            localStorage</p>
                    </section>
                    <section>
                        <h2>Drawing mode (rectangle / circle / polygon)</h2>
                        <p><span class="key">ENTER</span> &mdash; stop polygon
                            drawing
                            (or click on first helper)</p>
                        <p><span class="key">ESC</span> &mdash; cancel drawing
                            of a new
                            area</p>
                        <p><span class="key">SHIFT</span> &mdash; square drawing
                            in case
                            of a rectangle and right angle drawing in case of a
                            polygon
                        </p>
                    </section>
                    <section>
                        <h2>Editing mode</h2>
                        <p><span class="key">DELETE</span> &mdash; remove a
                            selected
                            area</p>
                        <p><span class="key">ESC</span> &mdash; cancel editing
                            of a
                            selected area</p>
                        <p><span class="key">SHIFT</span> &mdash; edit and save
                            proportions for rectangle</p>
                        <p><span class="key">I</span> &mdash; edit attributes of
                            a
                            selected area (or dblclick on an area)</p>
                        <p><span class="key">CTRL</span> + <span
                                    class="key">C</span>
                            &mdash; a copy of the selected area</p>
                        <p><span class="key">&uarr;</span> &mdash; move a
                            selected area
                            up</p>
                        <p><span class="key">&darr;</span> &mdash; move a
                            selected area
                            down</p>
                        <p><span class="key">&larr;</span> &mdash; move a
                            selected area
                            to the left</p>
                        <p><span class="key">&rarr;</span> &mdash; move a
                            selected area
                            to the right</p>
                    </section>
                </div>
            </div>
    </div>
</div>

at_symbolpush('scripts')

<script>
  loadCss('../vendor/betterfly/plugins/mascPlugin/masc.css');
</script>

<script src="../vendor/betterfly/plugins/mascPlugin/masc.js"></script>
at_symbolendpush