<template>
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-6 space-y-6">
        <!-- Header -->
        <div class="border-b pb-3 mb-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-700">
                {{ title }}
            </h2>
            <slot name="actions"></slot>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleSubmit" class="space-y-4">
            <slot></slot>

            <div class="pt-4 flex justify-end">
                <button
                    type="submit"
                    :disabled="loading"
                    class="bg-slate-600 hover:bg-slate-700 text-white font-semibold px-6 py-2 rounded-lg transition-all disabled:opacity-50 flex items-center"
                >
                    <svg
                        v-if="loading"
                        class="animate-spin h-5 w-5 mr-2 text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                        ></path>
                    </svg>
                    {{ loading ? "Menyimpan..." : submitText }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const props = defineProps({
    title: { type: String, default: "Form" },
    submitText: { type: String, default: "Simpan" },
    apiUrl: { type: String, required: true },
    method: { type: String, default: "post" },
});

const emit = defineEmits(["success"]);
const router = useRouter();
const loading = ref(false);

const handleSubmit = async (event) => {
    loading.value = true;
    const formData = new FormData(event.target);

    try {
        const res = await axios({
            method: props.method,
            url: props.apiUrl,
            data: Object.fromEntries(formData),
        });

        emit("success", res.data);
        router.back();
    } catch (error) {
        alert(
            "Terjadi kesalahan: " +
                (error.response?.data?.message || error.message)
        );
    } finally {
        loading.value = false;
    }
};
</script>
