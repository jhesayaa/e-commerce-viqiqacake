{{-- views/filament/tables/laporan-footer.blade.php --}}
<div class="px-4 py-3 bg-gray-800">
    <div class="flex justify-between">
        {{-- Menampilkan total keseluruhan pendapatan --}}
        <div>
            <span class="font-bold">Total Keseluruhan:</span>
            {{-- Format angka sebagai mata uang Rupiah --}}
            <span>Rp {{ number_format($total_keseluruhan, 2, ',', '.') }}</span>
        </div>
        {{-- Menampilkan total jumlah transaksi --}}
        <div>
            <span class="font-bold">Total Transaksi:</span>
            <span>{{ $total_transaksi }}</span>
        </div>
    </div>
</div>