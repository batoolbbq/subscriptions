<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Personalphotos;
use App\Models\retired;
use Illuminate\Http\Request;
use App\Models\CustomerAudit;
use App\Models\beneficiariesSupCategories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

use function PHPUnit\Framework\isNull;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // ActivityLogger::activity("عرض صفحة  البطافات ");
        return view('cards.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function editcard()
    {

        // ActivityLogger::activity("عرض صفحة  تعديل البطاقة ");
        return view('cards.cardsetting');
    }




    public function quary(Request $request)
    {
        $messages = [
            'regnumber.required' => "من فضلك ادخل رقم التسجيل",

        ];
        $this->validate($request, [
            'regnumber' => ['required'],

        ], $messages);
        $reg = $request->regnumber;
        $customers = Customer::with(['cities' , 'socialstatuses' , 'municipals' , 'nationalities' , 'bloodtypes'])->where('regnumber', $reg)->where('active', 0)->first();
        // $customer = retired::whereHas('customers', function ($query) use ($reg) {
        //     $query->where('regnumber', $reg);
        // })->with([
        //     'customers', 'customers.cities', 'customers.socialstatuses', 'customers.municipals',
        //     'customers.nationalities', 'customers.bloodtypes', 'customers.requesttypes', 'warrantyoffices', 'healthfacilities'
        // ])->first();

        // dd(    $pers->count );
        if (!empty($customers)) {
            $pers = Personalphotos::where('customers_id', $customers->id)->orderBy('id', 'desc')->first();
                    $beneficiary = beneficiariesSupCategories::find($customers->beneficiaries_sup_categories_id);

            return  view('cards.card')
                ->with('pers', $pers)
                ->with('customer', $customers)
                ->with('beneficiary', $beneficiary);
        } else {
            Alert::error("انت غير مسجل الرجاء التأكد من الرقم");

            return redirect()->back();
        }
    }








    // public function storephoto(Request $request)
    // {
    //     //  dd($request->filled('img'));
    //     try {
    //         if ($request->filled('img')) {
    //             $pers = Personalphotos::where('customers_id', $request->customers_id)->first();
    //             $ret = retired::where('customers_id', $request->customers_id)->first();
    //             $per = new Personalphotos();
    //             if ($pers === null) {
    //                 $img = str_replace('data:image/jpeg;base64,', '', $request->img);
    //                 $img = str_replace(' ', '+', $img);
    //                 $data = base64_decode($img);
    //                 $basename = "photo" . $ret->id . time();
    //                 $file = 'photo/personalphotos/' . $basename . '.jpeg';
    //                 $success = file_put_contents($file, $data);

    //                 $per->image = $basename;
    //                 $per->customers_id = $request->customers_id;
    //                 $per->retireds_id = $ret->id;
    //                 $per->count = 1;

    //                 $per->save();
    //                 Alert::success("تمت عملية حفظ الصورة   بنجاح");

    //                 return redirect()->back();
    //             }
    //             $perslast = Personalphotos::where('customers_id', $request->customers_id)->orderBy('id', 'desc')->first();

    //             $img = str_replace('data:image/jpeg;base64,', '', $request->img);
    //             $img = str_replace(' ', '+', $img);
    //             $data = base64_decode($img);
    //             $basename = "photo" . $ret->id . time();
    //             $file = 'photo/personalphotos/' . $basename . '.jpeg';
    //             $success = file_put_contents($file, $data);
    //             $per->image = $basename;
    //             $per->customers_id = $request->customers_id;
    //             $per->retireds_id = $ret->id;
    //             $per->image = $basename;
    //             $countt = $perslast->count + 1;
    //             $per->printed = $perslast->printed;

    //             $per->count = $countt;
    //             $per->save();
    //             Alert::success("تمت عملية حفظ الصورة   بنجاح");

    //             return redirect()->back();
    //         }

    //         Alert::error("الرجاء قم بالتقاط صورة");

    //         return redirect()->back();
    //     } catch (\Exception $e) {

    //         Alert::warning($e->getMessage());
    //         ActivityLogger::activity($e . "خطا في حفظ الصورة");

    //         return redirect()->back();
    //     }
    // }



    public function print(Request $request, $customers_id)
    {
        try {

            $pers = Personalphotos::where('customers_id', $customers_id)->orderBy('id', 'desc')->first();
            $ret = retired::where('customers_id', $customers_id)->first();
            if($pers != null){

                if ($request->filled('selfImg')) {
                    $img = str_replace('data:image/jpeg;base64,', '', $request->selfImg);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $basename = "photo" . $ret->id . time();
                    $file = 'photo/personalphotos/' . $basename . '.jpeg';
                    $success = file_put_contents($file, $data);     

                    $pers->image = $basename;
                    $pers->count = $pers->count + 1;
                    $pers->printed = 1;
                    $pers->save();

                    return response()->json(1);
                }
                else {
                    $pers->count = $pers->count + 1;
                    $pers->printed = 1;
                    $pers->save();

                return response()->json(1);
                }
            } else{
           
                return response()->json("خطأ في البيانات", 400);
            }

        } catch (\Exception $e) {

            ActivityLogger::activity($e."خطا في حفظ الصورة");
            return response()->json($e);

        }
    }




    // public function storephotosave(Request $request,$customers_id)
    // {
    //     // return $request;
    //     try {
    //         if ($request->filled('img')) {
    //             $pers = Personalphotos::where('customers_id', $customers_id)->first();
    //             $ret = retired::where('customers_id', $customers_id)->first();
    //                     //  dd($pers);
    //             $img = str_replace('data:image/jpeg;base64,', '', $request->selfImg);
    //             $img = str_replace(' ', '+', $img);
    //             $data = base64_decode($img);
    //             $basename = "photo" . $ret->id . time();
    //             $file = 'photo/personalphotos/' . $basename . '.jpeg';
    //             $success = file_put_contents($file, $data);        

    //             if ($pers === null) {

    //                 $per = new Personalphotos();
    //                 $per->image = $basename;
    //                 $per->customers_id = $customers_id;
    //                 $per->retireds_id = $ret->id;
    //                 $per->count = 1;
    //                 $per->printed = 1;

    //                 $per->save();
    //                 // Alert::success("تمت عملية حفظ الصورة بنجاح");
    //                 return response()->json(1);

    //                 // return redirect()->back();

    //             }
    //             // dd($request);
    //             $perslast = Personalphotos::where('customers_id', $customers_id)->orderBy('id', 'desc')->first();

    //             // $per = new Personalphotos();
    //             $perslast->image = $basename;
    //             // $perslast->customers_id = $customers_id;
    //             // $perslast->retireds_id = $ret->id;
    //             $perslast->count = $perslast->count + 1;
    //             $perslast->printed = 1;

    //             $perslast->save();
    //             // Alert::success("تمت عملية حفظ الصورة بنجاح");

    //             // return redirect()->back();
    //             return response()->json(1);
    //         }
    //         // dd($request);
    //         // // Alert::error("الرجاء قم بالتقاط صورة");

    //         // return response()->json(3); 
    //         Alert::error("الرجاء قم بالتقاط صورة");

    //         return redirect()->back();
    //     } catch (\Exception $e) {

    //         return response()->json($e);

    //         // Alert::warning($e->getMessage());
    //         // ActivityLogger::activity($e."خطا في حفظ الصورة");

    //         // return redirect()->back();

    //     }
    // }
    public function storephotosave(Request $request,$customers_id)
    {
        
            // return "okkkkk";
        //  dd($request->filled('selfImg'));
        try {
            if ($request->filled('selfImg')) {
                $pers = Personalphotos::where('customers_id', $customers_id)->first();
                $ret = retired::where('customers_id', $customers_id)->first();
                $per = new Personalphotos();
                        //  dd($pers);

                if ($pers === null) {
                    $img = str_replace('data:image/jpeg;base64,', '', $request->selfImg);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $basename = "photo" . $ret->id . time();
                    $file = 'photo/personalphotos/' . $basename . '.jpeg';
                    $success = file_put_contents($file, $data);

                    $per->image = $basename;
                    $per->customers_id = $customers_id;
                    $per->retireds_id = $ret->id;
                    $per->count = $per->count + 1;
                    $per->printed = 1;
                    $per->save();
                    // ActivityLogger::activity("Customer ID " . $customers_id . " photo taken by user id " . Auth()->user()->id . " with username " . Auth()->user()->username . " with personalphoto id " . $per->id);
                    
                    // Alert::success("تمت عملية حفظ الصورة   بنجاح");
                    
                    $customer = Customer::findOrFail($customers_id);
            
                    $customerAudit = new CustomerAudit();
                    $customerAudit->user_id = auth()->id();
                    $customerAudit->customer_id = $customer->id;
                    $customerAudit->inc_id = $customer->regnumber;
                    $customerAudit->audit_type = 'print';
                    $customerAudit->save();
                    
                    return response()->json(1);

                    // return redirect()->back();

                }
                
                $perslast = Personalphotos::where('customers_id', $customers_id)->orderBy('id', 'desc')->first();
                if($perslast->printed > 0){
                    Alert::error("تمت الطباعة مسبقا");
                    return redirect()->back();
                }
                $img = str_replace('data:image/jpeg;base64,', '', $request->selfImg);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $basename = "photo" . $ret->id . time();
                $file = 'photo/personalphotos/' . $basename . '.jpeg';
                $success = file_put_contents($file, $data);
                $perslast->image = $basename;
                $perslast->customers_id = $customers_id;
                $perslast->retireds_id = $ret->id;
                $perslast->image = $basename;
                $countt = $perslast->count + 1;
                $perslast->printed = 1;

                $perslast->count = $countt;
                $perslast->save();
                ActivityLogger::activity("Customer ID " . $customers_id . " photo taken by user id " . Auth()->user()->id . " with username " . Auth()->user()->username . " with personalphoto id " . $per->id);
                // Alert::success("تمت عملية حفظ الصورة   بنجاح");

                $customer = Customer::findOrFail($customers_id);
            
                    $customerAudit = new CustomerAudit();
                    $customerAudit->user_id = auth()->id();
                    $customerAudit->customer_id = $customer->id;
                    $customerAudit->inc_id = $customer->regnumber;
                    $customerAudit->audit_type = 'print';
                    $customerAudit->save();

                // return redirect()->back();
                return response()->json(1);
            }

            // Alert::error("الرجاء قم بالتقاط صورة");

            return response()->json(3);
        } catch (\Exception $e) {

            return response()->json($e);

            // Alert::warning($e->getMessage());
            // ActivityLogger::activity($e."خطا في حفظ الصورة");

            // return redirect()->back();

        }
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request, $customers_id)
    {
        
        
        try {
            if ($request->filled('selfImg')) {
                $pers = Personalphotos::where('customers_id', $customers_id)->first();
                // $ret = retired::where('customers_id', $customers_id)->first();
                        //  dd($pers);
                $img = str_replace('data:image/jpeg;base64,', '', $request->selfImg);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $basename = "photo" . $customers_id . time();
                $file = 'photo/personalphotos/' . $basename . '.jpeg';
                $success = file_put_contents($file, $data);        

                if ($pers === null) {

                    $per = new Personalphotos();
                    $per->image = $basename;
                    $per->customers_id = $customers_id;
                    // $per->retireds_id = $ret->id;
                    $per->count = 0;
                    $per->printed = 0;

                    $per->save();
                    // Alert::success("تمت عملية حفظ الصورة بنجاح");
                    return response()->json(1);

                }
                $perslast = Personalphotos::where('customers_id', $customers_id)->orderBy('id', 'desc')->first();
                $perslast->image = $basename;
                $perslast->save();
                // return redirect()->back();
                return response()->json(1);
            } else{
                return response()->json("الرجاء قم بالتقاط صورة", 400);
            }
            // dd($request);

        } catch (\Exception $e) {

            return response()->json($e);

        }
    }




// public function store(Request $request, $customers_id)
// {
//     try {
//         if (!$request->filled('selfImg')) {
//             return response()->json("الرجاء قم بالتقاط صورة", 400);
//         }

//         $img = str_replace('data:image/jpeg;base64,', '', $request->selfImg);
//         $img = str_replace(' ', '+', $img);
//         $data = base64_decode($img);

//         $basename = "photo" . $customers_id . time();

//         $path = public_path('photo/personalphotos/');
//         if (!file_exists($path)) mkdir($path, 0777, true);

//         $file = $path . $basename . '.jpeg';
//         $success = file_put_contents($file, $data);

//         if (!$success) {
//             return response()->json(['error' => 'فشل حفظ الصورة في المجلد'], 500);
//         }

//         $photo = Personalphotos::updateOrCreate(
//             ['customers_id' => $customers_id],
//             [
//                 'image' => $basename,
//                 'count' => 0,
//                 'printed' => 0,
//             ]
//         );

//         $piaResponse = $this->sendToPia($customers_id, $basename);

//         return response()->json([
//             'status' => true,
//             'message' => 'تم حفظ الصورة وإرسالها إلى نظام البطاقات ✅',
//             'pia_response' => $piaResponse,
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'status' => false,
//             'message' => $e->getMessage(),
//         ], 500);
//     }
// }





  public function showByRegnumber($regnumber)
    {
        $pers = null;

        $customer = \App\Models\Customer::with([
            'cities', 'socialstatuses', 'municipals', 'nationalities', 'bloodtypes'
        ])->where('regnumber', $regnumber)->first();

        if (!$customer) {
            \RealRashid\SweetAlert\Facades\Alert::error('خطأ', 'لم يتم العثور على المشترك.');
            return redirect()->route('customers.renedit');
        }

        // 🔹 لو حبيتي ترجع الصورة الشخصية مستقبلاً
        // $pers = \App\Models\Personalphotos::where('customers_id', $customer->id)
        //     ->orderBy('id', 'desc')
        //     ->first();

        $beneficiary = \App\Models\beneficiariesSupCategories::find($customer->beneficiaries_sup_categories_id);

        return view('cards.card', compact('customer', 'beneficiary', 'pers'));
    }





// public function getCustomerCardData($customers_id)
// {
//     try {
//         // 1) نجيب المشترك
//         $customer = Customer::find($customers_id);
//         if (!$customer) {
//             return response()->json(['status' => false, 'message' => 'المشترك غير موجود'], 404);
//         }

//         // 2) نجهز قائمة الحقول الممنوعة
//         $blocked = [
//             'subscription_id',
//             'iban',
//             'bank_branch_id',
//             'total_pension',
//             'pension_no',
//             'account_no',
//             'insured_no',
//             'institucion_id',   // في حاله الاسم عندك بدون T
//             'institution_id',   // وفي حاله الاسم عندك بـ T (نمنعو الاثنين للاحتياط)
//         ];

//         // 3) نشيل الحقول الممنوعة ونخلي الباقي
//         $safeCustomer = collect($customer->toArray())->except($blocked);

//         // 4) نجيب آخر صورة (لو فيه)
//         $lastPhoto = Personalphotos::where('customers_id', $customers_id)
//             ->latest('id')
//             ->first();

//         $photoBase64 = null;
//         if ($lastPhoto && !empty($lastPhoto->image)) {
//             $path = public_path('photo/personalphotos/' . $lastPhoto->image . '.jpeg');
//             if (file_exists($path)) {
//                 $photoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($path));
//             }
//         }

//         // 5) نرجع الرد
//         return response()->json([
//             'status'   => true,
//             'customer' => $safeCustomer,   // كل الحقول المسموحة فقط
//             'photo'    => $photoBase64     // ممكن تكون null لو ما فيش صورة
//         ]);

//     } catch (\Throwable $e) {
//         return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
//     }
// }


    // public function getCustomerCardData($customers_id)
    // {
    //     try {
    //         // 1) نجيب المشترك
    //         $customer = Customer::find($customers_id);
    //         if (!$customer) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'المشترك غير موجود'
    //             ], 404);
    //         }

    //         // 2) الحقول الممنوعة
    //         $blocked = [
    //             'subscription_id',
    //             'iban',
    //             'bank_branch_id',
    //             'total_pension',
    //             'pension_no',
    //             'account_no',
    //             'insured_no',
    //             'institucion_id',
    //             'institution_id', // احتياط لو اسم الحقل مختلف
    //         ];

    //         // 3) نخلي الحقول المسموحة
    //         $safeCustomer = collect($customer->toArray())->except($blocked);

    //         // 4) نجيب آخر صورة
    //         $lastPhoto = Personalphotos::where('customers_id', $customers_id)
    //             ->latest('id')
    //             ->first();

    //         $photoBase64 = null;
    //         if ($lastPhoto && !empty($lastPhoto->image)) {
    //             $path = public_path('photo/personalphotos/' . $lastPhoto->image . '.jpeg');
    //             if (file_exists($path)) {
    //                 $photoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($path));
    //             }
    //         }

    //         // 5) نرجع النتيجة
    //         return response()->json([
    //             'status'   => true,
    //             'customer' => $safeCustomer,
    //             'photo'    => $photoBase64
    //         ]);

    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }









