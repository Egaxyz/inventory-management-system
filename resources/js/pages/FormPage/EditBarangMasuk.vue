<template>
    <div>
        <Navbar />

        <div class="mt-5 shadow-md bg-white rounded-lg p-6 max-w-2xl mx-auto">
            <h2 class="text-xl font-semibold mb-4">Edit Data Barang Masuk</h2>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div v-if="loading" class="text-sm text-gray-500">
                    Memuat data...
                </div>

                <div v-else>
                    <!-- Produk -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Pilih Produk
                        </label>

                        <select
                            v-model="form.product_id"
                            name="product_id"
                            class="w-full border rounded-lg p-2 bg-gray-100 text-gray-500 cursor-not-allowed"
                            disabled
                        >
                            <option
                                v-for="product in products"
                                :key="product.id"
                                :value="product.id"
                            >
                                {{ product.product_code }} -
                                {{ product.product_name }} (Stok:
                                {{ product.qty }})
                            </option>
                        </select>

                        <!-- Hidden value agar tetap terkirim ke backend -->
                        <input
                            type="hidden"
                            v-model="form.product_id"
                            name="product_id"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Pilih Supplier
                        </label>
                        <select
                            v-model="form.supplier_id"
                            name="supplier_id"
                            class="w-full border rounded-lg p-2 bg-gray-100 text-gray-500 cursor-not-allowed"
                            disabled
                        >
                            <option
                                v-for="supplier in suppliers"
                                :key="supplier.id"
                                :value="supplier.id"
                            >
                                {{ supplier.supplier_name }} - (Status:
                                {{ supplier.supplier_status }})
                            </option>
                        </select>

                        <!-- Hidden value agar tetap terkirim -->
                        <input
                            type="hidden"
                            v-model="form.supplier_id"
                            name="supplier_id"
                        />
                    </div>
                    <!-- Qty -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Jumlah (Qty)
                        </label>
                        <input
                            v-model="form.qty_incoming_items"
                            name="qty_incoming_items"
                            type="number"
                            min="1"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                            required
                        />
                    </div>

                    <!-- Tanggal Pinjam -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Tanggal Pinjam
                        </label>
                        <input
                            v-model="form.incoming_date"
                            name="incoming_date"
                            type="datetime-local"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                            required
                        />
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center space-x-2">
                        <button
                            type="submit"
                            class="px-4 py-1 mt-2 bg-blue-600 text-white rounded-md hover:bg-blue-800 disabled:opacity-60"
                            :disabled="saving"
                        >
                            {{ saving ? "Menyimpan..." : "Simpan" }}
                        </button>

                        <button
                            type="button"
                            @click="goBack"
                            class="text-sm text-white underline bg-gray-500 px-4 py-1 mt-2 rounded-md hover:bg-white hover:text-gray-500"
                        >
                            Batal
                        </button>
                    </div>

                    <div v-if="error" class="text-sm text-red-600 mt-2">
                        {{ error }}
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import Navbar from "@/components/Navbar.vue";
import axios from "axios";

const route = useRoute();
const router = useRouter();
const id = route.params.id;

const loading = ref(true);
const saving = ref(false);
const error = ref("");

const products = ref([]);
const suppliers = ref([]);

const form = reactive({
    product_id: "",
    supplier_id: "",
    incoming_date: "",
    qty_incoming_items: "",
});

const API_GET = "http://localhost:8000/api/barang-masuk";
const API_PRODUK = "http://localhost:8000/api/produk";
const API_SUPPLIER = "http://localhost:8000/api/supplier";

const fetchData = async () => {
    try {
        loading.value = true;

        // Fetch daftar produk
        const resProduct = await axios.get(API_PRODUK);
        products.value = resProduct.data.data ?? resProduct.data;

        const resSupplier = await axios.get(API_SUPPLIER);
        suppliers.value = resSupplier.data.data ?? resSupplier.data;

        // Fetch data peminjaman
        const res = await axios.get(`${API_GET}/${id}`);
        const payload = res.data.data ?? res.data;

        // Mapping ke form
        form.product_id = payload.product_id;
        form.supplier_id = payload.supplier_id;
        form.incoming_date = payload.incoming_date.replace(" ", "T");
        form.qty_incoming_items = payload.qty_incoming_items;
    } catch (err) {
        error.value = "Gagal memuat data.";
    } finally {
        loading.value = false;
    }
};

const handleSubmit = async () => {
    saving.value = true;
    error.value = "";

    try {
        const res = await axios.put(`${API_GET}/${id}`, {
            product_id: form.product_id,
            supplier_id: form.supplier_id,
            incoming_date: form.incoming_date,
            qty_incoming_items: form.qty_incoming_items,
        });

        alert("Data berhasil diperbarui!");
        router.push({ name: "barang-masuk" });
    } catch (err) {
        error.value =
            err.response?.data?.message || "Gagal menyimpan perubahan.";
    } finally {
        saving.value = false;
    }
};

const goBack = () => router.back();

onMounted(fetchData);
</script>
