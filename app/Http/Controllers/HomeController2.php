<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\NidController;
use App\Models\Bloodtype;
use App\Models\Chronicdiseases;
use App\Models\City;
use App\Models\Customer;
use App\Models\CustomerAudit;
use App\Models\medicalexamination;
use App\Models\medicalHistoryProfile; //add this instead of medicalexamination
use App\Models\guarantybranch;
use App\Models\Healthfacilities;
use App\Models\Medicalprofile;
use App\Models\Municipal;
use App\Models\Nationality;
use App\Models\retired;
use App\Models\Retiredfile;
use App\Models\Socialstatus;
use App\Models\Verification;
use App\Models\SalNumber as Sal;
use App\Models\dead_retirees;
use App\Models\salaryNumber;
use App\Models\beneficiariesCategories;
use App\Models\beneficiariesSupCategories;
use App\Models\Warrantyoffice;
use App\Models\InsuranceAgentCompany;

use App\Services\CustomerretiredService;
use App\Services\SmsApiServiceLibyana;
use Carbon\Carbon;
use Complex\Functions;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Session;
use App\Services\SmsApiServiceMadar;

class HomeController extends Controller
{

    private $sms;
    private $sms2;
    private $nid;
    public $data;
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

    public function registerCustomerByAdmin()
    {
   
        $beneficiariesSupCategories = beneficiariesSupCategories::where('beneficiaries_categories_id' , 1)->get();
        return view('frontend.registerCustomer', ['beneficiariesSupCategories' => $beneficiariesSupCategories]);

    }

     public function registerCustomerByAdmin2()
    {

        $InsuranceAgentCompany = InsuranceAgentCompany::all();
        return view('dashbord.InsuranceAgents2.registerCustomer', ['beneficiariesSupCategories' => $InsuranceAgentCompany]);

    }


