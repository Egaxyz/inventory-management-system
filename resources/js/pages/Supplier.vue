<script setup>
import Navbar from "@/components/Navbar.vue";
import { onMounted, ref } from "vue";
import axios from "axios";

const suppliers = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get("http://localhost:8000/api/supplier");
        suppliers.value = response.data;
    } catch (error) {
        console.error("Gagal memuat data:", error);
    }
});

const deleteSupplier = async (id) => {
    if (confirm("Apakah Anda yakin ingin menghapus tipe barang ini?")) {
        try {
            await axios.delete(`http://localhost:8000/api/supplier/${id}`);
            suppliers.value = suppliers.value.filter(
                (supplier) => supplier.id !== id
            );
        } catch (error) {
            console.error("Gagal menghapus data:", error);
        }
    }
};
</script>
<template>
    <Navbar />
    <div class="p-6">
        <h2 class="text-2xl font-bold">Daftar Supplier</h2>
        <div class="mb-4">
            <RouterLink
                to="/supplier/tambah"
                class="float-right mb-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            >
                Tambah Supplier Baru
            </RouterLink>
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">Nama Supplier</th>
                    <th class="border px-4 py-2">Kontak</th>
                    <th class="border px-4 py-2">Email Supplier</th>
                    <th class="border px-4 py-2">Status Supplier</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="supplier in suppliers"
                    :key="supplier.id"
                    class="hover:bg-gray-50"
                >
                    <td class="border px-4 py-2">{{ supplier.id }}</td>
                    <td class="border px-4 py-2">
                        {{ supplier.supplier_name }}
                    </td>
                    <td class="border px-4 py-2">{{ supplier.contact }}</td>
                    <td class="border px-4 py-2">
                        {{ supplier.supplier_email }}
                    </td>
                    <td class="border px-4 py-2">
                        {{ supplier.supplier_status }}
                    </td>
                    <td class="border px-4 py-2">
                        <RouterLink
                            :to="`/supplier/edit/${supplier.id}`"
                            class="mr-3 px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500"
                        >
                            Edit
                        </RouterLink>
                        <button
                            @click="deleteSupplier(supplier.id)"
                            class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                        >
                            Hapus
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
