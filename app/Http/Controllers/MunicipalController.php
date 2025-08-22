<?php

namespace App\Http\Controllers;
use App\Models\Municipal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;


class MunicipalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
      //  $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ActivityLogger::activity("عرض صفحة  البلديات ");
        return view('municipal.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

       
            $city=City::get();
            ActivityLogger::activity("عرض صفحة  اضافة بلدية");
        return view('municipal.create')->with('city',$city);
       
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => "من فضلك ادخل  البلدية",
            'city_id.required' => "من فضلك احتر  المنطقة الصحية",

        ];
        
        $this->validate($request, [
            'name' => ['required', 'string', 'max:50'],
            'city_id' => ['required'],

        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $Municipal = new Municipal();
                $Municipal->name = $request->name;
                $Municipal->cities_id = decrypt($request->city_id);

                $Municipal->save();
            });
            Alert::success("تمت اضافة البلدية   بنجاح");
            ActivityLogger::activity($request->name ."تمت اضافة البلدية بنجاح");

            return redirect()->route('municipal');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());
            ActivityLogger::activity($request->name ."فشل إضافة البلدية");

            return redirect()->route('municipal');
        }
    }


    public function Municipal()
    {
            $Municipal = Municipal::with(['cities'])->orderBy('created_at', 'DESC');
        return datatables()->of($Municipal)
      
        ->addColumn('edit', function ($Municipal) {
        
            $Municipal_id = encrypt($Municipal->id);

            return '<a style="color: #f97424;" href="' . route('municipal/edit',$Municipal_id).'"><i  class="fa  fa-edit" > </i></a>';
        })
        ->addColumn('delete', function ($Municipal) {
            $Municipal_id = encrypt($Municipal->id);

            return ' <form action="' . route('municipal/delete', $Municipal_id) . '" method="POST">
        <input type="hidden" name="_method" value="DELETE">'
                . csrf_field() .
                '<button type="submit" style="background: none;border: none;"><i class="fa fa-trash" style="color:red"></i></button></form>';

        })
    
        ->rawColumns(['edit','delete'])


            ->make(true);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Municipal  $municipal
     * @return \Illuminate\Http\Response
     */
    public function show(Municipal $municipal)
    {
        //
    }

    public function edit($Municipal)
    { 
            $city=City::get();

            $Municipal_id = decrypt($Municipal);
            $Municipal = Municipal::find($Municipal_id);
            ActivityLogger::activity("تعديل يلدية");
            return view('municipal.edit')->with('city',$city)
            ->with('Municipal', $Municipal);
       

    }

      public function byCity($cityId)
    {
        // مهم: تأكد من اسم العمود الصحيح: cities_id أو city_id
        $items = Municipal::where('cities_id', $cityId)
                    ->orderBy('name')
                    ->get(['id','name']);

        return response()->json($items, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Municipal  $municipal
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Municipal  $municipal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $Municipal)
    {

        $Municipal_id = decrypt($Municipal);
        $messages = [
            'name.required' => "من فضلك ادخل  البلدية",
            'city_id.required' => "من فضلك احتر  المنطقة الصحية",

        ];
        
        $this->validate($request, [
            'name' => ['required', 'string', 'max:50'],
            'city_id' => ['required'],

        ], $messages);
        try {
            DB::transaction(function () use ($request, $Municipal) {
                $Municipal_id = decrypt($Municipal);

                $Municipal = Municipal::find($Municipal_id);
                $Municipal->name = $request->name;
                $Municipal->cities_id = decrypt($request->city_id);

                $Municipal->save();
                ActivityLogger::activity("تمت عملية تعديل بلدية بنجاح");

                ActivityLogger::activity($Municipal->name ."تمت عملية تعديل بلدية بنجاح");
            });

            Alert::success("تمت عملية تعديل بلدية بنجاح");

            return redirect()->route('municipal');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());
            ActivityLogger::activity($request->name ."فشل عملية تعديل بلدية ");

            return redirect()->route('municipal');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Municipal  $municipal
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $id = decrypt($id);
        $Municipal = Municipal::find($id);
        $Municipal->delete();
        Alert::success('تمت عملية حذف  بلدية   بنجاح');
        ActivityLogger::activity("حذف  بلدية ");
        return redirect()->back();
    }
    
        public function getMunicipal($id){


        $municipal = Municipal::where('cities_id' , $id)->get();
        return $municipal;



    }
}
