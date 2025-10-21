<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\produk;
use App\Models\Pesanan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LaporanPenjualan;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\LaporanPenjualanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LaporanPenjualanResource\RelationManagers;
use App\Filament\Resources\LaporanPenjualanResource\Pages\EditLaporanPenjualan;
use App\Filament\Resources\LaporanPenjualanResource\Pages\ListLaporanPenjualans;
use App\Filament\Resources\LaporanPenjualanResource\Pages\CreateLaporanPenjualan;

class LaporanPenjualanResource extends Resource
{
    // Menentukan model yang digunakan untuk resource ini
    // LaporanPenjualan sebenarnya menggunakan tabel pesanans
    protected static ?string $model = LaporanPenjualan::class;
    
    // Icon untuk menu di sidebar admin panel
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    
    // Label yang ditampilkan untuk model ini (dalam bentuk jamak)
    protected static ?string $pluralModelLabel = 'Laporan Penjualan';
    
    // Label yang ditampilkan di sidebar navigation
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    
    // Grup navigasi untuk pengelompokan menu
    protected static ?string $navigationGroup = 'Laporan';

    // Konfigurasi untuk tampilan tabel laporan penjualan
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            // Kolom periode/tanggal yang bisa diurutkan
            Tables\Columns\TextColumn::make('tanggal')
                ->label('Periode')
                ->date()
                ->sortable(),
                
            // Kolom total harga per hari - dihitung dari pesanan yang sudah selesai
            Tables\Columns\TextColumn::make('total_harga_per_hari')
                ->money('idr') // Format sebagai mata uang IDR
                ->label('Total Harga Perhari')
                ->getStateUsing(function ($record) {
                    // Menghitung total harga dari semua pesanan dengan status 'selesai' pada tanggal tersebut
                    return Pesanan::where('status', 'selesai')
                        ->whereDate('created_at', $record->tanggal)
                        ->sum('total_akhir');
                }),
                
            // Kolom jumlah transaksi - dihitung dari jumlah pesanan yang sudah selesai
            Tables\Columns\TextColumn::make('jumlah_transaksi')
                ->label('Jumlah Transaksi')
                ->getStateUsing(function ($record) {
                    // Menghitung jumlah pesanan dengan status 'selesai' pada tanggal tersebut
                    return Pesanan::where('status', 'selesai')
                        ->whereDate('created_at', $record->tanggal)
                        ->count();
                }),
                
            // Kolom status dengan badge berwarna
            Tables\Columns\TextColumn::make('status')
                ->badge() // Tampilkan sebagai badge
                ->colors([
                    'success' => 'selesai', // Warna hijau untuk status 'selesai'
                ]),
        ])
        ->modifyQueryUsing(fn (Builder $query) => $query
            // Mengubah query dasar untuk tabel
            ->select(
                // Mengambil tanggal unik dari created_at
                DB::raw('DISTINCT DATE(created_at) as tanggal'),
                // Mengambil ID minimum untuk setiap tanggal (untuk referensi)
                DB::raw('MIN(id) as id'),
                'status'
            )
            ->where('status', 'selesai') // Hanya ambil pesanan dengan status 'selesai'
            ->groupBy(DB::raw('DATE(created_at)'), 'status') // Kelompokkan berdasarkan tanggal dan status
            ->orderBy('tanggal', 'desc') // Urutkan dari tanggal terbaru
            ->distinct() // Hapus duplikat
        )
        ->contentFooter(fn () => view('filament.tables.laporan-footer', [
            // Mengirim data ke view footer:
            // Total keseluruhan penjualan dari semua transaksi yang selesai
            'total_keseluruhan' => Pesanan::where('status', 'selesai')->sum('total_akhir'),
            // Total jumlah transaksi yang selesai
            'total_transaksi' => Pesanan::where('status', 'selesai')->count(),
        ]));
    }

    // Menonaktifkan fitur Create untuk laporan
    // Karena laporan dibuat otomatis berdasarkan pesanan yang selesai
    public static function canCreate(): bool
    {
        return false; // Tidak bisa membuat laporan baru secara manual
    }

    // Mendefinisikan halaman yang tersedia untuk resource ini
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanPenjualans::route('/'), // Hanya ada halaman list/index
        ];
    }
}