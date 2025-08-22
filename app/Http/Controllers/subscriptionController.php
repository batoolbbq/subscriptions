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
            $types = subscription_type::all(); // أو لو عندك فلترة: ->where('category', 'subscription_type')->get();
    
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

        // التحقق من incomplete types (مدخلة جزئيًا)
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
            // $this->sendSubscriptionToApi($subscription, $validTypes);

            return redirect()->route('subscriptions.index')->with('success', 'تمت إضافة الاشتراك بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors('حدث خطأ أثناء الحفظ: ' . $e->getMessage());
        }
    }



    public function sendSubscriptionToApi($subscription, $validTypes)
    {
        $payload = [
            'id' => (int) $subscription->id,
            'name' => $subscription->name,
            'workCategoryId' => (int) $subscription->work_category_id,
            'subscriptionValues' => [],
        ];

        foreach ($validTypes as $typeId => $data) {
            $payload['subscriptionValues'][] = [
                'subscriptionType' => (int) $typeId,
                'value' => (float) $data['value'],
                'isPercentage' => (bool) $data['is_percentage'],
                'duration' => (int) $data['duration'],
                'status' => 0, // أو 1 حسب نظامك
            ];
        }

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode('admin:admin'),
            'Content-Type' => 'application/json',
        ])->post('http://192.168.81.17:6060/admin/Subscriptions', $payload);

        if ($response->successful()) {
        return [
            'success' => true,
            'message' => 'تم إرسال الاشتراك إلى الـ API بنجاح.',
            'response' => $response->json(),
        ];
    } else {
        return [
            'success' => false,
            'message' => 'فشل إرسال الاشتراك إلى الـ API.',
            'status' => $response->status(),
            'error' => $response->body(),
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
    $subscription->status = $subscription->status === '1' ? '0' : '1'; // نخزن كنص
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
