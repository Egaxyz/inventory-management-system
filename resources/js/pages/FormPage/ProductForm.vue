<template>
    <Navbar />

    <Form
        title="Tambah Produk Baru"
        api-url="http://localhost:8000/api/produk"
        submit-text="Simpan Produk"
        @success="handleSuccess"
        class="mt-5 shadow-md"
    >
        <!-- PRODUCT NAME -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
                >Nama Produk</label
            >
            <input
                name="product_name"
                type="text"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                autocomplete="off"
                required
            />
        </div>

        <!-- PRODUCT STATUS -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
                >Status Produk</label
            >
            <select
                name="product_status"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                required
            >
                <option value="" disabled selected>Pilih Status Barang</option>
                <option value="available">Tersedia</option>
                <option value="unavailable">Tidak Tersedia</option>
            </select>
        </div>

        <!-- PRICE -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
                >Harga</label
            >
            <input
                name="price"
                type="number"
                min="0"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                autocomplete="off"
                required
            />
        </div>

        <!-- TYPE ID DROPDOWN -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
                >Tipe Barang</label
            >
            <select
                name="type_id"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                required
            >
                <option value="" disabled selected>Pilih Tipe Barang</option>

                <!-- TYPE LIST (DINAMIS) -->
                <option v-for="type in types" :key="type.id" :value="type.id">
                    {{ type.type_name }}
                </option>
            </select>
        </div>

        <!-- DESCRIPTION -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
                >Deskripsi</label
            >
            <textarea
                name="description"
                rows="3"
                autocomplete="off"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
            ></textarea>
        </div>
    </Form>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Form from "@/components/Form.vue";
import Navbar from "../../components/Navbar.vue";

// LIST TIPE BARANG
const types = ref([]);

const fetchTypes = async () => {
    try {
        const res = await fetch("http://localhost:8000/api/type");
        const data = await res.json();
        types.value = data.data || data || [];
    } catch (err) {
        console.error("Gagal mengambil daftar tipe:", err);
    }
};

onMounted(fetchTypes);

const handleSuccess = (data) => {
    alert("Produk berhasil disimpan!");
    console.log(data);
};
</script>
