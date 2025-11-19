<template>
    <div>
        <Navbar />

        <div class="mt-5 shadow-md bg-white rounded-lg p-6 max-w-2xl mx-auto">
            <h2 class="text-xl font-semibold mb-4">Edit Peminjaman</h2>

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
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                            required
                        >
                            <option value="" disabled>Pilih Produk</option>

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
                                        product.product_status ===
                                            'unavailable',
                                }"
                            >
                                {{ product.product_code }} -
                                {{ product.product_name }} (Stok:
                                {{ product.qty }} | Status:
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
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Jumlah (Qty)
                        </label>
                        <input
                            v-model="form.qty_outgoing_items"
                            name="qty_outgoing_items"
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
                            v-model="form.outgoing_date"
                            name="outgoing_date"
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

const form = reactive({
    product_id: "",
    outgoing_date: "",
    qty_outgoing_items: "",
});

const API_GET = "http://localhost:8000/api/peminjaman";
const API_PRODUK = "http://localhost:8000/api/produk";

const fetchData = async () => {
    try {
        loading.value = true;

        // Fetch daftar produk
        const resProduct = await axios.get(API_PRODUK);
        products.value = resProduct.data.data ?? resProduct.data;

        // Fetch data peminjaman
        const res = await axios.get(`${API_GET}/${id}`);
        const payload = res.data.data ?? res.data;

        // Mapping ke form
        form.product_id = payload.product_id;
        form.outgoing_date = payload.outgoing_date.replace(" ", "T");
        form.qty_outgoing_items = payload.qty_outgoing_items;
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
            outgoing_date: form.outgoing_date,
            qty_outgoing_items: form.qty_outgoing_items,
        });

        alert("Data berhasil diperbarui!");
        router.push({ name: "peminjaman" });
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
