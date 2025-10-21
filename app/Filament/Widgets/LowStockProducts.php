<?php

namespace App\Filament\Widgets;

use App\Models\Produk;
use Filament\Tables\Table;  // Add this import
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder; // Add this import

class LowStockProducts extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int|string|array $columnSpan = 'full';

    protected int $minStockThreshold = 10;

    // Change this method signature
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Produk::query()
                    ->where('stok', '<=', $this->minStockThreshold)
                    ->orderBy('stok', 'asc')
            )
            ->columns([
                TextColumn::make('nama')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable()
                    ->color(fn($state) => 
                        $state <= 5 ? 'danger' :
                        ($state <= 8 ? 'warning' : 'success')
                    ),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
            ]);
    }

    // Remove getTableQuery() and getTableColumns() methods

    protected function getTableHeading(): string
    {
        return 'Stok Produk Yang Menipis';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No products with low stock';
    }
}