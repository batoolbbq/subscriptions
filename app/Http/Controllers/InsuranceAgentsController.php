<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\insuranceAgents;
use App\Models\City;
use App\Models\User;
use App\Models\Municipal;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;





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

  public function deactivate(Request $request, $id)
{
    $agent = InsuranceAgents::findOrFail($id);

    if ($agent->status == 0) {
        return redirect()->back()->with('info', 'الوكيل غير مفعّل مسبقًا.');
    }

    $agent->status = 0;
    $agent->save();

    $user = User::where('email', $agent->email)->first();
    if ($user) {
        $agent->users()->detach($user->id);
        $user->delete();
    }

    if (method_exists($this, 'deactivateInsuranceAgent')) {
        try {
            $this->deactivateInsuranceAgent($agent->id);
        } catch (\Throwable $e) {
            \Log::error("deactivateInsuranceAgent failed for agent {$agent->id}: " . $e->getMessage());
        }
    }

    return redirect()->back()->with('success', 'تم إلغاء تفعيل وكيل التأمين بنجاح.');
}



public function deactivateInsuranceAgent($id)
{
    $agent = InsuranceAgents::findOrFail($id);

    $data = [
        'status' => 0
    ];

    $url = "http://192.168.81.17:6060/admin/InsuranceAgents/{$agent->id}/Status";

    $response = Http::withBasicAuth('admin', 'admin')
        ->withHeaders([
            'accept' => 'text/plain',
            'Content-Type' => 'application/json'
        ])
        ->put($url, $data);

    if ($response->successful()) {
        return [
            'success' => true,
            'status' => $response->status(),
            'data' => $response->body()
        ];
    } else {
        return [
            'success' => false,
            'status' => $response->status(),
            'error' => $response->body()
        ];
    }
}








    public function activate(Request $request, $id)
    {
        $agent = InsuranceAgents::findOrFail($id);

        if ($agent->users()->exists()) {
            return redirect()->back()->with('info', 'الوكيل مفعّل مسبقًا.');
        }

        if ($request->has('agent_code') && !empty($request->agent_code)) {
            $request->validate([
                'agent_code' => 'string|max:50|unique:insurance_agents,agent_code,' . $id,
            ]);
            $agent->agent_code = $request->agent_code;
        }

        $agent->status = 1;
        $agent->save();

        $names = preg_split('/\s+/', trim($agent->name), 2);
        $user = new User();
        $user->first_name   = $names[0] ?? '';
        $user->last_name    = $names[1] ?? '';
        $user->username     = $agent->email;
        $user->email        = $agent->email;
        $user->phonenumber  = $agent->phone_number;
        $user->password     = Hash::make($agent->phone_number);
        $user->cities_id    = $agent->cities_id;
        $user->user_type_id = 3;
        $user->active       = 1;
        $user->save();

        $agent->users()->attach($user->id);

        $role = Role::find(49);
        if ($role && !$user->hasRole($role->name)) {
            $user->assignRole($role->name);
        }

        if (method_exists($this, 'postInsuranceAgent')) {
            try {
                $this->postInsuranceAgent($agent->id);
            } catch (\Throwable $e) {
                \Log::error("postInsuranceAgent failed for agent {$agent->id}: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'تم تفعيل وكيل التأمين بنجاح.');
    }


  public function postInsuranceAgent($id)
    {
        $agent  = InsuranceAgents::findOrFail($id);

        $data = [
            "codeId" => (string) $agent->id, 
            "name" => $agent->name,
            "email" => $agent->email,
            "phone" => $agent->phone_number,
            "address" => $agent->address,
            "municipalityId" =>1,
            "description" => $agent->description,
        ];

        $response = Http::withBasicAuth('admin', 'admin')
            ->withHeaders([
                'accept' => '*/*',
                'Content-Type' => 'application/json'
            ])
            ->post('http://192.168.81.17:6060/admin/InsuranceAgents', $data);

        if ($response->successful()) {
            return [
                'success' => true,
                'status' => $response->status(),
                'data' => $response->json(),
            ];
        } else {
            // الطلب فشل
            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->body(), // ممكن تكون رسالة خطأ
            ];
        }
    }




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
       
        if ($request->hasFile('Birth_creature')) {
            $file      = $request->file('Birth_creature');
            $ext       = $file->getClientOriginalExtension();
            $fileName  = 'birth_certificate_' . Str::uuid() . '.' . $ext;

            // يحفظ في public/insurancagents_files
            $file->move(public_path('insurancagents_files'), $fileName);

            $agent->birth_certificate_path = $fileName; 
        }

        if ($request->hasFile('qualification')) {
            $file      = $request->file('qualification');
            $ext       = $file->getClientOriginalExtension();
            $fileName  = 'qualification_' . Str::uuid() . '.' . $ext;

            $file->move(public_path('insurancagents_files'), $fileName);

            $agent->qualification_path = $fileName;
        }

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $ext       = $file->getClientOriginalExtension();
            $fileName  = 'location_image_' . Str::uuid() . '.' . $ext;

            $file->move(public_path('insurancagents_files'), $fileName);

            $agent->location_image_path = $fileName;
        }

        // مثال لو تبي شهادة التأمين
        // if ($request->hasFile('Insurance_certificate')) {
        //     $file      = $request->file('Insurance_certificate');
        //     $ext       = $file->getClientOriginalExtension();
        //     $fileName  = 'insurance_certificate_' . Str::uuid() . '.' . $ext;
        //
        //     $file->move(public_path('insurancagents_files'), $fileName);
        //
        //     $agent->Insurance_certificate = $fileName;
        // }

        $agent->save();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->back()->with('success', 'تم تسجيلك كوكيل بنجاح!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $insuranceAgent = InsuranceAgents::with(['municipals', 'cities', 'users'])->findOrFail($id);

        $userExists = $insuranceAgent->users()->exists();

        return view('insuranceAgents.show', [
            'insuranceAgents' => $insuranceAgent,
            'userExists' => $userExists,
        ]);
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
//   public function update(Request $request, $id)
// {
//     // التحقق من صحة البيانات
//     $validatedData = $request->validate([
//         'name' => 'required|string|max:50',
//         'phone_number' => [
//             'required', 'string', 'digits:9',
//             'starts_with:92,91,94,21',
//             Rule::unique('insurance_agents')->ignore($id) // ✅ تجاهل الرقم الحالي
//         ],
//         'address' => 'required|string|max:150',
//         'email' => [
//             'required', 'email', 'max:50',
//             Rule::unique('insurance_agents', 'email')->ignore($id)
//         ],
//         'cities_id' => 'required',
//         'municipals_id' => 'required',
//         'description' => 'required',
//     ]);

//     $agent = insuranceAgents::findOrFail($id);
//     $agent->name = $validatedData['name'];
//     $agent->phone_number = $validatedData['phone_number'];
//     $agent->address = $validatedData['address'];
//     $agent->email = $validatedData['email'];
//     $agent->cities_id = $validatedData['cities_id'];
//     $agent->municipals_id = $validatedData['municipals_id'];
//     $agent->description = $validatedData['description'];

//     // تحديث الملفات إذا تم رفعها
//     if ($request->hasFile('Birth_creature')) {
//         $file      = $request->file('Birth_creature');
//         $ext       = $file->getClientOriginalExtension();
//         $fileName  = 'birth_certificate_' . Str::uuid() . '.' . $ext;
//         $file->move(public_path('insurancagents_files'), $fileName);
//         $agent->birth_certificate_path = $fileName;
//     }

//     if ($request->hasFile('qualification')) {
//         $file      = $request->file('qualification');
//         $ext       = $file->getClientOriginalExtension();
//         $fileName  = 'qualification_' . Str::uuid() . '.' . $ext;
//         $file->move(public_path('insurancagents_files'), $fileName);
//         $agent->qualification_path = $fileName;
//     }

//     if ($request->hasFile('image')) {
//         $file      = $request->file('image');
//         $ext       = $file->getClientOriginalExtension();
//         $fileName  = 'location_image_' . Str::uuid() . '.' . $ext;
//         $file->move(public_path('insurancagents_files'), $fileName);
//         $agent->location_image_path = $fileName;
//     }

//     $agent->save();

//     // ✅ بعد التحديث رجع لصفحة الـ index

//     Alert::success('نجاح', 'تم تعديل بيانات الوكيل بنجاح!'); // ✅ السويت ألرت

//     return redirect()->route('insuranceAgents.index');
// }


public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:50',
        'phone_number' => [
            'required', 'string', 'digits:9',
            'starts_with:92,91,94,21',
            Rule::unique('insurance_agents')->ignore($id)
        ],
        'address' => 'required|string|max:150',
        'email' => [
            'required', 'email', 'max:50',
            Rule::unique('insurance_agents', 'email')->ignore($id)
        ],
        'cities_id' => 'required',
        'municipals_id' => 'required',
        'description' => 'required',
    ]);

    $agent = InsuranceAgents::findOrFail($id);

    $agent->update([
        'name' => $validatedData['name'],
        'phone_number' => $validatedData['phone_number'],
        'address' => $validatedData['address'],
        'email' => $validatedData['email'],
        'cities_id' => $validatedData['cities_id'],
        'municipals_id' => $validatedData['municipals_id'],
        'description' => $validatedData['description'],
    ]);

    if ($request->hasFile('Birth_creature')) {
        $file = $request->file('Birth_creature');
        $ext = $file->getClientOriginalExtension();
        $fileName = 'birth_certificate_' . Str::uuid() . '.' . $ext;
        $file->move(public_path('insurancagents_files'), $fileName);
        $agent->birth_certificate_path = $fileName;
    }

    if ($request->hasFile('qualification')) {
        $file = $request->file('qualification');
        $ext = $file->getClientOriginalExtension();
        $fileName = 'qualification_' . Str::uuid() . '.' . $ext;
        $file->move(public_path('insurancagents_files'), $fileName);
        $agent->qualification_path = $fileName;
    }

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $ext = $file->getClientOriginalExtension();
        $fileName = 'location_image_' . Str::uuid() . '.' . $ext;
        $file->move(public_path('insurancagents_files'), $fileName);
        $agent->location_image_path = $fileName;
    }

    $agent->save();
 
    try {
        $this->updateInsuranceAgent($agent);
    } catch (\Throwable $e) {
        \Log::error("updateInsuranceAgent failed for agent {$agent->id}: " . $e->getMessage());
    }

    Alert::success('نجاح', 'تم تعديل بيانات الوكيل بنجاح!');
    return redirect()->route('insuranceAgents.index');
}

