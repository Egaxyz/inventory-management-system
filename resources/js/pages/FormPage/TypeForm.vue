<template>
    <Navbar />
    <Form
        title="Tambah Tipe Barang"
        api-url="http://localhost:8000/api/type"
        submit-text="Simpan Tipe"
        @success="handleSuccess"
        class="mt-5 shadow-md"
    >
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"
                >Nama Tipe</label
            >
            <input
                name="type_name"
                type="text"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                autocomplete="off"
                required
            />
        </div>

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
import Form from "@/components/Form.vue";
import Navbar from "../../components/Navbar.vue";

const handleSuccess = (data) => {
    alert("Data berhasil disimpan!");
    console.log(data);
};
</script>
