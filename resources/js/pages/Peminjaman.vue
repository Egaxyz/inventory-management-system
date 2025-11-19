<script setup>
import Navbar from "@/components/Navbar.vue";
import { onMounted, ref } from "vue";
import axios from "axios";

const borrows = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get(
            "http://localhost:8000/api/peminjaman"
        );
        borrows.value = response.data.data; // pakai data.data
    } catch (error) {
        console.error("Gagal memuat data:", error);
    }
});

const formatDate = (value) => {
    if (!value) return "";
    const str = typeof value === "string" ? value.replace(" ", "T") : value;
    const date = new Date(str);
    return isNaN(date.getTime()) ? "" : date.toLocaleString("id-ID");
};

const deleteData = async (id) => {
    if (confirm("Yakin ingin menghapus peminjaman ini?")) {
        try {
            await axios.delete(`http://localhost:8000/api/peminjaman/${id}`);
            borrows.value = borrows.value.filter((item) => item.id !== id);
        } catch (error) {
            console.error("Gagal menghapus data:", error);
        }
    }
};
</script>

<template>
    <Navbar />
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">Daftar Peminjaman</h2>

        <RouterLink
            to="/peminjaman/tambah"
            class="float-right mb-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
        >
            Tambah Peminjaman
        </RouterLink>

        <table class="w-full border-collapse border border-gray-300 mt-4">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">No</th>
                    <th class="border px-4 py-2">Kode Produk</th>
                    <th class="border px-4 py-2">Nama Produk</th>
                    <th class="border px-4 py-2">Jumlah</th>
                    <th class="border px-4 py-2">Harga</th>
                    <th class="border px-4 py-2">Tanggal Pinjam</th>
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
                        {{ borrow.qty_outgoing_items }}
                    </td>
                    <td class="border px-4 py-2">
                        Rp
                        {{
                            borrow.product?.price && borrow.qty_outgoing_items
                                ? (
                                      borrow.product.price *
                                      borrow.qty_outgoing_items
                                  )
                                      .toLocaleString("id-ID", {
                                          minimumFractionDigits: 0,
                                          maximumFractionDigits: 0,
                                      })
                                      .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
                                : "0"
                        }}
                    </td>

                    <td class="border px-4 py-2">
                        {{
                            new Date(borrow.outgoing_date).toLocaleString(
                                "id-ID"
                            )
                        }}
                    </td>

                    <td class="border px-4 py-2">
                        <RouterLink
                            :to="`/peminjaman/edit/${borrow.id}`"
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
