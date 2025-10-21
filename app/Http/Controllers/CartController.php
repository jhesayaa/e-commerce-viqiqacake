<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cart;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\Voucher;
use App\Models\CartItem;
use App\Models\PesananItem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja
     * - Mengambil cart yang aktif dari user yang login
     * - Mereset session voucher untuk mencegah penggunaan voucher lama
     */
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->where('status', 'active')
            ->with('items.produk')
            ->first();

        // Reset session voucher jika ada
        session()->forget('active_voucher');


        return view('cart.index', compact('cart'));
    }

    /**
     * Menambahkan produk ke keranjang
     * - Validasi stok produk
     * - Membuat/mengupdate cart dan cart item
     * - Mengupdate total cart
     */
    public function addToCart(Request $request)
    {
        $produk = Produk::findOrFail($request->produk_id);

        // Cek stok
        if ($produk->stok < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $cart = Cart::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'status' => 'active'
            ]
        );

        $cartItem = CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'produk_id' => $produk->id,
            ],
            [
                'quantity' => $request->quantity,
                'price' => $produk->harga,
                'subtotal' => $produk->harga * $request->quantity
            ]
        );

        $this->updateCartTotals($cart);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Memproses penggunaan voucher
     * - Validasi kode voucher
     * - Cek apakah voucher masih aktif (tidak dihapus)
     * - Menyimpan voucher ke session
     * - Mengembalikan response dengan data voucher dan nilai diskon
     */
    public function applyVoucher(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string'
            ]);

            // Cari voucher yang aktif (tidak di-delete)
            $voucher = Voucher::whereNull('deleted_at')
                ->where('code', $request->code)
                ->first();

            if (!$voucher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode voucher tidak valid'
                ], 404);
            }

            // Simpan voucher ke session
            session(['active_voucher' => $voucher->id]);

            return response()->json([
                'success' => true,
                'message' => 'Voucher berhasil digunakan',
                'voucher' => [
                    'id' => $voucher->id,
                    'value' => (float)$voucher->price, // Konversi ke float untuk JSON
                    'code' => $voucher->code
                ],
                'discount' => (float)$voucher->price
            ]);
        } catch (\Exception $e) {
            Log::error('Voucher Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses voucher'
            ], 500);
        }
    }

    /**
     * Menampilkan halaman checkout
     * - Memvalidasi item yang dipilih
     * - Menghitung total dan diskon
     * - Mengambil data bank aktif
     * - Mengelola voucher untuk checkout
     */
    public function showCheckout(Request $request)
    {
        $selectedItems = explode(',', $request->query('items', ''));

        $cart = Cart::where('user_id', auth()->id())
            ->where('status', 'active')
            ->with(['items' => function ($query) use ($selectedItems) {
                $query->whereIn('id', $selectedItems);
            }])
            ->with('items.produk')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Pilih minimal satu produk untuk checkout');
        }

        // Hanya gunakan voucher jika ada dalam query parameters (dikirim dari cart)
        $discount = 0;
        $activeVoucher = null;

        if ($request->has('voucher_id')) {
            $voucherId = $request->voucher_id;
            $voucher = Voucher::find($voucherId);
            if ($voucher) {
                $discount = $voucher->price;
                $activeVoucher = $voucher;
            }
        }

        // Hapus session voucher yang mungkin tersisa dari checkout sebelumnya
        session()->forget('active_voucher');

        // Hanya simpan ke session jika ada voucher yang valid
        if ($activeVoucher) {
            session(['checkout_voucher' => $activeVoucher->id]);
        }

        // Ambil daftar bank yang aktif
        $banks = Bank::where('status', true)->get();

        // Hitung total setelah diskon
        $subtotal = $cart->items->sum('subtotal');
        $total = $subtotal - $discount;

        return view('checkout.index', compact('cart', 'discount', 'total', 'subtotal', 'banks'));
    }

    /**
     * Memproses checkout/pemesanan
     * - Validasi input form checkout
     * - Mengecek stok produk
     * - Membuat pesanan baru
     * - Mengupload bukti transfer jika ada
     * - Mengurangi stok produk
     * - Menghapus item dari keranjang
     * - Menggunakan database transaction untuk keamanan data
     */
    public function processCheckout(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'telepon' => 'required',
            'alamat' => 'required',
            'metode_pembayaran' => 'required|in:transfer,cod',
            'bank_id' => 'required_if:metode_pembayaran,transfer',
            'bukti_transfer' => 'required_if:metode_pembayaran,transfer|image|max:2048'
        ]);
        // Ambil item yang dipilih dari query parameter
        $selectedItems = explode(',', $request->query('items', ''));

        // Ambil cart dengan items yang dipilih saja
        $cart = Cart::where('user_id', auth()->id())
            ->where('status', 'active')
            ->with(['items' => function ($query) use ($selectedItems) {
                $query->whereIn('id', $selectedItems);
            }])
            ->with('items.produk')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal satu produk untuk checkout');
        }

        if ($request->has('voucher_id')) {
            $voucher = Voucher::find($request->voucher_id);
            if ($voucher) {
                $discount = $voucher->tipe === 'percentage'
                    ? ($totalAmount * $voucher->nilai / 100)
                    : $voucher->nilai;

                $totalAmount = $totalAmount - $discount;
            }
        }

        try {
            DB::beginTransaction();

            // Ambil cart dan items yang dipilih
            $selectedItems = explode(',', $request->items);
            $cart = Cart::where('user_id', auth()->id())
                ->where('status', 'active')
                ->with(['items' => function ($query) use ($selectedItems) {
                    $query->whereIn('id', $selectedItems);
                }])
                ->with('items.produk')
                ->firstOrFail();

            // Hitung total
            $totalAmount = $cart->items->sum('subtotal');
            $discount = 0;
            $voucherId = null;

            // Gunakan voucher HANYA jika ada dalam session checkout_voucher
            // (ini adalah voucher yang dikirim dari cart ke checkout)
            if (session()->has('checkout_voucher')) {
                $voucherId = session('checkout_voucher');
                $voucher = Voucher::find($voucherId);
                if ($voucher) {
                    $discount = $voucher->price;
                }
            }

            $finalAmount = $totalAmount - $discount;

            // Upload bukti transfer jika ada
            $buktiTransferPath = null;
            if ($request->hasFile('bukti_transfer')) {
                $buktiTransferPath = $request->file('bukti_transfer')
                    ->store('bukti-transfer', 'public');
            }

            // Buat pesanan
            $pesanan = Pesanan::create([
                'nama_pelanggan' => $request->name,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'quantity' => $cart->items->sum('quantity'),
                'total_harga' => $totalAmount,
                'total_akhir' => $finalAmount,
                'status' => 'pending',
                'metode_pembayaran' => $request->metode_pembayaran,
                'bank_id' => $request->bank_id,
                'bukti_transfer' => $buktiTransferPath,
                'catatan' => $request->catatan,
                'voucher_id' => $voucherId // Simpan voucher_id jika ada
            ]);

            // Loop melalui setiap item yang ada di keranjang
            foreach ($cart->items as $item) {
                // Cek apakah stok produk mencukupi untuk pesanan
                if ($item->produk->stok < $item->quantity) {
                    // Jika stok tidak cukup, lempar exception dengan pesan error
                    throw new \Exception("Stok {$item->produk->nama} tidak mencukupi");
                }

                // Buat record baru di tabel pesanan_items dengan data dari cart_items
                PesananItem::create([
                    'pesanan_id' => $pesanan->id,      // ID dari pesanan yang baru dibuat
                    'produk_id' => $item->produk_id,   // ID produk yang dipesan
                    'jumlah' => $item->quantity,       // Jumlah yang dipesan
                    'harga' => $item->price,           // Harga per item
                    'subtotal' => $item->subtotal      // Total harga (harga × jumlah)
                ]);

                // Kurangi stok produk sesuai jumlah yang dibeli
                $item->produk->decrement('stok', $item->quantity);

                // Hapus item dari keranjang dan keranjangnya
                $cart->items()->delete();  // Hapus semua items di keranjang
                $cart->delete();          // Hapus keranjang
            }

            // Setelah loop selesai, cek apakah keranjang kosong
            if ($cart->items()->count() === 0) {
                // Jika kosong, update status keranjang menjadi 'completed'
                $cart->update(['status' => 'completed']);
            } else {
                // Jika masih ada item lain di keranjang
                // Update total amount keranjang berdasarkan item yang tersisa
                $cart->update([
                    'total_amount' => $cart->items()->sum('subtotal')
                ]);
            }

            DB::commit();

            // Hapus session voucher setelah pesanan dibuat
            session()->forget('checkout_voucher');

            return redirect()->route('pesanan.success', $pesanan)
                ->with('success', 'Pesanan berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function success(Pesanan $pesanan)
    {
        return view('checkout.success', compact('pesanan'));
    }

    private function updateCartTotals(Cart $cart)
    {
        $totalAmount = $cart->items->sum('subtotal');
        $cart->update([
            'total_amount' => $totalAmount
        ]);
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        try {
            $request->validate([
                'quantity' => 'required|numeric|min:1'
            ]);

            // Cek stok
            if ($cartItem->produk->stok < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ], 422);
            }

            // Update quantity
            $cartItem->update([
                'quantity' => $request->quantity,
                'subtotal' => $cartItem->price * $request->quantity
            ]);

            // Update cart total
            $cart = $cartItem->cart;
            $cart->update([
                'total_amount' => $cart->items->sum('subtotal')
            ]);

            return response()->json([
                'success' => true,
                'subtotal' => $cartItem->subtotal,
                'total' => $cart->total_amount,
                'message' => 'Quantity updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating quantity'
            ], 500);
        }
    }

    public function removeItem(CartItem $cartItem)
    {
        try {
            // Ambil cart sebelum menghapus item
            $cart = $cartItem->cart;

            // Hapus item
            $cartItem->delete();

            // Update total cart
            $this->updateCartTotals($cart);

            return redirect()->route('cart.index')
                ->with('success', 'Produk berhasil dihapus dari keranjang');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk dari keranjang');
        }
    }
}
