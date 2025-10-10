<?php

namespace App\Services;

use App\Models\{
    Customer,
    BeneficiariesSupCategories,
    Subscription,
    Warrantyoffice,
    retired
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CustomerRegistrationService
{
    
    private static function generateRegNumber($benefCatId, $cityCode = null, $gender = null, $birthDate = null)
    {
        $prefix = str_pad($benefCatId, 2, '0', STR_PAD_LEFT);
        $cityPart = str_pad($cityCode ?? 0, 3, '0', STR_PAD_LEFT);
        $year = $birthDate ? Carbon::parse($birthDate)->format('y') : '00';
        $genderCode = $gender === 'ذكر' ? '1' : ($gender === 'أنثى' ? '2' : '0');
        $unique = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);

        return "{$prefix}{$cityPart}{$year}{$genderCode}{$unique}";
    }

   
    public static function register(array $data)
    {
        $benefCatId = (int) ($data['main']['beneficiaries_categories_id'] ?? 0);
        $cityCode   = $data['main']['cities_id'] ?? null;
        $main       = $data['main'];

        $customerId = null;
        $ignoredDependents = 0;

        try {
            DB::beginTransaction();

            $STATE_CATEGORY_ID   = 12;
            $SUBSCRIPTION_ADULT  = 13;
            $SUBSCRIPTION_MINOR  = 14;

            if ($benefCatId === $STATE_CATEGORY_ID) {
                $age = Carbon::parse($main['birthDate'])->age;
                $subscriptionId = $age > 17 ? $SUBSCRIPTION_ADULT : $SUBSCRIPTION_MINOR;
            } else {
                $subscriptionId = Subscription::where('beneficiaries_categories_id', $benefCatId)->value('id');
            }

            $supMain = BeneficiariesSupCategories::where('beneficiaries_categories_id', $benefCatId)
                ->where('type', 'مشترك')
                ->firstOrFail();

            $supDep = BeneficiariesSupCategories::where('beneficiaries_categories_id', $benefCatId)
                ->where('type', 'منتفع')
                ->first();

            DB::statement('LOCK TABLE customers WRITE');
            $regnumberMain = self::generateRegNumber(
                $benefCatId,
                $cityCode,
                $main['gender'] ?? null,
                $main['birthDate'] ?? null
            );

            $customer = Customer::create([
                'requesttypes_id'                 => 1,
                'regnumber'                       => $regnumberMain,
                'fullnamea'                       => $main['fullnamea'] ?? $main['name'] ?? null,
                'fullnamee'                       => $main['fullnamee'] ?? $main['name_en'] ?? null,
                'email'                           => $main['email'] ?? null,
                'phone'                           => $main['phone'] ?? null,
                'gender'                          => $main['gender'] === 'ذكر' ? 1 : ($main['gender'] === 'أنثى' ? 2 : null),
                'yearbitrh'                       => $main['birthDate'] ?? null,
                'registrationnumbers'             => $main['registry_number44'] ?? null,
                'registrationnumber'              => encrypt($main['registry_number44']),
                'nid'                             => encrypt($main['nationalID']),
                'nationalID'                      => $main['nationalID'],
                'passportnumber'                  => $main['passport_no'] ?? null,
                'nationalities_id'                => 1,
                'beneficiaries_categories_id'     => $benefCatId,
                'beneficiaries_sup_categories_id' => $supMain->id,
                'bloodtypes_id'                   => $main['bloodtypes_id'] ?? null,
                'joptype'                         => 3,
                'municipals_id'                   => $main['municipals_id'] ?? null,
                'nearestpoint'                    => $main['nearest_municipal_point22'] ?? null,
                'cities_id'                       => $main['cities_id'] ?? null,
                'socialstatuses_id'               => $main['socialstatuses_id'] ?? null,
                'diseasestate'                    => $main['diseasestate'] ?? null,
                'insured_no'                      => $main['insured_no'] ?? null,
                'pension_no'                      => $main['pension_no'] ?? null,
                'account_no'                      => $main['account_no'] ?? null,
                'total_pension'                   => $main['total_pension'] ?? 0.00,
                'bank_id'                         => $main['bank_id'] ?? null,
                'bank_branch_id'                  => $main['bank_branch_id'] ?? null,
                'iban'                            => $main['iban'] ?? null,
                'institucion_id'                  => $data['institutionId'] ?? null,
                'subscription_id'                 => $subscriptionId,
            ]);

            $customerId = $customer->id;

            if ($benefCatId === 1 && !empty($main['warrantynumber'])) {
                $code = substr($main['warrantynumber'], 1, 3);
                $Warrantyoffice = Warrantyoffice::where('code', $code)->first();

                if ($Warrantyoffice) {
                    retired::create([
                        'warrantynumber'      => $main['warrantynumber'],
                        'warrantyoffices_id'  => $Warrantyoffice->id,
                        'guarantybranches_id' => $Warrantyoffice->guarantybranches_id,
                        'customers_id'        => $customerId,
                    ]);
                }
            }

            $dependents = collect($data['dependents'] ?? [])->filter(fn($dep) => !empty($dep['nationalID']))->values();

            if ($dependents->isNotEmpty() && $supDep) {
                foreach ($dependents as $dep) {
                    try {
                        Customer::create([
                            'requesttypes_id'                 => 1,
                            'regnumber'                       => self::generateRegNumber(
                                $benefCatId,
                                $dep['cities_id'] ?? null,
                                $dep['gender'] ?? null,
                                $dep['birthDate'] ?? null
                            ),
                            'fullnamea'                       => $dep['name'] ?? null,
                            'fullnamee'                       => $dep['name_en'] ?? null,
                            'email'                           => $dep['email'] ?? null,
                            'phone'                           => $dep['phone'] ?? null,
                            'gender'                          => $dep['gender'] === 'ذكر' ? 1 : ($dep['gender'] === 'أنثى' ? 2 : null),
                            'yearbitrh'                       => $dep['birthDate'] ?? null,
                            'registrationnumbers'             => $main['registry_number44'] ?? null,
                            'registrationnumber'              => encrypt($main['registry_number44']),
                            'nid'                             => encrypt($dep['nationalID']),
                            'nationalID'                      => $dep['nationalID'],
                            'passportnumber'                  => $dep['passport_no'] ?? null,
                            'nationalities_id'                => 1,
                            'beneficiaries_categories_id'     => $benefCatId,
                            'beneficiaries_sup_categories_id' => $supDep->id,
                            'bloodtypes_id'                   => $dep['bloodtypes_id'] ?? null,
                            'joptype'                         => 3,
                            'municipals_id'                   => $dep['municipals_id'] ?? null,
                            'nearestpoint'                    => $dep['nearest_municipal_point33'] ?? null,
                            'cities_id'                       => $dep['cities_id'] ?? null,
                            'socialstatuses_id'               => $dep['socialstatuses_id'] ?? null,
                            'diseasestate'                    => $dep['diseasestate'] ?? null,
                            'main_customer_id'                => $customerId,
                        ]);
                    } catch (\Throwable $e) {
                        $ignoredDependents++;
                        Log::warning('Dependent registration failed', ['dep' => $dep, 'error' => $e->getMessage()]);
                    }
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'تم تسجيل المشترك بنجاح',
                'customer_id' => $customerId,
                'ignored_dependents' => $ignoredDependents,
            ];

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Customer registration failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء العملية',
                'error' => $e->getMessage(),
            ];
        }
    }
}
