<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { Link } from '@inertiajs/vue3';
defineOptions({
    name: 'Home',
    layout: MainLayout,
});
</script>

<template>
    <!-- Страница создана для теста формы логин-->
    <h1>Главная страница</h1>
    <Link method="post" href="/logout" as="button"> Выйти </Link>
    <Link method="get" href="/years" as="button" class="button"> Года </Link>
</template>

<style scoped>
button {
    background-color: #f80015;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
button:hover {
    background-color: #b40312;
}
</style>
