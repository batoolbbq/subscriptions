<?php

namespace App\Imports;

use App\Models\InstitucionSheetRow;
use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InstitucionSheetImport implements ToModel, WithStartRow
{
    protected int $institucionId;
    public int $inserted = 0;
    public int $updated  = 0;

    public function __construct(int $institucionId)
    {
        $this->institucionId = $institucionId;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $isEmpty = true;
        for ($i = 0; $i <= 8; $i++) {
            if (isset($row[$i]) && trim((string)$row[$i]) !== '') {
                $isEmpty = false;
                break;
            }
        }
        if ($isEmpty) return null;

        $national   = trim((string)($row[3] ?? ''));
        $totalRaw   = ($row[8] ?? null);

        // نحاول نحول إجمالي المعاش
        $total = null;
        if ($totalRaw !== null && $totalRaw !== '') {
            $clean = preg_replace('/[^\d\.\-]/', '', str_replace(',', '.', (string)$totalRaw));
            $total = is_numeric($clean) ? (float)$clean : null;
        }

        //تحقق من الكستمر
        $customer = Customer::where('nationalID', $national)->first();
        if ($customer) {
            $customer->active = 2;
            $customer->save();
            $this->updated++;
            return null;
        }

        $this->inserted++;
        return new InstitucionSheetRow([
            'institucion_id'     => $this->institucionId,
            'name'               => trim((string)($row[0] ?? '')) ?: null,
            'father_name'        => trim((string)($row[1] ?? '')) ?: null,
            'last_name'          => trim((string)($row[2] ?? '')) ?: null,
            'national_id'        => $national ?: null,
            'family_registry_no' => trim((string)($row[4] ?? '')) ?: null,
            'insured_no'         => trim((string)($row[5] ?? '')) ?: null,
            'pension_no'         => trim((string)($row[6] ?? '')) ?: null,
            'account_no'         => trim((string)($row[7] ?? '')) ?: null,
            'total_pension'      => $total,
        ]);
    }
}