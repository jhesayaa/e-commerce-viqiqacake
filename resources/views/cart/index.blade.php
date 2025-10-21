@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-serif text-amber-800 mb-4">Keranjang Belanja</h1>
                <p class="text-amber-600">Checkout produk pilihan Anda</p>
            </div>

            @if (isset($cart) && $cart->items->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Daftar Item Keranjang -->
                    <div class="md:col-span-2">
                        @foreach ($cart->items as $item)
                            <div class="bg-white rounded-lg shadow-md p-4 md:p-6 mb-4 cart-item">
                                <!-- Layout item keranjang yang dioptimalkan untuk mobile -->
                                <div class="flex flex-col sm:flex-row">
                                    <!-- Checkbox dan gambar berdampingan di mobile -->
                                    <div class="flex items-start mb-3 sm:mb-0">
                                        <div class="mr-2 pt-2">
                                            <input type="checkbox"
                                                class="w-5 h-5 text-amber-600 rounded border-gray-300 focus:ring-amber-500 cart-item-checkbox"
                                                data-item-id="{{ $item->id }}" data-price="{{ $item->price }}"
                                                data-quantity="{{ $item->quantity }}" onchange="updateTotal()">
                                        </div>
                                        <img src="{{ asset('storage/' . $item->produk->gambar) }}"
                                            class="w-20 h-20 object-cover rounded">
                                    </div>

                                    <!-- Detail produk, quantity dan subtotal -->
                                    <div class="flex-1 ml-0 sm:ml-4">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <!-- Info produk -->
                                            <div>
                                                <h2 class="text-lg font-bold text-amber-800">{{ $item->produk->nama }}</h2>
                                                <p class="text-amber-600">Rp {{ number_format($item->price) }}</p>
                                            </div>

                                            <!-- Subtotal (hanya tampil di desktop) -->
                                            <div class="hidden sm:block text-right">
                                                <p class="font-bold text-amber-800 subtotal">Rp
                                                    {{ number_format($item->subtotal) }}</p>
                                            </div>
                                        </div>

                                        <div class="flex flex-col sm:flex-row sm:items-center mt-3 sm:mt-2">
                                            <!-- Quantity control -->
                                            <div class="flex items-center">
                                                <div class="flex items-center border rounded-lg">
                                                    <button type="button"
                                                        class="px-3 py-1 text-amber-800 hover:bg-amber-100 decrease-quantity"
                                                        data-item-id="{{ $item->id }}"
                                                        onclick="decreaseQuantity(this)">-</button>
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                        class="w-12 border-x text-center py-1 focus:outline-none quantity-input"
                                                        data-item-id="{{ $item->id }}" data-price="{{ $item->price }}"
                                                        min="1" onchange="updateCart(this)">
                                                    <button type="button"
                                                        class="px-3 py-1 text-amber-800 hover:bg-amber-100 increase-quantity"
                                                        data-item-id="{{ $item->id }}"
                                                        onclick="increaseQuantity(this)">+</button>
                                                </div>

                                                <!-- Tombol hapus -->
                                                <form action="{{ route('cart.remove-item', $item) }}" method="POST"
                                                    class="ml-4">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-500 hover:text-red-700">Hapus</button>
                                                </form>
                                            </div>

                                            <!-- Subtotal (hanya tampil di mobile) -->
                                            <div class="sm:hidden mt-3">
                                                <p class="font-bold text-amber-800">Subtotal: <span class="subtotal">Rp
                                                        {{ number_format($item->subtotal) }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Ringkasan Belanja -->
                    <div class="bg-white rounded-lg shadow-md p-6 h-fit sticky top-24">
                        <h2 class="text-lg font-bold text-amber-800 mb-4">Ringkasan Belanja</h2>

                        <!-- Form Voucher -->
                        <div class="mb-4">
                            <form id="voucher-form" class="flex flex-col sm:flex-row gap-2">
                                <input type="text" id="voucher-code" name="voucher_code"
                                    placeholder="Masukkan kode voucher"
                                    class="w-full flex-1 border rounded-lg px-3 py-2 text-sm mb-2 sm:mb-0">
                                <button type="submit"
                                    class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 text-sm">
                                    Pakai
                                </button>
                            </form>
                        </div>

                        <!-- Subtotal dan Total -->
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-amber-800 cart-subtotal">Rp 0</span>
                            </div>

                            <div class="flex justify-between text-green-600">
                                <span>Diskon</span>
                                <span class="voucher-discount">-Rp 0</span>
                            </div>

                            <div class="flex justify-between font-bold">
                                <span class="text-amber-800">Total</span>
                                <span class="text-amber-800 cart-total">Rp 0</span>
                            </div>
                        </div>

                        <button onclick="proceedToCheckout()"
                            class="w-full text-center bg-amber-600 text-white rounded-lg px-4 py-3 hover:bg-amber-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            id="checkout-button" disabled>
                            Lanjut ke Pembayaran
                        </button>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="mb-4">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <p class="text-gray-600 mb-4">Keranjang belanja Anda kosong</p>
                    <a href="/produk"
                        class="inline-block bg-amber-600 text-white rounded-lg px-6 py-2 hover:bg-amber-700 transition-colors">
                        Mulai Belanja
                    </a>
                </div>
            @endif
        </div>


        <script>
            // Variable untuk menyimpan voucher aktif
            let activeVoucher = null;

            // Format angka ke format Rupiah
            function formatRupiah(amount) {
                return new Intl.NumberFormat('id-ID').format(Math.max(0, amount));
            }

            // Fungsi untuk menerapkan voucher
            function applyVoucher() {
                // Ambil nilai kode voucher dari input dan hilangkan spasi di awal/akhir
                const code = document.getElementById('voucher-code').value.trim();

                // Validasi: Jika kode kosong, tampilkan alert dan hentikan fungsi
                if (!code) {
                    alert('Masukkan kode voucher');
                    return;
                }

                // Ambil CSRF token dari meta tag untuk keamanan request
                const token = document.querySelector('meta[name="csrf-token"]').content;

                // Kirim request ke server untuk memvalidasi voucher
                fetch('/cart/apply-voucher', {
                        method: 'POST', // Menggunakan method POST
                        headers: {
                            'Content-Type': 'application/json', // Format data JSON
                            'X-CSRF-TOKEN': token // Token CSRF untuk keamanan
                        },
                        body: JSON.stringify({ // Kirim kode voucher dalam format JSON
                            code: code
                        })
                    })
                    .then(response => {
                        // Cek apakah response dari server OK (status 200-299)
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json(); // Parse response sebagai JSON
                    })
                    .then(data => {
                        // Jika server mengembalikan success = true
                        if (data.success) {
                            // Simpan data voucher ke variable global
                            activeVoucher = {
                                id: data.voucher.id,
                                value: parseFloat(data.voucher.value) // Konversi nilai ke number
                            };

                            // Update tampilan nilai diskon di UI
                            updateVoucherDisplay(data.voucher.value);

                            // Hitung ulang total belanja dengan diskon
                            updateTotal();

                            // Bersihkan input field voucher
                            document.getElementById('voucher-code').value = '';

                            // Tampilkan pesan sukses
                            alert('Voucher berhasil digunakan');
                        } else {
                            // Jika server mengembalikan success = false
                            throw new Error(data.message || 'Voucher tidak valid');
                        }
                    })
                    .catch(error => {
                        // Tangani semua error yang mungkin terjadi:
                        // - Error network
                        // - Error response tidak OK
                        // - Error voucher tidak valid
                        console.error('Error:', error);
                        alert(error.message || 'Terjadi kesalahan saat menggunakan voucher');
                    });
            }

            // Fungsi untuk update tampilan voucher
            function updateVoucherDisplay(discountAmount) {
                const discountElement = document.querySelector('.voucher-discount');
                if (discountElement) {
                    discountElement.textContent = `-Rp ${formatRupiah(discountAmount)}`;
                }
            }

            // Fungsi untuk update quantity
            function updateCart(input) {
                const quantity = parseInt(input.value);
                const itemId = input.dataset.itemId;
                const token = document.querySelector('meta[name="csrf-token"]').content;

                if (quantity < 1) {
                    input.value = 1;
                    return;
                }

                fetch(`/cart/update-quantity/${itemId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const cartItem = input.closest('.cart-item');
                            const pricePerItem = parseFloat(input.dataset.price);
                            const subtotal = pricePerItem * quantity;

                            // Update subtotal displays
                            cartItem.querySelectorAll('.subtotal').forEach(el => {
                                el.textContent = 'Rp ' + formatRupiah(subtotal);
                            });

                            // Update checkbox data
                            const checkbox = cartItem.querySelector('.cart-item-checkbox');
                            if (checkbox) {
                                checkbox.dataset.quantity = quantity;
                                if (checkbox.checked) {
                                    updateTotal();
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal mengupdate jumlah barang');
                    });
            }

            // Fungsi untuk increment quantity
            function increaseQuantity(button) {
                const input = button.parentElement.querySelector('input');
                input.value = parseInt(input.value) + 1;
                updateCart(input);
            }

            // Fungsi untuk decrement quantity
            function decreaseQuantity(button) {
                const input = button.parentElement.querySelector('input');
                const currentValue = parseInt(input.value);
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    updateCart(input);
                }
            }

            // Fungsi untuk update total
            function updateTotal() {
                let subtotal = 0;
                let checkedItems = [];

                // Hitung subtotal dari item yang dicentang
                document.querySelectorAll('.cart-item-checkbox:checked').forEach(checkbox => {
                    const price = parseFloat(checkbox.dataset.price);
                    const quantity = parseInt(checkbox.dataset.quantity);
                    subtotal += price * quantity;
                    checkedItems.push(checkbox.dataset.itemId);
                });

                // Hitung diskon HANYA jika ada voucher aktif
                let discount = 0;
                if (activeVoucher && activeVoucher.value) {
                    discount = activeVoucher.value;
                }

                // Hitung total (pastikan tidak negatif)
                const total = Math.max(0, subtotal - discount);

                // Update tampilan
                document.querySelector('.cart-subtotal').textContent = 'Rp ' + formatRupiah(subtotal);
                document.querySelector('.voucher-discount').textContent = '-Rp ' + formatRupiah(discount);
                document.querySelector('.cart-total').textContent = 'Rp ' + formatRupiah(total);

                // Update status tombol checkout
                const checkoutButton = document.getElementById('checkout-button');
                if (checkoutButton) {
                    const isDisabled = checkedItems.length === 0;
                    checkoutButton.disabled = isDisabled;
                    checkoutButton.classList.toggle('opacity-50', isDisabled);
                    checkoutButton.classList.toggle('cursor-not-allowed', isDisabled);
                }

                // Simpan state ke localStorage
                localStorage.setItem('selectedItems', JSON.stringify(checkedItems));
                if (activeVoucher) {
                    localStorage.setItem('activeVoucher', JSON.stringify(activeVoucher));
                } else {
                    localStorage.removeItem('activeVoucher');
                }
            }

            // Fungsi untuk proses ke checkout
            function proceedToCheckout() {
                const selectedItems = JSON.parse(localStorage.getItem('selectedItems') || '[]');
                if (selectedItems.length === 0) {
                    alert('Pilih minimal satu produk untuk checkout');
                    return;
                }

                let checkoutUrl = `/checkout?items=${selectedItems.join(',')}`;
                if (activeVoucher && activeVoucher.id) {
                    checkoutUrl += `&voucher_id=${activeVoucher.id}`;
                }

                window.location.href = checkoutUrl;
            }

            // Event Listeners
            document.addEventListener('DOMContentLoaded', function() {
                // Reset voucher state
                activeVoucher = null;
                localStorage.removeItem('activeVoucher');

                // Reset tampilan diskon
                const discountElement = document.querySelector('.voucher-discount');
                if (discountElement) {
                    discountElement.textContent = '-Rp 0';
                }

                // Restore checkbox state
                const savedItems = JSON.parse(localStorage.getItem('selectedItems') || '[]');
                savedItems.forEach(itemId => {
                    const checkbox = document.querySelector(`.cart-item-checkbox[data-item-id="${itemId}"]`);
                    if (checkbox) checkbox.checked = true;
                });

                // Update total tanpa voucher
                updateTotal();

                // Add event listeners
                document.getElementById('voucher-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    applyVoucher();
                });

                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.addEventListener('change', function() {
                        updateCart(this);
                    });
                });

                document.querySelectorAll('.cart-item-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', updateTotal);
                });
            });
        </script>
    </div>
@endsection
