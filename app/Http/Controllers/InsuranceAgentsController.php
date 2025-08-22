<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\insuranceAgents;
use App\Models\City;
use App\Models\User;
use App\Models\Municipal;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;



class InsuranceAgentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('insuranceAgents.index');

    }
    // public function get_index()
    // {
    //     $insuranceAgents = insuranceAgents::all();
    //     return datatables()->of($insuranceAgents)
    //         ->addColumn('created_at', function ($insuranceAgents) {
    //             return $insuranceAgents->created_at;
    //         })->addColumn('change', function ($insuranceAgents) {
    //             return '<a class="btn-sm btn-outline-info"  href="' . route('InsuranceAgents-show', $insuranceAgents->id) . '">  عرض   </a>';
    //         })->rawColumns(['change'])
    //         ->make(true);
    // }
    
      public function get_index()
        {
            $insuranceAgents = insuranceAgents::all();

            return datatables()->of($insuranceAgents)
                ->addColumn('created_at', function ($insuranceAgents) {
                    return $insuranceAgents->created_at;
                })
                       ->addColumn('action', function ($insuranceAgents) {
          return '
    <div class="btn-group" role="group">
        <a class="btn btn-sm btn-outline-info" href="' . route('InsuranceAgents-show', $insuranceAgents->id) . '">عرض</a>
        <a class="btn btn-sm btn-outline-warning" href="' . route('insuranceAgents-edit', $insuranceAgents->id) . '">تعديل</a>
    </div>
';

        
                
                // ->addColumn('status', function ($insuranceAgents) {
                //     $user = \App\Models\User::where('email', $insuranceAgents->email)->first();

                //     if ($user) {
                //         if ($user->active == 1) {
                //             return '
                //                 <form action="' . route('insurance-agents.deactivate', $insuranceAgents->id) . '" method="POST" class="deactivate-form" style="display:inline;">
                //                     ' . csrf_field() . '
                //                     <button type="submit" class="btn btn-sm btn-danger">إلغاء التفعيل</button>
                //                 </form>
                //                 <span class="badge badge-success mt-1 d-block">مفعّل</span>
                //             ';
                //         } else {
                //             return '
                //                 <form action="' . route('insurance-agents.activate', $insuranceAgents->id) . '" method="POST" class="activate-form" style="display:inline;">
                //                     ' . csrf_field() . '
                //                     <button type="submit" class="btn btn-sm btn-warning">إعادة التفعيل</button>
                //                 </form>
                //                 <span class="badge badge-warning mt-1 d-block">موقوف</span>
                //             ';
                //         }
                //     }

                    // return '
                    //     <form action="' . route('insurance-agents.activate', $insuranceAgents->id) . '" method="POST" class="activate-form" style="display:inline;">
                    //         ' . csrf_field() . '
                    //         <button type="submit" class="btn btn-sm btn-success">تفعيل</button>
                    //     </form>
                    // ';
                })
                ->rawColumns(['action' ])
                ->make(true);
        }
public function deactivate($id)
{
    $agent = InsuranceAgents::findOrFail($id);

    $user = User::where('email', $agent->email)->first();

    if ($user) {
        $agent->users()->detach($user->id);  
        $user->delete();                  
        $agent->status = 0;                   
        $agent->save();

        return redirect()->back()->with('success', 'تم إلغاء التفعيل وحذف المستخدم.');
    }

    return redirect()->back()->with('info', 'الوكيل غير مفعّل.');
}



    public function activate($id)
    {
        $agent = InsuranceAgents::findOrFail($id);

        if ($agent->users()->exists()) {
            return redirect()->back()->with('info', 'الوكيل مفعّل مسبقًا.');
        }

        $names = explode(' ', $agent->name, 2);

        $user = new User();
        $user->first_name = $names[0];
        $user->last_name = $names[1] ?? '';
        $user->username = $agent->email;
        $user->email = $agent->email;
        $user->phonenumber = $agent->phone_number;
        $user->password = Hash::make($agent->phone_number);
        $user->cities_id = $agent->cities_id;
        $user->user_type_id = 3;
        $user->active = 1;
        $user->save();

        $agent->users()->attach($user->id);

        $role = Role::find(49);
        if ($role && !$user->hasRole($role->name)) {
            $user->assignRole($role->name);
        }

        $agent->status = 1;
        $agent->save();

        // استدعاء API الإرسال هنا
        // $this->postInsuranceAgent($agent->id);

        return redirect()->back()->with('success', 'تم تفعيل وكيل التأمين، وإنشاء المستخدم، وإرسال البيانات إلى الـ API.');
    }

