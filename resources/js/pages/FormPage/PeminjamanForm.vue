<template>
    <Navbar />

    <Form
        title="Tambah Peminjaman Barang"
        api-url="http://localhost:8000/api/peminjaman"
        submit-text="Simpan Peminjaman"
        @success="handleSuccess"
        class="mt-5 shadow-md"
    >
        <!-- Pilih Produk -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Pilih Produk
            </label>
            <select
                name="product_id"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                required
            >
                <option value="" disabled selected>Pilih Produk</option>

                <option
                    v-for="product in products"
                    :key="product.id"
                    :value="product.id"
                    :disabled="
                        product.qty == 0 ||
                        product.product_status === 'unavailable'
                    "
                    :class="{
                        'text-gray-400 bg-gray-100':
                            product.qty == 0 ||
                            product.product_status === 'unavailable',
                    }"
                >
                    {{ product.product_code }} -
                    {{ product.product_name }} (Stok: {{ product.qty }} |
                    Status:
                    {{
                        product.product_status === "available"
                            ? "Tersedia"
                            : "Tidak Tersedia"
                    }})
                </option>
            </select>
        </div>

        <!-- Qty -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Jumlah (Qty)
            </label>
            <input
                name="qty_outgoing_items"
                type="number"
                min="1"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                required
            />
        </div>

        <!-- Tanggal Pinjam -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tanggal Pinjam
            </label>
            <input
                name="outgoing_date"
                type="datetime-local"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                required
            />
        </div>
    </Form>
</template>

<script setup>
import Form from "@/components/Form.vue";
import Navbar from "@/components/Navbar.vue";
import axios from "axios";
import { ref, onMounted } from "vue";

const products = ref([]);
const users = ref([]);

onMounted(async () => {
    try {
        const resProduct = await axios.get("http://localhost:8000/api/produk");
        products.value = resProduct.data.data ?? resProduct.data;

        const resUser = await axios.get("http://localhost:8000/api/user");
        users.value = resUser.data.data ?? resUser.data;
    } catch (err) {
        console.error("Gagal memuat data:", err);
    }
});

const handleSuccess = () => {
    alert("Peminjaman berhasil disimpan!");
};
</script>
