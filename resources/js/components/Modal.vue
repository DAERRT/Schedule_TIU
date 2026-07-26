<template>
    <Teleport v-if="isReady && modelValue" :to="target">
        <div class="modal-overlay" @click.self="close">
            <div class="modal-content">
                <header class="modal-header">
                    <slot name="header">Заголовок</slot>
                    <button class="close-btn" @click="close">&times;</button>
                </header>
                <main class="modal-body">
                    <slot>Содержимое модального окна</slot>
                </main>
                <footer class="modal-footer">
                    <slot name="footer">
                        <button @click="close">Закрыть</button>
                    </slot>
                </footer>
            </div>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';

const props = defineProps<{
    modelValue: boolean; // v-model
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const target = ref<string | HTMLElement | null>(null);
const isReady = ref(false);

onMounted(() => {
    if (typeof document !== 'undefined') {
        let root = document.querySelector('#modal-root');

        if (!root) {
            root = document.createElement('div');
            root.id = 'modal-root';
            document.body.appendChild(root);
        }

        target.value = root as HTMLElement;
        isReady.value = true;
    }
});

const close = (): void => {
    emit('update:modelValue', false);
};
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
}
.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    min-width: 300px;
    max-width: 80%;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}
</style>
