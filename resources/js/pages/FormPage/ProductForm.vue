<template>
    <Navbar />

    <div class="mt-5 shadow-md bg-white rounded-lg p-6 max-w-3xl mx-auto">
        <h2 class="text-xl font-semibold mb-4">Tambah Produk Baru</h2>

        <form @submit.prevent="handleSubmit" class="space-y-6">
            <!-- LOOP FIELD PRODUK -->
            <div
                v-for="(prod, index) in products"
                :key="index"
                class="p-4 border rounded-lg bg-gray-50"
            >
                <h3 class="font-semibold mb-2">Produk #{{ index + 1 }}</h3>

                <!-- NAMA PRODUK -->
                <div>
                    <label class="block text-sm font-medium mb-1"
                        >Nama Produk</label
                    >
                    <input
                        v-model="prod.product_name"
                        type="text"
                        class="w-full border rounded-lg p-2"
                        required
                    />
                </div>

                <!-- STATUS -->
                <div>
                    <label class="block text-sm font-medium mb-1"
                        >Status Produk</label
                    >
                    <select
                        v-model="prod.product_status"
                        class="w-full border rounded-lg p-2"
                        required
                    >
                        <option value="" disabled>Pilih Status Barang</option>
                        <option value="available">Tersedia</option>
                        <option value="unavailable">Tidak Tersedia</option>
                    </select>
                </div>

                <!-- HARGA -->
                <div>
                    <label class="block text-sm font-medium mb-1">Harga</label>
                    <input
                        v-model="prod.price"
                        type="number"
                        min="0"
                        class="w-full border rounded-lg p-2"
                        required
                    />
                </div>

                <!-- TIPE -->
                <div>
                    <label class="block text-sm font-medium mb-1"
                        >Tipe Barang</label
                    >
                    <select
                        v-model="prod.type_id"
                        class="w-full border rounded-lg p-2"
                        required
                    >
                        <option value="" disabled>Pilih Tipe Barang</option>
                        <option v-for="t in types" :value="t.id">
                            {{ t.type_name }}
                        </option>
                    </select>
                </div>

                <!-- DESKRIPSI -->
                <div>
                    <label class="block text-sm font-medium mb-1"
                        >Deskripsi</label
                    >
                    <textarea
                        v-model="prod.description"
                        rows="2"
                        class="w-full border rounded-lg p-2"
                    ></textarea>
                </div>

                <!-- HAPUS PRODUK JIKA LEBIH DARI 1 -->
                <button
                    v-if="products.length > 1"
                    type="button"
                    @click="removeProduct(index)"
                    class="text-red-500 mt-2 underline text-sm"
                >
                    Hapus Produk Ini
                </button>
            </div>

            <!-- BUTTON TAMBAH PRODUK -->
            <button
                type="button"
                @click="addProduct"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
            >
                + Tambah Produk
            </button>

            <!-- SUBMIT SEMUA -->
            <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-800"
            >
                Simpan Semua Produk
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Navbar from "@/components/Navbar.vue";

// TYPES
const types = ref([]);

// LIST PRODUK (DINAMIS)
const products = ref([
    {
        product_name: "",
        product_status: "",
        price: "",
        type_id: "",
        description: "",
    },
]);

// Fetch type barang
const fetchTypes = async () => {
    const res = await fetch("http://localhost:8000/api/type");
    const data = await res.json();
    types.value = data.data || [];
};

onMounted(fetchTypes);

// Tambah field baru
const addProduct = () => {
    products.value.push({
        product_name: "",
        product_status: "",
        price: "",
        type_id: "",
        description: "",
    });
};

// Hapus form tertentu
const removeProduct = (index) => {
    products.value.splice(index, 1);
};

// Submit semua produk sekaligus
const handleSubmit = async () => {
    try {
        await fetch("http://localhost:8000/api/produk/bulk", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ products: products.value }),
        });

        alert("Semua produk berhasil disimpan!");
        products.value = [
            {
                product_name: "",
                product_status: "",
                price: "",
                type_id: "",
                description: "",
            },
        ];
    } catch (err) {
        alert("Gagal menyimpan produk.");
    }
};
</script>
