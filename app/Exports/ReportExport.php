<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Report::with([
            'user',
            'response',
            'response.staff',
            'response.staff.staffprovince',
            'response.response_progress',
            'user.staffprovince',
        ])->when($this->startDate && $this->endDate, function ($query) {
            // Memfilter berdasarkan tanggal
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        })->get();
    }

    public function headings(): array
    {
        return [
            "Email Pelapor",
            "Dilaporkan Pada Tanggal",
            "Deskripsi Pengaduan",
            "URL Gambar",
            "Lokasi",
            "Jumlah Voting",
            "Status Pengaduan",
            "Progres Tanggapan",
            "Staff Terkait"
        ];
    }

    public function map($item): array
{
    $responseStatus = optional($item->response)->response_status ?? 'Tidak Diketahui';

    // Memastikan 'histories' diambil dengan benar dan digabungkan menjadi string
    $historiesString = 'Tidak Ada Progres'; 
    if ($item->response && $item->response->response_progress) {
        // Ambil response_progress dan pastikan bentuknya adalah array
        $historiesArray = $item->response->response_progress;

        // Membuat string kosong untuk menampung semua nilai histories
        $historiesString = '';
        foreach ($historiesArray as $history) {
            // Pastikan history adalah objek dan 'histories' adalah properti yang ada
            if (isset($history->histories)) {
                // Jika 'histories' adalah JSON, kita pastikan itu didecode
                $historiesNote = $history->histories;
                if (is_string($historiesNote)) {
                    $decodedHistories = json_decode($historiesNote, true);
                    if (is_array($decodedHistories)) {
                        $historiesNote = implode('; ', $decodedHistories);
                    }
                }

                // Gabungkan setiap histories dengan separator '; '
                $historiesString .= $historiesNote . '; '; 
            }
        }

        // Menghapus '; ' ekstra yang ada di akhir string
        $historiesString = rtrim($historiesString, '; ');
    }

    // Memastikan lokasi digabungkan menjadi satu string
    // Mengambil lokasi yang disimpan dalam bentuk JSON string
    $locationsArray = [];

// Cek apakah data location ada dan dalam format JSON
if (is_string($item->province) && !empty($item->province)) {
    // Jika data province adalah JSON, dekode
    $province = json_decode($item->province, true);
    if (is_array($province) && isset($province['name'])) {
        $locationsArray[] = $province['name'];  // Ambil nama dari JSON
    } else {
        $locationsArray[] = $item->province;  // Ambil string langsung jika bukan JSON
    }
}

if (is_string($item->regency) && !empty($item->regency)) {
    // Jika data regency adalah JSON, dekode
    $regency = json_decode($item->regency, true);
    if (is_array($regency) && isset($regency['name'])) {
        $locationsArray[] = $regency['name'];  // Ambil nama dari JSON
    } else {
        $locationsArray[] = $item->regency;  // Ambil string langsung jika bukan JSON
    }
}

if (is_string($item->subdistrict) && !empty($item->subdistrict)) {
    // Jika data subdistrict adalah JSON, dekode
    $subdistrict = json_decode($item->subdistrict, true);
    if (is_array($subdistrict) && isset($subdistrict['name'])) {
        $locationsArray[] = $subdistrict['name'];  // Ambil nama dari JSON
    } else {
        $locationsArray[] = $item->subdistrict;  // Ambil string langsung jika bukan JSON
    }
}

if (is_string($item->village) && !empty($item->village)) {
    // Jika data village adalah JSON, dekode
    $village = json_decode($item->village, true);
    if (is_array($village) && isset($village['name'])) {
        $locationsArray[] = $village['name'];  // Ambil nama dari JSON
    } else {
        $locationsArray[] = $item->village;  // Ambil string langsung jika bukan JSON
    }
}

// Gabungkan lokasi yang valid menjadi satu string
$locationsString = !empty($locationsArray) ? implode(', ', $locationsArray) : 'Lokasi tidak tersedia';


    $votingCount = 0;
    if ($item->voting) {
        if (is_array($item->voting)) {
            $votingCount = count($item->voting);
        } elseif (is_string($item->voting)) {
            $votingArray = json_decode($item->voting, true);
            if (is_array($votingArray)) {
                $votingCount = count($votingArray);
            }
        }
    }

    // Mendapatkan nama staff yang terkait
    $staffName = $item->response->staff->email ?? 'Tidak Ada';

    // Kembalikan data dalam format array untuk Excel
    return [
        optional($item->user)->email ?? 'Email tidak tersedia',
        $item->created_at->translatedFormat('d F Y H:i'),
        $item->description,
        url('storage/' . $item->image),
        $locationsString, // Lokasi dalam format string
        $votingCount,     // Jumlah voting
        $responseStatus,  // Status pengaduan
        $historiesString, // Progres tanggapan sebagai string
        $staffName        // Nama staff terkait
    ];
}



}
