<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\NidController;
use App\Models\Bloodtype;
use App\Models\City;
use App\Models\Customer;
use App\Models\CustomerAudit;
use App\Models\guarantybranch;
use App\Models\Municipal;
use App\Models\Nationality;
use App\Models\Verification;
use App\Models\SalNumber as Sal;
use App\Models\salaryNumber;
use App\Models\beneficiariesCategories;
use App\Models\beneficiariesSupCategories;
use App\Models\InsuranceAgents;
use App\Models\WorkCategory;
use App\Models\Institucion;
use App\Models\InstitucionSheetRow;
use App\Models\Socialstatus;
use App\Models\Warrantyoffice;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use App\Models\ServiceLog;






use App\Services\CustomerretiredService;
use App\Services\SmsApiServiceLibyana;
use Carbon\Carbon;
use Complex\Functions;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Session;
use App\Services\SmsApiServiceMadar;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class CustomerController extends Controller
{
    private $sms;
    private $sms2;
    private $nid;
    public $data;
    // NidController $nid
    public function __construct(SmsApiServiceMadar $api, SmsApiServiceLibyana $api2, NidController $nid)
    {
        $this->sms = $api;
        $this->sms2 = $api2;
        $this->nid = $nid;
    }

    public function refreshCaptchaRE()
    {
        return response()->json(['captcha' => captcha_img()]);
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        return view('frontend.complete-data2');
        // return view('frontend.index');
    }


    // App\Http\Controllers\RegisterController.php


    // public function registerCustomerByAdmin()
    // {

    //     $beneficiariesSupCategories = beneficiariesSupCategories::where('beneficiaries_categories_id', 1)->get();
    //     return view('frontend.registerCustomer', ['beneficiariesSupCategories' => $beneficiariesSupCategories]);
    // }

    public function registerCustomerByAdmin2()
    {

        $customer = beneficiariesCategories::whereIn('id', [1,7,8,9,12])->get();
        $workCategories  = WorkCategory::select('id', 'name')->orderBy('name')->get();
        $institucions    = Institucion::select('id', 'name', 'work_categories_id')->orderBy('name')->get();

        return view('customers.registerCustomer', compact(
            'customer',
            'workCategories',
            'institucions'
        ));
    }


    // public function RegisterBeneficiary()
    // {

    //     $beneficiariesSupCategories = beneficiariesSupCategories::where('beneficiaries_categories_id', 2)->get();
    //     return view('frontend.RegisterBeneficiary.index', ['beneficiariesSupCategories' => $beneficiariesSupCategories]);
    // }





    public function OTP($phone)
    {
        // تحقق هل فيه عميل بنفس الرقم
        $customer = Customer::where('phone', $phone)->first();

        // if (!$customer) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'المشترك غير موجود'
        //     ], 404);
        // }

        // استدعاء API الخاصة بالـ OTP
        $response = Http::withOptions(['verify' => false])
            ->get('https://test.phif.gov.ly/api/otp-verification');

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'فشل الاتصال بخدمة OTP'
            ], 500);
        }

        $otp = $response->json()['otp'] ?? null;

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'الـ API لم ترجع كود OTP'
            ], 500);
        }

        // إرسال SMS للمشترك
        $vendor = substr($phone, 1, 1);

        if ($vendor != "1" && $vendor != "3") {
            $states = $this->sms2->sendSms((string) $phone, "رمز التحقق: " . $otp)->successful();
        } else {
            $states = $this->sms->sendSms((string) $phone, "رمز التحقق: " . $otp)->successful();
        }

        if (!$states) {
            return response()->json([
                'success' => false,
                'message' => 'فشل إرسال الرسالة'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رمز التحقق',
            'otp' => $otp // مؤقتاً للتيست
        ]);
    }
    // create customer by admin
    public function checkCustomersIdentity2(Request $request)
    {

        $messages = [
            'phone.required' => "الرجاء ادخال رقم الهاتف",
            'nationalID.required' => "الرجاء ادخال رقم الوطني",
            'nationalID.unique' => "الرقم الوطني مستخدم من قبل",
            'otp.required' => "الرجاء ادخال الرمز ",
        ];
        $this->validate($request, [
            'phone' => 'required|digits_between:9,9|numeric|starts_with:91,92,94,21,93 ',
            'nationalID' => ['required', 'unique:customers', 'digits_between:12,12', 'starts_with:2,1'],
            'otp' => 'required|digits_between:6,6',

        ], $messages);


        $customer = Customer::where('phone', $request->phone)->first();

        if ($customer != null) {
            //dd($customer);
            // return response()->json(['message' => 'رقم الهاتف مسجل مسبقا'], 500);
            Alert::error('رقم الهاتف مسجل مسبقاً');
            return redirect()->back();
        }
        $error = session()->get('errors');
        if ($error == null) {

            $ve = Verification::where('phone', $request->phone)->first();

            $trget = Carbon::parse($ve->otp_time)->addMinute(2);
            $result = now()->gte($trget);

            if ($result == true) {

                $ve->otp = mt_rand(100000, 999999);
                $ve->save();
                Alert::error('لقد انتهت صلاحية رمز التحقق');
                return redirect()->route('register-customer');
            } else {

                if ($ve->otp == $request->otp) {
                    $ve->save();
                    Alert::success('تمت عملية التحقق بنجاح');
                } else {
                    Alert::error('رمز التحقق غير صحيح الرجاء التأكد');
                    return redirect()->route('register-customer');
                }
            }
        }
        $nidAr = $this->nid->getNidData($request->nationalID);
        $nidEn = $this->nid->getNidEnData($request->nationalID);
        $nationality = Nationality::orderBy('name', 'ASC')->get();
        $bloodtype = Bloodtype::orderBy('name', 'ASC')->get();
        $city = City::orderBy('name', 'ASC')->get();
        $socialstatuses = Socialstatus::orderBy('name', 'ASC')->get();

        // warrantynumber .. retired
        $warrantyoffices = Warrantyoffice::orderBy('name', 'ASC')->get();
        $healthfacilities = Healthfacilities::orderBy('name', 'ASC')->get();
        $guarantybranch = guarantybranch::orderBy('name', 'ASC')->get();
        $Chronicdiseases = Chronicdiseases::all();

        try {

            if ($nidAr == "Nid Not Found!") {
                Alert::error('هذا الرقم غير موجود الرجاء التأكد');
                return redirect()->back();
            } else if ($nidAr->isLife) {
                $fullNameArabic = $nidAr->firstName . ' ' . $nidAr->fatherName . ' ' . $nidAr->grandFatherName . ' ' . $nidAr->surName;
                $fullNameEnglish = $nidEn->FirstNameEn . ' ' . $nidEn->FatherNameEn . ' ' . $nidEn->GrandFatherNameEn . ' ' . $nidEn->SurNameEn;
                $birtdateandtiem = explode("T00", $nidAr->birthDate);
                $birtdate = $birtdateandtiem['0'];
                $gendertype = substr($nidAr->nationalID, 0, 1);

                return view('frontend.registerCustomersByAdmin')
                    ->with('bloodtype', $bloodtype)
                    ->with('city', $city)
                    ->with('socialstatuses', $socialstatuses)
                    ->with('nationality', $nationality)
                    ->with('fullNameArabic', $fullNameArabic)
                    ->with('fullNameEnglish', $fullNameEnglish)
                    ->with('birtdate', $birtdate)
                    ->with('gendertype', $gendertype)
                    ->with('nidAr', $nidAr)
                    ->with('nidEn', $nidEn)
                    ->with('phoneNum', $request->phone)
                    ->with('Chronicdiseases', $Chronicdiseases)
                    ->with('guarantybranch', $guarantybranch)
                    ->with('warrantyoffices', $warrantyoffices)
                    ->with('healthfacilities', $healthfacilities);
            } else {
                Alert::error('لايمكنك الاستفادة من الخدمة ');
                return redirect()->back();
            }
        } catch (Exception $e) {
            Alert::error('لايمكنك الاستفادة من الخدمة ');
            return redirect()->back();
        }
    }


    public function registerByAdmin()
    {

        $beneficiariesSupCategories = beneficiariesSupCategories::where('beneficiaries_categories_id', 3)->get();
        return view('customers.registerCustomer', ['beneficiariesSupCategories' => $beneficiariesSupCategories]);
    }





    //     public function checkIdentity(Request $request)
    // {
    //     // رسائل التحقق
    //     $messages = [
    //         'phone.required'                => "الرجاء ادخال رقم الهاتف",
    //         'phone.starts_with'             => "رقم الهاتف يجب أن يبدأ بـ 91 أو 92 أو 93 أو 94 أو 21",
    //         'nationalID.required'           => "الرجاء ادخال رقم الوطني",
    //         'nationalID.unique'             => "الرقم الوطني مستخدم من قبل",
    //         'otp.required'                  => "الرجاء ادخال الرمز ",
    //         'work_category_id.required_if'  => 'يرجى اختيار نوع جهة العمل للفئات 7 أو 8',
    //         'institution_id.required_if'    => 'يرجى اختيار جهة العمل للفئات 7 أو 8',
    //     ];

    //     // التحقق من المدخلات
    //     $this->validate($request, [
    //         'phone'                      => 'required|digits_between:9,9|numeric|starts_with:91,92,94,21,93',
    //         'nationalID'                 => ['required','unique:customers,nationalID','digits_between:12,12','starts_with:2,1'],
    //         'otp'                        => 'required|digits_between:6,6',
    //         'beneficiariesSupCategories' => ['required'],
    //         'work_category_id'           => 'required_if:beneficiariesSupCategories,7,8|nullable',
    //         'institution_id'             => 'required_if:beneficiariesSupCategories,7,8|nullable',
    //     ], $messages);

    //     // تمنع تكرار الهاتف
    //     $customer = Customer::where('phone', $request->phone)->first();
    //     if ($customer) {
    //         Alert::error('رقم الهاتف مسجل مسبقاً');
    //         return redirect()->back()->withInput();
    //     }

    //     // تحقق الـ OTP
    //     $ve = Verification::where('phone', $request->phone)->first();
    //     if (!$ve) {
    //         Alert::error('لم يتم إرسال رمز تحقق لهذا الرقم');
    //         return redirect()->route('register-customer')->withInput();
    //     }

    //     $expiresAt = Carbon::parse($ve->otp_time)->addMinute(2);
    //     if (now()->gte($expiresAt)) {
    //         $ve->otp = mt_rand(100000, 999999);
    //         $ve->save();
    //         Alert::error('لقد انتهت صلاحية رمز التحقق');
    //         return redirect()->route('register-customer')->withInput();
    //     }
    //     if ($ve->otp != $request->otp) {
    //         Alert::error('رمز التحقق غير صحيح الرجاء التأكد');
    //         return redirect()->route('register-customer')->withInput();
    //     }
    //     $ve->save();
    //     Alert::success('تمت عملية التحقق بنجاح');

    //     // متغيّرات افتراضية لتجنّب undefined vars
    //     $fullNameArabic   = null;
    //     $fullNameEnglish  = null;
    //     $birtdate         = null;
    //     $gendertype       = null;
    //     $nidAr            = null;
    //     $nidEn            = null;
    //     $Customer         = null;
    //     $warrantynumber   = null;

    //     // قيم الشيت الافتراضية
    //     $insured_no    = null;
    //     $pension_no    = null;
    //     $account_no    = null;
    //     $total_pension = null;

    //     // هل الفئة تتبع جهة عمل؟
    //     $needsInstitution = in_array((string)$request->beneficiariesSupCategories, ['7','8'], true);

    //     // تحقق الشيت + استدعاء CRA فقط للفئات التابعة لجهة عمل
    //     $craStatus       = false;
    //     $craMembers      = [];
    //     $craMembersCount = 0;
    //     $craError        = null;

    //     if ($needsInstitution) {
    //         if (!$request->institution_id) {
    //             Alert::error('الرجاء اختيار جهة العمل للفئات المحددة (7 أو 8)');
    //             return back()->withInput();
    //         }

    //         // البحث في الشيت (الرقم الوطني + جهة العمل)
    //         $row = InstitucionSheetRow::where('national_id', $request->nationalID)
    //             ->where('institucion_id', $request->institution_id)
    //             ->first();

    //         if (!$row) {
    //             Alert::error('لم يتم العثور على بيانات مطابقة في الشيت (الرقم الوطني + جهة العمل)');
    //             return back()->withInput();
    //         }

    //         // حفظ بعض القيم من الشيت (لو تبي تعرضيها في الفورم)
    //         $insured_no    = $row->insured_no;
    //         $pension_no    = $row->pension_no;
    //         $account_no    = $row->account_no;
    //         $total_pension = $row->total_pension;

    //         // ************** نداء CRA باستخدام family_registry_no **************
    //         $registryNumberForApi = $row->family_registry_no ?: '';

    //         try {
    //             $craResponse = Http::timeout(12)
    //                 ->withOptions(['verify' => false]) // في حال شبكة داخلية أو SSL غير مضبوط
    //                 ->post('http://10.110.110.90/api/Phif-cra', [
    //                     'NationalID'     => (string) $request->nationalID,
    //                     'RegistryNumber' => (string) $registryNumberForApi,
    //                 ]);

    //             if (!$craResponse->successful()) {
    //                 $craError = 'تعذر الاتصال بخدمة CRA (HTTP '.$craResponse->status().')';
    //             } else {
    //                 $js       = $craResponse->json();
    //                 $craStatus = (bool) data_get($js, 'status', false);
    //                 $data      = (array) data_get($js, 'data', []);
    //                 $members   = (array) data_get($data, 'members', []);

    //                 if ($craStatus && is_array($members)) {
    //                     $craMembers = collect($members)->map(function ($m) {
    //                         $birthDateRaw = data_get($m, 'birthDate');
    //                         $birthDate    = $birthDateRaw ? (explode('T', $birthDateRaw)[0] ?? $birthDateRaw) : null;

    //                         return [
    //                             'nationalID'            => data_get($m, 'nationalID'),
    //                             'arabicFirstName'       => data_get($m, 'arabicFirstName'),
    //                             'arabicFatherName'      => data_get($m, 'arabicFatherName'),
    //                             'arabicGrandFatherName' => data_get($m, 'arabicGrandFatherName'),
    //                             'arabicFamilyName'      => data_get($m, 'arabicFamilyName'),
    //                             'arabicMotherName'      => data_get($m, 'arabicMotherName'),
    //                             'birthDate'             => $birthDate,
    //                             'birthPlace'            => data_get($m, 'birthPlace'),
    //                             'gender'                => data_get($m, 'gender'),       // 0/1
    //                             'isALive'               => data_get($m, 'isALive'),      // "Y"/"N"
    //                             'relationship'          => data_get($m, 'relationship'), // 1/2/3...
    //                             'status'                => data_get($m, 'status'),
    //                         ];
    //                     })->all();

    //                     $craMembersCount = (int) (data_get($data, 'membersCount') ?? count($craMembers));
    //                 } else {
    //                     $craError = 'التحقق من CRA لم يرجع بيانات صالحة';
    //                 }
    //             }
    //         } catch (\Throwable $e) {
    //             $craError = 'خطأ أثناء الاتصال بخدمة CRA: '.$e->getMessage();
    //         }

    //         // لو تحبي تفشّلي العملية عند فشل خدمة CRA فعّلي السطور التالية:
    //         // if ($craError) {
    //         //     Alert::error($craError);
    //         //     return back()->withInput();
    //         // }
    //         // *********************************************
    //     }

    //     // القوائم المعتادة
    //     $nationality      = Nationality::orderBy('name','ASC')->get();
    //     $bloodtype        = Bloodtype::orderBy('name','ASC')->get();
    //     $city             = City::orderBy('name','ASC')->get();
    //     $socialstatuses   = Socialstatus::orderBy('name','ASC')->get();
    //     $beneficiariesSC  = beneficiariesSupCategories::all();
    //     $warrantyoffices  = Warrantyoffice::orderBy('name','ASC')->get();
    //     $healthfacilities = Healthfacilities::orderBy('name','ASC')->get();
    //     $guarantybranch   = guarantybranch::orderBy('name','ASC')->get();
    //     $Chronicdiseases  = Chronicdiseases::all();

    //     return view('frontend.registerCustomersByAdmin')
    //         ->with('bloodtype', $bloodtype)
    //         ->with('city', $city)
    //         ->with('socialstatuses', $socialstatuses)
    //         ->with('nationality', $nationality)
    //         ->with('fullNameArabic', $fullNameArabic)
    //         ->with('fullNameEnglish', $fullNameEnglish)
    //         ->with('birtdate', $birtdate)
    //         ->with('gendertype', $gendertype)
    //         ->with('nidAr', $nidAr)
    //         ->with('nidEn', $nidEn)
    //         ->with('phoneNum', $request->phone)
    //         ->with('Chronicdiseases', $Chronicdiseases)
    //         ->with('guarantybranch', $guarantybranch)
    //         ->with('warrantyoffices', $warrantyoffices)
    //         ->with('healthfacilities', $healthfacilities)
    //         ->with('beneficiariesSupCategories', $beneficiariesSC)
    //         ->with('beneficiariesSupCategoriesType', $request->beneficiariesSupCategories)
    //         ->with('Customer', $Customer)
    //         ->with('warrantynumber', $warrantynumber)
    //         // قيم الشيت
    //         ->with('insured_no', $insured_no)
    //         ->with('pension_no', $pension_no)
    //         ->with('account_no', $account_no)
    //         ->with('total_pension', $total_pension)
    //         // نتائج CRA
    //         ->with('craStatus', $craStatus)
    //         ->with('craMembers', $craMembers)
    //         ->with('craMembersCount', $craMembersCount)
    //         ->with('craError', $craError);
    // }

