<template>
    <div>
        <router-view />
    </div>
</template>

<script setup>
window.onpageshow = function (event) {
    if (
        event.persisted ||
        performance.getEntriesByType("navigation")[0].type === "back_forward"
    ) {
        window.location.reload();
    }
};
</script>

<style>
body {
    font-family: sans-serif;
}
</style>
