<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PesananExport implements FromCollection, WithHeadings
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
                'name' => $item->name,
                'tgl_pesanan' => $item->tgl_pesanan,
                'tot_harga' => $item->tot_harga,
                'alamat_pengiriman' => $item->alamat_pengiriman,
                'telp' => $item->telp,
                'status' => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Tanggal',
            'Total Harga',
            'Alamat Pengiriman',
            'Telepon',
            'Status',
        ];
    }
}
