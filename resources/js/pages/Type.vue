<script setup>
import Navbar from "@/components/Navbar.vue";
import { onMounted, ref } from "vue";
import axios from "axios";

const types = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get("http://localhost:8000/api/type");
        types.value = response.data;
    } catch (error) {
        console.error("Gagal memuat data:", error);
    }
});

const deleteType = async (id) => {
    if (confirm("Apakah Anda yakin ingin menghapus tipe barang ini?")) {
        try {
            await axios.delete(`http://localhost:8000/api/type/${id}`);
            types.value = types.value.filter((type) => type.id !== id);
        } catch (error) {
            console.error("Gagal menghapus data:", error);
        }
    }
};
</script>
<template>
    <Navbar />
    <div class="p-6">
        <h2 class="text-2xl font-bold">Daftar Tipe Barang</h2>
        <div class="mb-4">
            <RouterLink
                to="/type/tambah"
                class="float-right mb-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            >
                Tambah Tipe Baru
            </RouterLink>
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">Kode Tipe</th>
                    <th class="border px-4 py-2">Nama Tipe</th>
                    <th class="border px-4 py-2">Deskripsi</th>
                    <th class="border px-4 py-2">Aksi</th>
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
                    <td class="border px-4 py-2">
                        <RouterLink
                            :to="`/type/edit/${type.id}`"
                            class="mr-3 px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500"
                        >
                            Edit
                        </RouterLink>
                        <button
                            @click="deleteType(type.id)"
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
