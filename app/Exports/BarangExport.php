<?php

namespace App\Exports;

use App\Models\Barang;
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
        // Map data to include country name
        return $this->data->map(function ($item) {
            return [
                'id' => $item->id,
                'nm_barang' => $item->nm_barang,
                'harga' => $item->harga,
                'stok' => $item->stok,
                'keterangan' => $item->ket_barang,
                'kategori' => $item->nm_kategori,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Barang',
            'Harga',
            'Stok',
            'Keterangan',
            'Kategori',
        ];
    }
}
