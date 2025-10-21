<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\Voucher;
use App\Models\Bank;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LaporanPenjualan;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload; 
use App\Filament\Resources\PesananResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PesananResource\RelationManagers;
use App\Filament\Resources\PesananResource\Pages\EditPesanan;
use App\Filament\Resources\PesananResource\Pages\ListPesanans;
use App\Filament\Resources\PesananResource\Pages\CreatePesanan;

class PesananResource extends Resource
{
    // Menentukan model yang digunakan untuk resource ini
    protected static ?string $model = Pesanan::class;
    
    // Icon untuk menu di sidebar admin panel
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    // Label yang ditampilkan untuk model ini (dalam bentuk jamak)
    protected static ?string $pluralModelLabel = 'Pesanan';
    
    // Label yang ditampilkan di sidebar navigation
    protected static ?string $navigationLabel = 'Pesanan';
    
    // Grup navigasi untuk pengelompokan menu
    protected static ?string $navigationGroup = 'Master';

    // Menentukan urutan tampilan menu di sidebar
    public static function getNavigationSort(): ?int
    {
        return 1; // Urutan kedua dalam grup Master
    }

    // Mendefinisikan struktur form untuk membuat/mengedit pesanan
    public static function form(Form $form): Form
    {
        return $form->schema([
            // Field nama pelanggan - wajib diisi
            Forms\Components\TextInput::make('nama_pelanggan')
                ->required(),
                
            // Field email - wajib diisi dengan format email
            Forms\Components\TextInput::make('email')
                ->email()
                ->required(),
                
            // Field telepon - wajib diisi dengan format numerik
            Forms\Components\TextInput::make('telepon')
                ->numeric()
                ->required(),
                
            // Field alamat - wajib diisi
            Forms\Components\Textarea::make('alamat')
                ->required(),
                
            // Repeater untuk item pesanan - bisa menambahkan multiple produk
            Forms\Components\Repeater::make('items')
                ->relationship() // Menghubungkan dengan relasi items di model Pesanan
                ->schema([
                    // Dropdown pilih produk
                    Forms\Components\Select::make('produk_id')
                        ->label('Produk')
                        ->options(Produk::where('stok', '>', 0)->pluck('nama', 'id')) // Hanya tampilkan produk yang stoknya > 0
                        ->required()
                        ->reactive() // Bereaksi saat nilai berubah
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            // Otomatis isi harga produk saat produk dipilih
                            $produk = Produk::find($state);
                            if ($produk) {
                                $set('harga', $produk->harga);
                            }
                        }),
                    // Field jumlah item
                    Forms\Components\TextInput::make('jumlah')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required()
                        ->reactive() // Bereaksi saat nilai berubah
                        ->afterStateUpdated(function ($state, $get, Forms\Set $set) {
                            // Hitung subtotal otomatis (harga x jumlah)
                            $harga = $get('harga');
                            $subtotal = $harga * $state;
                            $set('subtotal', $subtotal);
                            
                            // Update total harga keseluruhan
                            $container = $get('../../items') ?? [];
                            $totalHarga = collect($container)->sum('subtotal');
                            $set('../../total_harga', $totalHarga);

                            // Set total_akhir sama dengan total_harga jika tidak ada voucher
                            $set('../../total_akhir', $totalHarga);
                        }),
                    // Field harga - disabled, diisi otomatis
                    Forms\Components\TextInput::make('harga')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),
                    // Field subtotal - disabled, dihitung otomatis
                    Forms\Components\TextInput::make('subtotal')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),
                ])
                ->live() // Update realtime
                ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                    // Pastikan data subtotal valid sebelum disimpan
                    $data['subtotal'] = $data['harga'] * $data['jumlah'];
                    return $data;
                })
                ->reactive(),
                
            // Field total harga pesanan - disabled, dihitung otomatis
            Forms\Components\TextInput::make('total_harga')
                ->numeric()
                ->required()
                ->disabled()
                ->dehydrated(),

            // Dropdown pilih voucher untuk diskon
            Forms\Components\Select::make('voucher_id')
                ->label('Voucher')
                ->options(Voucher::pluck('name', 'id'))
                ->searchable()
                ->reactive() // Bereaksi saat nilai berubah
                ->afterStateUpdated(function ($state, $get, Forms\Set $set) {
                    // Hitung total akhir setelah diskon voucher
                    $totalHarga = $get('total_harga') ?? 0;
                    if ($state) {
                        $voucher = Voucher::find($state);
                        if ($voucher) {
                            $totalAkhir = $totalHarga - $voucher->price;
                            $set('total_akhir', max(0, $totalAkhir)); // Pastikan tidak negatif
                        }
                    } else {
                        $set('total_akhir', $totalHarga);
                    }
                }),

            // Field total akhir - disabled, dihitung otomatis
            Forms\Components\TextInput::make('total_akhir')
                ->label('Total Akhir')
                ->numeric()
                ->required()
                ->disabled()
                ->dehydrated(),
                
            // Dropdown status pesanan
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'diproses' => 'Diproses',
                    'dikirim' => 'Dikirim',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                ])
                ->default('pending')
                ->required(),
                
            // Dropdown metode pembayaran
            Select::make('metode_pembayaran')
                ->options([
                    'transfer' => 'Transfer Bank',
                    'cod' => 'Cash on Delivery',
                ])
                ->required()
                ->live() // Update realtime
                ->afterStateUpdated(function ($state, $set) {
                    // Reset field bank saat metode bukan transfer
                    if ($state !== 'transfer') {
                        $set('bank_id', null);
                    }
                }),
    
            // Dropdown pilih bank (hanya muncul jika metode = transfer)
            Select::make('bank_id')
                ->label('Bank')
                ->options(fn () => Bank::where('status', true)
                    ->pluck('nama_bank', 'id'))
                ->visible(fn (callable $get) => $get('metode_pembayaran') === 'transfer') // Hanya tampil jika metode = transfer
                ->required(fn (callable $get) => $get('metode_pembayaran') === 'transfer') // Wajib jika metode = transfer
                ->searchable()
                ->preload(),
                
            // Upload bukti transfer (hanya muncul jika metode = transfer)
            FileUpload::make('bukti_transfer')
                ->image()
                ->directory('bukti-transfer') // Simpan ke direktori ini
                ->visible(fn (callable $get) => $get('metode_pembayaran') === 'transfer') // Hanya tampil jika metode = transfer
                ->required(fn (callable $get) => $get('metode_pembayaran') === 'transfer') // Wajib jika metode = transfer
                ->enableDownload()
                ->enableOpen(),
                
            // Field catatan tambahan
            Forms\Components\Textarea::make('catatan'),
        ]);
    }

    // Konfigurasi tampilan tabel pesanan
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            // Kolom kode pesanan
            Tables\Columns\TextColumn::make('kode_pesanan')
                ->searchable()
                ->sortable(),
                
            // Kolom nama pelanggan
            Tables\Columns\TextColumn::make('nama_pelanggan')
                ->searchable(),
                
            // Kolom metode pembayaran
            Tables\Columns\TextColumn::make('metode_pembayaran')
                ->label('Metode Pembayaran')
                ->formatStateUsing(fn (string $state): string => match($state) {
                    'transfer' => 'Transfer Bank',
                    'cod' => 'Cash on Delivery',
                    default => $state,
                })
                ->sortable()
                ->searchable(),
                    
            // Kolom nama voucher yang digunakan
            Tables\Columns\TextColumn::make('voucher.name')
                ->label('Voucher')
                ->default('-'),
                
            // Kolom total akhir (setelah diskon)
            Tables\Columns\TextColumn::make('total_akhir')
                ->label('Total Akhir')
                ->money('idr')
                ->sortable(),
                
            // Kolom bukti transfer (hanya tampil jika metode = transfer)
            Tables\Columns\ImageColumn::make('bukti_transfer')
                ->label('Bukti Transfer')
                ->visible(fn ($record) => $record && $record->metode_pembayaran === 'transfer'),
                
            // Kolom status dengan badge berwarna
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->colors([
                    'danger' => 'dibatalkan',   // Merah untuk dibatalkan
                    'warning' => 'pending',     // Kuning untuk pending
                    'primary' => 'diproses',    // Biru untuk diproses
                    'info' => 'dikirim',        // Biru muda untuk dikirim
                    'success' => 'selesai',     // Hijau untuk selesai
                ]),
                
            // Kolom tanggal pembuatan pesanan
            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal')
                ->dateTime('Y-m-d H:i:s', 'Asia/Jakarta')
                ->sortable(),
        ])
        ->filters([
            // Filter untuk data yang sudah dihapus (soft delete)
            TrashedFilter::make(),
            
            // Filter berdasarkan status
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'diproses' => 'Diproses',
                    'dikirim' => 'Dikirim',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                ]),
                
            // Filter berdasarkan rentang tanggal
            Tables\Filters\Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('dari'),  // Tanggal mulai
                    Forms\Components\DatePicker::make('sampai'),  // Tanggal akhir
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when(
                            $data['dari'],
                            fn($query) => $query->whereDate('created_at', '>=', $data['dari`'])
                        )
                        ->when(
                            $data['sampai'],
                            fn($query) => $query->whereDate('created_at', '<=', $data['sampai'])
                        );
                })
        ])
        ->actions([
            // Grup aksi untuk setiap baris data
            Tables\Actions\ActionGroup::make([
                // Tombol lihat detail
                Tables\Actions\ViewAction::make(),
                
                // Tombol update status
                Tables\Actions\Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        // Form dropdown untuk memilih status
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'diproses' => 'Diproses',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->required()
                    ])
                    ->action(function (Pesanan $record, array $data) {
                        // Update status pesanan
                        $record->update([
                            'status' => $data['status']
                        ]);
                        
                        // Tampilkan notifikasi sukses
                        Notification::make()
                            ->title('Status updated successfully')
                            ->success()
                            ->send();
                    }),
                    
                // Tombol hapus (soft delete)
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(), // Tampilkan konfirmasi sebelum hapus
                    
                // Tombol hapus permanen
                Tables\Actions\ForceDeleteAction::make(),
                
                // Tombol restore (untuk data yang sudah dihapus)
                Tables\Actions\RestoreAction::make(),
            ])
        ])
        ->bulkActions([
            // Grup aksi untuk multiple data
            Tables\Actions\BulkActionGroup::make([
                // Bulk hapus (soft delete)
                Tables\Actions\DeleteBulkAction::make(),
                
                // Bulk hapus permanen
                Tables\Actions\ForceDeleteBulkAction::make(),
                
                // Bulk restore
                Tables\Actions\RestoreBulkAction::make(),
            ]),
        ])
        ->defaultSort('created_at', 'desc'); // Urutkan pesanan dari yang terbaru
    }

    // Mendefinisikan relasi yang bisa diakses dari resource ini
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // Fungsi untuk menangani pembuatan record baru
    protected function handleRecordCreation(array $data): Model
    {
        // Hitung total akhir berdasarkan voucher yang digunakan
        if (!isset($data['voucher_id'])) {
            $data['total_akhir'] = $data['total_harga'];
        } else {
            $voucher = Voucher::find($data['voucher_id']);
            if ($voucher) {
                $data['total_akhir'] = $data['total_harga'] - $voucher->price;
            } else {
                $data['total_akhir'] = $data['total_harga'];
            }
        }
    
        // Buat pesanan baru
        $pesanan = static::getModel()::create($data);
        
        // Simpan item-item pesanan
        foreach ($data['items'] as $item) {
            $pesanan->items()->create([
                'produk_id' => $item['produk_id'],
                'jumlah' => $item['jumlah'],
                'harga' => $item['harga'],
                'subtotal' => $item['subtotal'],
            ]);
        }
        
        return $pesanan;
    }

    // Fungsi untuk otomatis membuat kode pesanan
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Format: INV-tanggal-nomor urut
            $model->kode_pesanan = 'INV-' . date('Ymd') . '-' . str_pad((Pesanan::count() + 1), 4, '0', STR_PAD_LEFT);
        });
    }

    // Mendefinisikan halaman-halaman untuk resource ini
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesanans::route('/'),             // Halaman list pesanan
            'create' => Pages\CreatePesanan::route('/create'),     // Halaman buat pesanan baru
            'edit' => Pages\EditPesanan::route('/{record}/edit'),  // Halaman edit pesanan
        ];
    }
}