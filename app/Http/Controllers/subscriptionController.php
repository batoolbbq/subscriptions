<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\subscription_values;
use App\Models\subscription_type;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
    use Illuminate\Support\Facades\Http;
    
use Illuminate\Support\Facades\Log;

/**
 *
 * @param  \App\Models\Subscription $subscription
 * @param  \Illuminate\Support\Collection|array $validTypes  (مصفوفة/كولكشن من الأنواع المكتملة)
 * @return array
 */


use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
   public function index()
    {
        $subscriptions = Subscription::with(['values.type','beneficiariesCategory'])->get();

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $workCategories = \App\Models\beneficiariesCategories::where('status', 1)->get();
            $types = subscription_type::all();
            $paymentDueTypes = \App\Models\PaymentDueType::all(); 

    
        return view('subscriptions.create', compact('workCategories','types','paymentDueTypes'));
    }

    public function getData()
    {
        $subscriptions = Subscription::with('workCategory')->get();

         return DataTables::of($subscriptions)
        ->addColumn('work_category', function ($sub) {
            return $sub->workCategory->name ?? '<span class="text-danger">غير محددة</span>';
        })
        ->addColumn('status_label', function ($sub) {
            return $sub->status
                ? '<span class="badge badge-success">نشط</span>'
                : '<span class="badge badge-secondary">غير نشط</span>';
        })
        ->addColumn('actions', function ($sub) {
            $edit = route('subscriptions.edit', $sub->id);
            $toggle = route('subscriptions.toggleStatus', $sub->id);
            return '
                <a href="' . $edit . '" class="btn btn-sm btn-warning">تعديل</a>
                <a href="' . $toggle . '" class="btn btn-sm btn-info">'
                . ($sub->status ? 'تعطيل' : 'تفعيل') .
                '</a>
            ';
        })
        ->rawColumns(['work_category', 'status_label', 'actions'])
        ->make(true);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'types' => 'required|array',
           'payment_due_type_id' => 'required|exists:payment_due_types,id', // 👈 تحقق جديد
            'types.*.value' => 'nullable|numeric|min:0',
            'types.*.is_percentage' => 'nullable|in:0,1',
            'types.*.duration' => 'nullable|integer|min:0',
        ]);

        $types = $request->input('types');

        $validTypes = collect($types)->filter(function ($item) {
            return isset($item['value'], $item['is_percentage'], $item['duration']) &&
                $item['value'] !== '' && $item['is_percentage'] !== '' && $item['duration'] !== '';
        });

        $incompleteTypes = collect($types)->filter(function ($item) {
            $filledCount = collect($item)->filter(fn($v) => $v !== null && $v !== '')->count();
            return $filledCount > 0 && $filledCount < 3;
        });

        if ($incompleteTypes->isNotEmpty()) {
            return back()->withInput()->withErrors('يرجى تعبئة كل الحقول (القيمة، النوع، المدة) لأي نوع اشتراك تم استخدامه.');
        }

        if ($validTypes->isEmpty()) {
            return back()->withInput()->withErrors('يجب إدخال نوع اشتراك مكتمل واحد على الأقل.');
        }

        foreach ($validTypes as $typeId => $data) {
            if ($data['is_percentage'] == '1' && ($data['value'] < 0 || $data['value'] > 100)) {
                return back()->withInput()->withErrors("قيمة النسبة في نوع الاشتراك رقم $typeId يجب أن تكون بين 0 و 100.");
            }
        }

        DB::beginTransaction();

        try {
            $subscription = Subscription::create([
                'name' => $request->name,
                'beneficiaries_categories_id' =>$request->beneficiaries_categories_id,
                'status' => true,
                 'payment_due_type_id' => $request->payment_due_type_id, 

            ]);

            foreach ($validTypes as $typeId => $data) {
                subscription_values::create([
                    'subscription_id' => $subscription->id,
                    'subscription_type' => $typeId, 
                    'value' => $data['value'],
                    'is_percentage' => $data['is_percentage'],
                    'duration' => $data['duration'],
                    'status' => 1,
                ]);
            }
            DB::commit();

            $result = $this->sendSubscriptionToApi($subscription, $validTypes);

            if (!$result['success']) {
              
                return redirect()
                    ->route('subscriptions.index')
                    ->with('warning', 'تم الحفظ محليًا لكن فشل إرسال البيانات للـ API الخارجي.');

                // لو تبغى تكمل بدون رسائل:
                // return redirect()->route('subscriptions.index')->with('success', 'تمت إضافة الاشتراك بنجاح');
            }

            return redirect()->route('subscriptions.index')->with('success', 'تمت إضافة الاشتراك بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors('حدث خطأ أثناء الحفظ: ' . $e->getMessage());
        }
    }



   public function sendSubscriptionToApi($subscription, $validTypes)
    {
        $apiBaseUrl  = 'http://192.168.81.17:6060';
        $apiEndpoint = '/admin/Subscriptions';
        $apiUser     = 'admin';
        $apiPass     = 'admin';

        $payload = [
            'id'             => $subscription->id, //  مثل الـ curl الرسمي
            'name'           => $subscription->name,
            'workCategoryId' => $subscription->beneficiaries_categories_id, // أو رقم حقيقي لو متاح عندك
            'subscriptionValues' => collect($validTypes)->map(function ($data, $typeId) {
                $subscriptionTypeId = is_numeric($typeId) ? (int)$typeId : 0;

                return [
                    'subscriptionType' => $subscriptionTypeId,
                    'value'        => isset($data['value']) ? (float)$data['value'] : 0.0,
                    'isPercentage' => ((int)($data['is_percentage'] ?? 0) === 1),
                    'duration'     => (int)($data['duration'] ?? 0),
                    'paymentDue'   => 1, //  أضفناه كما في الـ curl
                    'status'       => 0, //  مثل الـ curl الرسمي
                ];
            })->values()->all(),
        ];

        try {
            $response = Http::withBasicAuth($apiUser, $apiPass)
                ->acceptJson()
                ->asJson()
                ->timeout(10)
                ->retry(2, 200)
                ->post(rtrim($apiBaseUrl, '/') . '/' . ltrim($apiEndpoint, '/'), $payload);

            if ($response->successful()) {
                Log::info('✅ Subscription sent successfully', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
                return [
                    'success' => true,
                    'message' => 'تم إرسال الاشتراك إلى الـ API بنجاح.',
                    'response' => $response->json(),
                    'payload' => $payload,
                ];
            }

            Log::error('❌ Subscription API error', [
                'status' => $response->status(),
                'error' => $response->body(),
                'payload' => $payload,
            ]);
            return [
                'success' => false,
                'message' => 'فشل إرسال الاشتراك إلى الـ API.',
                'status' => $response->status(),
                'error' => $response->body(),
                'payload' => $payload,
            ];
        } catch (\Throwable $th) {
            Log::error('⚠️ Subscription API exception: ' . $th->getMessage(), ['payload' => $payload]);
            return [
                'success' => false,
                'message' => 'حدث استثناء أثناء الاتصال بالـ API.',
                'status' => 0,
                'error' => $th->getMessage(),
                'payload' => $payload,
            ];
        }
    }



    public function edit($id)
    {
        $subscription = Subscription::with('values')->findOrFail($id);

        $beneficiariesCategories = \App\Models\beneficiariesCategories::all();

        $types = subscription_type::all();

        return view('subscriptions.edit', compact('subscription', 'types', 'beneficiariesCategories'));
    }
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'beneficiaries_categories_id' => 'required|exists:beneficiaries_categories,id',
        'types' => 'required|array',
        'types.*.value' => 'nullable|numeric|min:0',
        'types.*.is_percentage' => 'nullable|in:0,1',
        'types.*.duration' => 'nullable|integer|min:0',
    ]);

    $types = $request->input('types');

    $validTypes = collect($types)->filter(function ($item) {
        return isset($item['value'], $item['is_percentage'], $item['duration']) &&
            $item['value'] !== '' && $item['is_percentage'] !== '' && $item['duration'] !== '';
    });

    if ($validTypes->isEmpty()) {
        return back()->withInput()->withErrors('يجب إدخال نوع اشتراك مكتمل واحد على الأقل.');
    }

    DB::beginTransaction();

    try {
        // 🟠 تحديث البيانات المحلية
        $subscription = Subscription::findOrFail($id);
        $subscription->update([
            'name' => $request->name,
            'beneficiaries_categories_id' => $request->beneficiaries_categories_id,
            'status' => true,
        ]);

        subscription_values::where('subscription_id', $subscription->id)->delete();

        foreach ($validTypes as $typeId => $data) {
            subscription_values::create([
                'subscription_id' => $subscription->id,
                'subscription_type' => $typeId,
                'value' => $data['value'],
                'is_percentage' => $data['is_percentage'],
                'duration' => $data['duration'],
                'status' => 1,
            ]);
        }

        DB::commit();

        // 🧩 نتحقق أن الدالة فعلاً تُستدعى
        Log::info('📡 وصلنا إلى updateSubscriptionInApi', ['subscription_id' => $subscription->id]);

        $result = $this->updateSubscriptionInApi($subscription, $validTypes);

        // 🧠 فحص النتيجة بالتفصيل
        Log::info('📬 نتيجة الاتصال بـ API', ['result' => $result]);

        if (!$result['success']) {
            return redirect()->route('subscriptions.index')
                ->with('warning', 'تم التعديل محليًا لكن فشل تحديث البيانات في الـ API الخارجي.');
        }

        return redirect()->route('subscriptions.index')->with('success', 'تم تعديل الاشتراك بنجاح.');

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('🔥 خطأ أثناء عملية التحديث', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->withInput()->withErrors('حدث خطأ أثناء التحديث: ' . $e->getMessage());
    }
}



