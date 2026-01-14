<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// 1. Menerima Data dari Controller (UserController@index)
const props = defineProps({
    users: Array,  // Daftar semua user
    roles: Array,  // Daftar pilihan role (Admin, Seller, Pelanggan) untuk dropdown
});

// Setup Flash Message (Notifikasi Sukses)
const page = usePage();
const flashSuccess = computed(() => page.props.flash.success);

// State untuk melacak user mana yang sedang diedit (loading state)
const processingId = ref(null);

// 2. Logika Ganti Role
const changeRole = (user, newRoleId) => {
    // Jangan lakukan apa-apa jika role yang dipilih sama dengan role saat ini
    if (user.role_id === newRoleId) return;

    // Konfirmasi sederhana sebelum mengubah (Opsional, tapi bagus untuk UX)
    if (!confirm(`Yakin ingin mengubah role ${user.name}?`)) {
        // Jika batal, kembalikan dropdown ke nilai semula (reload halaman parsial)
        router.reload(); 
        return;
    }

    processingId.value = user.id; // Aktifkan indikator loading

    // Kirim Request PUT ke backend
    router.put(route('users.updateRole', user.id), {
        role_id: newRoleId
    }, {
        onFinish: () => processingId.value = null, // Matikan loading setelah selesai
        preserveScroll: true, // Jangan scroll ke atas
    });
};
</script>

<template>
    <Head title="Manajemen User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Pengguna & Role</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div v-if="flashSuccess" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline"> {{ flashSuccess }}</span>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Pengguna Sistem</h3>
                    <p class="text-sm text-gray-600 mb-6">Kelola hak akses pengguna aplikasi disini.</p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 border-b text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                                    <th class="py-3 px-4 border-b text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                                    <th class="py-3 px-4 border-b text-left text-xs font-semibold text-gray-600 uppercase">Role Saat Ini</th>
                                    <th class="py-3 px-4 border-b text-left text-xs font-semibold text-gray-600 uppercase">Ubah Role</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-sm text-gray-900 font-medium">{{ user.name }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-600">{{ user.email }}</td>
                                    
                                    <td class="py-3 px-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="{
                                                'bg-purple-100 text-purple-800': user.role.name === 'Admin',
                                                'bg-blue-100 text-blue-800': user.role.name === 'Seller',
                                                'bg-green-100 text-green-800': user.role.name === 'Pelanggan'
                                            }">
                                            {{ user.role.name }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <select 
                                                :value="user.role_id" 
                                                @change="changeRole(user, $event.target.value)"
                                                :disabled="processingId === user.id"
                                                class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer"
                                            >
                                                <option v-for="role in roles" :key="role.id" :value="role.id">
                                                    {{ role.name }}
                                                </option>
                                            </select>
                                            
                                            <span v-if="processingId === user.id" class="text-xs text-gray-500 animate-pulse">
                                                Menyimpan...
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>