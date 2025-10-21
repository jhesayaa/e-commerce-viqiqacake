<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    // Menggunakan SoftDeletes agar data tidak benar-benar dihapus dari database
    // Data hanya akan ditandai dengan timestamp deleted_at saat dihapus
    // Ini penting untuk laporan keuangan agar data historis tetap tersimpan
    use SoftDeletes;

    // Mendefinisikan kolom-kolom yang bisa diisi massal (mass assignment)
    protected $fillable = [
        'kode_pesanan',       // Kode unik pesanan (format: INV-YmdXXXX)
        'nama_pelanggan',     // Nama customer
        'email',              // Email customer untuk konfirmasi
        'telepon',            // Nomor telepon untuk konfirmasi pengiriman
        'alamat',             // Alamat pengiriman
        'quantity',           // Jumlah total item
        'total_harga',        // Total harga sebelum diskon
        'total_akhir',        // Total harga setelah diskon voucher
        'status',             // Status pesanan (pending, diproses, dikirim, selesai, dibatalkan)
        'metode_pembayaran',  // Metode pembayaran (transfer, cod)
        'bank_id',            // ID bank jika pembayaran via transfer
        'bukti_transfer',     // Path file bukti transfer
        'catatan',            // Catatan tambahan dari customer
        'voucher_id'          // ID voucher yang digunakan
    ];

    // Otomatis generate kode pesanan saat membuat pesanan baru
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Format: INV-tanggal-nomor urut
            // Contoh: INV-20240219-0001
            $model->kode_pesanan = 'INV-' . date('Ymd') . '-' . str_pad((Pesanan::count() + 1), 4, '0', STR_PAD_LEFT);
        });
    }

    // Relasi one-to-many: satu pesanan memiliki banyak item
    public function items()
    {
        return $this->hasMany(PesananItem::class);
    }

    // Relasi many-to-many: satu pesanan memiliki banyak produk
    // dengan informasi tambahan (jumlah, harga, subtotal) di pivot table
    public function produk()
    {
        return $this->belongsToMany(Produk::class, 'pesanan_items')
            ->withPivot(['jumlah', 'harga', 'subtotal']);
    }

    // Relasi self-referencing: untuk struktur pesanan yang lebih kompleks
    // Misalnya untuk kasus pesanan yang memiliki sub-pesanan
    public function pesanans()
    {
        return $this->hasMany(self::class);
    }

    // Relasi one-to-many inverse: satu pesanan milik satu voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    // Relasi one-to-many inverse: satu pesanan milik satu bank
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    // Override cara format tanggal untuk menampilkan waktu sesuai timezone Asia/Jakarta
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Jakarta'));
    }
}