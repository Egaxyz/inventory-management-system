<script setup>
import Navbar from "@/components/Navbar.vue";
import { onMounted, ref } from "vue";
import axios from "axios";

const borrows = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get(
            "http://localhost:8000/api/barang-masuk"
        );
        borrows.value = response.data.data;
    } catch (error) {
        console.error("Gagal memuat data:", error);
    }
});

const deleteProduct = async (id) => {
    if (confirm("Apakah Anda yakin ingin menghapus Data ini?")) {
        try {
            await axios.delete(`http://localhost:8000/api/barang-masuk/${id}`);
            borrows.value = borrows.value.filter(
                (product) => product.id !== id
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
        <h2 class="text-2xl font-bold">Daftar Barang Masuk</h2>
        <div class="mb-4">
            <RouterLink
                to="/barang-masuk/tambah"
                class="float-right mb-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            >
                Tambah Barang Masuk
            </RouterLink>
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">Kode Produk</th>
                    <th class="border px-4 py-2">Nama Produk</th>
                    <th class="border px-4 py-2">Jumlah</th>
                    <th class="border px-4 py-2">Tanggal Masuk</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(borrow, index) in borrows" :key="borrow.id">
                    <td class="border px-4 py-2">{{ index + 1 }}</td>

                    <!-- Relasi Product -->
                    <td class="border px-4 py-2">
                        {{ borrow.product?.product_code }}
                    </td>
                    <td class="border px-4 py-2">
                        {{ borrow.product?.product_name }}
                    </td>
                    <td class="border px-4 py-2">
                        {{ borrow.qty_incoming_items }}
                    </td>
                    <td class="border px-4 py-2">
                        {{
                            new Date(borrow.incoming_date).toLocaleString(
                                "id-ID"
                            )
                        }}
                    </td>

                    <td class="border px-4 py-2">
                        <RouterLink
                            :to="`/barang-masuk/edit/${borrow.id}`"
                            class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500"
                        >
                            Edit
                        </RouterLink>
                        <!-- Depends on need -->
                        <!-- <button
                            @click="deleteData(borrow.id)"
                            class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                        >
                            Hapus
                        </button> -->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
