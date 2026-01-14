<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, usePage, Link } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import Swal from 'sweetalert2'; 

const props = defineProps({
    products: Object, // Ubah Array jadi Object karena ada data pagination
    filters: Object,
});

// Setup Flash Message
const page = usePage();

// --- FITUR PENCARIAN ---
const search = ref(props.filters.search || '');
// Watcher: Auto search saat mengetik (dengan delay/debounce dikit biar ga berat)
let timeout = null;
watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(route('products.index'), { search: value }, {
            preserveState: true,
            replace: true,
        });
    }, 300);
});

// --- FORM TAMBAH PRODUK ---
const form = useForm({
    name: '',
    price: '',
    stock: '',
});

const submit = () => {
    form.post(route('products.store'), {
        onSuccess: () => {
            form.reset();
            // Notifikasi Cantik
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Produk berhasil ditambahkan.',
                timer: 1500,
                showConfirmButton: false
            });
        },
    });
};

// --- LOGIKA JUAL BARANG ---
const sellProduct = (product) => {
    if (product.stock <= 0) return;

    router.post(route('products.sell', product.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            // Cek flash message dari backend jika perlu, atau langsung popup
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: `-1 Stok ${product.name}`,
                showConfirmButton: false,
                timer: 1000,
                toast: true
            });
        },
        onError: () => {
             Swal.fire({ icon: 'error', title: 'Oops...', text: 'Stok Habis!' });
        }
    });
};

const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
};
</script>

<template>
    <Head title="Inventaris Produk" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventaris Toko</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Input Barang Baru
                    </h3>
                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="col-span-1 md:col-span-2">
                            <label class="text-gray-600 text-sm font-semibold">Nama Produk</label>
                            <input v-model="form.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Kopi Robusta" required>
                        </div>
                        <div>
                            <label class="text-gray-600 text-sm font-semibold">Harga (Rp)</label>
                            <input v-model="form.price" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="15000" required>
                        </div>
                        <div>
                            <label class="text-gray-600 text-sm font-semibold">Stok Awal</label>
                            <div class="flex gap-2">
                                <input v-model="form.stock" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="100" required>
                                <button type="submit" :disabled="form.processing" class="mt-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow transition">
                                    <span v-if="form.processing">...</span>
                                    <span v-else>Simpan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-lg font-bold text-gray-800">Daftar Stok</h3>
                        
                        <div class="relative w-full md:w-1/3">
                            <input v-model="search" type="text" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Cari nama barang...">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Stok</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ product.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatRupiah(product.price) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="{
                                                'bg-green-100 text-green-800': product.stock > 10,
                                                'bg-yellow-100 text-yellow-800': product.stock > 0 && product.stock <= 10,
                                                'bg-red-100 text-red-800': product.stock <= 0
                                            }">
                                            {{ product.stock > 0 ? `${product.stock} Unit` : 'Habis' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button 
                                            @click="sellProduct(product)"
                                            :disabled="product.stock <= 0"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white focus:outline-none transition"
                                            :class="product.stock > 0 ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed'"
                                        >
                                            <svg v-if="product.stock > 0" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                            {{ product.stock > 0 ? 'Jual' : 'Sold Out' }}
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="products.data.length === 0">
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                        Tidak ada barang ditemukan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <div v-for="(link, k) in products.links" :key="k">
                            <Link 
                                v-if="link.url" 
                                :href="link.url" 
                                v-html="link.label"
                                class="px-3 py-1 border rounded text-sm mx-1 hover:bg-gray-100"
                                :class="{ 'bg-indigo-600 text-white font-bold border-indigo-600 hover:bg-indigo-700': link.active, 'bg-white text-gray-700': !link.active }"
                            />
                            <span v-else v-html="link.label" class="px-3 py-1 text-gray-400 text-sm mx-1"></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>