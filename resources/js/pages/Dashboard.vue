<script setup lang="ts">
import Navbar from "../components/Navbar.vue";
</script>

<template>
    <div class="min-h-screen bg-gray-100 text-gray-800">
        <Navbar />
        <main class="p-6">
            <router-view />
        </main>

        <h2 class="text-xl font-semibold">
            Selamat datang di sistem peminjaman barang
        </h2>
    </div>
</template>
