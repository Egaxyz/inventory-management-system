<script setup>
import Navbar from "@/components/Navbar.vue";
import { onMounted, ref } from "vue";

const types = ref([]);
onMounted(async () => {
    try {
        const response = await axios.get("http://localhost:8000/api/type");
        types.value = response.data;
    } catch (error) {
        console.error("Gagal memuat data:", error);
    }
});
</script>
<template>
    <Navbar />
    <div class="p-6">
        <h2 class="text-2xl font-bold">Daftar Tipe Barang</h2>
        <div class="mb-4">
            <button
                class="float-right px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            >
                Tambah Tipe Baru
            </button>
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">Kode Tipe</th>
                    <th class="border px-4 py-2">Nama Tipe</th>
                    <th class="border px-4 py-2">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="type in types"
                    :key="type.id"
                    class="hover:bg-gray-50"
                >
                    <td class="border px-4 py-2">{{ type.id }}</td>
                    <td class="border px-4 py-2">{{ type.type_code }}</td>
                    <td class="border px-4 py-2">{{ type.type_name }}</td>
                    <td class="border px-4 py-2">{{ type.description }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
