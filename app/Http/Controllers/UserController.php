<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\FacilityDoctor;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('roles')->select('users.*');
            return DataTables::of($users)
                ->addColumn('roles', function ($user) {
                    return $user->roles->name;
                })

                ->addColumn('action', function ($user) {
                    $editBtn = '<a href="' . route('users.edit', $user->id) . '" class="btn btn-sm btn-warning">تعديل</a>';
                    $deleteBtn = '<button data-id="' . $user->id . '" class="btn btn-sm btn-danger delete-user">حذف</button>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index');
    }

    public function create()
    {
        $roles = Role::latest()->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $messages = [
            'first_name.required' => trans('users.first_name_R'),
            'last_name.required' => trans('users.last_name_R'),
            'username.required' => trans('users.username_R'),
            'email.required' => trans('users.email_R'),
            'phone.required' => trans('users.phonenumber_R'),
            'password.required' => trans('users.password_R'),
            'role.required' => trans('users.role_R'),
        ];

        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'digits:9', 'numeric', 'starts_with:91,92,93,94,21', 'unique:users,phone'],
            'status' => ['required', 'in:0,1'],
            'role' => ['required', 'exists:roles,id'],
        ], $messages);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'status' => $request->status,
                ]);

                $user->syncRoles($request->role);
            });

            Alert::success(trans('users.successusersadd'));

            return redirect()->route('users.index');
        } catch (\Exception $e) {
            Alert::warning($e->getMessage());
            return redirect()->route('users.index');
        }
    }


    public function addDocUser()
    {

        $cities = City::all();

        // $hospitals = Hospital::all();

        return view('dashbord.doctorusers.create')->with('Cities', $cities);
    }

    public function addDoctorUser(Request $request)
    {

        $messages = [
            'first_name.required' => trans('users.first_name_R'),
            'last_name.required' => trans('users.last_name_R'),
            // 'username.required' => trans('users.username_R'),
            'city_id.required' => trans('users.address_R'),
            'email.required' => trans('users.email_R'),
            'phonenumber.required' => trans('users.phonenumber_R'),
            'user_type_id.required' => trans('users.user_type_id_R'),
        ];

        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            // 'last_name' => ['required', 'string', 'max:50','unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phonenumber' => 'required|digits_between:9,9|numeric|starts_with:91,92,94,21,93|unique:users',
            'user_type_id' => ['required'],
            'doctor_profession_permit' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5000']
        ], $messages);
        if (Auth()->user()->sanatoria_id == null  && Auth()->user()->hospital_id == null) {
            Alert::error("Contact System Admin");
            return redirect()->back();
        }
        try {
            DB::transaction(function () use ($request) {

                $user = new User();
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->username = $request->email;
                $user->cities_id = Auth()->user()->cities_id;
                $user->phonenumber = $request->phonenumber;

                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->user_type_id = $request->user_type_id;
                $user->active = 0;
                // dd(Auth::user()->hospital_id);
                if (Auth()->user()->hasRole('hospital')) {
                    $user->hospital_id = Auth()->user()->hospital_id;
                }

                if (Auth()->user()->hasRole('sanatorium')) {
                    $user->sanatoria_id = Auth()->user()->sanatoria_id;
                }

                $user->save();

                $path = $request->doctor_profession_permit->store('/UserDoctors');

                $uploadedFile = $user->files()->create([
                    'path' => $path,
                    'type' => 'profession permit',
                ]);

                if ($request->role == 1) {
                    $role = Role::where('name', 'general practioner')->first();
                    $specialtiesID = 17;
                } else if ($request->role == 2) {
                    $role = Role::where('name', 'doctor')->first();
                    $specialtiesID = 18;
                } else if ($request->role == 4) {
                    $role = Role::where('name', 'eye examination')->first();
                    $specialtiesID = 6;
                } else if ($request->role == 5) {
                    $role = Role::where('name', 'eye surgery')->first();
                    $specialtiesID = 6;
                } else if ($request->role == 6) {
                    $role = Role::where('name', 'orthopedic examination')->first();
                    $specialtiesID = 7;
                } else if ($request->role == 7) {
                    $role = Role::where('name', 'orthopedic surgery')->first();
                    $specialtiesID = 7;
                } else {
                    $role = Role::where('name', 'BLOOD LAB')->first();
                    $specialtiesID = null;
                }
                $user->syncRoles($role);
                $userDoctor = new FacilityDoctor();
                $userDoctor->specialties_services_id = $specialtiesID;
                $userDoctor->facility_id = auth()->user()->facility->id;
                $userDoctor->user_id = $user->id;
                $userDoctor->save();
            });



            Alert::success(trans('users.successusersadd'));

            return redirect()->to('/home');
        } catch (\Throwable $th) {
            throw $th;
        }
        // $messages = [
        //     'first_name.required' =>trans('users.first_name_R'),
        //     'last_name.required' => trans('users.last_name_R'),
        //     'username.required' => trans('users.username_R'),
        //     'address.required' => trans('users.address_R'),
        //     'email.required' => trans('users.email_R'),
        //     'phonenumber.required' => trans('users.phonenumber_R'),
        //     'user_type_id.required' =>trans('users.user_type_id_R'),
        // ];
        // $this->validate($request, [
        //     'first_name' => ['required', 'string', 'max:50'],
        //     'last_name' => ['required', 'string', 'max:50'],
        //     'username' => ['required', 'string', 'max:50','unique:users'],
        //     'address' => ['required'],
        //     'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
        //     'password' => ['required', 'string', 'min:8'],
        //     'phonenumber' => 'required|digits_between:9,9|numeric|starts_with:91,92,94,21,93|unique:users',
        //     'user_type_id' => ['required'],
        // ], $messages);


        // if(Auth()->user()->sanatoria_id == null  && Auth()->user()->hospital_id == null){
        //     Alert::error("Contact System Admin");
        //     return redirect()->back();
        // }
        // $user = new User();
        // $user->first_name = $request->first_name;
        // $user->last_name = $request->last_name;
        // $user->username = $request->username;

        // $user->cities_id = 2;
        // $user->phonenumber = $request->phonenumber;

        // $user->email = $request->email;
        // $user->password = Hash::make($request->password);
        // $user->user_type_id = $request->user_type_id;
        // $user->active = 1;
        // // dd(Auth::user()->hospital_id);
        // if(Auth()->user()->hasRole('hospital')){
        //     $user->hospital_id = Auth()->user()->hospital_id;
        // }

        // if(Auth()->user()->hasRole('sanatorium')){
        //     $user->sanatoria_id = Auth()->user()->sanatoria_id;
        // }

        // $user->save();
        // if($request->role == 1){
        // $role = Role::where('name', 'general practioner')->first();
        // }else{
        // $role = Role::where('name', 'doctor')->first();
        // }
        // $user->syncRoles($role);


        // Alert::success(trans('users.successusersadd'));

        // return redirect()->to('/home');
    }


    public function users()
    {

        $user = User::with('roles')->select('*')
            ->where('id', '!=', auth()->id())
            ->orderBy('created_at', 'DESC');

        return datatables()->of($user)
            ->addColumn('role', function ($user) {
                return $user->roles->pluck('name')->toArray();
            })

            ->addColumn('changeStatus', function ($user) {
                $user_id = encrypt($user->id);
                return '<a href=""><i class="fa fa-refresh"></i></a>';
            })
            ->addColumn('edit', function ($user) {
                $user_id = encrypt($user->id);
                return '<a style="color: #f97424;" href=""><i class="fa fa-edit"></i></a>';
            })
            ->rawColumns(['changeStatus', 'edit'])
            ->make(true);
    }

    public function addPermissions(Request $request, $userID)
    {
        try {
            if (isset($request->permissions)) {
                $user = User::findOrFail($userID);
                $user->givePermissionTo($request->permissions);

                return response()->json('success', 200);
            }
            return response()->json('success', 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json($th, 400);
        }
    }

    public function edit($id)
    {

        $user_id = decrypt($id);
        $user = User::with('roles')->find($user_id);
        $permissions = Permission::all();
        $user_types = UserType::all()->where('id', '!=', 1)->where('id', '!=', 4)->where('id', '!=', 5)->where('id', '!=', 6);
        ActivityLogger::activity($user->email . trans('users.loggerofedituserspage'));
        $Cities = City::all();

        $hospitals = Hospital::all();
        return view('dashbord.users.edit')
            ->with('user_types', $user_types)
            ->with('user', $user)
            ->with('hospitals', $hospitals)
            ->with('Cities', $Cities)
            ->with('permissions', $permissions)
            ->with('roles', Role::latest()->get());
    }
    public function update(Request $request, $id)
    {

        $user_id = decrypt($id);

        $messages = [
            'first_name.required' => trans('users.first_name_R'),
            'last_name.required' => trans('users.last_name_R'),
            'username.required' => trans('users.username_R'),
            'address.required' => trans('users.address_R'),
            'email.required' => trans('users.email_R'),
            'phonenumber.required' => trans('users.phonenumber_R'),
            'user_type_id.required' => trans('users.user_type_id_R'),
        ];
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50'],
            'address' => ['required'],
            'phonenumber' => 'required|digits_between:9,9|numeric|starts_with:91,92,94,21,93',
            'user_type_id' => ['required'],
            'email' => 'required|email|max:50|string|unique:users,email,' . $user_id,

        ], $messages);
        try {
            DB::transaction(function () use ($request, $id) {
                $user_id = decrypt($id);
                $user = User::find($user_id);
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->username = $request->username;
                $user->cities_id = decrypt($request->address);
                $user->phonenumber = $request->phonenumber;
                $user->email = $request->email;
                $user->user_type_id = decrypt($request->user_type_id);
                $user->active = 1;
                $user->hospital_id = $request->hospital_id;
                $role = Role::find($request->get('role'));

                $user->syncRoles($request->get('role'));
                $user->save();
                ActivityLogger::activity($user->email . trans('users.logeeredituserseccess'));
            });
            Alert::success(trans('users.successuseredit'));

            return redirect()->route('users');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());
            ActivityLogger::activity($request->email . trans('users.logeeredituserfaulss'));

            return redirect()->route('users');
        }
    }
    public function show($id)
    {
        // $user_id = decrypt($id);
        $user = User::find($id);
        // ActivityLogger::activity(trans('users.profilelogger'));

        // return view('dashbord.users.profile')->with('user', $user);
    }


    public function showChangePasswordForm()
    {
        // ActivityLogger::activity(trans('users.changepasslogger'));

        return view('dashbord.users.change_form');
    }

    public function changePassword(Request $request)
    {
        $messages = [

            'current-password.required' => trans('users.current-password_r'),
            'new-password.required' => trans('users.new-password_r'),
            'new-password-confirm.required' => trans('users.new-password-confirm'),
        ];

        $this->validate($request, [
            'current-password' => ['required', 'string', 'min:6'],
            'new-password' => ['required', 'string', 'min:6'],
            'new-password-confirm' => ['required', 'same:new-password', 'string', 'min:6'],
        ], $messages);
        if (!(Hash::check($request->input('current-password'), Auth::user()->password))) {
            ActivityLogger::activity(trans('users.changefailloogger'));
            Alert::warning(trans('users.passwordnotmatcheing'));
            return redirect()->back();
        }
        //Change Password
        $user = Auth::user();
        $user->password = Hash::make($request->input('new-password'));
        $user->save();
        ActivityLogger::activity(trans('users.changesecclogger'));
        Alert::success(trans('users.changesecc'));
        return redirect()->back();
    }
    public function changeStatus(Request $request, $id)
    {
        $user_id = decrypt($id);
        $user = User::find($user_id);

        try {
            DB::transaction(function () use ($request, $id) {
                $user_id = decrypt($id);
                $user = User::find($user_id);
                if ($user->active == 1) {
                    $active = 0;
                } else {
                    $active = 1;
                }

                $user->active = $active;
                $user->save();
            });
            ActivityLogger::activity($user->email . trans('users.changestatueslogger'));
            Alert::success(trans('users.changestatuesalert'));

            return redirect('users');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());
            ActivityLogger::activity($user->email . trans('users.changestatuesloggerfail'));

            return redirect('users');
        }
    }
}
