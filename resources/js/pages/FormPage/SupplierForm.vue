<template>
    <Navbar />

    <Form
        title="Tambah Supplier Baru"
        api-url="http://localhost:8000/api/supplier"
        submit-text="Simpan Supplier"
        @success="handleSuccess"
        class="mt-5 shadow-md"
    >
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
                >Nama Supplier</label
            >
            <input
                name="supplier_name"
                type="text"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                autocomplete="off"
                required
            />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
                >Kontak</label
            >
            <input
                name="contact"
                type="number"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                autocomplete="off"
                required
            />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
                >Email Supplier</label
            >
            <input
                name="supplier_email"
                type="email"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                autocomplete="off"
                required
            />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
                >Status Supplier</label
            >
            <select
                name="supplier_status"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                required
            >
                <option value="" disabled>Pilih Status Supplier</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
            </select>
        </div>
    </Form>
</template>

<script setup>
import Form from "@/components/Form.vue";
import Navbar from "../../components/Navbar.vue";

const handleSuccess = (data) => {
    alert("Data berhasil disimpan!");
    console.log(data);
};
</script>
