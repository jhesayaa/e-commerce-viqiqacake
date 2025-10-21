@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-serif text-amber-800 mb-4">Checkout</h1>
                <p class="text-amber-600">Lengkapi informasi pengiriman Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Form Checkout -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-bold text-amber-800 mb-4">Informasi Pengiriman</h2>

                        <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="items" value="{{ request()->query('items') }}">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-700 mb-2">Nama Penerima</label>
                                    <input type="text" name="name" required class="w-full border rounded-lg px-3 py-2"
                                        value="{{ auth()->user()->name }}">
                                </div>

                                <div>
                                    <label class="block text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" required class="w-full border rounded-lg px-3 py-2"
                                        value="{{ auth()->user()->email }}">
                                </div>

                                <div>
                                    <label class="block text-gray-700 mb-2">No. Telepon</label>
                                    <input type="tel" name="telepon" required
                                        class="w-full border rounded-lg px-3 py-2"
                                        value="{{ auth()->user()->phone }}">
                                </div>

                                <div>
                                    <label class="block text-gray-700 mb-2">Alamat Lengkap</label>
                                    <textarea name="alamat" required class="w-full border rounded-lg px-3 py-2" rows="3">{{ auth()->user()->address }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-gray-700 mb-2">Metode Pembayaran</label>
                                    <select name="metode_pembayaran" required 
                                            class="w-full border rounded-lg px-3 py-2"
                                            onchange="toggleBankSelection(this.value)">
                                        <option value="">Pilih metode pembayaran</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="cod">Cash on Delivery</option>
                                    </select>
                                </div>
                                
                                <!-- Bank Selection (initially hidden) -->
                                <div id="bank_selection" class="hidden">
                                    <label class="block text-gray-700 mb-2">Pilih Bank</label>
                                    <select name="bank_id" class="w-full border rounded-lg px-3 py-2">
                                        <option value="">Pilih bank tujuan</option>
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}">
                                                {{ $bank->nama_bank }} - {{ $bank->no_rekening }} ({{ $bank->nama_pemilik }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- di checkout.index.blade.php -->
                                <div id="bukti_transfer_section" class="hidden">
                                    <label class="block text-gray-700 mb-2">Bukti Transfer</label>
                                    <div class="space-y-2">
                                        <input type="file" 
                                            name="bukti_transfer" 
                                            accept="image/*"
                                            class="w-full border rounded-lg px-3 py-2" 
                                            id="bukti_transfer_input">
                                        <p class="text-sm text-gray-500">Upload bukti transfer dalam format gambar (JPG, PNG, dsb)</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-gray-700 mb-2">Catatan (opsional)</label>
                                    <textarea name="catatan" class="w-full border rounded-lg px-3 py-2" rows="2"></textarea>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-amber-600 text-white rounded-lg px-4 py-2 mt-6 hover:bg-amber-700">
                                Buat Pesanan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Ringkasan Pesanan -->
                <div class="bg-white rounded-lg shadow-md p-6 h-fit">
                    <h2 class="text-lg font-bold text-amber-800 mb-4">Ringkasan Pesanan</h2>

                    <div class="space-y-4 mb-4">
                        @foreach ($cart->items as $item)
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <img src="{{ asset('storage/' . $item->produk->gambar) }}"
                                        class="w-12 h-12 object-cover rounded">
                                    <div class="ml-3">
                                        <p class="text-amber-800">{{ $item->produk->nama }}</p>
                                        <p class="text-sm text-gray-500">{{ $item->quantity }}x</p>
                                    </div>
                                </div>
                                <span class="text-amber-800">Rp {{ number_format($item->subtotal) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-amber-800">Rp {{ number_format($subtotal) }}</span>
                        </div>

                        @if ($discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Diskon</span>
                                <span>-Rp {{ number_format($discount) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between font-bold">
                            <span class="text-amber-800">Total</span>
                            <span class="text-amber-800">Rp {{ number_format($total) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleBankSelection(value) {
            const bankSelection = document.getElementById('bank_selection');
            const buktiTransfer = document.getElementById('bukti_transfer_section');
            const buktiInput = document.getElementById('bukti_transfer_input');

            bankSelection.style.display = value === 'transfer' ? 'block' : 'none';
            buktiTransfer.style.display = value === 'transfer' ? 'block' : 'none';
            
            const bankInput = bankSelection.querySelector('select');
            bankInput.required = value === 'transfer';
            buktiInput.required = value === 'transfer';
        }
        </script>
@endsection
