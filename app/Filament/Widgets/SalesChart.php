<?php

namespace App\Filament\Widgets;

use App\Models\LaporanPenjualan;
use Filament\Widgets\ChartWidget;
use App\Models\Pesanan; // Import model Pesanan yang akan digunakan
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
   // Mendefinisikan judul widget
    protected static ?string $heading = 'Grafik Penjualan';
    
    // Menambahkan filter periode untuk memilih range waktu
    protected function getFilters(): ?array
    {
        return [
            'hari' => 'Hari Ini', // Filter untuk menampilkan data hari ini
            'minggu' => '7 Hari Terakhir', // Filter untuk 7 hari terakhir 
            'bulan' => '30 Hari Terakhir', // Filter untuk 30 hari terakhir
        ];
    }

    // Method utama untuk mengambil data yang akan ditampilkan di grafik
    protected function getData(): array
    {
        // Mengambil filter yang dipilih, default 'hari'
        $filter = $this->filter ?? 'hari';
        
        // Menentukan data berdasarkan filter yang dipilih
        $data = match ($filter) {
            'hari' => $this->getDailyData(), // Jika hari ini
            'minggu' => $this->getWeeklyData(), // Jika 7 hari
            'bulan' => $this->getMonthlyData(), // Jika 30 hari
        };

        // Return format data untuk chart
        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data['totals'], // Data total penjualan
                ],
            ],
            'labels' => $data['labels'], // Label untuk sumbu x
        ];
    }

    // Method untuk mengambil data penjualan per jam dalam hari ini
    private function getDailyData(): array
    {
        // Query untuk mengambil data per jam
        $data = Pesanan::query()
            ->whereDate('created_at', Carbon::today()) // Filter hari ini
            ->selectRaw('HOUR(created_at) as hour') // Ambil jam dari created_at
            ->selectRaw('SUM(total_harga) as total') // Jumlahkan total_harga
            ->groupBy('hour') // Grup berdasarkan jam
            ->orderBy('hour') // Urutkan berdasarkan jam
            ->get();

        // Buat array untuk 24 jam
        $hours = range(0, 23);
        $totals = array_fill(0, 24, 0); // Inisialisasi array dengan nilai 0
        
        // Isi data total penjualan ke array berdasarkan jam
        foreach ($data as $row) {
            $totals[$row->hour] = $row->total;
        }

        return [
            'labels' => array_map(fn($hour) => sprintf('%02d:00', $hour), $hours), // Format label jam
            'totals' => $totals, // Data total penjualan
        ];
    }

    // Method untuk mengambil data penjualan 7 hari terakhir
    private function getWeeklyData(): array
    {
        // Query untuk mengambil data per hari dalam seminggu
        $data = Pesanan::query()
            ->whereBetween('created_at', [Carbon::now()->subDays(6), Carbon::now()]) // Filter 7 hari terakhir
            ->selectRaw('DATE(created_at) as date') // Ambil tanggal
            ->selectRaw('SUM(total_harga) as total') // Jumlahkan total_harga
            ->groupBy('date') // Grup berdasarkan tanggal
            ->orderBy('date') // Urutkan berdasarkan tanggal
            ->get();

        // Buat array tanggal untuk 7 hari terakhir
        $dates = collect(range(6, 0))->map(fn($days) => Carbon::now()->subDays($days));
        $totals = array_fill(0, 7, 0); // Inisialisasi array dengan nilai 0

        // Isi data total penjualan ke array berdasarkan tanggal
        foreach ($data as $row) {
            $index = $dates->search(fn($date) => $date->toDateString() === $row->date);
            if ($index !== false) {
                $totals[$index] = $row->total;
            }
        }

        return [
            'labels' => $dates->map(fn($date) => $date->format('d M'))->toArray(), // Format label tanggal
            'totals' => $totals, // Data total penjualan
        ];
    }

    // Method untuk mengambil data penjualan 30 hari terakhir
    private function getMonthlyData(): array
    {
        // Query untuk mengambil data per hari dalam sebulan
        $data = Pesanan::query()
            ->whereBetween('created_at', [Carbon::now()->subDays(29), Carbon::now()]) // Filter 30 hari terakhir
            ->selectRaw('DATE(created_at) as date') // Ambil tanggal
            ->selectRaw('SUM(total_harga) as total') // Jumlahkan total_harga
            ->groupBy('date') // Grup berdasarkan tanggal
            ->orderBy('date') // Urutkan berdasarkan tanggal
            ->get();

        // Buat array tanggal untuk 30 hari terakhir
        $dates = collect(range(29, 0))->map(fn($days) => Carbon::now()->subDays($days));
        $totals = array_fill(0, 30, 0); // Inisialisasi array dengan nilai 0

        // Isi data total penjualan ke array berdasarkan tanggal
        foreach ($data as $row) {
            $index = $dates->search(fn($date) => $date->toDateString() === $row->date);
            if ($index !== false) {
                $totals[$index] = $row->total;
            }
        }

        return [
            'labels' => $dates->map(fn($date) => $date->format('d M'))->toArray(), // Format label tanggal
            'totals' => $totals, // Data total penjualan
        ];
    }

    // Menentukan tipe chart yang digunakan
    protected function getType(): string
    {
        return 'line'; // Menggunakan tipe line chart
    }
}