//    public function test(Request $request)
//     {
//         $nationalId         = $request->nationalID;
//         $registryNumber     = $request->family_registry_no;
//         $phone              = $request->phone;
//         $benefCat           = (string) $request->beneficiariesSupCategories;
//         $institutionId      = $request->institution_id;
//         $otpInput           = $request->otp;

//         if ($phone && \App\Models\Customer::where('phone', $phone)->exists()) {
//             return back()->withErrors(['phone' => 'رقم الهاتف مسجل مسبقاً'])->withInput();
//         }

//         // 2) تحقق من الرمز
//         $ve = \App\Models\Verification::where('phone', $phone)->first();
//         if (!$ve) {
//             return back()->withErrors(['otp' => 'لم يتم إرسال رمز تحقق لهذا الرقم'])->withInput();
//         }

//         // الوقت المسموح = otp_time + دقيقتين
//         $expiresAt = \Carbon\Carbon::parse($ve->otp_time)->addMinutes(3);

//         if (now()->gt($expiresAt)) {
//             return back()->withErrors(['otp' => 'انتهت صلاحية رمز التحقق'])->withInput();
//         }

//         if ($ve->otp != $otpInput) {
//             return back()->withErrors(['otp' => 'رمز التحقق غير صحيح'])->withInput();
//         }

//         // =========================
//         // A) ربط مصلحة الأحوال (CRA) — نفس منطقك تماماً
//         // =========================
//         $craOk         = false;
//         $craMain       = null;
//         $craDependents = collect();
//         $craCount      = 0;

//         try {

//             // $nidEn = $this->nid->getNidEnData($nationalId);
//             // // تحبّي تشوفيها مؤقتاً:
//             // dd($nidEn);

//             // ✅ 4) تسجيل الدخول لجلب التوكن
//             $login = Http::timeout(10)
//                 ->withOptions(['verify' => false])
//                 ->post('http://10.110.110.90/api/login-api?email=cra@phif.gov.ly&password=cra%23@PasS');

//             if (!$login->successful()) {
//                 return back()->withErrors([
//                     'nationalID' => 'Login failed: HTTP ' . $login->status(),
//                 ])->withInput();
//             }

//             $token = data_get($login->json(), 'token');
//             if (!$token) {
//                 return back()->withErrors([
//                     'nationalID' => 'Login response has no token',
//                 ])->withInput();
//             }

//             $resp = Http::timeout(30)
//                 ->withOptions(['verify' => false])
//                 ->withToken($token) // التوكن اللي جبته من /login-api
//                 ->post('http://10.110.110.90/api/Phif-cra', [
//                     'NationalID'     => $nationalId,
//                     'RegistryNumber' => $registryNumber,
//                 ]);

//             // return $resp->json();

//             if (!$resp->successful()) {
//                 return back()->withErrors([
//                     'nationalID' => 'CRA call failed: HTTP ' . $resp->status(),
//                 ])->withInput();
//             }

//             $json = $resp->json();
//             if (!$json || !data_get($json, 'status')) {
//                 return back()->withErrors([
//                     'nationalID' => 'CRA response invalid',
//                 ])->withInput();
//             }

//             $members = data_get($json, 'data.members', []);
//             $count   = data_get($json, 'data.membersCount', count($members));

//             $normalized = collect($members)->map(function ($m) {
//                 $birthRaw = data_get($m, 'birthDate');
//                 $birth    = $birthRaw ? explode('T', $birthRaw)[0] : null;
//                 return [
//                     'nationalID'   => data_get($m, 'nationalID'),
//                     'name'         => trim((data_get($m, 'arabicFirstName') . ' ' . data_get($m, 'arabicFatherName') . ' ' . data_get($m, 'arabicGrandFatherName') . ' ' . data_get($m, 'arabicFamilyName'))),
//                     'mother'       => data_get($m, 'arabicMotherName'),
//                     'birthDate'    => $birth,
//                     'birthPlace'   => data_get($m, 'birthPlace'),
//                     'gender'       => (data_get($m, 'gender') == 0 ? 'ذكر' : 'أنثى'),
//                     'isAlive'      => (data_get($m, 'isALive') === 'Y'),
//                     'relationship' => data_get($m, 'relationship'),
//                     'status'       => data_get($m, 'status'),
//                     'name_en'      => null,
//                 ];
//             });

//             $normalized = $normalized->map(function ($item) {
//                 try {
//                     $respEn = Http::timeout(10)
//                         ->withOptions(['verify' => false])
//                         ->get("https://test.phif.gov.ly/getnidinfoEN/" . $item['nationalID']);

//                     if ($respEn->successful()) {
//                         $enData = $respEn->json();
//                         $item['name_en'] = trim(
//                             ($enData['FirstNameEn'] ?? '') . ' ' .
//                                 ($enData['FatherNameEn'] ?? '') . ' ' .
//                                 ($enData['GrandFatherNameEn'] ?? '') . ' ' .
//                                 ($enData['SurNameEn'] ?? '')
//                         );
//                     }
//                 } catch (\Throwable $e) {
//                     $item['name_en'] = null;
//                 }

