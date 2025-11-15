<template>
    <div>
        <Navbar />

        <div class="mt-5 shadow-md bg-white rounded-lg p-6 max-w-2xl mx-auto">
            <h2 class="text-xl font-semibold mb-4">Edit Produk</h2>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div v-if="loading" class="text-sm text-gray-500">
                    Memuat data...
                </div>

                <div v-else>
                    <!-- PRODUCT CODE -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Kode Produk</label
                        >
                        <input
                            name="product_code"
                            type="text"
                            class="w-full border rounded-lg p-2 bg-gray-100 text-gray-700"
                            :value="form.product_code"
                            readonly
                        />
                        <p class="text-xs text-gray-500 mt-1">
                            Kode tidak dapat diubah.
                        </p>
                    </div>

                    <!-- PRODUCT NAME -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nama Produk</label
                        >
                        <input
                            v-model="form.product_name"
                            name="product_name"
                            type="text"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                            autocomplete="off"
                            required
                        />
                    </div>

                    <!-- PRODUCT STATUS -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Status Produk</label
                        >
                        <select
                            v-model="form.product_status"
                            name="product_status"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                            required
                        >
                            <option value="" disabled>Pilih Status</option>
                            <option value="available">Tersedia</option>
                            <option value="unavailable">Tidak Tersedia</option>
                        </select>
                    </div>

                    <!-- QTY -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Jumlah (Qty)</label
                        >
                        <input
                            v-model="form.qty"
                            name="qty"
                            type="number"
                            min="0"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                            autocomplete="off"
                            required
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Harga</label
                        >
                        <input
                            v-model="form.price"
                            name="price"
                            type="number"
                            min="0"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                            autocomplete="off"
                            required
                        />
                    </div>

                    <!-- TYPE ID -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Tipe Barang</label
                        >
                        <select
                            v-model="form.type_id"
                            name="type_id"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                            required
                        >
                            <option disabled value="">-- pilih tipe --</option>
                            <option
                                v-for="type in types"
                                :key="type.id"
                                :value="type.id"
                            >
                                {{ type.type_name ?? type.name }}
                            </option>
                        </select>
                    </div>

                    <!-- DESCRIPTION -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Deskripsi</label
                        >
                        <textarea
                            v-model="form.description"
                            name="description"
                            rows="3"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                        ></textarea>
                    </div>

                    <!-- BUTTON -->
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

const route = useRoute();
const router = useRouter();
const id = route.params.id;

const loading = ref(true);
const saving = ref(false);
const error = ref("");

const form = reactive({
    product_code: "",
    product_name: "",
    product_status: "",
    price: "",
    qty: "",
    description: "",
    type_id: "",
});

const types = ref([]);

const API_BASE = "http://localhost:8000/api/produk";
const TYPE_API = "http://localhost:8000/api/type";

/* === FETCH PRODUCT AND TYPES === */
const fetchAllData = async () => {
    loading.value = true;
    error.value = "";

    try {
        // Jalankan fetch product + fetch type secara paralel
        const [resProduct, resType] = await Promise.all([
            fetch(`${API_BASE}/${id}`),
            fetch(TYPE_API),
        ]);

        // === CEK JENIS RESPON PRODUCT ===
        const ctype = resProduct.headers.get("content-type");
        if (!ctype || !ctype.includes("application/json")) {
            throw new Error("API produk tidak mengembalikan JSON!");
        }

        if (!resProduct.ok) {
            const err = await resProduct.json().catch(() => null);
            throw new Error(err?.message || "Gagal memuat data produk.");
        }

        const productData = await resProduct.json();
        const product =
            productData?.data ??
            productData?.product ??
            productData?.payload ??
            productData;

        // === CEK RESPON TYPE ===
        const typeData = await resType.json();
        types.value = typeData?.data ?? typeData;

        // === MAPPING FIELD PRODUK KE FORM ===
        form.product_code =
            product.product_code ?? product.code ?? product.kode ?? "";

        form.product_name =
            product.product_name ?? product.name ?? product.nama ?? "";

        form.product_status =
            product.product_status ?? product.status ?? "available";

        form.qty = product.qty ?? product.quantity ?? 0;

        form.price = product.price ?? product.hsrga ?? 0;

        form.description =
            product.description ?? product.desc ?? product.deskripsi ?? "";

        form.type_id = product.type_id ?? product.tipe_id ?? product.type ?? "";
    } catch (err) {
        error.value = err.message;
    } finally {
        loading.value = false;
    }
};

/* === SUBMIT UPDATE === */
const handleSubmit = async () => {
    saving.value = true;
    error.value = "";

    try {
        const res = await fetch(`${API_BASE}/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify(form),
        });

        if (!res.ok) {
            const err = await res.json().catch(() => null);
            throw new Error(err?.message || "Gagal memperbarui data.");
        }

        alert("Data berhasil disimpan!");
        router.push({ name: "produk" });
    } catch (err) {
        error.value = err.message;
    } finally {
        saving.value = false;
    }
};

const goBack = () => router.back();

onMounted(() => {
    if (!id) {
        error.value = "ID tidak ditemukan di route.";
        loading.value = false;
        return;
    }

    // panggil fetch gabungan
    fetchAllData();
});
</script>