public function updateSubscriptionInApi($subscription, $validTypes)
{
    $apiBaseUrl  = 'http://192.168.81.17:6060';
    $apiUser     = 'admin';
    $apiPass     = 'admin';

    $payload = [
        'id'             => $subscription->id,
        'name'           => $subscription->name,
        'workCategoryId' => (int)$subscription->beneficiaries_categories_id,
        'subscriptionValues' => collect($validTypes)->map(function ($data, $typeId) {
            return [
                'subscriptionType' => (int)$typeId,
                'value'            => (float)($data['value'] ?? 0),
                'isPercentage'     => ((int)($data['is_percentage'] ?? 0) === 1),
                'duration'         => (int)($data['duration'] ?? 0),
                'paymentDue'       => 0,
                'status'           => 0,
            ];
        })->values()->all(),
    ];

    $url = "{$apiBaseUrl}/admin/Subscriptions/UpdateInfo/{$subscription->id}";

    try {
        Log::info('🚀 محاولة تحديث الاشتراك في الـ API', ['url' => $url, 'payload' => $payload]);

        $response = Http::withBasicAuth($apiUser, $apiPass)
            ->acceptJson()
            ->asJson()
            ->timeout(15)
            ->put($url, $payload);

        $data = [
            'status' => $response->status(),
            'body'   => $response->body(),
            'json'   => $response->json(),
            'payload' => $payload,
        ];

        Log::info('📨 رد الـ API', $data);

        return [
            'success' => $response->successful(),
            'status'  => $response->status(),
            'response' => $response->json(),
        ];

    } catch (\Throwable $th) {
        Log::error('⚠️ خطأ أثناء الاتصال بالـ API', ['message' => $th->getMessage()]);
        return [
            'success' => false,
            'error'   => $th->getMessage(),
        ];
    }
}








        public function destroy($id)
        {
            $subscription = Subscription::findOrFail($id);
            $subscription->delete();
            return redirect()->route('subscriptions.index')->with('success', 'تم حذف الاشتراك بنجاح');
        }
public function toggleStatus($id)
{
    $subscription = Subscription::findOrFail($id);
    $subscription->status = $subscription->status === '1' ? '0' : '1'; 
    $subscription->save();

    $message = $subscription->status === '1' 
        ? 'تم تفعيل الاشتراك بنجاح' 
        : 'تم إلغاء تفعيل الاشتراك بنجاح';

    return redirect()->route('subscriptions.index')->with('success', $message);
}


   public function show($id)
    {
        $subscription = Subscription::with(['values.type', 'beneficiariesCategory'])
            ->findOrFail($id);

        return view('subscriptions.show', compact('subscription'));
    }

}
