<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProdukResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProdukResource\RelationManagers;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class; //menentukan  model apa yang akan digunakan
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square'; //buat kasih icon
    protected static ?string $pluralModelLabel = 'Produk'; //buat kasih label nama
    protected static ?string $navigationLabel = 'Produk'; //buat namakan menu
    protected static ?string $navigationGroup = 'Master'; //buat kasih nama grup

    public static function getNavigationSort(): ?int
    {
        return 1; // buat mengurutkan menu dalam master
    }   

    public static function form(Form $form): Form  // buat form input data
    {
        return $form
            ->schema([
            Forms\Components\TextInput::make('nama')  // field nama produk
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('kategori_id')
                ->label('Kategori')
                ->relationship('kategori', 'nama')
                ->searchable()
                ->preload() 
                ->nullable(),
            Forms\Components\Toggle::make('favorit')
                ->label('Produk Favorit')
                ->helperText('Tandai untuk menampilkan di menu favorit di homepage')
                ->default(false),
            Forms\Components\TextInput::make('harga')  // field harga
                ->required()
                ->numeric() 
                ->prefix('Rp'),
            Forms\Components\TextInput::make('stok')  // field stok
                ->required()
                ->numeric()
                ->default(0)
                ->minValue(0),
            Forms\Components\Textarea::make('deskripsi')  // field deskripsi
                ->maxLength(65535),
            Forms\Components\FileUpload::make('gambar')  // field upload gambar
                ->image()  // hanya menerima file gambar
                ->directory('produk')  // simpan di folder produk
                ->disk('public')  // simpan di disk public
                ->visibility('public')  // set visibility public
                ->preserveFilenames()  // pertahankan nama file asli
                ->imagePreviewHeight('250')  // tinggi preview gambar
                ->loadingIndicatorPosition('left')  // posisi loading indicator
                ->panelAspectRatio('2:1')  // rasio aspek panel
                ->panelLayout('integrated')  // layout panel
                ->imageResizeMode('cover')  // mode resize gambar
                ->maxSize(5120),  // ukuran maksimal file
            Forms\Components\Toggle::make('aktif')  // field status aktif
                ->default(true)
                ->helperText('Tandai untuk menampilkan produk'),
            ]);
    }

    public static function table(Table $table): Table  // buat tampilan tabel
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('nama')  // kolom nama
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('kategori.nama')  // kolom nama
                ->searchable()
                ->sortable(),
            Tables\Columns\IconColumn::make('favorit')
                ->boolean()
                ->label('Favorit'),
            Tables\Columns\TextColumn::make('harga')  // kolom harga
                ->money('idr')
                ->sortable(),
            Tables\Columns\TextColumn::make('stok')
                ->numeric()
                ->sortable()
                ->label('Stok'),
            Tables\Columns\TextColumn::make('deskripsi')  // kolom deskripsi
                ->limit(50),
            Tables\Columns\ImageColumn::make('gambar')  // kolom gambar
                ->disk('public'),
            Tables\Columns\IconColumn::make('aktif')  // kolom status aktif
                ->boolean(),
            ])
            ->filters([  // buat filter pencarian data
                TrashedFilter::make(),
            ])
            ->actions([  // buat aksi pada setiap baris data
                Tables\Actions\EditAction::make(),  // tombol edit
                Tables\Actions\DeleteAction::make(),  // tombol hapus
                Tables\Actions\ForceDeleteAction::make(),  // Tambahkan ini
                Tables\Actions\RestoreAction::make(),      // Tambahkan ini
            ])
            
            ->bulkActions([  // buat aksi untuk multiple data
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),  // tombol hapus multiple data
                Tables\Actions\ForceDeleteBulkAction::make(),  // Tambahkan ini
                Tables\Actions\RestoreBulkAction::make(),      // Tambahkan ini
                ]),
            ]);
    }

    public static function getRelations(): array  // buat relasi dengan tabel lain
    {
        return  [
            //
        ];
    }

    public static function getPages(): array  // buat halaman yang tersedia
    {
        return [
            'index' => Pages\ListProduks::route('/'),  // halaman list produk
            'create' => Pages\CreateProduk::route('/create'),  // halaman buat produk
            'edit' => Pages\EditProduk::route('/{record}/edit'),  // halaman edit produk
        ];
    }
}