    public function RegisterBeneficiary()
    {

        $beneficiariesSupCategories = beneficiariesSupCategories::where('beneficiaries_categories_id', 2)->get();
        return view('frontend.RegisterBeneficiary.index', ['beneficiariesSupCategories' => $beneficiariesSupCategories]);

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

        $beneficiariesSupCategories = beneficiariesSupCategories::where('beneficiaries_categories_id' , 3)->get();
        return view('frontend.registerCustomerTum', ['beneficiariesSupCategories' => $beneficiariesSupCategories]);

    }
    
    
        public function checkIdentity(Request $request)
    {
        // dd($request->all());

        $messages = [
            'nationalID.required' => "الرجاء ادخال رقم الوطني"
        ];
        $this->validate($request, [
            'nationalID' => ['required', 'unique:customers', 'digits_between:12,12', 'starts_with:2,1'],
        ], $messages);

        $warrantynumber = null;

        $customer = Customer::where('phone', $request->phone)->first();

        $nidAr = $this->nid->getNidData($request->nationalID);
        $nidEn = $this->nid->getNidEnData($request->nationalID);
        $nationality = Nationality::orderBy('name', 'ASC')->get();
        $bloodtype = Bloodtype::orderBy('name', 'ASC')->get();
        $city = City::orderBy('name', 'ASC')->get();
        $socialstatuses = Socialstatus::orderBy('name', 'ASC')->get();
        $beneficiariesSupCategories = beneficiariesSupCategories::all();
        // if($){}

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

                $Customer = null;

                if ($request->beneficiariesSupCategories == 2) {
                    if ($nidAr->nationalID[0] != 2) {

                        Alert::error(' نأسف لايمكنك الاستفادة من الخدمة ');
                        return redirect()->back();

                    }
                    $Customer = Customer::where('registrationnumbers', $nidAr->quidNumber)->where('gender', 1)->first();
                    if (!isset($Customer)) {
                        Alert::error(' المشترك الرئيسي غير مسجل ');
                        return redirect()->back();
                    }
                }
                if ($request->beneficiariesSupCategories == 3) {
                    if ($nidAr->nationalID[0] != 2) {
                        Alert::error(' نأسف لايمكنك الاستفادة من الخدمة ');
                        return redirect()->back();
                    }

                    $warrantynumber = dead_retirees::where('registrationnumbers', $nidAr->quidNumber)->first();

                    //  if(isset($warrantynumber)) 1; else Alert::error(' نأسف لايمكنك الاستفادة من الخدمة ');  return redirect()->back(); 

                }




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
                    ->with('healthfacilities', $healthfacilities)
                    ->with('beneficiariesSupCategories', $beneficiariesSupCategories)
                    ->with('beneficiariesSupCategoriesType', $request->beneficiariesSupCategories)
                    ->with('Customer', $Customer)
                    ->with('warrantynumber', $warrantynumber);

            } else {
                Alert::error('لايمكنك الاستفادة من الخدمة ');
                return redirect()->back();
            }
        } catch (Exception $e) {
            Alert::error('لايمكنك الاستفادة من الخدمة ');
            return redirect()->back();

        }

    }


    public function checkCustomersIdentity(Request $request)
    {
        // dd($request->all());

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

        $warrantynumber = null;

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
        $beneficiariesSupCategories = beneficiariesSupCategories::all();
        // if($){}

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

                $Customer = null;

                if ($request->beneficiariesSupCategories == 2) {
                    if ($nidAr->nationalID[0] != 2) {

                        Alert::error(' نأسف لايمكنك الاستفادة من الخدمة ');
                        return redirect()->back();

                    }
                    $Customer = Customer::where('registrationnumbers', $nidAr->quidNumber)->where('gender', 1)->first();
                    if (!isset($Customer)) {
                        Alert::error(' المشترك الرئيسي غير مسجل ');
                        return redirect()->back();
                    }
                }
                if ($request->beneficiariesSupCategories == 3) {
                    if ($nidAr->nationalID[0] != 2) {
                        Alert::error(' نأسف لايمكنك الاستفادة من الخدمة ');
                        return redirect()->back();
                    }

                    $warrantynumber = dead_retirees::where('registrationnumbers', $nidAr->quidNumber)->first();

                    //  if(isset($warrantynumber)) 1; else Alert::error(' نأسف لايمكنك الاستفادة من الخدمة ');  return redirect()->back(); 

                }




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
                    ->with('healthfacilities', $healthfacilities)
                    ->with('beneficiariesSupCategories', $beneficiariesSupCategories)
                    ->with('beneficiariesSupCategoriesType', $request->beneficiariesSupCategories)
                    ->with('Customer', $Customer)
                    ->with('warrantynumber', $warrantynumber);

            } else {
                Alert::error('لايمكنك الاستفادة من الخدمة ');
                return redirect()->back();
            }
        } catch (Exception $e) {
            Alert::error('لايمكنك الاستفادة من الخدمة ');
            return redirect()->back();

        }

    }



    public function checkBeneficiaryIdentity(Request $request)
    {
        // dd($request->all());
        $warrantynumber = null;

        if ($request->beneficiariesSupCategories == 4) {

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
                    // Alert::error('لقد انتهت صلاحية رمز التحقق');
                    // return redirect()->route('RegisterBeneficiary');

                } else {

                    if ($ve->otp == $request->otp) {
                        $ve->save();
                        Alert::success('تمت عملية التحقق بنجاح');

                    } else {
                        Alert::error('رمز التحقق غير صحيح الرجاء التأكد');
                        return redirect()->route('RegisterBeneficiary');
                    }

                }

            }

        }

        // return 123;
        if ($request->beneficiariesSupCategories == 5) {
             $messages = [
                'nationalID.required' => "الرجاء ادخال رقم الوطني",
                'nationalID.unique' => "الرقم الوطني مستخدم من قبل",
            ];

            $this->validate($request, [
                'nationalID' => ['required', 'unique:customers', 'digits_between:12,12', 'starts_with:2,1'],
            ], $messages);
            $Customer = Customer::where('regnumber', $request->regnumber)->where('active', 1)->first();
            if (!$Customer) {
                Alert::error(' لايوجد مشترك رئيسي ');
                return redirect()->back();
            }
        } else {
            $Customer = null;
        }

        $nidAr = $this->nid->getNidData($request->nationalID);
        $nidEn = $this->nid->getNidEnData($request->nationalID);


        try {

            if ($nidAr == "Nid Not Found!") {
                Alert::error('هذا الرقم غير موجود الرجاء التأكد');
                return redirect()->back();
            } else if ($nidAr->isLife) {
                $birtdateandtiem = explode("T00", $nidAr->birthDate);
                $birtdate = $birtdateandtiem['0'];
                $gendertype = substr($nidAr->nationalID, 0, 1);

                $age = $this->countAge($birtdate) > 24 && $gendertype == 2;

                if ($this->countAge($birtdate) > 24 && ($gendertype == 1 && $request->beneficiariesSupCategories == 5)) {
                    Alert::error('تجاوز السن المحدد للمنتفع');
                    return redirect()->back();
                }

                $nationality = Nationality::orderBy('name', 'ASC')->get();
                $bloodtype = Bloodtype::orderBy('name', 'ASC')->get();
                $city = City::orderBy('name', 'ASC')->get();
                $socialstatuses = Socialstatus::orderBy('name', 'ASC')->get();
                $beneficiariesSupCategories = beneficiariesSupCategories::all();
                // if($){}

                // warrantynumber .. retired
                $warrantyoffices = Warrantyoffice::orderBy('name', 'ASC')->get();
                $healthfacilities = Healthfacilities::orderBy('name', 'ASC')->get();
                $guarantybranch = guarantybranch::orderBy('name', 'ASC')->get();
                $Chronicdiseases = Chronicdiseases::all();
                $fullNameArabic = $nidAr->firstName . ' ' . $nidAr->fatherName . ' ' . $nidAr->grandFatherName . ' ' . $nidAr->surName;
                $fullNameEnglish = $nidEn->FirstNameEn . ' ' . $nidEn->FatherNameEn . ' ' . $nidEn->GrandFatherNameEn . ' ' . $nidEn->SurNameEn;

// return $Customer;
                return view('frontend.RegisterBeneficiary.register')
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
                    ->with('healthfacilities', $healthfacilities)
                    ->with('beneficiariesSupCategories', $beneficiariesSupCategories)
                    ->with('beneficiariesSupCategoriesType', $request->beneficiariesSupCategories)
                    ->with('Customer', $Customer)
                    ->with('warrantynumber', $warrantynumber)
                    ->with('age', $age);

            } else {
                Alert::error('لايمكنك الاستفادة من الخدمة ');
                return redirect()->back();
            }
        } catch (Exception $e) {
            Alert::error('لايمكنك الاستفادة من الخدمة ');
            return redirect()->back();

        }

    }


    public function countAge($birtdate)
    {

        $birthDate = new \DateTime($birtdate); // Replace with the actual birth date
        $currentDate = new \DateTime();
        return $age = $currentDate->diff($birthDate)->y;

    }

    public function saveCustomersByAdmin2(Request $request)
    {

        dd($request->all());
        $warrantyoffices_id_id = $request->warrantyoffices_id;

        $warrantynumber = Warrantyoffice::find($warrantyoffices_id_id);
        // dd( strlen($guarantybranch->code));
        if (strlen($warrantynumber->code) == 2) {
            $grcode = '0' . $warrantynumber->code;
        } else {
            $grcode = $warrantynumber->code;
        }

        $messages = [
            // 'fullnamea.required' => "الرجاء ادخال الأسم باللغة العربية",
            // 'fullnamee.required' => "الرجاء ادخال  الاسم باللغة الانجليزية",
            // 'email.required' => "الرجاء ادخال  البريد الإلكتروني ",
            'phone.required' => "الرجاء ادخال رقم الهاتف",
            'gender.required' => "الرجاء اختيار نوع الجنس  ",
            // 'yearbitrh.required' => "الرجاء اختيار سنة الميلاد ",
            // 'registrationnumber.required' => "الرجاء ادخال  رقم القيد",
            // 'nid.required' => "الرجاء ادخال رقم الوطني",
            'passportnumber.required' => "الرجاء ادخال رقم الجواز",
            // 'nationalities_id.required' => "الرجاء   اختيار الجنسية",
            'bloodtypes_id.required' => "الرجاء  اختيار فصيلة الدم ",
            'joptype.required' => "الرجاء ادخال نوع العمل  ",
            'municipals_id.required' => "الرجاء اختار البلدية",
            'nearestpoint.required' => "الرجاء  ادخل عنوان اقرب نقطة دالة ",
            'cities_id.required' => "الرجاء   اختيار المنطقة الصحية",
            'phone.unique' => "رقم الهاتف مستخدم من قبل",
            'warrantynumber.required' => "الرجاء ادخال رقم المعاش",
            'warrantyoffices_id.required' => "الرجاء اختر  مكتب الضمان     ",
            'guarantybranches_id.required' => "الرجاء  اختر  الفرع      ",
            'warrantynumber.starts_with' => "الرجاء التأكد من رقم المعاش",
            'chronicdiseases_id.*.required' => "من فضلك اختر المرض المزمن   ",
            // 'product.*.required' => "من فضلك ادخل    الدواء",

        ];
        $this->validate($request, [
            // 'fullnamea' => ['required', 'string', 'unique:customers'],
            // 'fullnamee' => ['required', 'string', 'unique:customers'],
            'email' => ['nullable', 'string', 'email', 'max:50', 'unique:customers'],
            'phone' => 'required|digits_between:9,9|numeric|starts_with:91,92,94,21,93|unique:customers',
            'gender' => ['required', 'string'],
            'yearbitrh' => ['required'],
            // 'registrationnumber' => ['required', 'digits_between:5,7'],
            // 'nid' => ['required', 'unique:customers', 'digits_between:12,12', 'starts_with:' . $gender . $year],
            'passportnumber' => ['required', 'unique:customers'],
            // 'nationalities_id' => ['required'],
            'bloodtypes_id' => ['required'],
            'joptype' => ['required'],
            'municipals_id' => ['required'],
            'nearestpoint' => ['required'],
            'cities_id' => ['required'],
            'socialstatuses_id' => ['required'],
            'warrantyoffices_id' => ['required', 'string'],
            'guarantybranches_id' => ['required', 'string'],
            // 'warrantynumber' => ['required', 'unique:retireds', 'digits_between:11,11',$grcode],
            'warrantynumber' => ['required', 'unique:retireds', 'digits_between:11,11', 'starts_with:2' . $grcode . ',3' . $grcode . ',4' . $grcode . ',5' . $grcode],
            "chronicdiseases_id.*" => "required|string|distinct|min:3",
        ], $messages);

        $cityCode = City::find($request->cities_id);
        $regnumber = $cityCode->code . $request->gender . substr($request->yearbitrh, '2', '2') . '01' . random_int(10000, 99999);
        $checkData = Customer::where("regnumber", $regnumber)->first();



        if (isset($checkData)) {
            do {
                $number = random_int(10000, 99999);
                $regnumber = $cityCode->code . $request->gender . substr($request->yearbitrh, '2', '2') . '01' . $number;
            } while (Customer::where("regnumber", $regnumber)->first());
        }

        // check and update Sal table with customer nid and warrantynumber
        $sal_num = Sal::where('nid', $request->nid)->where('sal_no', $request->warrantynumber)->first();
        if (!isset($sal_num)) {
            $newSalNum = new Sal();
            $newSalNum->nid = $request->nid;
            $newSalNum->sal_no = $request->warrantynumber;
            $newSalNum->save();
        }

        $oldwarrantynumbers = Sal::where('nid', $request->nid)->where('sal_no', '<>', $request->warrantynumber)
            ->orWhere('sal_no', $request->warrantynumber)->where('nid', '<>', $request->nid)->get();

        if (isset($oldwarrantynumbers)) {
            foreach ($oldwarrantynumbers as $value) {
                $value->delete();
            }
        }

        try {
            DB::transaction(function () use ($request, $regnumber) {

                $customer = new Customer();
                $customer->requesttypes_id = 1;
                $customer->regnumber = $regnumber;
                $customer->fullnamea = $request->fullnamea;
                $customer->fullnamee = $request->fullnamee;
                $customer->email = $request->email;
                $customer->phone = $request->phone;
                $customer->gender = $request->gender;
                $customer->yearbitrh = $request->yearbitrh;
                $customer->registrationnumber = encrypt($request->registrationnumber);
                $customer->nid = encrypt($request->nid);
                $customer->nationalID = $request->nid;
                $customer->passportnumber = $request->passportnumber;
                $customer->nationalities_id = 1;
                $customer->bloodtypes_id = $request->bloodtypes_id;
                $customer->joptype = decrypt($request->joptype);
                $customer->municipals_id = $request->municipals_id;
                $customer->nearestpoint = $request->nearestpoint;
                $customer->cities_id = $request->cities_id;
                $customer->socialstatuses_id = $request->socialstatuses_id;
                $customer->diseasestate = $request->diseasestate;
                $customer->save();

                $retired = new retired();
                $retired->warrantynumber = $request->warrantynumber;
                $retired->warrantyoffices_id = $request->warrantyoffices_id;
                $retired->healthfacilities_id = $request->healthfacilities_id;
                $retired->guarantybranches_id = decrypt($request->guarantybranches_id);
                $retired->customers_id = $customer->id;
                $retired->save();

                $customerAudit = new CustomerAudit();
                $customerAudit->user_id = auth()->id();
                $customerAudit->customer_id = $customer->id;
                $customerAudit->inc_id = $customer->regnumber;
                $customerAudit->audit_type = 'register';
                $customerAudit->save();

            });

            $vendor = substr($request->phone, 1, 1);
            if ($vendor != "1" && $vendor != "3") {
                $states = $this->sms2->sendSmsreg((string) $request->phone, $regnumber)->successful();
            } else {
                $states = $this->sms->sendSmsreg((string) $request->phone, $regnumber)->successful();
            }

            // return redirect(route('medicalprofile',encrypt($regnumber)));
            Alert::success("تمت عمليه التسجيل بنجاح  ");
            // return response()->json(['message' => 'رقم الهاتف مسجل مسبقا'], 500);
            return view('frontend.donereg')->with('regnumber', $regnumber);

        } catch (Exception $e) {
            Alert::error("الرجاء المحاولة مرة اخرى");
            Log::error($e);
            return response()->json(['message' => 'يوجد خطأ في عملية لبتسجيل'], 500);
            // return redirect()->back();
        }

    }

    public function saveCustomersByAdmin(Request $request)
    {

        // dd($request->all());
        if ($request->joptype == 1) {

            $warrantyoffices_id_id = $request->warrantyoffices_id;

            $warrantynumber = Warrantyoffice::find($warrantyoffices_id_id);
            // dd( strlen($guarantybranch->code));
            if (strlen($warrantynumber->code) == 2) {
                $grcode = '0' . $warrantynumber->code;
            } else {
                $grcode = $warrantynumber->code;
            }
        } else {
            $grcode = 0;
        }
        $messages = [
            // 'fullnamea.required' => "الرجاء ادخال الأسم باللغة العربية",
            // 'fullnamee.required' => "الرجاء ادخال  الاسم باللغة الانجليزية",
            // 'email.required' => "الرجاء ادخال  البريد الإلكتروني ",
            'phone.required' => "الرجاء ادخال رقم الهاتف",
            'gender.required' => "الرجاء اختيار نوع الجنس  ",
            // 'yearbitrh.required' => "الرجاء اختيار سنة الميلاد ",
            // 'registrationnumber.required' => "الرجاء ادخال  رقم القيد",
            // 'nid.required' => "الرجاء ادخال رقم الوطني",
            'passportnumber.required' => "الرجاء ادخال رقم الجواز",
            // 'nationalities_id.required' => "الرجاء   اختيار الجنسية",
            'bloodtypes_id.required' => "الرجاء  اختيار فصيلة الدم ",
            'joptype.required' => "الرجاء ادخال نوع العمل  ",
            'municipals_id.required' => "الرجاء اختار البلدية",
            'nearestpoint.required' => "الرجاء  ادخل عنوان اقرب نقطة دالة ",
            'cities_id.required' => "الرجاء   اختيار المنطقة الصحية",
            'phone.unique' => "رقم الهاتف مستخدم من قبل",
            // 'warrantynumber.required' => "الرجاء ادخال رقم المعاش",
            // 'warrantyoffices_id.required' => "الرجاء اختر  مكتب الضمان     ",
            // 'guarantybranches_id.required' => "الرجاء  اختر  الفرع      ",
            'warrantynumber.starts_with' => "الرجاء التأكد من رقم المعاش",
            // 'chronicdiseases_id.*.required' => "من فضلك اختر المرض المزمن   ",
            // 'product.*.required' => "من فضلك ادخل    الدواء",

        ];
        $this->validate($request, [
            // 'fullnamea' => ['required', 'string', 'unique:customers'],
            // 'fullnamee' => ['required', 'string', 'unique:customers'],
            'email' => ['nullable', 'string', 'email', 'max:50', 'unique:customers'],
            'phone' => 'required|digits_between:9,9|numeric|starts_with:91,92,94,21,93',
            'gender' => ['required', 'string'],
            'yearbitrh' => ['required'],
            // 'registrationnumber' => ['required', 'digits_between:5,7'],
            // 'nid' => ['required', 'unique:customers', 'digits_between:12,12', 'starts_with:' . $gender . $year],
            'passportnumber' => ['required', 'unique:customers'],
            // 'nationalities_id' => ['required'],
            'bloodtypes_id' => ['required'],
            'joptype' => ['required'],
            'municipals_id' => ['required'],
            'nearestpoint' => ['required'],
            'cities_id' => ['required'],
            'socialstatuses_id' => ['required'],
            //  'warrantyoffices_id' => ['required', 'string'],
            // 'guarantybranches_id' => ['required', 'string'],
            // 'warrantynumber' => ['required', 'unique:retireds', 'digits_between:11,11',$grcode],
            // 'warrantynumber' => ['unique:retireds', 'digits_between:11,11', 'starts_with:2' . $grcode . ',3' . $grcode . ',4' . $grcode . ',5' . $grcode],
            // "chronicdiseases_id.*"  => "required|string|distinct|min:3",
        ], $messages);
        $cityCode = City::find($request->cities_id);
        $beneficiaries_sup_categories = beneficiariesSupCategories::find($request->joptype);
        $beneficiaries_categories = beneficiariesCategories::find($beneficiaries_sup_categories->beneficiaries_categories_id);
        $regnumber = $beneficiaries_sup_categories->code . $cityCode->id . $request->gender . substr($request->yearbitrh, '2', '2') . $beneficiaries_categories->code . random_int(10000, 99999);
        $checkData = Customer::where("regnumber", $regnumber)->first();



        if (isset($checkData)) {
            do {
                $number = random_int(10000, 99999);
                $regnumber = $beneficiaries_sup_categories->code . $cityCode->id . $request->gender . substr($request->yearbitrh, '2', '2') . $beneficiaries_categories->code . $number;
            } while (Customer::where("regnumber", $regnumber)->first());
        }
        if ($request->joptype == 1) {

            // check and update Sal table with customer nid and warrantynumber
            $sal_num = Sal::where('nid', $request->nid)->where('sal_no', $request->warrantynumber)->first();
            if (!isset($sal_num)) {
                $newSalNum = new Sal();
                $newSalNum->nid = $request->nid;
                $newSalNum->sal_no = $request->warrantynumber;
                $newSalNum->save();
            }

            $oldwarrantynumbers = Sal::where('nid', $request->nid)->where('sal_no', '<>', $request->warrantynumber)
                ->orWhere('sal_no', $request->warrantynumber)->where('nid', '<>', $request->nid)->get();

            if (isset($oldwarrantynumbers)) {
                foreach ($oldwarrantynumbers as $value) {
                    $value->delete();
                }
            }
        }

        $Warrantyoffice = Warrantyoffice::where('code', substr($request->warrantynumber, 1, 3))->first();

        try {
            DB::transaction(function () use ($request, $regnumber, $beneficiaries_categories) {

                $customer = new Customer();
                $customer->requesttypes_id = 1;
                $customer->regnumber = $regnumber;
                $customer->fullnamea = $request->fullnamea;
                $customer->fullnamee = $request->fullnamee;
                $customer->email = $request->email;
                $customer->phone = $request->phone;
                $customer->gender = $request->gender;
                $customer->yearbitrh = $request->yearbitrh;
                $customer->registrationnumber = encrypt($request->registrationnumber);
                $customer->registrationnumbers = $request->registrationnumber;
                $customer->nid = encrypt($request->nid);
                $customer->nationalID = $request->nid;
                $customer->passportnumber = $request->passportnumber;
                $customer->nationalities_id = 1;
                $customer->beneficiaries_categories_id = $beneficiaries_categories->id;
                $customer->beneficiaries_sup_categories_id = $request->joptype;
                $customer->bloodtypes_id = $request->bloodtypes_id;
                $customer->joptype = $request->joptype;
                $customer->municipals_id = $request->municipals_id;
                $customer->nearestpoint = $request->nearestpoint;
                $customer->cities_id = $request->cities_id;
                $customer->socialstatuses_id = $request->socialstatuses_id;
                $customer->diseasestate = $request->diseasestate;
                $customer->save();
                if($request->joptype==1){
                $retired = new retired();
                $retired->warrantynumber=$request->warrantynumber; 
                $retired->warrantyoffices_id=$request->warrantyoffices_id; 
                $retired->healthfacilities_id=$request->healthfacilities_id; 
                $retired->guarantybranches_id=decrypt($request->guarantybranches_id); 
                $retired->customers_id=$customer->id; 
                $retired->save();
                }else if($request->joptype == 3){
                $retired = new retired();
                $Warrantyoffice = Warrantyoffice::where('code' , substr($request->warrantynumber, 1, 3))->first();
                $retired->warrantynumber=$request->warrantynumber; 
                $retired->warrantyoffices_id=$Warrantyoffice->id; 
                $retired->healthfacilities_id=$request->healthfacilities_id; 
                $retired->guarantybranches_id=$Warrantyoffice->guarantybranches_id; 
                $retired->customers_id=$customer->id; 
                $retired->save();
                }else if(request()->has('warrantynumber')){
                $retired = new retired();
                $retired->warrantynumber=$request->warrantynumber; 
                $retired->warrantyoffices_id=$request->warrantyoffices_id; 
                $retired->healthfacilities_id=$request->healthfacilities_id; 
                $retired->guarantybranches_id=decrypt($request->guarantybranches_id); 
                $retired->customers_id=$customer->id; 
                $retired->type=2; 
                $retired->save();
                }

                if (request()->has('salaryNumber')) {
                    $salaryNumber = new salaryNumber();
                    $salaryNumber->customer_id = $customer->id;
                    $salaryNumber->type = $request->salary_type;
                    $salaryNumber->salary_number = $request->salaryNumber;
                    $salaryNumber->save();
                }
                $customerAudit = new CustomerAudit();
                $customerAudit->user_id = auth()->id();
                $customerAudit->customer_id = $customer->id;
                $customerAudit->inc_id = $customer->regnumber;
                $customerAudit->audit_type = 'register';
                $customerAudit->save();

            });

            $vendor = substr($request->phone, 1, 1);
            if ($vendor != "1" && $vendor != "3") {
                $states = $this->sms2->sendSmsreg((string) $request->phone, $regnumber)->successful();
            } else {
                $states = $this->sms->sendSmsreg((string) $request->phone, $regnumber)->successful();
            }

            // return redirect(route('medicalprofile',encrypt($regnumber)));
            Alert::success("تمت عمليه التسجيل بنجاح  ");
            // return response()->json(['message' => 'رقم الهاتف مسجل مسبقا'], 500);
            return view('frontend.donereg')->with('regnumber', $regnumber);

        } catch (Exception $e) {
            Alert::error("الرجاء المحاولة مرة اخرى");
            Log::error($e);
            return response()->json(['message' => 'يوجد خطأ في عملية لبتسجيل'], 500);
            // return redirect()->back();
        }

    }


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


    public function completeform(Request $request)
    {
        if (!empty(Session::get('nationalID'))) {

            $nidAr = $this->nid->getNidData(Session::get('nationalID'));
            $nidEn = $this->nid->getNidEnData(Session::get('nationalID'));
            $nationality = Nationality::orderBy('name', 'ASC')->get();
            $bloodtype = Bloodtype::orderBy('name', 'ASC')->get();
            $city = City::orderBy('name', 'ASC')->get();
            $socialstatuses = Socialstatus::orderBy('name', 'ASC')->get();
            if ($nidAr == "Nid Not Found!") {
                Alert::error('هذا الرقم غير موجود الرجاء التأكد');
                return redirect()->back();
            } else if ($nidAr->isLife) {
                $firstnamearabic = $nidAr->firstName . ' ' . $nidAr->fatherName . ' ' . $nidAr->grandFatherName . ' ' . $nidAr->surName;
                $firstnameenglis = $nidEn->FirstNameEn . ' ' . $nidEn->FatherNameEn . ' ' . $nidEn->GrandFatherNameEn . ' ' . $nidEn->SurNameEn;
                $birtdateandtiem = explode("T00", $nidAr->birthDate);
                $birtdate = $birtdateandtiem['0'];
                $gendertype = substr($nidAr->nationalID, 0, 1);

                return view('frontend.register')
                    ->with('bloodtype', $bloodtype)
                    ->with('city', $city)
                    ->with('socialstatuses', $socialstatuses)
                    ->with('nationality', $nationality)
                    ->with('firstnamearabic', $firstnamearabic)
                    ->with('firstnameenglis', $firstnameenglis)
                    ->with('birtdate', $birtdate)
                    ->with('gendertype', $gendertype)
                    ->with('nidAr', $nidAr)
                    ->with('nidEn', $nidEn);

            } else {
                Alert::error('لايمكنك الاستفادة من الخدمة ');
                return redirect()->back();

            }
        } else {
            return redirect(route('/'));
        }
    }


    public function completeregister(Request $request)
    {
        if (!empty(Session::get('phone'))) {
            // $yearb = new Carbon($request->yearbitrh ); 

            $year = date('Y', strtotime($request->yearbitrh));
            $gender = $request->gender;
            $phone = $request->phone;
            $ve = Verification::where('phone', $phone)->first();


            $messages = [
                // 'fullnamea.required' => "الرجاء ادخال الأسم باللغة العربية",
                // 'fullnamee.required' => "الرجاء ادخال  الاسم باللغة الانجليزية",
                'email.required' => "الرجاء ادخال  البريد الإلكتروني ",
                'phone.required' => "الرجاء ادخال رقم الهاتف",
                'gender.required' => "الرجاء اختيار نوع الجنس  ",
                // 'yearbitrh.required' => "الرجاء اختيار سنة الميلاد ",
                // 'registrationnumber.required' => "الرجاء ادخال  رقم القيد",
                // 'nid.required' => "الرجاء ادخال رقم الوطني",
                'passportnumber.required' => "الرجاء ادخال رقم الجواز",
                // 'nationalities_id.required' => "الرجاء   اختيار الجنسية",
                'bloodtypes_id.required' => "الرجاء  اختيار فصيلة الدم ",
                'joptype.required' => "الرجاء ادخال نوع العمل  ",
                'municipals_id.required' => "الرجاء اختار البلدية",
                'nearestpoint.required' => "الرجاء  ادخل عنوان اقرب نقطة دالة ",
                'cities_id.required' => "الرجاء   اختيار المنطقة الصحية",
                'phone.unique' => "رقم الهاتف مستخدم من قبل",


            ];
            $this->validate($request, [
                // 'fullnamea' => ['required', 'string', 'unique:customers'],
                // 'fullnamee' => ['required', 'string', 'unique:customers'],
                'email' => ['nullable', 'string', 'email', 'max:50', 'unique:customers'],
                'phone' => 'required|digits_between:9,9|numeric|starts_with:91,92,94,21,93|unique:customers',
                'gender' => ['required', 'string'],
                'yearbitrh' => ['required'],
                // 'registrationnumber' => ['required', 'digits_between:5,7'],
                // 'nid' => ['required', 'unique:customers', 'digits_between:12,12', 'starts_with:' . $gender . $year],

                'passportnumber' => ['required', 'unique:customers'],
                // 'nationalities_id' => ['required'],
                'bloodtypes_id' => ['required'],
                'joptype' => ['required'],
                'municipals_id' => ['required'],
                'nearestpoint' => ['required'],
                'cities_id' => ['required'],
                'socialstatuses_id' => ['required'],
                "product.*" => "string",
            ], $messages);

            // try {
            //    if(decrypt($request->joptype==1)){
            $data = array(
                'fullnamea' => $request->fullnamea,
                'fullnamee' => $request->fullnamee,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'yearbitrh' => $request->yearbitrh,
                'registrationnumber' => encrypt($request->registrationnumber),
                'nid' => encrypt($request->nid),
                // 'nationalID' => $request->nid,
                'passportnumber' => $request->passportnumber,
                'nationalities_id' => 1,
                'bloodtypes_id' => $request->bloodtypes_id,
                'joptype' => $request->joptype,
                'municipals_id' => $request->municipals_id,
                'cities_id' => $request->cities_id,
                'socialstatuses_id' => $request->socialstatuses_id,
                'nearestpoint' => $request->nearestpoint,
                'diseasestate' => $request->diseasestate,
            );

            if (is_null($ve)) {
                Alert::warning("الرجاء التأكد من رقم الهاتف");
                return redirect()->back();

                // return redirect()->back();
            } else
                if (Session::get('phone') == $phone) {

                    Session::put('customer_data', $data);
                    return redirect(route('confirmation'))->with('data', $data);
                } else {
                    Alert::warning("الرجاء التأكد من رقم الهاتف");
                    return redirect(route('/'));
                }
        } else {
            return redirect(route('/'));
        }
    }

    public function warrantyoffices($id)
    {
        try {
            $id = decrypt($id);

            $wa = Warrantyoffice::where('guarantybranches_id', $id)->orderBy('name', 'ASC')->get();
            return response()->json($wa);
        } catch (DecryptException $e) {
            abort(404, 'هذه الصفحة غير موجودة');
        }
    }


    public function confirmation()
    {
        $warrantyoffices = Warrantyoffice::orderBy('name', 'ASC')->get();
        $healthfacilities = Healthfacilities::orderBy('name', 'ASC')->get();
        $guarantybranch = guarantybranch::orderBy('name', 'ASC')->get();
        $Chronicdiseases = Chronicdiseases::all();
        // dd( decrypt(Session::get('customer_data')["nid"]));
        $pensionNon = Retiredfile::where('nationalNumber', decrypt(Session::get('customer_data')["nid"]))->first();
        $sal = Sal::where('nid', decrypt(Session::get('customer_data')["nid"]))->first();
        //  dd($pensionNon);
//  dd($pensionNo);
        if ($pensionNon) {
            $pensionNo = $pensionNon->pensionNo;

        } else {
            $pensionNo = null;
        }
        return view('frontend.retiredinfo')
            ->with('Chronicdiseases', $Chronicdiseases)
            ->with('guarantybranch', $guarantybranch)
            ->with('warrantyoffices', $warrantyoffices)
            ->with('healthfacilities', $healthfacilities)
            ->with('pensionNo', $pensionNo)
            ->with('sal_no', $sal ? $sal->sal_no : null);

    }


    public function store(Request $request)
    {
        if (!empty(Session::get('customer_data'))) {
            $warrantyoffices_id_id = $request->warrantyoffices_id;

            $warrantynumber = Warrantyoffice::find($warrantyoffices_id_id);
            // dd( strlen($guarantybranch->code));
            if (strlen($warrantynumber->code) == 2) {
                $grcode = '0' . $warrantynumber->code;
            } else {
                $grcode = $warrantynumber->code;
            }

            $messages = [

                'warrantynumber.required' => "الرجاء ادخال رقم المعاش",
                'warrantyoffices_id.required' => "الرجاء اختر  مكتب الضمان     ",
                'guarantybranches_id.required' => "الرجاء  اختر  الفرع      ",
                'warrantynumber.starts_with' => "الرجاء التأكد من رقم المعاش",
                'chronicdiseases_id.*.required' => "من فضلك اختر المرض المزمن   ",
                // 'product.*.required' => "من فضلك ادخل    الدواء",
                'Diagnosis_date.*.required' => "من فضلك اختر تاريخ التشخيص"
            ];
            $this->validate($request, [

                'warrantyoffices_id' => ['required', 'string'],
                'guarantybranches_id' => ['required', 'string'],
                // 'warrantynumber' => ['required', 'unique:retireds', 'digits_between:11,11',$grcode],
                //  'warrantynumber' => ['required', 'digits_between:11,11', 'starts_with:2' . $grcode . ',3' . $grcode . ',4' . $grcode . ',5' . $grcode],

                "chronicdiseases_id.*" => "required|string|distinct|min:3",
                "product.*" => "string",
                "Diagnosis_date.*" => "required",
                "prescription.*" => "mimes:pdf",
                "medical_report.*" => "mimes:pdf",
                "follow_card.*" => "mimes:pdf",

            ], $messages);

            try {

                //reg-jop-bir-g-city
                $year = date('Y', strtotime(Session::get('customer_data')["yearbitrh"]));
                $city = City::where('id', decrypt(Session::get('customer_data')["cities_id"]))->get()->first();
                $citycode = $city->code;
                $gender = Session::get('customer_data')["gender"];
                $phone = Session::get('customer_data')["phone"];
                $bistring = substr($year, -2);

                // $regnumber1 = mt_rand(10000, 99999) . '01' . $bistring . $gender . $citycode;
                $regnumber = $citycode . $gender . $bistring . '01' . mt_rand(10000, 99999);

                $fullnamea = Session::get('customer_data')["fullnamea"];
                $fullnamee = Session::get('customer_data')["fullnamee"];
                $email = Session::get('customer_data')["email"];
                $phone = Session::get('customer_data')["phone"];
                $gender = Session::get('customer_data')["gender"];
                $yearbitrh = Session::get('customer_data')["yearbitrh"];
                $registrationnumber = Session::get('customer_data')["registrationnumber"];
                $nid = Session::get('customer_data')["nid"];
                $passportnumber = Session::get('customer_data')["passportnumber"];
                $nationalities_id = 1;
                $bloodtypes_id = decrypt(Session::get('customer_data')["bloodtypes_id"]);
                $joptype = decrypt(Session::get('customer_data')["joptype"]);
                $municipals_id = Session::get('customer_data')["municipals_id"];
                $nearestpoint = Session::get('customer_data')["nearestpoint"];
                $cities_id = decrypt(Session::get('customer_data')["cities_id"]);
                $warrantyoffices_id = $request->warrantyoffices_id;
                $healthfacilities_id = null;
                $guarantybranches_id = decrypt($request->guarantybranches_id);


                $socialstatuses_id = Session::get('customer_data')["socialstatuses_id"];

                $warrantynumber = $request->warrantynumber;
                $diseasestate = Session::get('customer_data')["diseasestate"];

                $ve = Verification::where('phone', $phone)->first();
                if ($ve->phone == $phone) {
                    if ($diseasestate == 1) {
                        $Customer = CustomerretiredService::save(
                            $regnumber,
                            $fullnamea,
                            $fullnamee,
                            $email,
                            $phone,
                            $gender,
                            $yearbitrh,
                            $registrationnumber,
                            $nid,
                            $passportnumber,
                            $nationalities_id,
                            $bloodtypes_id,
                            $joptype,
                            $municipals_id,
                            $nearestpoint,
                            $cities_id,
                            $socialstatuses_id,
                            $warrantynumber,
                            $warrantyoffices_id,
                            $healthfacilities_id,
                            $guarantybranches_id,
                            $diseasestate
                        );

                    } else {

                        // dd($request);
                        $Customer = CustomerretiredService::save(
                            $regnumber,
                            $fullnamea,
                            $fullnamee,
                            $email,
                            $phone,
                            $gender,
                            $yearbitrh,
                            $registrationnumber,
                            $nid,
                            $passportnumber,
                            $nationalities_id,
                            $bloodtypes_id,
                            $joptype,
                            $municipals_id,
                            $nearestpoint,
                            $cities_id,
                            $socialstatuses_id,
                            $warrantynumber,
                            $warrantyoffices_id,
                            $healthfacilities_id,
                            $guarantybranches_id,
                            $diseasestate
                        );
                        //   dd($Customer['customer']);
                        foreach ($request->addmore as $key => $value) {


                            if (isset($value['product']) == false) {
                                $productx = null;
                            } else {

                                $productx = $value['product'];
                            }
                            if (isset($value['prescription']) == false) {
                                $prescriptionx = null;
                            } else {
                                $ex = $value['prescription']->getClientOriginalExtension();
                                if ($ex == "pdf") {
                                    $fileObject = $value['prescription'];
                                    $prescription = $Customer['customer'] . time() . ".pdf";
                                    $prescriptionx = $prescription;

                                    $path = $fileObject->move('pdf/prescription/', $prescription);
                                } else {

                                    $fileObject = $value['prescription'];
                                    $prescription = $Customer['customer'] . time() . ".jpg";
                                    $prescriptionx = $prescription;

                                    $path = $fileObject->move('pdf/prescription/', $prescription);
                                    $prescriptionx = $prescription;
                                }
                            }

                            if (isset($value['medical_report']) == false) {
                                $medical_reportx = null;
                            } else {
                                $ex = $value['medical_report']->getClientOriginalExtension();
                                if ($ex == "pdf") {
                                    $fileObject1 = $value['medical_report'];
                                    $medical_report = $Customer['customer'] . time() . ".pdf";
                                    $path = $fileObject1->move('pdf/medical_report/', $medical_report);
                                    $medical_reportx = $medical_report;
                                } else {

                                    $fileObject1 = $value['medical_report'];
                                    $medical_report = $Customer['customer'] . time() . ".jpg";
                                    $path = $fileObject1->move('pdf/medical_report/', $medical_report);
                                    $medical_reportx = $medical_report;
                                }
                            }
                            if (isset($value['follow_card']) == false) {
                                $follow_cardx = '';
                            } else {

                                $ex = $value['follow_card']->getClientOriginalExtension();
                                if ($ex == "pdf") {
                                    $fileObject2 = $value['follow_card'];
                                    $follow_card = $Customer['customer'] . time() . ".pdf";
                                    $path = $fileObject2->move('pdf/followcard/', $follow_card);
                                    $follow_cardx = $follow_card;
                                } else {

                                    $fileObject2 = $value['follow_card'];
                                    $follow_card = $Customer['customer'] . time() . ".jpg";
                                    $path = $fileObject2->move('pdf/followcard/', $follow_card);
                                    $follow_cardx = $follow_card;
                                }
                            }

                            Medicalprofile::create([
                                'chronicdiseases_id' => decrypt($value["chronicdiseases_id"]),
                                'product' => $productx,
                                'customers_id' => $Customer['customer'],
                                'retireds_id' => $Customer['retired'],
                                'Diagnosis_date' => $value["Diagnosis_date"],
                                'prescription' => $prescriptionx,
                                'medical_report' => $medical_reportx,
                                'follow_card' => $follow_cardx,
                                'assigns' => 0,
                            ]);
                        }
                    }
                    session()->forget('customer_data');
                    session()->forget('phone');

                    $vendor = substr($phone, 1, 1);
                    if ($vendor != "1" && $vendor != "3") {
                        $states = $this->sms2->sendSmsreg((string) $phone, $regnumber)->successful();
                    } else {
                        $states = $this->sms->sendSmsreg((string) $phone, $regnumber)->successful();
                    }

                    // return redirect(route('medicalprofile',encrypt($regnumber)));
                    Alert::success("تمت عمليه التسجيل بنجاح  ");

                    return view('frontend.donereg')
                        ->with('regnumber', $regnumber);
                } else {
                    Alert::success("الرجاء التأكد من رقم الهاتف");
                    return redirect(route('complete'));

                    // return redirect()->back();
                }
            } catch (\Exception $e) {
                // dd($e );
                if ($e->getCode() == "23000") {
                    Alert::error("البيانات موجودة الرجاء التأكد" . $e);
                } else {
                    Alert::error("الرجاء المحاولة مرة اخرى" . $e);
                }
                return redirect(route('complete'));
            }
        } else {
            return redirect(route('/'));
        }
    }

    public function query($regnumber)
    {

        $reg = decrypt($regnumber);
        $recustomers = retired::whereHas('customers', function ($query) use ($reg) {
            $query->where('regnumber', $reg);
        })->with([
                    'customers',
                    'customers.cities',
                    'customers.socialstatuses',
                    'customers.municipals',
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



    public function searchBeneficiary()
    {

        return view('dashbord.customers.search');

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

        return view('dashbord.customers.index', ['data' => $request->regnumber]);

    }
}