//                 return $item;
//             });

//             $craMain       = $normalized->firstWhere('nationalID', $nationalId);
//             $craDependents = $normalized->where('nationalID', '!=', $nationalId)->values();
//             $craCount      = count($normalized);
//             $craOk         = true;
//         } catch (\Throwable $e) {
//             return back()->withErrors([
//                 'nationalID' => 'Exception: ' . $e->getMessage(),
//             ])->withInput();
//         }

//         // =========================
//         // B) الشِّيت (للـفئات 7 أو 8 فقط)
//         // =========================
//         $sheetMatch       = null;
//         $needsInstitution = in_array($benefCat, ['7', '8'], true);

//         if ($needsInstitution) {
//             $sheetMatch = InstitucionSheetRow::where('national_id', $nationalId)
//                 ->where('institucion_id', $institutionId)
//                 ->first();

//             if (!$sheetMatch) {
//                 // Useful hint: الرقم قد يكون موجود لكن لجهة أخرى
//                 $existsNat = InstitucionSheetRow::where('national_id', $nationalId)->exists();

//                 return back()->withErrors([
//                     'institution_id' => $existsNat
//                         ? 'تم العثور على الرقم الوطني في الشيت لكنه مرتبط بجهة عمل مختلفة'
//                         : 'لم يتم العثور على بيانات مطابقة في الشيت (الرقم الوطني + جهة العمل)'
//                 ])->withInput();
//             }
//         }


//         return redirect()->route('customers.register.step2')->with([
//             // CRA
//             'cra_ok'         => $craOk,
//             'cra_main'       => $craMain,
//             'cra_dependents' => $craDependents,
//             'cra_count'      => $craCount,
//             'phone'          => $phone,
//             'registryNumber' => $registryNumber,
//             'beneficiariesCategoriesId' => $request->beneficiariesCategories,

//             'beneficiariesSupCategories' => $benefCat,

//             'verified_ok'    => $craOk && ($needsInstitution ? (bool)$sheetMatch : true),
//             'sheetMatch'     => $sheetMatch,
//             'insured_no'     => $sheetMatch?->insured_no,
//             'pension_no'     => $sheetMatch?->pension_no,
//             'account_no'     => $sheetMatch?->account_no,
//             'total_pension'  => $sheetMatch?->total_pension,


