<?php

namespace App\Exports;

use App\Models\StockTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents, WithTitle
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function title(): string
    {
        return 'Laporan Transaksi';
    }

    public function headings(): array
    {
        return ['Tanggal', 'Kode', 'Nama Barang', 'Jenis', 'Jumlah', 'Gudang', 'Petugas'];
    }

    public function map($trx): array
    {
        return [
            $trx->created_at->translatedFormat('d M Y'),
            $trx->item->sku ?? '-',
            $trx->item->name ?? '-',
            $trx->type === 'in' ? 'Masuk' : 'Keluar',
            $trx->type === 'in' ? '+' . $trx->quantity : '-' . $trx->quantity,
            $trx->item->warehouse->name ?? '-',
            $trx->user->name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1C2333'],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Border tipis di semua sel data
                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '4A4F5C'],
                        ],
                    ],
                ]);

                // Tinggi baris header lebih lega
                $sheet->getRowDimension(1)->setRowHeight(24);

                // Rata tengah untuk kolom Jenis & Jumlah
                $sheet->getStyle("D2:E{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Bekukan baris header supaya tetap kelihatan saat scroll
                $sheet->freezePane('A2');

                // Warna selang-seling per baris (zebra stripe) biar gampang dibaca
                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$lastColumn}{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F7F8FA'],
                            ],
                        ]);
                    }
                }
            },
        ];
    }
}