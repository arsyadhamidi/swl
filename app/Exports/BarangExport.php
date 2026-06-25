<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {

            $rentangHarga =
                $item->harga_min == $item->harga_max
                ? 'Rp ' . number_format($item->harga_min, 0, ',', '.')
                : 'Rp ' . number_format($item->harga_min, 0, ',', '.')
                    . ' - Rp '
                    . number_format($item->harga_max, 0, ',', '.');

            return [
                'id'            => $item->id,
                'nm_barang'     => $item->nm_barang,
                'kategori'      => $item->nm_kategori,
                'variasi'       => $item->total_variasi,
                'stok'          => $item->total_stok,
                'harga'         => $rentangHarga,
                'keterangan'    => $item->ket_barang,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Barang',
            'Kategori',
            'Jumlah Variasi',
            'Total Stok',
            'Rentang Harga',
            'Keterangan',
        ];
    }
}