public function updateInsuranceAgent($agent)
{
    $url = "http://192.168.81.17:6060/admin/InsuranceAgents/{$agent->id}";

    $data = [
        'name' => $agent->name,
        'email' => $agent->email,
        'phone' => $agent->phone_number,
        'address' => $agent->address,
        'municipalityId' => 1,
        'description' => $agent->description,
    ];

    $response = Http::withBasicAuth('admin', 'admin')
        ->withHeaders([
            'accept' => 'text/plain',
            'Content-Type' => 'application/json',
        ])
        ->put($url, $data);

    dd([
        'status' => $response->status(),
        'body'   => $response->body(),
        'json'   => $response->json(),
    ]);
}




public function postAddedServiceTransactionToApi(Request $request, $agentId)
{
    $apiBaseUrl = 'http://192.168.81.17:6060';
    $apiUser    = 'admin';
    $apiPass    = 'admin';
    $endpoint   = "/admin/AddedServiceTransactions/{$agentId}/AddedServiceTransaction/Add";

    $payload = [
        'accountInsuranceNumber' => $request->accountInsuranceNumber ?? '',
        'accountSubscriptionId'  => (int) ($request->accountSubscriptionId ?? 0),
        'institutionId'          => (int) ($request->institutionId ?? 0),
        'addedServiceId'         => (int) ($request->addedServiceId ?? 0),
        'paymentType'            => (int) ($request->paymentType ?? 1),
    ];

    \Log::info('إرسال Added Service Transaction إلى الـ API', [
        'url'     => "{$apiBaseUrl}{$endpoint}",
        'payload' => $payload,
    ]);

    try {
        $response = \Illuminate\Support\Facades\Http::withBasicAuth($apiUser, $apiPass)
            ->acceptJson()
            ->asJson()
            ->timeout(15)
            ->retry(2, 300)
            ->post("{$apiBaseUrl}{$endpoint}", $payload);

        $result = [
            'success' => $response->successful(),
            'status'  => $response->status(),
            'body'    => $response->body(),
            'json'    => $response->json(),
        ];

        \Log::info('✅ رد النظام المركزي (AddedServiceTransaction API):', $result);

        return response()->json($result, $response->status());
    } catch (\Throwable $th) {
        \Log::error('🚨 خطأ أثناء إرسال Added Service Transaction: ' . $th->getMessage(), [
            'payload' => $payload
        ]);

        return response()->json([
            'success' => false,
            'status'  => 0,
            'error'   => $th->getMessage(),
        ], 500);
    }
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