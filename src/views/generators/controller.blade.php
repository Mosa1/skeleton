<?php echo '<?php' ?>

namespace {{$namespace}};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
@foreach($relations as $relation)
use {{ $relation->relativeModel }};
@endforeach
{{ $parentModule ? 'use App\\Modules\\'.ucfirst($parentModule).'\\'.ucfirst($parentModule).';' : '' }}

class {{ $moduleName }}Controller extends Controller
{
    protected ${{strtolower($moduleName)}}Service;

    public function __construct({{$moduleName}}Service ${{strtolower($moduleName)}}Service)
    {
        $this->{{strtolower($moduleName)}}Service = ${{strtolower($moduleName)}}Service;
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request{{ $parentModule ? ','.ucfirst($parentModule).' $'.str_singular($parentModule): '' }})
    {

        @if($parentModule)
        $data = {{ '$'.str_singular($parentModule) }}->{{ strtolower($moduleName )}};
        @else
        $data = {!! $dataLoaderMethod ? $dataLoaderMethod."();" : '$this->'.strtolower($moduleName)."Service->getList(['paginate' => 10])".($sortable ? '->toTree();': ';') !!}
        @endif
        if($request->ajax())
            return $data;
        else
            return view('admin.{{ strtolower($moduleName) }}.index')->with([
                'data' => $data,
                {!! $parentModule ? "'".str_singular($parentModule)."' => ".'$'.str_singular($parentModule) : '' !!}
]);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create({{ $parentModule ? ucfirst($parentModule).' $'.str_singular($parentModule) : '' }})
    {
    @foreach($relations as $key => $relation)
        @if($key == str_singular($parentModule)) @continue @endif

        ${{ $key }} = {{ $relation->relativeModelShortName }}::all();
    @endforeach
    return view('admin.{{ strtolower($moduleName) }}.create')->with([
    @foreach($relations as $key => $relation)
        @if($key == str_singular($parentModule)) @continue @endif
        '{{ $key }}' => ${{ $key }},
    @endforeach
    {!! $parentModule ? "'".str_singular($parentModule)."' => ".'$'.str_singular($parentModule) : '' !!}
    ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function store({{$moduleName}}Request $request{{ $parentModule ? ','.ucfirst($parentModule).' $'.str_singular($parentModule) : '' }}){

        @if($parentModule)
        $request->merge(['{{ str_singular($parentModule) }}' => ${{ str_singular($parentModule) }}->id]);
        @endif
        $item = $this->{{ strtolower($moduleName) }}Service->create($request->input());
        if($request->ajax()){
            return $item;
        }else{
            \Session::flash('status','Successfully created');
            return redirect()->route('{{ $routeName }}.index'{!! $parentModule ? ', $'.str_singular($parentModule).'->id' : '' !!});
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  int $id
    * @return \Illuminate\Http\Response
    */
    public function show($id){
        return $this->{{strtolower($moduleName)}}Service->getById($id) ?:
        response(['message' => 'Record not found']);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int $id
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request,{{ $parentModule ? ucfirst($parentModule).' $'.str_singular($parentModule).',' : '' }}$id){
    @foreach($relations as $key => $relation)
        @if($key == str_singular($parentModule)) @continue @endif

    ${{ $key }} = {{ $relation->relativeModelShortName }}::all();
    @endforeach
    $data = $this->{{strtolower($moduleName)}}Service->getById($id);
        if($request->ajax())
            return $data;
        else
            return view('admin.{{ strtolower($moduleName) }}.edit')->with([
                'data' => $data,
    @foreach($relations as $key => $relation)
        @if($key == str_singular($parentModule)) @continue @endif
            '{{ $key }}' => ${{ $key }},
    @endforeach

        {!! $parentModule ? "'".str_singular($parentModule)."' => ".'$'.str_singular($parentModule) : '' !!}
        ]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request $request
    * @param  int $id
    * @return \Illuminate\Http\Response
    */
    public function update({{$moduleName}}Request $request,{{ $parentModule ? ucfirst($parentModule).' $'.str_singular($parentModule).',' : '' }} $id){
        $data = $request->input();
        $data['id'] = $id;

        $data = $this->{{strtolower($moduleName)}}Service->update($data);

        if($request->ajax()){
            return $data;
        }else{
            \Session::flash('status','Successfully updated');
            return redirect()->route('{{ $routeName }}.edit',[{!! $parentModule ? '$'.str_singular($parentModule).'->id,' : '' !!}$data->id]);
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int $id
    * @return \Illuminate\Http\Response
    */
    public function destroy({{ $parentModule ? ucfirst($parentModule).' $'.str_singular($parentModule).',' : '' }}$id)
    {
        return $this->{{strtolower($moduleName)}}Service->delete($id) ?
        response(['message' => 'Successfully deleted']) :
        response(['message' => 'Something went wrong']);
    }
}