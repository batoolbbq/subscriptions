<?php

namespace App\Imports;

use App\Models\InstitucionSheetRow;
use Maatwebsite\Excel\Concerns\ToModel;

class InstitucionSheetImport implements ToModel
{
    protected int $institucionId;

    public function __construct(int $institucionId)
    {
        $this->institucionId = $institucionId;
    }

    /**
     * الأعمدة بالترتيب:
     * 0 => الاسم
     * 1 => الرقم الوطني
     * 2 => رقم القيد
     * 3 => رقم الحساب
     */
    public function model(array $row)
    {
        // تخطي الصف الفارغ
        if (!isset($row[0]) || trim($row[0]) === '') {
            return null;
        }

        return new InstitucionSheetRow([
            'institucion_id'     => $this->institucionId,
            'name'               => trim((string)$row[0]),
            'national_id'        => $row[1] ?? null,
            'family_registry_no' => $row[2] ?? null,
            'account_no'         => $row[3] ?? null,
        ]);
    }
}