// public function indexApi()
// {
//     try {
//         $blocked = [
//             'subscription_id','iban','bank_branch_id','total_pension','pension_no',
//             'account_no','insured_no','institucion_id','institution_id','bank_id'
//         ];

//         $customers = Customer::where('active', 0)
//             ->with('lastPhoto')
//             ->latest()
//             ->get()
//             ->makeHidden($blocked);

//         $customers = $customers->filter(function ($customer) {
//             return $customer->lastPhoto && $customer->lastPhoto->image;
//         })->map(function ($customer) {
//             $customer->photo = asset('photo/personalphotos/' . $customer->lastPhoto->image . '.jpeg');
//             unset($customer->lastPhoto);
//             return $customer;
//         })->values();

//         return response()->json([
//             'status' => true,
//             'data'   => $customers,
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             'status'  => false,
//             'message' => $e->getMessage(),
//         ], 500);
//     }
// }











     public function printed($customers_id)
    {
        $perslast = Personalphotos::where('customers_id', $customers_id)->orderBy('id', 'desc')->first();
    
        if ($perslast === null) {
            
            $per = new Personalphotos();
            
            $per->printed = 1;
            $per->save();
            ActivityLogger::activity("Customer ID " . $customers_id . " photo taken by user id " . Auth()->user()->id . " with username " . Auth()->user()->username . " with personalphoto id " . $per->id);
            
            $customer = Customer::findOrFail($customers_id);
            $customerAudit = new CustomerAudit();
            $customerAudit->user_id = auth()->id();
            $customerAudit->customer_id = $customer->id;
            $customerAudit->inc_id = $customer->regnumber;
            $customerAudit->audit_type = 'print';
            $customerAudit->save();
            
            return response()->json(1);
        } else {
            // Personalphotos::where('customers_id', $customers_id)->update(array('printed' => $pers->printed +1));
            if($perslast->printed > 0){
                Alert::error("تمت الطباعة مسبقا");
                return redirect()->back();
            }
            $perslast->printed = $perslast->printed + 1;
            $perslast->save();
            ActivityLogger::activity("Customer ID " . $customers_id . " photo taken by user id " . Auth()->user()->id . " with username " . Auth()->user()->username . " with personalphoto id " . $perslast->id);

            $customer = Customer::findOrFail($customers_id);
            
            $customerAudit = new CustomerAudit();
            $customerAudit->user_id = auth()->id();
            $customerAudit->customer_id = $customer->id;
            $customerAudit->inc_id = $customer->regnumber;
            $customerAudit->audit_type = 'print';
            $customerAudit->save();
            
            return response()->json(1);
        }
    }
    public function allowPrint($customer_id)
    {
        $pers = Personalphotos::where('customers_id', $customer_id)->orderBy('id', 'desc')->first();

        if ($pers === null) {
            return response()->json('error', 400);
        }
            $pers->printed = 0;
            $pers->save();
            return response()->json(1);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        //
    }
}
