<script setup>
import Navbar from "@/components/Navbar.vue";
import { onMounted, ref } from "vue";
import axios from "axios";

const products = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get("http://localhost:8000/api/produk");
        products.value = response.data;
    } catch (error) {
        console.error("Gagal memuat data:", error);
    }
});

const deleteProduct = async (id) => {
    if (confirm("Apakah Anda yakin ingin menghapus Produk ini?")) {
        try {
            await axios.delete(`http://localhost:8000/api/produk/${id}`);
            products.value = products.value.filter(
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
        <h2 class="text-2xl font-bold">Daftar Produk</h2>
        <div class="mb-4">
            <RouterLink
                to="/produk/tambah"
                class="float-right mb-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            >
                Tambah Produk Baru
            </RouterLink>
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">Nama Produk</th>
                    <th class="border px-4 py-2">Kode Produk</th>
                    <th class="border px-4 py-2">Status Produk</th>
                    <th class="border px-4 py-2">Jumlah</th>
                    <th class="border px-4 py-2">Harga</th>
                    <th class="border px-4 py-2">Deskripsi</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="product in products"
                    :key="product.id"
                    class="hover:bg-gray-50"
                >
                    <td class="border px-4 py-2">
                        {{ products.indexOf(product) + 1 }}
                    </td>
                    <td class="border px-4 py-2">
                        {{ product.product_code }}
                    </td>
                    <td class="border px-4 py-2">{{ product.product_name }}</td>
                    <td class="border px-4 py-2">
                        <span v-if="product.product_status == 'available'"
                            >Ada</span
                        >
                        <span v-else> Habis </span>
                    </td>
                    <td class="border px-4 py-2">
                        {{ product.qty }}
                    </td>
                    <td class="border px-4 py-2">
                        Rp
                        {{
                            product.price
                                ? product.price
                                      .toLocaleString("id-ID", {
                                          minimumFractionDigits: 0,
                                          maximumFractionDigits: 0,
                                      })
                                      .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
                                : "0"
                        }}
                    </td>
                    <td class="border px-4 py-2">
                        {{ product.description }}
                    </td>
                    <td class="border px-4 py-2">
                        <RouterLink
                            :to="`/produk/edit/${product.id}`"
                            class="mr-3 px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500"
                        >
                            Edit
                        </RouterLink>
                        <button
                            @click="deleteProduct(product.id)"
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