//   public function postInsuranceAgent($id)
//     {
//         $agent  = InsuranceAgents::findOrFail($id);

//         $data = [
//             "codeId" => (string) $agent->id, // تحويل إلى نص
//             "name" => $agent->name,
//             "email" => $agent->email,
//             "phone" => $agent->phone_number,
//             "address" => $agent->address,
//             "municipalityId" =>1,
//             "description" => $agent->description,
//         ];

//         $response = Http::withBasicAuth('admin', 'admin')
//             ->withHeaders([
//                 'accept' => '*/*',
//                 'Content-Type' => 'application/json'
//             ])
//             ->post('http://192.168.81.17:6060/admin/InsuranceAgents', $data);

//         if ($response->successful()) {
//             // الطلب نجح
//             return [
//                 'success' => true,
//                 'status' => $response->status(),
//                 'data' => $response->json(),
//             ];
//         } else {
//             // الطلب فشل
//             return [
//                 'success' => false,
//                 'status' => $response->status(),
//                 'error' => $response->body(), // ممكن تكون رسالة خطأ
//             ];
//         }
//     }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $city = City::all();
        return view('insuranceAgents.create' , ['city'=>$city]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->all());

        // التحقق من صحة البيانات
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'phone_number' => ['required', 'string', 'digits:9', 'starts_with:92,91,94,21', 'unique:insurance_agents'],
            'address' => 'required|string|max:150',
            'email' => 'required|email|max:50|unique:insurance_agents,email',
            'cities_id' => 'required',
            'municipals_id' => 'required',
            'description' => 'required',
            // 'Birth_creature' => 'required|file|mimes:jpeg,png,gif|max:2048',
            // 'qualification' => 'required|file|mimes:jpeg,png,gif|max:2048',
            // 'image' => 'required|file|mimes:jpeg,png,gif|max:2048',
        ]);

        $agent = new insuranceAgents();
        $agent->name = $validatedData['name'];
        $agent->phone_number = $validatedData['phone_number'];
        $agent->address = $validatedData['address'];
        $agent->email = $validatedData['email'];
        $agent->cities_id = $validatedData['cities_id'];
        $agent->municipals_id = $validatedData['municipals_id'];
        $agent->description = $validatedData['description'];
        $agent->birth_certificate_path = $request->Birth_creature->store('public/insurancagents_files');
        $agent->qualification_path = $request->qualification->store('public/insurancagents_files');
        $agent->location_image_path = $request->image->store('public/insurancagents_files');
        $agent->Insurance_certificate = $request->Insurance_certificate->store('public/insurancagents_files');

        $agent->save();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->back()->with('success', 'تم تسجيل الوكيل بنجاح!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $insuranceAgents = insuranceAgents::with(['municipals' , 'cities'])->findOrFail($id);
        return  view('insuranceAgents.show' , ['insuranceAgents' => $insuranceAgents]);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function edit($id)
{
    $agent = insuranceAgents::findOrFail($id);

    $cities = City::orderBy('name')->pluck('name', 'id');

    // بلديات المدينة الحالية فقط (لو تبغى كل البلديات احذف الشرط)
    $municipals = Municipal::get();

    return view('insuranceAgents.edit', compact('agent', 'cities', 'municipals'));
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

    }
}