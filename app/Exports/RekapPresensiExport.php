<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RekapPresensiExport implements Export, WithMultipleSheets
{
    use Exportable;

    protected $rekapPeserta;
    protected $detailSesi;

    public function __construct(array $rekapPeserta, array $detailSesi)
    {
        $this->rekapPeserta = $rekapPeserta;
        $this->detailSesi = $detailSesi;
    }

    public function sheets(): array
    {
        return [
            new RekapPesertaSheet($this->rekapPeserta),
            new DetailPresensiSheet($this->detailSesi),
        ];
    }
}
