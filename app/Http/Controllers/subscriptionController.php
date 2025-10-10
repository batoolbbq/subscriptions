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
    
        return view('subscriptions.create', compact('workCategories','types'));
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

        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'types' => 'required|array',
            'types.*.value' => 'nullable|numeric|min:0',
            'types.*.is_percentage' => 'nullable|in:0,1',
            'types.*.duration' => 'nullable|integer|min:0',
        ]);

        $types = $request->input('types');

        // فلترة الأنواع المكتملة فقط
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

        // التحقق من حدود النسبة المئوية
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
            ]);

            foreach ($validTypes as $typeId => $data) {
                subscription_values::create([
                    'subscription_id' => $subscription->id,
                    'subscription_type' => $typeId, // الآن نربطه بـ KValue.id
                    'value' => $data['value'],
                    'is_percentage' => $data['is_percentage'],
                    'duration' => $data['duration'],
                    'status' => 1,
                ]);
            }
            DB::commit();

          return  $result = $this->sendSubscriptionToApi($subscription, $validTypes);

            if (!$result['success']) {
                // خيار 1: تكتفي بالتسجيل وتكمل عادي
                // خيار 2: تعرض تنبيه للمستخدم بدون إلغاء الحفظ المحلي:
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
            'id'             => 1,
            'name'           => $subscription->name,
            'workCategoryId' => 1,
            'subscriptionValues' => collect($validTypes)->map(function ($data, $typeId) {
                $subscriptionTypeId = is_numeric($typeId) && (int)$typeId > 0
                    ? (int)$typeId
                    : (int)($data['subscription_type'] ?? $data['subscription_type_id'] ?? 0);

                $isPercentage = isset($data['is_percentage'])
                    ? ((int)$data['is_percentage'] === 1)
                    : (bool)($data['isPercentage'] ?? false);

                return [
                    'subscriptionType' => $subscriptionTypeId,
                    'value'        => isset($data['value']) ? (float)$data['value'] : 0.0,
                    'isPercentage' => $isPercentage,
                    'duration'     => isset($data['duration']) ? (int)$data['duration'] : 0,
                    'status'       => (int)($data['status'] ?? 1),
                ];
            })
            ->filter(fn ($row) => $row['subscriptionType'] > 0) 
            ->values()
            ->all(),
        ];

        try {
            $response = Http::withBasicAuth($apiUser, $apiPass)
                ->acceptJson()
                ->asJson()
                ->timeout(10)
                ->retry(2, 200)
                ->post(rtrim($apiBaseUrl, '/') . '/' . ltrim($apiEndpoint, '/'), $payload);

            if ($response->successful()) {
                Log::info('Subscriptions API success', [
                    'status' => $response->status(),
                    'api_response' => $response->json(),
                ]);

                return [
                    'success'  => true,
                    'message'  => 'تم إرسال الاشتراك إلى الـ API بنجاح.',
                    'response' => $response->json(),
                    'payload'  => $payload, 
                ];
            }

            Log::error('Subscriptions API error', [
                'status'  => $response->status(),
                'error'   => $response->body(),
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'message' => 'فشل إرسال الاشتراك إلى الـ API.',
                'status'  => $response->status(),
                'error'   => $response->body(),
                'payload' => $payload,
            ];
        } catch (\Throwable $th) {
            Log::error('Subscriptions API exception: ' . $th->getMessage(), ['payload' => $payload]);

            return [
                'success' => false,
                'message' => 'استثناء أثناء الاتصال بالـ API.',
                'status'  => 0,
                'error'   => $th->getMessage(),
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
            return redirect()->route('subscriptions.index')->with('success', 'تم تعديل الاشتراك بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors('حدث خطأ أثناء التحديث: ' . $e->getMessage());
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
