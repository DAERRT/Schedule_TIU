<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { Link } from '@inertiajs/vue3';
interface Year {
    id_years: number;
    year_start: string;
    year_end: string;
}
const props = defineProps({
    years: Array as () => Year[],
});
defineOptions({
    name: 'Years',
    layout: MainLayout,
});
</script>

<template>
    <div class="wrapper">
        <div class="header">
            <h1>Года</h1>
            <Link method="post" href="/logout" as="button"> Выйти </Link>
        </div>
        <ul>
            <div v-for="year in years" :key="year.id_years">
                <Link :href="`/years/${year.id_years}`">
                    <li>Год {{ year.year_start }} - {{ year.year_end }}</li>
                </Link>
            </div>
        </ul>
    </div>
</template>

<style scoped>
.wrapper {
    padding: 20px;
}
h1 {
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
}
.header {
    text-align: center;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
ul {
    list-style-type: none;
    padding: 0;
}
li {
    padding: 10px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}
li:hover {
    background-color: #f0f0f0;
}
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
