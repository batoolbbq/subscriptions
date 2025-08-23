<?php

namespace App\Imports;

use App\Models\InstitucionSheetRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InstitucionSheetImport implements ToModel, WithStartRow
{
    protected int $institucionId;

    public function __construct(int $institucionId)
    {
        $this->institucionId = $institucionId;
    }

    // نتجاوز صف العناوين
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // لو الصف كله فاضي نتخطّاه
        $isEmpty = true;
        for ($i = 0; $i <= 8; $i++) {
            if (isset($row[$i]) && trim((string)$row[$i]) !== '') {
                $isEmpty = false; break;
            }
        }
        if ($isEmpty) return null;

        // ترتيب الأعمدة مطابق للإكسل:
        $name       = trim((string)($row[0] ?? ''));
        $father     = trim((string)($row[1] ?? ''));
        $last       = trim((string)($row[2] ?? ''));
        $national   = trim((string)($row[3] ?? ''));
        $family     = trim((string)($row[4] ?? ''));
        $insured    = trim((string)($row[5] ?? ''));
        $pension    = trim((string)($row[6] ?? ''));
        $account    = trim((string)($row[7] ?? ''));
        $totalRaw   = ($row[8] ?? null);

        // نحاول نحول إجمالي المعاش لرقم: نشيل فواصل ومسافات
        $total = null;
        if ($totalRaw !== null && $totalRaw !== '') {
            $clean = preg_replace('/[^\d\.\-]/', '', str_replace(',', '.', (string)$totalRaw));
            $total = is_numeric($clean) ? (float)$clean : null;
        }

        return new InstitucionSheetRow([
            'institucion_id'     => $this->institucionId,
            'name'               => $name ?: null,
            'father_name'        => $father ?: null,
            'last_name'          => $last ?: null,
            'national_id'        => $national ?: null,
            'family_registry_no' => $family ?: null,
            'insured_no'         => $insured ?: null,
            'pension_no'         => $pension ?: null,
            'account_no'         => $account ?: null,
            'total_pension'      => $total,
        ]);
    }
}
