<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/components/Modal.vue';

interface Year {
    id_years: number;
    year_start: string;
    year_end: string;
    groups_count?: number;
}

const props = defineProps({
    years: Array as () => Year[],
});

defineOptions({
    name: 'Years',
    layout: MainLayout,
});

const showModal = ref<boolean>(false);
const page = usePage();

const form = useForm({
    year_start: '',
    year_end: '',
});

const submit = () => {
    form.post('/years', {
        onSuccess: () => {
            showModal.value = false;
            form.reset('year_start', 'year_end');
        },
    });
};

const deleteYear = (yearId: number, groupsCount = 0) => {
    if (groupsCount > 0) {
        alert('Нельзя удалить год, пока к нему привязаны группы.');
        return;
    }

    if (!confirm('Вы действительно хотите удалить этот год?')) {
        return;
    }

    form.delete(`/years/${yearId}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="wrapper">
        <div class="header">
            <h1>Года</h1>
            <div class="header-actions">
                <Link method="post" href="/logout" as="button">Выйти</Link>
            </div>
        </div>

        <div
            v-if="page.props.flash && page.props.flash.success"
            class="flash-message"
        >
            {{ page.props.flash.success }}
        </div>

        <ul class="year-list">
            <li v-for="year in years" :key="year.id_years">
                <div class="year-item">
                    <Link :href="`/years/${year.id_years}`" class="year-link">
                        Год {{ year.year_start }} - {{ year.year_end }}
                    </Link>
                    <div class="year-actions">
                        <span class="group-count">
                            {{ year.groups_count ?? 0 }} групп
                        </span>
                        <button
                            class="delete-year-button"
                            type="button"
                            @click="
                                deleteYear(
                                    year.id_years,
                                    year.groups_count ?? 0,
                                )
                            "
                        >
                            Удалить
                        </button>
                    </div>
                </div>
            </li>
        </ul>

        <button
            class="add-year-button"
            type="button"
            @click="showModal = true"
            aria-label="Добавить год"
        >
            +
        </button>
    </div>

    <Modal v-model="showModal">
        <template #header>Создать новый учебный год</template>
        <form class="year-form" @submit.prevent="submit">
            <div class="inputs-wrapper">
                <label>
                    Начало
                    <input
                        v-model="form.year_start"
                        type="text"
                        placeholder="2024"
                        maxlength="4"
                    />
                    <span v-if="form.errors.year_start" class="field-error">
                        {{ form.errors.year_start }}
                    </span>
                </label>
                <span class="separator">—</span>
                <label>
                    Конец
                    <input
                        v-model="form.year_end"
                        type="text"
                        placeholder="2025"
                        maxlength="4"
                    />
                    <span v-if="form.errors.year_end" class="field-error">
                        {{ form.errors.year_end }}
                    </span>
                </label>
            </div>
            <button
                class="submit-button"
                type="submit"
                :disabled="form.processing"
            >
                Создать год
            </button>
        </form>
    </Modal>
</template>

<style scoped>
.wrapper {
    position: relative;
    padding: 24px;
    max-width: 900px;
    margin: 0 auto;
}

.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 20px;
}

.header-actions {
    display: flex;
    align-items: center;
}

h1 {
    font-size: 28px;
    margin: 0;
}

.flash-message {
    background: #ecfdf5;
    border: 1px solid #22c55e;
    color: #166534;
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 18px;
}

.year-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 12px;
}

.year-list li {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

.year-list li:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
}

.year-link {
    display: block;
    padding: 18px 20px;
    color: #111827;
    text-decoration: none;
    font-weight: 600;
}

.year-link:hover {
    color: #047857;
}

.year-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.year-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.group-count {
    font-size: 14px;
    color: #4b5563;
}

.delete-year-button {
    padding: 10px 16px;
    border-radius: 9999px;
    border: 1px solid #d1d5db;
    background: #ffffff;
    color: #b91c1c;
    font-weight: 600;
    cursor: pointer;
    transition:
        background-color 0.2s ease,
        transform 0.2s ease;
}

.delete-year-button:hover:not(:disabled) {
    background: #fee2e2;
    transform: translateY(-1px);
}

.delete-year-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.add-year-button {
    position: fixed;
    right: 28px;
    bottom: 28px;
    width: 62px;
    height: 62px;
    border-radius: 50%;
    border: none;
    font-size: 36px;
    line-height: 1;
    color: white;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    box-shadow: 0 18px 30px rgba(16, 185, 129, 0.25);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

.add-year-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 22px 36px rgba(16, 185, 129, 0.32);
}

.year-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.inputs-wrapper {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    justify-content: space-between;
}

label {
    display: flex;
    flex-direction: column;
    gap: 8px;
    font-weight: 600;
    color: #111827;
    flex: 1;
    min-width: 160px;
}

input {
    width: 100%;
    padding: 14px 16px;
    border-radius: 12px;
    border: 1px solid #d1d5db;
    background: #f9fafb;
    font-size: 16px;
}

.separator {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    font-size: 18px;
    padding: 0 8px;
}

.field-error {
    color: #b91c1c;
    font-size: 13px;
    margin-top: 4px;
}

.submit-button {
    width: fit-content;
    align-self: flex-end;
    padding: 12px 24px;
    border-radius: 9999px;
    border: none;
    background: #16a34a;
    color: white;
    font-weight: 700;
    cursor: pointer;
    transition:
        background-color 0.2s ease,
        transform 0.2s ease;
}

.submit-button:hover:not(:disabled) {
    background: #15803d;
    transform: translateY(-1px);
}

.submit-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