//         ]);
//     }




     public function test(Request $request)
        {
            $nationalId         = $request->nationalID;
            $registryNumber     = $request->family_registry_no;
            $phone              = $request->phone;
            $benefCat           = (string) $request->beneficiariesSupCategories;
            $institutionId      = $request->institution_id;
            $otpInput           = $request->otp;

            // نوع المشترك: husband | wife | single
            $subscriberType     = $request->subscriber_type;
            $spouseNationalId   = $request->spouse_national_id;

            // لو زوجة: لازم رقم الزوج
            if ($subscriberType === 'wife' && empty($spouseNationalId)) {
                return back()->withErrors([
                    'spouse_national_id' => 'يجب إدخال الرقم الوطني للزوج عند اختيار (زوجة).'
                ])->withInput();
            }

            // تحقق من الهاتف (فريد)
            if ($phone && \App\Models\Customer::where('phone', $phone)->exists()) {
                return back()->withErrors(['phone' => 'رقم الهاتف مسجل مسبقاً'])->withInput();
            }

            // تحقق من OTP
            $ve = \App\Models\Verification::where('phone', $phone)->first();
            if (!$ve) {
                return back()->withErrors(['otp' => 'لم يتم إرسال رمز تحقق لهذا الرقم'])->withInput();
            }

            $expiresAt = Carbon::parse($ve->otp_time)->addMinutes(3);
            if (now()->gt($expiresAt)) {
                return back()->withErrors(['otp' => 'انتهت صلاحية رمز التحقق'])->withInput();
            }
            if ($ve->otp != $otpInput) {
                return back()->withErrors(['otp' => 'رمز التحقق غير صحيح'])->withInput();
            }

            $craOk         = false;
            $craMain       = null;
            $craDependents = collect();
            $craCount      = 0;

            try {
                // ======================================================
                // 👇 لو المشترك أعزب نستعمل فقط API getnidinfo/{natId}
                // ======================================================
                if ($subscriberType === 'single') {
                    $resp = Http::withOptions([
                        'verify' => false,
                        'timeout' => 20,
                        'connect_timeout' => 10,
                    ])->get("https://test.phif.gov.ly/getnidinfo/" . $nationalId);

                    if (!$resp->successful()) {
                        return back()->withErrors(['nationalID' => 'تعذر جلب البيانات من النظام'])->withInput();
                    }

                    $json = $resp->json();
                    if (!$json || !isset($json['nationalID'])) {
                        return back()->withErrors(['nationalID' => 'لم يتم العثور على بيانات لهذا الرقم الوطني'])->withInput();
                    }

                    // 👇 نحاول نجيب الاسم بالإنجليزي من API ثاني
                    $nameEn = null;
                    try {
                        $respEn = Http::withOptions([
                            'verify' => false,
                            'timeout' => 10,
                            'connect_timeout' => 10,
                        ])->get("https://test.phif.gov.ly/getnidinfoEN/" . $nationalId);

                        if ($respEn->successful()) {
                            $enData = $respEn->json();
                            $nameEn = trim(
                                ($enData['FirstNameEn'] ?? '') . ' ' .
                                    ($enData['FatherNameEn'] ?? '') . ' ' .
                                    ($enData['GrandFatherNameEn'] ?? '') . ' ' .
                                    ($enData['SurNameEn'] ?? '')
                            );
                        }
                    } catch (\Throwable $e) {
                        $nameEn = null;
                    }

                    $craMain = [
                        'nationalID'   => $json['nationalID'] ?? null,
                        'name'         => trim(($json['firstName'] ?? '') . ' ' . ($json['fatherName'] ?? '') . ' ' . ($json['grandFatherName'] ?? '') . ' ' . ($json['surName'] ?? '')),
                        'name_en'      => $nameEn,
                        'mother'       => null,
                        'birthDate'    => $json['birthDate'] ? explode('T', $json['birthDate'])[0] : null,
                        'birthPlace'   => $json['birthPlace'] ?? null,
                        'gender'       => null,
                        'isAlive'      => (bool)($json['isLife'] ?? true),
                        'relationship' => null,
                        'status'       => null,
                    ];
                    $craDependents = collect();
                    $craCount      = 1;
                    $craOk         = true;
                } else {
                    // ======================================================
                    // 👇 باقي الحالات (husband / wife) نستعمل CRA API
                    // ======================================================
                    $login = Http::withOptions([
                        'verify' => false,
                        'timeout' => 30,
                        'connect_timeout' => 30,
                    ])->post('http://10.110.110.90/api/login-api?email=cra@phif.gov.ly&password=cra%23@PasS');

                    if (!$login->successful()) {
                        return back()->withErrors([
                            'nationalID' => 'Login failed: HTTP ' . $login->status(),
                        ])->withInput();
                    }

                    $token = data_get($login->json(), 'token');
                    if (!$token) {
                        return back()->withErrors([
                            'nationalID' => 'Login response has no token',
                        ])->withInput();
                    }

                    $fetchWithEnglish = function (string $natId, string $regNo) use ($token) {
                        $resp = Http::withOptions([
                            'verify' => false,
                            'timeout' => 60,
                            'connect_timeout' => 30,
                        ])->withToken($token)->post('http://10.110.110.90/api/Phif-cra', [
                            'NationalID'     => $natId,
                            'RegistryNumber' => $regNo,
                        ]);

                        if (!$resp->successful()) {
                            throw new \Exception("CRA call failed: HTTP " . $resp->status());
                        }

                        $json = $resp->json();
                        if (!$json || !data_get($json, 'status')) {
                            throw new \Exception("CRA response invalid");
                        }

                        $members = collect(data_get($json, 'data.members', []));
                        $normalized = $members->map(function ($m) {
                            $birthRaw = data_get($m, 'birthDate');
                            $birth    = $birthRaw ? explode('T', $birthRaw)[0] : null;
                            return [
                                'nationalID'   => data_get($m, 'nationalID'),
                                'name'         => trim((data_get($m, 'arabicFirstName') . ' ' . data_get($m, 'arabicFatherName') . ' ' . data_get($m, 'arabicGrandFatherName') . ' ' . data_get($m, 'arabicFamilyName'))),
                                'mother'       => data_get($m, 'arabicMotherName'),
                                'birthDate'    => $birth,
                                'birthPlace'   => data_get($m, 'birthPlace'),
                                'gender'       => (data_get($m, 'gender') == 0 ? 'ذكر' : 'أنثى'),
                                'isAlive'      => (data_get($m, 'isALive') === 'Y'),
                                'relationship' => data_get($m, 'relationship'),
                                'status'       => data_get($m, 'status'),
                                'name_en'      => null,
                            ];
                        });

                        // 👇 نكمل بالإنجليزي
                        $withEn = $normalized->map(function ($item) {
                            try {
                                $respEn = Http::withOptions([
                                    'verify' => false,
                                    'timeout' => 10,
                                    'connect_timeout' => 10,
                                ])->get("https://test.phif.gov.ly/getnidinfoEN/" . $item['nationalID']);

                                if ($respEn->successful()) {
                                    $enData = $respEn->json();
                                    $item['name_en'] = trim(
                                        ($enData['FirstNameEn'] ?? '') . ' ' .
                                            ($enData['FatherNameEn'] ?? '') . ' ' .
                                            ($enData['GrandFatherNameEn'] ?? '') . ' ' .
                                            ($enData['SurNameEn'] ?? '')
                                    );
                                }
                            } catch (\Throwable $e) {
                                $item['name_en'] = null;
                            }
                            return $item;
                        });

                        return $withEn;
                    };

                    if ($subscriberType === 'wife') {
                        $wifeData = $fetchWithEnglish($nationalId, $registryNumber);
                        $craMain  = $wifeData->firstWhere('nationalID', $nationalId);

                        $husbandData   = $fetchWithEnglish($spouseNationalId, $registryNumber);
                        $craDependents = $husbandData->filter(fn($m) => (int)$m['relationship'] === 3)->values();

                        $craCount = 1 + $craDependents->count();
                    } elseif ($subscriberType === 'husband') {
                        $normalized    = $fetchWithEnglish($nationalId, $registryNumber);
                        $craMain       = $normalized->firstWhere('nationalID', $nationalId);

                        $craDependents = $normalized->filter(function ($item) use ($nationalId) {
                            if ($item['nationalID'] == $nationalId) return false;
                            if ((int)$item['relationship'] === 2) return true;
                            if ((int)$item['relationship'] === 3 && !empty($item['birthDate'])) {
                                $age = Carbon::parse($item['birthDate'])->age;
                                if ($item['gender'] === 'أنثى' && $age <= 25) return true;
                                if ($item['gender'] === 'ذكر'  && $age <= 18) return true;
                            }
                            return false;
                        })->values();

                        $craCount = 1 + $craDependents->count();
                    }
                    $craOk = true;
                }

                // تحقق العمر لو أكبر من الحد ولم يختار متقاعدين
                $RETIREES_IDS = [1];
                $benefCatInt = (int)$benefCat;
                $isRetireeSelected = in_array($benefCatInt, $RETIREES_IDS, true);

                $mainBirth  = $craMain['birthDate'] ?? null;
                $mainGender = $craMain['gender']    ?? null;

                if ($mainBirth) {
                    $mainAge = Carbon::parse($mainBirth)->age;
                    if ($mainGender === 'ذكر' && $mainAge > 64 && !$isRetireeSelected) {
                        return back()->withErrors([
                            'beneficiariesSupCategories' => 'المشترك عمره أكبر من 64 سنة. الرجاء اختيار فئة المتقاعدين.'
                        ])->withInput();
                    }
                    if ($mainGender === 'أنثى' && $mainAge > 60 && !$isRetireeSelected) {
                        return back()->withErrors([
                            'beneficiariesSupCategories' => 'المشتركة عمرها أكبر من 60 سنة. الرجاء اختيار فئة المتقاعدين.'
                        ])->withInput();
                    }
                }
            } catch (\Throwable $e) {
                return back()->withErrors([
                    'nationalID' => 'Exception: ' . $e->getMessage(),
                ])->withInput();
            }

            // =========================
            // B) مطابقة الشِّيت (لفئات 7 أو 8 فقط)
            // =========================
            $sheetMatch       = null;
            $needsInstitution = in_array($benefCat, ['7', '8'], true);

            if ($needsInstitution) {
                $sheetMatch = \App\Models\InstitucionSheetRow::where('national_id', $nationalId)
                    ->where('institucion_id', $institutionId)
                    ->first();
            }

            // التحويل للخطوة الثانية
            return redirect()->route('customers.register.step2')->with([
                'cra_ok'         => $craOk,
                'cra_main'       => $craMain,
                'cra_dependents' => $craDependents,
                'cra_count'      => $craCount,
                'phone'          => $phone,
                'registryNumber' => $registryNumber,
                'subscriber_type' => $subscriberType,
                'spouse_id'      => $spouseNationalId,
                'beneficiariesCategoriesId'  => $request->beneficiariesCategories,
                'beneficiariesSupCategories' => $benefCat,
                'institution_id'             => $institutionId,
                'verified_ok'    => $craOk && ($needsInstitution ? (bool)$sheetMatch : true),
                'sheetMatch'     => $sheetMatch,
                'insured_no'     => $sheetMatch?->insured_no,
                'pension_no'     => $sheetMatch?->pension_no,
                'account_no'     => $sheetMatch?->account_no,
                'total_pension'  => $sheetMatch?->total_pension,
            ]);
        }


    public function sendOtps(Request $request)
    {
        // ناخذ الرقم من Body أو Query
        $phone = $request->input('phone') ?? $request->query('phone');

        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'الرقم مطلوب'
            ], 400);
        }

        // تنظيف الرقم: نشيل أي صفر في البداية
        // $phone = ltrim($phone, '0');

        // استدعاء API الخارجي
        $response = Http::withOptions(['verify' => false])
            ->get("https://test.phif.gov.ly/api/otp-verification?phone={$phone}");

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'فشل الاتصال بخدمة OTP'
            ], 500);
        }

        $otp = $response->json()['otp'] ?? null;
        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'الـ API لم يرجع كود OTP'
            ], 500);
        }

        \App\Models\Verification::updateOrCreate(
            ['phone' => $phone],
            [
                'otp' => $otp,
                'otp_time' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رمز التحقق',
            'otp' => $otp // مؤقتاً للتيست
        ]);
    }





    public function test2()
    {

        $socialstatuses = Socialstatus::distinct('name')->get();
        $bloodtype = Bloodtype::distinct('name', 'ASC')->get();
        $city = City::distinct('name')->get();
        $warrantyOffices = Warrantyoffice::distinct('name')->get();
        $guarantyBranches = Guarantybranch::distinct('name')->get();
        return view('customers.register_step2', compact('bloodtype', 'city', 'socialstatuses', 'warrantyOffices', 'guarantyBranches'));
    }


    // public function test44(Request $request)
    // {

    //     // dd($request->all());
    //     $messages = [
    //         // 'main.phone.required' => "الرجاء ادخال رقم الهاتف",
    //         // 'main.gender.required' => "الرجاء اختيار نوع الجنس",
    //         // 'main.passport_no.required' => "الرجاء ادخال رقم الجواز",
    //         // 'main.bloodtypes_id.required' => "الرجاء  اختيار فصيلة الدم ",
    //         // 'main.joptype.required' => "الرجاء ادخال نوع العمل  ",
    //         // 'main.municipals_id.required' => "الرجاء اختار البلدية",
    //         // 'main.nearest_municipal_point.required' => "الرجاء  ادخل عنوان اقرب نقطة دالة ",
    //         // 'main.cities_id.required' => "الرجاء   اختيار المنطقة الصحية",
    //         // 'main.phone.unique' => "رقم الهاتف مستخدم من قبل",
    //     ];

    //     // ✨ نعدل الفالديشن باش يتماشى مع الـ main[..]
    //     $this->validate($request, [
    //         // 'main.email' => ['nullable', 'string', 'email', 'max:50', 'unique:customers,email'],
    //         // 'main.phone' => 'required|digits:9|numeric|starts_with:91,92,94,21,93|unique:customers,phone',
    //         // 'main.gender' => ['required', 'string'],
    //         // 'main.yearbitrh' => ['nullable'],
    //         // 'main.passport_no' => ['required', 'unique:customers,passportnumber'],
    //         // 'main.bloodtypes_id' => ['required'],
    //         // 'main.joptype' => ['nullable'],
    //         // 'main.municipals_id' => ['required'],
    //         // 'main.nearest_municipal_point' => ['required'],
    //         // 'main.cities_id' => ['required'],
    //         // 'main.socialstatuses_id' => ['required'],
    //     ], $messages);

    //     // الفئة الأساسية
    //     $benefCatId = 1;
    //     $cityCode   = 1;

    //     $customerId = null;




    
    //     // $subscriptionId = Institucion::where('id', $request->institution_id)
    //     //     ->value('subscriptions_id');

    //     try {
    //         DB::transaction(function () use ($request, $benefCatId, $cityCode, &$customerId) {
    //             // ===== 1) المشترك الرئيسي =====
    //             $supMain = BeneficiariesSupCategories::where('beneficiaries_categories_id', $benefCatId)
    //                 ->where('type', 'مشترك')
    //                 ->first();

    //             if (!$supMain) {
    //                 throw new \Exception("تعذر تحديد الفئة الفرعية للمشترك");
    //             }

    //             $main = $request->input('main');

    //             $regnumberMain = $this->generateRegNumber(
    //                 $benefCatId,
    //                 $cityCode,
    //                 $main['gender'] ?? null,
    //                 $main['yearbitrh'] ?? null,
    //                 $supMain->code
    //             );

    //             $insuredNo     = $main['insured_no']     ?? null;
    //             $pensionNo     = $main['pension_no']     ?? null;
    //             $accountNo     = $main['account_no']     ?? null;
    //             $totalPension  = $main['total_pension']  ?? 0.00;

    //             // dd($insuredNo);

    //             $customer = new Customer();
    //             $customer->requesttypes_id = 1;
    //             $customer->regnumber = $regnumberMain;
    //             $customer->fullnamea = $main['fullnamea'] ?? $main['name'] ?? null;
    //             $customer->fullnamee = $main['fullnamee'] ?? $main['name_en'] ?? null;
    //             $customer->email = $main['email'] ?? null;
    //             $customer->phone = $main['phone'] ?? null;
    //             $customer->gender = $main['gender'] ?? null;
    //             $customer->yearbitrh = $main['birthDate'] ?? null;
    //             $customer->registrationnumbers = $main['registry_number44'] ?? null;
    //             $customer->registrationnumber = encrypt($main['registry_number44']);

    //             $customer->nid = encrypt($main['nationalID']);
    //             $customer->nationalID = $main['nationalID'];
    //             $customer->passportnumber = $main['passport_no'] ?? null;
    //             $customer->nationalities_id = 1;
    //             $customer->beneficiaries_categories_id  = $benefCatId;
    //             $customer->beneficiaries_sup_categories_id = $supMain->id;
    //             $customer->bloodtypes_id = $main['bloodtypes_id'] ?? null;
    //             $customer->joptype = 3;
    //             $customer->municipals_id = $main['municipals_id'] ?? null;
    //             $customer->nearestpoint  = $main['nearest_municipal_point22'] ?? null;
    //             $customer->cities_id = $main['cities_id'] ?? null;
    //             $customer->socialstatuses_id = $main['socialstatuses_id'] ?? null;
    //             $customer->diseasestate = $main['diseasestate'] ?? null;
    //             // بيانات المطابقة من الشيت (للمشترك الرئيسي فقط)
    //             $customer->insured_no    = $insuredNo;
    //             $customer->pension_no    = $pensionNo;
    //             $customer->account_no    = $accountNo;
    //             $customer->total_pension = $totalPension;
    //             $customer->bank_id        = $main['bank_id']        ?? null;
    //             $customer->bank_branch_id = $main['bank_branch_id'] ?? null;
    //             $customer->iban           = $main['iban']           ?? null;

    //             // $customer->institucion_id = $request->institutionId;

    //             // $customer->subscription_id = $subscriptionId;


    //             $customer->save();

    //             $customerId = $customer->id;

    //             if ($request->beneficiaries_categories_id == 1) {
    //             $Warrantyoffice = Warrantyoffice::where('code', substr($main['warrantynumber'], 1, 3))->first();

    //             if ($Warrantyoffice) {
    //                 $retired = new Retired();
    //                 $retired->warrantynumber = $main['warrantynumber'];
    //                 $retired->warrantyoffices_id = $Warrantyoffice->id;
    //                 $retired->healthfacilities_id = $main['healthfacilities_id'] ?? null;
    //                 $retired->guarantybranches_id = $Warrantyoffice->guarantybranches_id;
    //                 $retired->customers_id = $customer->id;
    //                 $retired->save();
    //             }
    //         }

    //             // ===== 2) المشتركين الفرعيين =====
    //             if ($request->has('dependents')) {
    //                 foreach ($request->dependents as $dep) {
    //                     if (empty($dep['nationalID'])) continue;

    //                     $supDep = BeneficiariesSupCategories::where('beneficiaries_categories_id', $benefCatId)
    //                         ->where('type', 'منتفع')
    //                         ->first();

    //                     if (!$supDep) {
    //                         throw new \Exception("تعذر تحديد الفئة الفرعية للمنتفع");
    //                     }

    //                     $regnumberDep = $this->generateRegNumber(
    //                         $benefCatId,
    //                         $dep['cities_id'] ?? null,
    //                         $dep['gender'] ?? null,
    //                         $dep['birthDate'] ?? null,
    //                         $supDep->code
    //                     );

    //                     $dependent = new Customer();
    //                     $dependent->requesttypes_id = 1;
    //                     $dependent->regnumber = $regnumberDep;
    //                     $dependent->fullnamea = $dep['name'] ?? null;
    //                     $dependent->fullnamee = $dep['name_en'] ?? null;
    //                     $dependent->email = $dep['email'] ?? null;
    //                     $dependent->phone = $dep['phone'] ?? null;
    //                     $dependent->gender = $dep['gender'] ?? null;
    //                     $dependent->yearbitrh = $dep['birthDate'] ?? null;
    //                     $dependent->registrationnumbers = $main['registry_number44'] ?? null;
    //                     $dependent->registrationnumber = encrypt($main['registry_number44']);

    //                     $dependent->nid = encrypt($dep['nationalID']);
    //                     $dependent->nationalID = $dep['nationalID'];
    //                     $dependent->passportnumber = $dep['passport_no'] ?? null;
    //                     $dependent->nationalities_id = 1;
    //                     $dependent->beneficiaries_categories_id  = $benefCatId;
    //                     $dependent->beneficiaries_sup_categories_id = $supDep->id;
    //                     $dependent->bloodtypes_id = $dep['bloodtypes_id'] ?? null;
    //                     $dependent->joptype = 3;
    //                     $dependent->municipals_id = $dep['municipals_id'] ?? null;
    //                     $dependent->nearestpoint  = $dep['nearest_municipal_point33'] ?? null;
    //                     $dependent->cities_id = $dep['cities_id'] ?? null;
    //                     $dependent->socialstatuses_id = $dep['socialstatuses_id'] ?? null;
    //                     $dependent->diseasestate = $dep['diseasestate'] ?? null;
    //                     $dependent->save();
    //                 }
    //             }
    //         });

    //         Alert::success("تمت عملية التسجيل بنجاح");
    //         return redirect()->route('customers.show', $customerId)
    //             ->with('success', 'تم تسجيل المشترك والمشتركين الفرعيين')
    //             ->with('message', 'تم تسجيل المشترك والمشتركين الفرعيين');
    //     } catch (\Exception $e) {
    //         dd($e->getMessage(), $e->getTraceAsString());
    //         Alert::error("الرجاء المحاولة مرة اخرى");
    //         return back()->withErrors(['general' => 'يوجد خطأ في عملية التسجيل'])->withInput();
    //     }
    // }



     public function test44(Request $request)
    {
        $messages = [];

        $benefCatId = (int) ($request->input('main.beneficiaries_categories_id') ?? 0);
        $cityCode   = $request->input('main.cities_id');

        $customerId = null;
        $ignoredDependents = 0;

        try {
            DB::transaction(function () use ($request, $benefCatId, $cityCode, &$customerId, &$ignoredDependents) {

                // ===== 1) تحديد الاشتراك حسب الفئة / العمر =====
                $STATE_CATEGORY_ID   = 12; // عدلها حسب نظامك
                $SUBSCRIPTION_ADULT  = 13;
                $SUBSCRIPTION_MINOR  = 14;

                $main = $request->input('main');

                if ($benefCatId == $STATE_CATEGORY_ID) {
                    $age = \Carbon\Carbon::parse($main['birthDate'])->age;
                    $subscriptionId = $age > 17 ? $SUBSCRIPTION_ADULT : $SUBSCRIPTION_MINOR;
                } else {
                    $subscriptionId = \App\Models\Subscription::where('beneficiaries_categories_id', $benefCatId)->value('id');
                }

                // ===== 2) المشترك الرئيسي =====
                $supMain = BeneficiariesSupCategories::where('beneficiaries_categories_id', $benefCatId)
                    ->where('type', 'مشترك')
                    ->first();

                if (!$supMain) {
                    throw new \Exception("تعذر تحديد الفئة الفرعية للمشترك");
                }

               $regnumberMain = $this->generateRegNumber(
                $benefCatId,
                $cityCode,
                $main['gender'] ?? null,
                $main['birthDate'] ?? null


                );

                $insuredNo    = $main['insured_no']    ?? null;
                $pensionNo    = $main['pension_no']    ?? null;
                $accountNo    = $main['account_no']    ?? null;
                $totalPension = $main['total_pension'] ?? 0.00;

                $customer = new Customer();
                $customer->requesttypes_id = 1;
                $customer->regnumber = $regnumberMain;
                $customer->fullnamea = $main['fullnamea'] ?? $main['name'] ?? null;
                $customer->fullnamee = $main['fullnamee'] ?? $main['name_en'] ?? null;
                $customer->email = $main['email'] ?? null;
                $customer->phone = $main['phone'] ?? null;
                $gender = $main['gender'] ?? null;
                $customer->gender = $gender === 'ذكر' ? 1 : ($gender === 'أنثى' ? 2 : null);
                $customer->yearbitrh = $main['birthDate'] ?? null;
                $customer->registrationnumbers = $main['registry_number44'] ?? null;
                $customer->registrationnumber = encrypt($main['registry_number44']);
                $customer->nid = encrypt($main['nationalID']);
                $customer->nationalID = $main['nationalID'];
                $customer->passportnumber = $main['passport_no'] ?? null;
                $customer->nationalities_id = 1;
                $customer->beneficiaries_categories_id = $benefCatId;
                $customer->beneficiaries_sup_categories_id = $supMain->id;
                $customer->bloodtypes_id = $main['bloodtypes_id'] ?? null;
                $customer->joptype = 3;
                $customer->municipals_id = $main['municipals_id'] ?? null;
                $customer->nearestpoint  = $main['nearest_municipal_point22'] ?? null;
                $customer->cities_id = $main['cities_id'] ?? null;
                $customer->socialstatuses_id = $main['socialstatuses_id'] ?? null;
                $customer->diseasestate = $main['diseasestate'] ?? null;
                $customer->insured_no    = $insuredNo;
                $customer->pension_no    = $pensionNo;
                $customer->account_no    = $accountNo;
                $customer->total_pension = $totalPension;
                $customer->bank_id        = $main['bank_id']        ?? null;
                $customer->bank_branch_id = $main['bank_branch_id'] ?? null;
                $customer->iban           = $main['iban']           ?? null;

                // حفظ المؤسسة إن وجدت
                $customer->institucion_id = $request->input('institutionId');

                // نفس الاشتراك المحدد فوق
                $customer->subscription_id = $subscriptionId;

                $customer->save();
                $customerId = $customer->id;

                // ===== 3) بيانات المتقاعدين (لو الفئة 1) =====
                if ($benefCatId == 1 && !empty($main['warrantynumber'])) {
                    $Warrantyoffice = Warrantyoffice::where('code', substr($main['warrantynumber'], 1, 3))->first();
                    if ($Warrantyoffice) {
                        $retired = new retired();
                        $retired->warrantynumber = $main['warrantynumber'];
                        $retired->warrantyoffices_id = $Warrantyoffice->id;
                        $retired->healthfacilities_id = $main['healthfacilities_id'] ?? null;
                        $retired->guarantybranches_id = $Warrantyoffice->guarantybranches_id;
                        $retired->customers_id = $customer->id;
                        $retired->save();
                    }
                }

                // ===== 4) المنتفعين =====
                if ($request->has('dependents')) {

                    $allDependents = collect($request->input('dependents', []));

                    $dependents = $allDependents->filter(function ($dep) {
                        return !empty($dep['nationalID']) &&
                            !empty($dep['bloodtypes_id']) &&
                            !empty($dep['cities_id']) &&
                            !empty($dep['municipals_id']) &&
                            !empty($dep['socialstatuses_id']);
                    })->values();

                    $ignoredDependents = $allDependents->count() - $dependents->count();

                    if ($dependents->isNotEmpty()) {
                        // نجيب الفئة الفرعية للمنتفع مرة وحدة
                        $supDep = BeneficiariesSupCategories::where('beneficiaries_categories_id', $benefCatId)
                            ->where('type', 'منتفع')
                            ->first();

                        if (!$supDep) {
                            throw new \Exception("تعذر تحديد الفئة الفرعية للمنتفع");
                        }

                        foreach ($dependents as $dep) {
                            $regnumberDep = $this->generateRegNumber(
                            $benefCatId,
                            $dep['cities_id'] ?? null,
                            $dep['gender'] ?? null,
                            $dep['birthDate'] ?? null
                             );


                            $dependent = new Customer();
                            $dependent->requesttypes_id = 1;
                            $dependent->regnumber = $regnumberDep;
                            $dependent->fullnamea = $dep['name'] ?? null;
                            $dependent->fullnamee = $dep['name_en'] ?? null;
                            $dependent->email = $dep['email'] ?? null;
                            $dependent->phone = $dep['phone'] ?? null;
                            $dependent->gender = $dep['gender'] === 'ذكر' ? 1 : ($dep['gender'] === 'أنثى' ? 2 : null);
                            $dependent->yearbitrh = $dep['birthDate'] ?? null;
                            $dependent->registrationnumbers = $main['registry_number44'] ?? null;
                            $dependent->registrationnumber = encrypt($main['registry_number44']);
                            $dependent->nid = encrypt($dep['nationalID']);
                            $dependent->nationalID = $dep['nationalID'];
                            $dependent->passportnumber = $dep['passport_no'] ?? null;
                            $dependent->nationalities_id = 1;
                            $dependent->beneficiaries_categories_id  = $benefCatId;
                            $dependent->beneficiaries_sup_categories_id = $supDep->id;
                            $dependent->bloodtypes_id = $dep['bloodtypes_id'];
                            $dependent->joptype = 3;
                            $dependent->municipals_id = $dep['municipals_id'];
                            $dependent->nearestpoint  = $dep['nearest_municipal_point33'] ?? null;
                            $dependent->cities_id = $dep['cities_id'];
                            $dependent->socialstatuses_id = $dep['socialstatuses_id'];
                            $dependent->diseasestate = $dep['diseasestate'] ?? null;
                            $dependent->main_customer_id = $customerId;

                            // إن احتجت تربط المؤسسة/الاشتراك للمنتفعين، فعّل السطور التالية:
                            // $dependent->institucion_id  = $request->input('institutionId');
                            // $dependent->subscription_id = $subscriptionId;

                            $dependent->save();
                        }
                    }
                }
            });

            // رسالة نجاح مع عدد المنتفعين المتجاهَلين إن وجد
            $message = 'تم تسجيل المشترك والمشتركين الفرعيين';
            if ($ignoredDependents > 0) {
                $message .= " (تم تجاهل {$ignoredDependents} منتفع/ين بسبب نقص البيانات)";
            }

            Alert::success("تمت عملية التسجيل بنجاح");
            return redirect()->route('customers.show', $customerId)
                ->with('success', $message)
                ->with('message', $message);
        } catch (\Exception $e) {
            // تقدر تفعل الـ dd للتصحيح فقط
            // dd($e->getMessage(), $e->getTraceAsString());
            Alert::error("الرجاء المحاولة مرة اخرى");
            return back()
                ->withErrors(['general' => 'يوجد خطأ في عملية التسجيل: ' . $e->getMessage()])
                ->withInput();
        }
    }


   protected function generateRegNumber($benefCatId, $cityCode, $gender, $yearBirth)
{
    // الخانة الأولى ثابتة
    $first = '0';

    // الخانتين الثانية والثالثة حسب الفئة
    $cat = $benefCatId == 1 ? '00' : '01';

    // المدينة (خانة واحدة فقط)
    $city = str_pad($cityCode ?? 0, 1, '0', STR_PAD_LEFT);

    // الجندر
    $gen = ($gender == 'أنثى' || $gender == 2) ? 2 : 1;

    // آخر رقمين من سنة الميلاد
    $year = $yearBirth ? substr($yearBirth, -2) : '00';

    // الخانة الثامنة ثابتة
    $fixed = '1';

    // 5 أرقام عشوائية
    $rand = random_int(10000, 99999);

    // النتيجة (13 خانة)
    return $first . $cat . $city . $gen . $year . $fixed . $rand;
}





      public function getpendingCustomers()
    {
        $customers = Customer::where('active', 2)
        ->get([
            'subscription_id',
            'iban',
            'bank_branch_id',
            'total_pension',
            'account_no',
            'institucion_id',
            'bank_id',
            'regnumber',
            'uuid',
            'nationalID',
            'active',
            'payment_status',
            'municipals_id',
            'fullnamea'
        ]);     
           return response()->json($customers);
    }


       public function getInactiveCustomers()
    {
        $customers = Customer::where('active', 0)->get();
        return response()->json($customers);
    }
 

