<?php echo '<?php' ?>

namespace {{$namespace}};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        $data = $this->{{strtolower($moduleName)}}Service->getList(['paginate' => 5]);
        if($request->ajax())
            return $data;
        else
            return view('admin.{{strtolower($moduleName)}}.index')->with(['data' => $data]);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('admin.{{strtolower($moduleName)}}.create');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function store({{$moduleName}}Request $request){
        $item = $this->{{strtolower($moduleName)}}Service->create($request->input());
        if($request->ajax()){
            return $item;
        }else{
            \Session::flash('status','Successfully created');
            return redirect()->route('{{str_plural(strtolower($moduleName))}}.index');
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
    public function edit(Request $request,$id){
        $data = $this->{{strtolower($moduleName)}}Service->getById($id);
        if($request->ajax())
            return $data;
        else
            return view('admin.{{strtolower($moduleName)}}.edit')->with(['data' => $data]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request $request
    * @param  int $id
    * @return \Illuminate\Http\Response
    */
    public function update({{$moduleName}}Request $request, $id){
        $data = $request->input();
        $data['id'] = $id;

        $data = $this->{{strtolower($moduleName)}}Service->update($data);

        if($request->ajax()){
            return $data;
        }else{
            \Session::flash('status','Successfully updated');
            return redirect()->route('{{str_plural(strtolower($moduleName))}}.edit',$data->id);
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        return $this->{{strtolower($moduleName)}}Service->delete($id) ?
        response(['message' => 'Successfully deleted']) :
        response(['message' => 'Something went wrong']);
    }
}