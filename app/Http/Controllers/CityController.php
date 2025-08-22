<?php


namespace App\Http\Controllers\Dashbord;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ActivityLogger::activity(trans('city.loggerofshowall'));

        return view('dashbord.city.index');
    }


    public function cities()
    {
    
        $City = City::orderBy('created_at', 'DESC');
        return datatables()->of($City)
      
        ->addColumn('edit', function ($City) {
        
            $city_id = encrypt($City->id);

            return '<a style="color: #f97424;" href="' . route('cities/edit',$city_id).'"><i  class="fa  fa-edit" > </i></a>';
        })
        ->addColumn('delete', function ($City) {
            $city_id = encrypt($City->id);

            return ' <form action="' . route('cities/delete', $city_id) . '" method="POST">
        <input type="hidden" name="_method" value="DELETE">'
                . csrf_field() .
                '<button type="submit" style="background: none;border: none;"><i class="fa fa-trash" style="color:red"></i></button></form>';

        })
    
        ->rawColumns(['edit','delete'])


            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

       

            ActivityLogger::activity(trans('city.loggerofCreatcitypage'));

        return view('dashbord.city.create');
       
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
            'name.required' => trans('city.valirequiredcity'),
            'code.required' => "من فضلك ادخل رمز المنطقة الصحية",

        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:50'],
            'code' => ['required', 'digits_between:3,3','unique:cities'],

        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $city = new City();
                $city->name = $request->name;
                $city->code = $request->code;

                $city->save();
            });
            Alert::success(trans('city.successcityadd'));
            ActivityLogger::activity($request->name .trans('city.logeeraddcityseccess'));

            return redirect()->route('cities');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());
            ActivityLogger::activity($request->name .trans('city.logeeraddcityfaul'));

            return redirect()->route('cities');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit( $city)
    { 

            $city_id = decrypt($city);
            $city = City::find($city_id);
            ActivityLogger::activity(trans('city.loggerofeditcitypage'));
            return view('dashbord.city.edit')->with('city', $city);
     
    }
    public function delete($id)
    {
        $id = decrypt($id);
        $City = City::find($id);
        $City->delete();
        Alert::success('تمت عملية حذف  مدينة   بنجاح');
        ActivityLogger::activity("حذف  مدينة ");
        return redirect()->back();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
  
    public function update(Request $request, $city)
    {

        $city_id = decrypt($city);
      
        $messages = [
            'name.required' => trans('city.valirequiredcity'),
            'code.required' => "من فضلك ادخل رمز المنطقة الصحية",

        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:50'],
            'code' => ['required', 'digits_between:3,3','unique:cities'],

        ], $messages);
        try {
            DB::transaction(function () use ($request, $city) {
                $city_id = decrypt($city);

                $cit = City::find($city_id);
                $cit->name = $request->name;
                $cit->code = $request->code;

                $cit->save();
                ActivityLogger::activity(trans('city.loggerofeditcitypage'));

                ActivityLogger::activity($cit->name . trans('city.logeereditcityseccess'));
            });

            Alert::success(trans('city.successcityedit'));

            return redirect()->route('cities');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());
            ActivityLogger::activity($request->name .trans('city.logeereditcityfaul'));

            return redirect()->route('cities');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        //
    }
}
