<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Illuminate\Http\Request;
use App\Models\InsuranceAgent;       // تأكدي من اسم الموديل: InsuranceAgent أو insuranceAgents
use App\Models\User;
use App\Models\Customer;
use App\Models\Institucion;          // اسم الموديل حسب ملفك المرفوع
use App\Models\ServiceLog;
use App\Models\insuranceAgents;
use Illuminate\Support\Facades\DB;
use App\Models\AddedServiceService;



class insuranceperformance extends Controller
{

        public function insuranceData(Request $request)
       {
       $phone = trim((string) $request->get('phone', ''));
        $agent = null;
        $totalServices = 0;

        if ($phone !== '') {
            $agent = insuranceAgents::with('users')
                ->where('phone_number', $phone)
                ->first();

            if ($agent) {
                $userIds = $agent->users->pluck('id');

                if ($userIds->isNotEmpty()) {
                    $totalServices = ServiceLog::whereIn('user_id', $userIds)->count();
                }
            }
        }
        return view('insuranceAgents.performance', compact('phone', 'agent','totalServices'));
    }



  public function servicesCustomers(insuranceAgents $agent, Request $request)
    {
        // نجلب كل المستخدمين المرتبطين بالوكيل
        $agent->load('users');
        $userIds = $agent->users->pluck('id');

        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        $logs = ServiceLog::with(['service', 'customer', 'performedBy'])
            ->whereIn('user_id', $userIds)
            ->whereNotNull('customer_id')
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->appends($request->query());

        return view('insuranceAgents.agentsservices', [
            'agent'    => $agent,
            'logs'     => $logs,
            'mode'     => 'customers', // يحدد شكل الجدول في البليد
            'dateFrom' => $dateFrom,
            'dateTo'   => $dateTo,
        ]);
    }

        public function servicesInstitutions(InsuranceAgents $agent, Request $request)
    {
        $agent->load('users');
        $userIds = $agent->users->pluck('id');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        $logs = ServiceLog::with(['service', 'institution', 'performedBy'])
            ->whereIn('user_id', $userIds)
            ->whereNotNull('institucion_id')
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->appends($request->query());

        return view('insuranceAgents.agentsservices', [
            'agent'    => $agent,
            'logs'     => $logs,
            'mode'     => 'institutions',
            'dateFrom' => $dateFrom,
            'dateTo'   => $dateTo,
        ]);
    }
}


 
    