public function getpendingCustomerByUuid($uuid)
{
    $customer = Customer::where('uuid', $uuid)
        ->where('active', 2)
        ->first([
            'subscription_id',
            'iban',
            'bank_branch_id',
            'total_pension',
            'account_no',
            'institucion_id',
            'bank_id',
            'regnumber',
            'uuid',
            'nationalID',
            'nid',
            'active',
            'payment_status',
            'municipals_id',
            'fullnamea'
        ]);

    if (!$customer) {
        return response()->json(['message' => 'Customer not found or not active=2'], 404);
    }

    return response()->json($customer);
}


public function getInactiveCustomerByUuid($uuid)
{
    try {
        $blocked = [
            'subscription_id','iban','bank_branch_id','total_pension','pension_no',
            'account_no','insured_no','institucion_id','institution_id','bank_id'
        ];

        $customer = Customer::where('uuid', $uuid)
                            ->where('active', 0)
                            ->with('lastPhoto')
                            ->first();

        if (!$customer) {
            return response()->json([
                'status'  => false,
                'message' => 'Customer not found or not active=0'
            ], 404);
        }

        // إخفاء الحقول غير المرغوبة
        $customer->makeHidden($blocked);

        // معالجة الصورة مثل indexApi
        if ($customer->lastPhoto && $customer->lastPhoto->image) {
            $customer->photo = asset('photo/personalphotos/' . $customer->lastPhoto->image . '.jpeg');
        }
        unset($customer->lastPhoto);

        return response()->json([
            'status' => true,
            'data'   => $customer
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}




public function bulkActivateToTwo(Request $request)
{
    $request->validate([
        'uuids'  => 'required|array',
        'uuids.*' => 'exists:customers,uuid',
    ]);

    $updated = Customer::whereIn('uuid', $request->uuids)
        ->where('active', 0)
        ->update(['active' => 2]);

    return response()->json([
        'message' => "Updated status from 0 to 1 for {$updated} customers successfully"
    ]);
}


public function activateToTwo($uuid)
{
    $customer = Customer::where('uuid', $uuid)->first();

    if (!$customer) {
        return response()->json(['message' => 'Customer not found'], 404);
    }

    if ($customer->active == 0) {
        $customer->active = 2;
        $customer->save();

        return response()->json([
            'message' => 'Customer status updated from 0 to 1 successfully',
            'customer' => $customer
        ]);
    }

    return response()->json([
        'message' => 'Customer is not in state 0, so cannot change to 1'
    ], 400);
}


public function activateAndPayment($uuid, Request $request)
{
    $request->validate([
        'payment_status' => 'required|in:0,1', // نقبل فقط 0 أو 1
    ]);

    $customer = Customer::where('uuid', $uuid)->first();

    if (!$customer) {
        return response()->json(['message' => 'Customer not found'], 404);
    }

    $updated = false;

    // تغيير active من 2 إلى 1
    if ($customer->active == 2) {
        $customer->active = 1;
        $updated = true;
    }

    if ($customer->payment_status != $request->payment_status) {
        $customer->payment_status = $request->payment_status;
        $updated = true;
    }

    if ($updated) {
        $customer->save();
    }

    return response()->json([
        'message' => $updated 
            ? 'Customer updated successfully' 
            : 'No changes applied to customer',
        'customer' => $customer
    ]);
}


public function bulkActivateAndPayment(Request $request)
{
    $request->validate([
        'uuids'  => 'required|array',
        'uuids.*' => 'exists:customers,uuid',
        'payment_status' => 'required|in:1,2',
    ]);

    $customers = Customer::whereIn('uuid', $request->uuids)->get();
    $updatedCustomers = [];

    foreach ($customers as $customer) {
        $updated = false;

        if ($customer->active == 2) {
            $customer->active = 1;
            $updated = true;
        }

        if ($customer->payment_status != $request->payment_status) {
            $customer->payment_status = $request->payment_status;
            $updated = true;
        }

        if ($updated) {
            $customer->save();
            $updatedCustomers[] = $customer;
        }
    }

    return response()->json([
        'message' => "Updated " . count($updatedCustomers) . " customers successfully",
        'customers' => $updatedCustomers
    ]);
}








    // public function lookup(Request $request)
    // {
    //     // return 1111;

    //     // ✅ 1) الفالديشن الأساسي
    //     // $v = Validator::make($request->all(), [
    //     //     'nationalID'         => ['required','string','size:12'],
    //     //     'family_registry_no' => ['required','string'],
    //     // ], [
    //     //     'nationalID.required' => 'الرجاء إدخال الرقم الوطني',
    //     //     'nationalID.size'     => 'الرقم الوطني يجب أن يكون 12 رقم',
    //     //     'family_registry_no.required' => 'الرجاء إدخال رقم القيد العائلي',
    //     // ]);
    //     // if ($v->fails()) {
    //     //     return response()->json([
    //     //         'ok'    => false,
    //     //         'error' => $v->errors()->first(),
    //     //         'errors'=> $v->errors()
    //     //     ], 422);
    //     // }

    //     // // ✅ 2) قراءة البيانات من الفورم
    //     $nationalId     = $request->nationalID;
    //     $registryNumber = $request->family_registry_no;

    //     // ✅ 3) قراءة بيانات الدخول من .env
    //     $email    = 'cra@phif.gov.ly';
    //     $password = 'cra%23@PasS';

    //     try {
    //         // ✅ 4) تسجيل الدخول لجلب التوكن
    //         $login = Http::timeout(10)
    //             ->withOptions(['verify' => false])
    //             ->post('http://10.110.110.90/api/login-api?email=cra@phif.gov.ly&password=cra%23@PasS');


    //         if (!$login->successful()) {
    //             return response()->json([
    //                 'ok' => false,
    //                 'error' => 'Login failed: HTTP ' . $login->status(),
    //                 'body' => $login->body(),
    //             ], 502);
    //         }

    //         $token = data_get($login->json(), 'token');
    //         if (!$token) {
    //             return response()->json([
    //                 'ok' => false,
    //                 'error' => 'Login response has no token',
    //                 'json'  => $login->json(),
    //             ], 502);
    //         }

    //         $resp = Http::timeout(30)
    //             ->withOptions(['verify' => false])
    //             ->withToken($token) // التوكن اللي جبته من /login-api
    //             ->post('http://10.110.110.90/api/Phif-cra', [
    //                 'NationalID'     => $nationalId,
    //                 'RegistryNumber' => $registryNumber,
    //             ]);

    //         // return $resp->json();

    //         if (!$resp->successful()) {
    //             return response()->json([
    //                 'ok' => false,
    //                 'error' => 'CRA call failed: HTTP ' . $resp->status(),
    //                 'body' => $resp->body(),
    //             ], 502);
    //         }

    //         $json = $resp->json();
    //         if (!$json || !data_get($json, 'status')) {
    //             return response()->json([
    //                 'ok' => false,
    //                 'error' => 'CRA response invalid',
    //                 'json' => $json,
    //             ], 422);
    //         }

    //         // ✅ 6) استخراج الأعضاء
    //         $members = data_get($json, 'data.members', []);
    //         $count   = data_get($json, 'data.membersCount', count($members));

    //         // ✅ 7) تنسيق البيانات
    //         $normalized = collect($members)->map(function ($m) {
    //             $birthRaw = data_get($m, 'birthDate');
    //             $birth    = $birthRaw ? explode('T', $birthRaw)[0] : null;
    //             return [
    //                 'nationalID'  => data_get($m, 'nationalID'),
    //                 'name'        => trim((data_get($m, 'arabicFirstName') . ' ' . data_get($m, 'arabicFatherName') . ' ' . data_get($m, 'arabicGrandFatherName') . ' ' . data_get($m, 'arabicFamilyName'))),
    //                 'mother'      => data_get($m, 'arabicMotherName'),
    //                 'birthDate'   => $birth,
    //                 'birthPlace'  => data_get($m, 'birthPlace'),
    //                 'gender'      => (data_get($m, 'gender') == 0 ? 'ذكر' : 'أنثى'),
    //                 'isAlive'     => (data_get($m, 'isALive') === 'Y'),
    //                 'relationship' => data_get($m, 'relationship'),
    //                 'status'      => data_get($m, 'status'),
    //             ];
    //         });

    //         $main = $normalized->firstWhere('nationalID', $nationalId);
    //         $dependents = $normalized->where('nationalID', '!=', $nationalId)->values();

    //         return response()->json([
    //             'ok'         => true,
    //             'count'   => $count,
    //             'main'       => $main,
    //             'dependents' => $dependents,
    //         ]);
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'ok' => false,
    //             'error' => 'Exception: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }







    // public function checksheet(Request $request)
    // {
    //     $messages = [
    //         'phone.required'                  => "الرجاء ادخال رقم الهاتف",
    //         'phone.starts_with'               => "رقم الهاتف يجب أن يبدأ بـ 91 أو 92 أو 93 أو 94 أو 21",
    //         'nationalID.required'             => "الرجاء ادخال رقم الوطني",
    //         // 'otp.required'                    => "الرجاء ادخال الرمز ",
    //         'beneficiariesSupCategories.*'    => 'يرجى اختيار الفئة',
    //         'work_category_id.required_if'    => 'يرجى اختيار نوع جهة العمل للفئات 7 أو 8',
    //         'institution_id.required_if'      => 'يرجى اختيار جهة العمل للفئات 7 أو 8',
    //     ];

    //     $data = $request->validate([
    //         'phone'                      => 'required|digits:9|numeric|starts_with:91,92,94,21,93',
    //         'nationalID'                 => ['required', 'digits:12', 'starts_with:2,1'],
    //         // 'otp'                        => 'required|digits:6',
    //         'beneficiariesSupCategories' => ['required'],
    //         'work_category_id'           => 'required_if:beneficiariesSupCategories,7,8|nullable',
    //         'institution_id'             => 'required_if:beneficiariesSupCategories,7,8|nullable',
    //     ], $messages);

    //     if (Customer::where('phone', $data['phone'])->exists()) {
    //         return back()->withErrors(['phone' => 'رقم الهاتف مسجل مسبقاً'])->withInput();
    //     }

    //     // ==============================
    //     //  OTP — كووولـــه موقوف/معلّق
    //     // ==============================
    //     // $ve = Verification::where('phone', $data['phone'])->first();
    //     // if (!$ve) {
    //     //     return back()->withErrors(['phone' => 'لم يتم إرسال رمز تحقق لهذا الرقم'])->withInput();
    //     // }
    //     // $expiresAt = \Carbon\Carbon::parse($ve->otp_time)->addMinutes(10);
    //     // if (now()->gte($expiresAt)) {
    //     //     return back()->withErrors(['otp' => 'لقد انتهت صلاحية رمز التحقق'])->withInput();
    //     // }
    //     // if ((string)$ve->otp !== (string)$data['otp']) {
    //     //     return back()->withErrors(['otp' => 'رمز التحقق غير صحيح الرجاء التأكد'])->withInput();
    //     // }

    //     // الشــيــت فقط
    //     $sheetMatch = null;
    //     $needsInstitution = in_array((string)$data['beneficiariesSupCategories'], ['7', '8'], true);

    //     if ($needsInstitution) {
    //         $sheetMatch = InstitucionSheetRow::where('national_id', $data['nationalID'])
    //             ->where('institucion_id', $data['institution_id'])
    //             ->first();

    //         if (!$sheetMatch) {
    //             return back()
    //                 ->withErrors(['institution_id' => 'لم يتم العثور على بيانات مطابقة في الشيت (الرقم الوطني + جهة العمل)'])
    //                 ->withInput();
    //         }
    //     }

    //     // رجوع بنفس الصفحة بالقيم
    //     return back()->with([
    //         'verified_ok'   => true,
    //         'sheetMatch'    => $sheetMatch,
    //         'insured_no'    => $sheetMatch?->insured_no,
    //         'pension_no'    => $sheetMatch?->pension_no,
    //         'account_no'    => $sheetMatch?->account_no,
    //         'total_pension' => $sheetMatch?->total_pension,
    //     ])->withInput();
    // }


















    public function sendotp($phone)
    {

        $customer = Customer::where('phone', $phone)->first();

        // dd($customer);
        if ($customer != null) {
            //dd($customer);
            return response()->json(['message' => 'رقم الهاتف مسجل مسبقا'], 500);
        }

        $otp = mt_rand(100000, 999999);
        $vendor = substr($phone, 1, 1);
        if ($vendor != "1" && $vendor != "3") {
            $states = $this->sms2->sendSms((string) $phone, $otp)->successful();
        } else {
            $states = $this->sms->sendSms((string) $phone, $otp)->successful();
        }

        if ($states) {
            $ve = Verification::where('phone', $phone)->first();

            if ($ve) {
                DB::transaction(function () use ($ve, $otp) {
                    $ve->otp = $otp;
                    $ve->otp_time = now();
                    $ve->save();
                });
            } else {
                DB::transaction(function () use ($otp, $phone) {

                    $Ve = new Verification();
                    $Ve->otp = $otp;
                    $Ve->phone = $phone;
                    $Ve->otp_time = now();
                    $Ve->save();
                });
            }
            return response()->json(1);
        } else {
            return response()->json(5);
        }
    }

    public function confirm(Request $request)
    {
        //   dd($request);
        $messages = [
            'phone.required' => "الرجاء ادخال رقم الهاتف",
            'otp.required' => "الرجاء ادخال الرمز ",
            'captcha.required' => "الرجاء ادخال captcha ",
            'captcha.captcha' => "الرجاء التحقق من captcha",
            'nationalID.required' => "الرجاء ادخال رقم الوطني",
            'nationalID.unique' => "االرقم الوطني مستخدم من قبل",

        ];

        $this->validate($request, [
            'phone' => 'required|digits_between:9,9|numeric|starts_with:91,92,94,21,93 ',
            'otp' => 'required|digits_between:6,6',
            'nationalID' => ['required', 'unique:customers', 'digits_between:12,12', 'starts_with:2,1'],
            'captcha' => 'required|captcha',


        ], $messages);


        $sal = Sal::where('nid', $request->nationalID)->get();


        $exist = Customer::where('phone')->get();
        if ($sal->count() == 0 || $exist->count() > 0) {
            if (sizeof($exist)) {
                Alert::error("هدا الرقم مسجل الرجاء التاكد");
            } else {

                //Alert::error("هذا الرقم لا يمكنه الاستفادة من الخدمة");
                return view('frontend.complete-data');
            }
            return redirect()->route('/');
        } else {
            try {
                $ve = Verification::where('phone', $request->phone)->first();

                $trget = Carbon::parse($ve->otp_time)->addMinute(2);
                $result = now()->gte($trget);
                if ($result == true) {
                    $ve->otp = mt_rand(100000, 999999);
                    $ve->save();
                    Alert::error('لقد انتهت صلاحية رمز التحقق');
                    return redirect()->route('/');
                } else {
                    if ($ve->otp == $request->otp) {
                        $ve->save();

                        Alert::success('تمت عملية التحقق بنجاح');
                        Session::put('phone', $request->phone);
                        Session::put('nationalID', $request->nationalID);

                        //codehere
                        return redirect(route('complete-form'));
                    } else {
                        Alert::error('رمز التحقق غير صحيح الرجاء التأكد');
                        return redirect()->route('/');
                    }
                }
            } catch (\Exception $e) {
                Alert::error("الرجاء المحاولة مرة اخرى");

                return back();
                return redirect()->route('/');
            }
        }
    }




    public function municipals($id)
    {
        if (strlen((string) $id) < 5) {
            $id = encrypt($id);
        }
        try {
            $idd = decrypt($id);
            $Municipal = Municipal::with(['cities'])->select('*')->where('cities_id', $idd)->orderBy('name', 'ASC')->get();
            return response()->json($Municipal);
        } catch (DecryptException $e) {
            abort(404, $e . 'هذه الصفحة غير موجودة');
        }
    }







    private function checkRegistryWithCRA($nationalId, $localRegistryNumber)
    {
        try {
            // تسجيل الدخول
            $login = Http::withOptions(['verify' => false])
                ->post('http://10.110.110.90/api/login-api?email=cra@phif.gov.ly&password=cra%23@PasS');

            if (!$login->successful()) {
                return false;
            }

            $token = data_get($login->json(), 'token');

            // التحقق
            $resp = Http::withOptions(['verify' => false])
                ->withToken($token)
                ->post('http://10.110.110.90/api/Phif-cra', [
                    'NationalID'     => $nationalId,
                    'RegistryNumber' => $localRegistryNumber,
                ]);

            if (!$resp->successful()) {
                return false;
            }

            $json = $resp->json();

            return data_get($json, 'status') === true;
        } catch (\Throwable $e) {
            \Log::error("CRA check error: " . $e->getMessage());
            return false;
        }
    }
    public function searchEditForm()
    {
        return view('customers.searchEdit');
    }
    public function searchEdit(Request $request)
    {
        $request->validate([
            'regnumber' => 'required|numeric',
        ], [
            'regnumber.required' => 'الرجاء إدخال الرقم التأميني',
        ]);

        $regnumber = $request->input('regnumber');

        $customer = Customer::where('regnumber', $regnumber)->first();

        if (!$customer) {
            return back()->withErrors(['regnumber' => 'المشترك غير موجود']);
        }

        $apiOk = $this->checkRegistryWithCRA($customer->nationalID, $customer->registrationnumbers);

        if (!$apiOk) {
            return back()->withErrors(['regnumber' => 'رقم القيد غير مطابق لبيانات مصلحة الأحوال']);
        }

        return redirect()->route('customer.edit', $customer->id);
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        $socialstatuses   = Socialstatus::all();
        $bloodtype        = Bloodtype::all();
        $cities             = City::all();
        $warrantyOffices  = Warrantyoffice::all();
        $guarantyBranches = Guarantybranch::all();

        return view('customers.edit', compact(
            'customer',
            'socialstatuses',
            'bloodtype',
            'cities',
            'warrantyOffices',
            'guarantyBranches'
        ));
    }

   public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $this->validate($request, [
            'email' => [
                'nullable',
                'string',
                'email',
                'max:50',
                // Rule::unique('customers', 'email')->ignore($customer->id)
            ],
            'phone' => [
                'required',
                'digits:9',
                'numeric',
                'starts_with:91,92,93,94,21',
                // Rule::unique('customers', 'phone')->ignore($customer->id)
            ],
        ]);

        // الحقول الأساسية
        $customer->email             = $request->email;
        $customer->phone             = $request->phone;
        $customer->gender            = $request->gender;
        $customer->yearbitrh         = $request->birthDate;
        $customer->bloodtypes_id     = $request->bloodtypes_id;
        $customer->socialstatuses_id = $request->socialstatuses_id;

        // المنطقة + البلدية + أقرب نقطة
        $customer->cities_id         = $request->cities_id;
        $customer->municipals_id     = $request->municipals_id;
        $customer->nearestpoint      = $request->nearestpoint;

        // بيانات المصرف
        $customer->bank_id           = $request->bank_id;
        $customer->bank_branch_id    = $request->bank_branch_id;
        $customer->iban              = $request->iban;

        // الحقول المالية (اختيارية)
        if ($request->filled('total_pension')) {
            $customer->total_pension = $request->total_pension;
        }
        if ($request->filled('pension_no')) {
            $customer->pension_no = $request->pension_no;
        }
        if ($request->filled('account_no')) {
            $customer->account_no = $request->account_no;
        }
        if ($request->filled('insured_no')) {
            $customer->insured_no = $request->insured_no;
        }

        $customer->save();

        Alert::success("تم تحديث البيانات بنجاح");
        return redirect()->route('home');
    }


    public function query($regnumber)
    {

        $reg = decrypt($regnumber);
        $recustomers = retired::whereHas('customers', function ($query) use ($reg) {
            $query->where('regnumber', $reg);
        })->with([
            'customers',
            'customers.cities',
            'customers.municipals',
            'customers.socialstatuses',
            'customers.nationalities',
            'customers.bloodtypes',
            'customers.requesttypes'
        ])->first();
        if (!empty($recustomers)) {

            $pastvisit = medicalHistoryProfile::where('inc_id', $reg)->first();
            // dd($pastvisit);
            return view('frontend.query')->with('recustomers', $recustomers)->with('examination', $pastvisit);
        } else {
            Alert::error("انت غير مسجل الرجاء التأكد من الرقم");

            return redirect(route('/'));
        }
    }




    public function searchForm()
    {
        return view('customers.search');
    }

    protected function eagerWith(): array
    {
        return [
            'beneficiariesCategoryRelation',   // الجديدة
            'beneficiariesSupCategoryRelation',// الجديدة
            'socialstatuses',
            'bloodtypes',
            'cities',
        'municipals',
            'institucion',
            'bank',
            'bankBranch',
            'subscription.beneficiariesCategory',
            'subscription.values.type',


        ];
    }


    public function searchUnified(Request $request)
    {
        $raw = $request->only(['nationalID', 'regnumber', 'phone']);
        $data = array_map(function ($v) {
            return is_string($v) ? trim($v) : $v;
        }, $raw);

        if (!empty($data['phone'])) {
            $p = preg_replace('/\D+/', '', $data['phone']);     // إبقاء الأرقام فقط
            $p = preg_replace('/^(00218|218)/', '', $p);         // إزالة مقدمة الدولة
            $p = preg_replace('/^0/', '', $p);                   // إزالة الصفر الأول إن وُجد
            $data['phone'] = $p;
        }

        $filled = collect($data)->filter(fn ($v) => filled($v));
        if ($filled->count() === 0 || $filled->count() > 1) {
            $errorMsg = $filled->count() === 0
                ? 'أدخل الرقم الوطني أو الرقم التأميني أو رقم الهاتف.'
                : 'من فضلك املأ خانة واحدة فقط.';
            return response()->json(['status' => 'error', 'message' => $errorMsg]);
        }

        $field = $filled->keys()->first();
        $rules = [];
        $messages = [
            'nationalID.regex' => 'الرقم الوطني يجب أن يتكون من 12 رقمًا.',
            'regnumber.regex'  => 'الرقم التأميني يجب أن يتكون من 13 رقمًا.',
            'phone.regex'      => 'رقم الهاتف يجب أن يبدأ بـ 91 أو 92 أو 93 أو 94 ويكون 9 أرقام فقط.',
        ];

        if ($field === 'nationalID') {
            $rules['nationalID'] = ['regex:/^\d{12}$/'];
        } elseif ($field === 'regnumber') {
            $rules['regnumber'] = ['regex:/^\d{13}$/'];
        } elseif ($field === 'phone') {
            // 9 أرقام: (91|92|93|94) + 7 أرقام
            $rules['phone'] = ['regex:/^(91|92|93|94)\d{7}$/'];
        }

        Validator::make($data, $rules, $messages)->validate();

        // 4) تنفيذ البحث
        $field = $filled->keys()->first();     // أعد التعيين بعد التطبيع
        $value = $data[$field];

        $customer = \App\Models\Customer::with($this->eagerWith())
            ->where($field, $value)
            ->first();

        if (!$customer) {
            return response()->json(['status' => 'error', 'message' => 'لم يتم العثور على مشترك بهذه البيانات.']);
        }

        // (اختياري) تأكيد تحميل الاشتراك وعلاقاته لو ما كانت ضمن eagerWith
        $customer->loadMissing([
            'subscription.beneficiariesCategory',
            'subscription.values.type',
        ]);

        return response()->json([
            'status'   => 'success',
            'customer' => $customer,

        ]);
    }





    // فتح صفحة البحث
    public function showLookupForm()
    {
        return view('customers.lookup');
    }




public function doLookup(Request $request)
{
    $raw = $request->only(['nationalID', 'regnumber']);
    $data = array_map(fn($v) => is_string($v) ? trim($v) : $v, $raw);

    $filled = collect($data)->filter(fn($v) => filled($v));
    if ($filled->count() === 0 || $filled->count() > 1) {
        $errorMsg = $filled->count() === 0
            ? 'أدخل الرقم الوطني أو الرقم التأميني.'
            : 'من فضلك املأ خانة واحدة فقط.';
        return response()->json(['status' => 'error', 'message' => $errorMsg]);
    }

    $field = $filled->keys()->first();
    $value = $data[$field];

    $customer = Customer::where($field, $value)->first();

    if (!$customer) {
        return response()->json(['status' => 'error', 'message' => 'لم يتم العثور على مشترك بهذه البيانات.']);
    }

    // نرسل OTP
    if ($customer->phone) {
        $this->sendOtps(new Request(['phone' => $customer->phone]));
    }

    return response()->json([
        'status'   => 'success',
        'needOtp'  => true,
        'customer' => ['id' => $customer->id, 'phone' => $customer->phone],
    ]);
}



public function verifyOtp(Request $request)
{
    // 1) هات البيانات من الطلب
    $phone = $this->normalizePhone($request->input('phone'));
    $otp   = trim((string) $request->input('otp'));

    // 2) هات آخر OTP متخزن لهاتف معين
    $ver = \App\Models\Verification::where('phone', $phone)
        ->latest() // ياخد أحدث سطر
        ->first();

    // 3) تحقق
    if (!$ver) {
        return response()->json(['success' => false, 'message' => 'لم يتم العثور على رمز تحقق لهذا الرقم']);
    }

    if (trim((string) $ver->otp) !== $otp) {
        return response()->json(['success' => false, 'message' => 'OTP غير صحيح']);
    }

    // 4) هات بيانات المشترك بكل العلاقات
    $customer = \App\Models\Customer::with($this->eagerWith())
        ->where('phone', $phone)
        ->first();

    if (!$customer) {
        return response()->json(['success' => false, 'message' => 'المشترك غير موجود']);
    }

    // 5) لو كل شي تمام رجع الداتا
    return response()->json([
        'success'  => true,
        'customer' => $customer,
    ]);
}

/**
 * 🔧 دالة مساعدة لتوحيد شكل رقم الهاتف
 */
protected function normalizePhone($p)
{
    $p = preg_replace('/\D+/', '', $p);         // خلي بس أرقام
    $p = preg_replace('/^(00218|218)/', '', $p); // شيل مقدمة الدولة
    $p = preg_replace('/^0/', '', $p);           // شيل صفر البداية
    return $p;
}






    // الطباعة
    public function printCard(Customer $customer)
    {
        return view('customers.fakad', compact('customer'));
    }








    public function show($id)
{
    $customer = Customer::with('dependents')->findOrFail($id);

    return view('customers.show', [
        'customer'   => $customer,
        'dependents' => $customer->dependents,
    ]);
}



    public function printOne(Customer $customer)
        {
            // نجهز الواجهة كـ HTML لمشترك واحد

            $user = auth()->user();
        $agentName = optional($user->insuranceAgents()->first())->name;
            $html = view('customers.print-one', compact('customer','agentName'))->render();

            // إعداد mPDF مع الخط العربي (Tajawal)
            $mpdf = new \Mpdf\Mpdf([
                'tempDir' => public_path('tmp'),
                'fontDir' => [
                    public_path('/fonts'),
                ],
                'fontdata' => [
                    'tajawal' => [
                        'R' => 'Tajawal-Normal.ttf',
                        'B' => 'Tajawal-Bold.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ]
                ],
                'default_font' => 'tajawal'
            ]);

            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont   = true;

            // كتابة محتوى البليد
            $mpdf->WriteHTML($html);

            // عرض مباشرة في المتصفح باسم الملف
            return $mpdf->Output("customer_{$customer->id}.pdf", 'I');
        }


public function fakad(Customer $customer)
{
    $user      = auth()->user();
    $agentName = optional($user->insuranceAgents()->first())->name;

    // ✅ نحاول نسجل الخدمة
    $this->logReplacementIfFirstIn30Days($customer, 2);

    // 👇 تجهيز PDF
    $html = view('customers.fakad', compact('customer','agentName'))->render();

    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => public_path('tmp'),
        'fontDir' => [
            public_path('/fonts'),
        ],
        'fontdata' => [
            'tajawal' => [
                'R' => 'Tajawal-Normal.ttf',
                'B' => 'Tajawal-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ]
        ],
        'default_font' => 'tajawal'
    ]);

    $mpdf->autoScriptToLang = true;
    $mpdf->autoLangToFont   = true;

    $mpdf->WriteHTML($html);

    return $mpdf->Output("customer_{$customer->id}.pdf", 'I');
}





protected function logReplacementIfFirstIn30Days(Customer $customer, int $serviceId = 2): bool
{
    return DB::transaction(function () use ($customer, $serviceId) {
        $already = ServiceLog::where('customer_id', $customer->id)
            ->where('service_id', $serviceId)
            ->where('created_at', '>=', now()->subDays(30))
            ->exists();

        if ($already) {
            return false;
        }

        $user = auth()->user();

        // ✅ نجيب الوكيل المرتبط باليوزر
        $insuranceAgentId = optional($user->insuranceAgents()->first())->id;

        ServiceLog::create([
            'customer_id'       => $customer->id,
            'user_id'           => $user->id,
            'service_id'        => $serviceId,
            // 'institucion_id'    => $customer->institucions_id ?? null,
            // 'insurance_agent_id'=> $insuranceAgentId, // نخزنه هنا باش نبعثه للـ API
        ]);

        // 🚀 إرسال للـ API
        // if ($insuranceAgentId) {
        //     Http::post('https://external-system/api/services', [
        //         'customer_id'        => $customer->id,
        //         'insurance_agent_id' => $insuranceAgentId,
        //         'service_id'         => $serviceId,
        //         'date'               => now()->toDateString(),
        //     ]);
        

        return true;
    });
}









public function printAll(Customer $customer)
{
    // نحدد هل هو أساسي أو منتفع
    if ($customer->main_customer_id === null) {
        // أساسي → نجيب هو + منتفعينه
        $all = Customer::where('main_customer_id', $customer->id)
            ->orWhere('id', $customer->id)
            ->get();
    } else {
        // منتفع → نجيب الأساسي + باقي المنتفعين
        $main = $customer->mainCustomer;
        $all = Customer::where('main_customer_id', $main->id)
            ->orWhere('id', $main->id)
            ->get();
    }

    // نجهز الواجهة كـ HTML
    $html = view('customers.print-all', compact('all'))->render();

    // إعداد mPDF مع الخط العربي
    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => public_path('tmp'),
        'fontDir' => [
            public_path('/fonts'),
        ],
        'fontdata' => [
            'tajawal' => [
                'R' => 'Tajawal-Normal.ttf',
                'B' => 'Tajawal-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ]
        ],
        'default_font' => 'tajawal'
    ]);

    $mpdf->autoScriptToLang = true;
    $mpdf->autoLangToFont   = true;

    // كتابة محتوى البليد
    $mpdf->WriteHTML($html);

    // عرض مباشرة في المتصفح
    return $mpdf->Output("customers_group_{$customer->id}.pdf", 'I');
}







    public function searchBeneficiary()
    {

        return view('customers.search');
    }
    public function getbeneficiaries($id)
    {

        $customer = Customer::where('regnumber', $id)->orWhere('phone', $id)->orWhere('nationalID', $id)->orWhere('registrationnumbers', $id)->first();
        $customer = Customer::where('registrationnumbers', $customer->registrationnumbers)->get();

        return datatables()->of($customer)
            ->addColumn('type', function ($customer) {
                return beneficiariesSupCategories::find($customer->beneficiaries_sup_categories_id)->type;
            })->rawColumns(['type'])->make(true);
    }
    public function beneficiaries(Request $request)
    {
        // dd($request);

        return view('customers.index', ['data' => $request->regnumber]);
    }
    private $token;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */




    public function getToken()
    {



        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://sso.ndb.gov.ly/connect/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&scope=nid&client_id=LHI&client_secret=Nhq*7f0#IS6o0wf@RT1wCN#w@56unRx1Qhq3cd7");

        $headers = array();
        $headers = ['Content-Type: application/x-www-form-urlencoded'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return  $token = json_decode($result);
        return $token->access_token;
    }


    public function test222()
    {
        return 3333;
    }

    public function getinfo($nid)
    {

        if ($nid == 119950012329)
            return $array = [
                "quidNumber" => "213889",
                "birthPlace" => "طرابلس",
                "birthDate" => "1995-12-24T00:00:00",
                "surName" => "التقاز",
                "grandFatherName" => "عمر",
                "fatherName" => "ابوالقاسم",
                "firstName" => "سند",
                "nationalID" => "119950012329",
                "isLife" => true
            ];

        return $this->getNidData($nid);
    }

    public function getinfoEN($nid)
    {
        return $this->getNidEnData($nid);
    }

    public function getNidData($nid)
    {


        $nid = ['nid' => $nid];
        $nid = json_encode($nid);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://nid.ndb.gov.ly/search/byNid');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nid);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->getToken();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        return $result = json_decode($result);
    }

    public function getNidEnData($nid)
    {


        $nid = ['nid' => $nid];
        $nid = json_encode($nid);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://nid.ndb.gov.ly/search/byNiden');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nid);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->getToken();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        return $result = json_decode($result);
    }
    }
