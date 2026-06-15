<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import { useForm } from '@inertiajs/vue3';
defineOptions({
    name: 'Login',
    layout: MainLayout,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login');
};
</script>

<template>
    <div>
        <div class="form-wrapper">
            <form @submit.prevent="submit">
                <h1>Вход</h1>
                <div>
                    <label>Почта</label>
                    <input
                        type="text"
                        v-model="form.email"
                        placeholder="ermakovaa@tyuiu.ru"
                    />
                    <div v-if="form.errors.email" style="color: red">
                        {{ form.errors.email }}
                    </div>
                </div>
                <div>
                    <label>Пароль</label>
                    <input
                        type="password"
                        v-model="form.password"
                        placeholder="Пароль"
                    />
                    <div v-if="form.errors.password" style="color: red">
                        {{ form.errors.password }}
                    </div>
                </div>
                <div style="display: flex; align-items: center">
                    <input
                        type="checkbox"
                        id="remember"
                        v-model="form.remember"
                    />
                    <label class="checkbox" for="remember"
                        >Запомнить меня?</label
                    >
                </div>
                <button type="submit">Войти</button>
            </form>
        </div>
    </div>
</template>

<style scoped>
button {
    padding: 10px;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: white;
    font-weight: bold;
    cursor: pointer;
}
.checkbox {
    display: inline-block;
    margin-left: 5px;
}
h1 {
    text-align: center;
    font-size: 24px;
}
input {
    padding: 10px;
    margin-top: 0px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
label {
    font-weight: bold;
    margin: 0px;
}
.form-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 300px;
    padding: 20px;
    border-radius: 10px;
    background-color: #f0f0f0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
