<template>
    <div>
        <Navbar />

        <div class="mt-5 shadow-md bg-white rounded-lg p-6 max-w-2xl mx-auto">
            <h2 class="text-xl font-semibold mb-4">Edit Supplier</h2>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div v-if="loading" class="text-sm text-gray-500">
                    Memuat data...
                </div>

                <div v-else>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nama Supplier</label
                        >
                        <input
                            v-model="form.supplier_name"
                            name="supplier_name"
                            type="text"
                            class="w-full border rounded-lg p-2 bg-gray-100 text-gray-700"
                            autocomplete="off"
                            required
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Kontak</label
                        >
                        <input
                            v-model="form.contact"
                            name="contact"
                            type="text"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                            autocomplete="off"
                            required
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Email Supplier</label
                        >
                        <input
                            v-model="form.supplier_email"
                            name="supplier_email"
                            type="email"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                            autocomplete="off"
                            required
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Status Supplier</label
                        >
                        <select
                            v-model="form.supplier_status"
                            name="supplier_status"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-slate-300"
                            required
                        >
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                    </div>

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
    supplier_name: "",
    contact: "",
    supplier_email: "",
    supplier_status: "",
});

const API_BASE = "http://localhost:8000/api/supplier";

const fetchData = async () => {
    loading.value = true;
    error.value = "";

    try {
        const url = `${API_BASE}/${id}`;
        console.log("Fetching:", url);

        const res = await fetch(url, {
            method: "GET",
            headers: { Accept: "application/json" },
        });

        const contentType = res.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const html = await res.text();
            console.error("Backend mengembalikan HTML:", html);
            throw new Error("API tidak mengembalikan JSON.");
        }

        if (!res.ok) {
            const err = await res.json().catch(() => null);
            throw new Error(err?.message || "Gagal memuat data.");
        }

        const data = await res.json();
        console.log("Response JSON:", data);

        const payload =
            data?.data ?? data?.payload ?? data?.supplier ?? data?.item ?? data;

        if (!payload) {
            throw new Error("Data kosong dari API.");
        }

        // MAP FIELD — mengikuti fleksibilitas yang kamu pakai
        form.supplier_name =
            payload.supplier_name ??
            payload.name ??
            payload.nama_supplier ??
            "";

        form.contact = payload.contact ?? payload.phone ?? payload.kontak ?? "";

        form.supplier_email =
            payload.supplier_email ?? payload.email ?? payload.mail ?? "";

        form.supplier_status =
            payload.supplier_status ??
            payload.status ??
            payload.state ??
            "active";
    } catch (err) {
        error.value = err.message;
    } finally {
        loading.value = false;
    }
};

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
            body: JSON.stringify({
                supplier_name: form.supplier_name,
                contact: form.contact,
                supplier_email: form.supplier_email,
                supplier_status: form.supplier_status,
            }),
        });

        if (!res.ok) {
            const err = await res.json().catch(() => null);
            throw new Error(err?.message || "Gagal memperbarui data.");
        }

        alert("Data berhasil disimpan!");
        router.push({ name: "supplier" });
        router.back();
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
    fetchData();
});
</script>
