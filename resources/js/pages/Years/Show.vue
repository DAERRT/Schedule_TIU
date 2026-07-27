<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue';
import Modal from '@/components/Modal.vue';
import ScheduleTable from '@/components/ScheduleTable.vue';
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

interface Room {
    id_rooms: number;
    name: string;
    id_buildings: number;
}

interface Building {
    id_buildings: number;
    name: string;
    rooms: Room[];
}

interface Teacher {
    id_teachers: number;
    name: string;
}

interface Faculty {
    id_faculties: number;
    name: string;
    teachers: Teacher[];
}

interface Year {
    id_years: number;
    year_start: string;
    year_end: string;
}

const props = defineProps({
    year: Object as () => Year,
    buildings: Array as () => Building[],
    faculties: Array as () => Faculty[],
    lessons: Array as () => any[],
});

const lessons = computed(() => props.lessons || []);

const page = usePage();

defineOptions({
    name: 'YearShow',
    layout: MainLayout,
});

const selectedBuildingId = ref<number | null>(null);
const selectedRoomId = ref<number | null>(null);
const selectedFacultyId = ref<number | null>(null);
const selectedTeacherId = ref<number | null>(null);
const buildingMenuOpen = ref(false);
const roomMenuOpen = ref(false);
const facultyMenuOpen = ref(false);
const teacherMenuOpen = ref(false);
const isBuildingCreateOpen = ref(false);
const isBuildingEditOpen = ref(false);
const isBuildingDeleteOpen = ref(false);
const isRoomCreateOpen = ref(false);
const isRoomEditOpen = ref(false);
const isRoomDeleteOpen = ref(false);
const isFacultyCreateOpen = ref(false);
const isFacultyEditOpen = ref(false);
const isFacultyDeleteOpen = ref(false);
const isTeacherCreateOpen = ref(false);
const isTeacherEditOpen = ref(false);
const isTeacherDeleteOpen = ref(false);
const editingBuilding = ref<Building | null>(null);
const deletingBuilding = ref<Building | null>(null);
const editingRoom = ref<Room | null>(null);
const deletingRoom = ref<Room | null>(null);
const editingFaculty = ref<Faculty | null>(null);
const deletingFaculty = ref<Faculty | null>(null);
const editingTeacher = ref<Teacher | null>(null);
const deletingTeacher = ref<Teacher | null>(null);

const buildingForm = useForm({
    name: '',
});

const roomForm = useForm({
    name: '',
    id_buildings: '',
});

const facultyForm = useForm({
    name: '',
});

const teacherForm = useForm({
    name: '',
    faculty_id: '',
});

const buildings = computed(() => props.buildings || []);
const rooms = computed(() => {
    const building = buildings.value.find(
        (item) => item.id_buildings === selectedBuildingId.value,
    );
    return building ? building.rooms : [];
});

const selectedBuilding = computed(() =>
    buildings.value.find(
        (item) => item.id_buildings === selectedBuildingId.value,
    ),
);

const selectedRoom = computed(() =>
    rooms.value.find((item) => item.id_rooms === selectedRoomId.value),
);

const selectedRoomLessons = computed(() =>
    lessons.value.filter((lesson) => lesson.id_rooms === selectedRoomId.value),
);

const faculties = computed(() => props.faculties || []);
const teachers = computed(() => {
    const faculty = faculties.value.find(
        (item) => item.id_faculties === selectedFacultyId.value,
    );
    return faculty ? faculty.teachers : [];
});

const selectedFaculty = computed(() =>
    faculties.value.find(
        (item) => item.id_faculties === selectedFacultyId.value,
    ),
);

const selectedTeacher = computed(() =>
    teachers.value.find((item) => item.id_teachers === selectedTeacherId.value),
);

const selectedTeacherLessons = computed(() =>
    lessons.value.filter(
        (lesson) => lesson.id_teachers === selectedTeacherId.value,
    ),
);

const openBuildingCreateModal = () => {
    buildingForm.reset();
    isBuildingCreateOpen.value = true;
    buildingMenuOpen.value = false;
};

const openBuildingEditModal = (building: Building) => {
    editingBuilding.value = building;
    buildingForm.name = building.name;
    isBuildingEditOpen.value = true;
    buildingMenuOpen.value = false;
};

const openBuildingDeleteModal = (building: Building) => {
    deletingBuilding.value = building;
    isBuildingDeleteOpen.value = true;
    buildingMenuOpen.value = false;
};

const createBuilding = () => {
    buildingForm.post('/buildings', {
        onSuccess: () => {
            buildingForm.reset('name');
            isBuildingCreateOpen.value = false;
        },
        preserveScroll: true,
    });
};

const updateBuilding = () => {
    if (!editingBuilding.value) {
        return;
    }

    buildingForm.put(`/buildings/${editingBuilding.value.id_buildings}`, {
        onSuccess: () => {
            buildingForm.reset('name');
            isBuildingEditOpen.value = false;
        },
        preserveScroll: true,
    });
};

const deleteBuilding = () => {
    if (!deletingBuilding.value) {
        return;
    }

    buildingForm.delete(`/buildings/${deletingBuilding.value.id_buildings}`, {
        onSuccess: () => {
            deletingBuilding.value = null;
            isBuildingDeleteOpen.value = false;
        },
        preserveScroll: true,
    });
};

const openRoomCreateModal = () => {
    roomForm.reset();
    roomForm.id_buildings = selectedBuildingId.value
        ? selectedBuildingId.value.toString()
        : '';
    isRoomCreateOpen.value = true;
    roomMenuOpen.value = false;
};

const openRoomEditModal = (room: Room) => {
    editingRoom.value = room;
    roomForm.name = room.name;
    roomForm.id_buildings = room.id_buildings.toString();
    isRoomEditOpen.value = true;
    roomMenuOpen.value = false;
};

const openRoomDeleteModal = (room: Room) => {
    deletingRoom.value = room;
    isRoomDeleteOpen.value = true;
    roomMenuOpen.value = false;
};

const createRoom = () => {
    roomForm.post('/rooms', {
        onSuccess: () => {
            roomForm.reset('name', 'id_buildings');
            isRoomCreateOpen.value = false;
        },
        preserveScroll: true,
    });
};

const updateRoom = () => {
    if (!editingRoom.value) {
        return;
    }

    roomForm.put(`/rooms/${editingRoom.value.id_rooms}`, {
        onSuccess: () => {
            roomForm.reset('name', 'id_buildings');
            isRoomEditOpen.value = false;
        },
        preserveScroll: true,
    });
};

const deleteRoom = () => {
    if (!deletingRoom.value) {
        return;
    }

    roomForm.delete(`/rooms/${deletingRoom.value.id_rooms}`, {
        onSuccess: () => {
            deletingRoom.value = null;
            isRoomDeleteOpen.value = false;
        },
        preserveScroll: true,
    });
};

const openFacultyCreateModal = () => {
    facultyForm.reset();
    isFacultyCreateOpen.value = true;
    facultyMenuOpen.value = false;
};

const openFacultyEditModal = (faculty: Faculty) => {
    editingFaculty.value = faculty;
    facultyForm.name = faculty.name;
    isFacultyEditOpen.value = true;
    facultyMenuOpen.value = false;
};

const openFacultyDeleteModal = (faculty: Faculty) => {
    deletingFaculty.value = faculty;
    isFacultyDeleteOpen.value = true;
    facultyMenuOpen.value = false;
};

const createFaculty = () => {
    facultyForm.post('/faculties', {
        onSuccess: () => {
            facultyForm.reset('name');
            isFacultyCreateOpen.value = false;
        },
        preserveScroll: true,
    });
};

const updateFaculty = () => {
    if (!editingFaculty.value) {
        return;
    }

    facultyForm.put(`/faculties/${editingFaculty.value.id_faculties}`, {
        onSuccess: () => {
            facultyForm.reset('name');
            isFacultyEditOpen.value = false;
        },
        preserveScroll: true,
    });
};

const deleteFaculty = () => {
    if (!deletingFaculty.value) {
        return;
    }

    facultyForm.delete(`/faculties/${deletingFaculty.value.id_faculties}`, {
        onSuccess: () => {
            deletingFaculty.value = null;
            isFacultyDeleteOpen.value = false;
        },
        preserveScroll: true,
    });
};

const openTeacherCreateModal = () => {
    teacherForm.reset();
    teacherForm.faculty_id = selectedFacultyId.value
        ? selectedFacultyId.value.toString()
        : '';
    isTeacherCreateOpen.value = true;
    teacherMenuOpen.value = false;
};

const openTeacherEditModal = (teacher: Teacher) => {
    editingTeacher.value = teacher;
    teacherForm.name = teacher.name;
    teacherForm.faculty_id = selectedFacultyId.value
        ? selectedFacultyId.value.toString()
        : '';
    isTeacherEditOpen.value = true;
    teacherMenuOpen.value = false;
};

const openTeacherDeleteModal = (teacher: Teacher) => {
    deletingTeacher.value = teacher;
    isTeacherDeleteOpen.value = true;
    teacherMenuOpen.value = false;
};

const createTeacher = () => {
    teacherForm.post('/teachers', {
        onSuccess: () => {
            teacherForm.reset('name', 'faculty_id');
            isTeacherCreateOpen.value = false;
        },
        preserveScroll: true,
    });
};

const updateTeacher = () => {
    if (!editingTeacher.value) {
        return;
    }

    teacherForm.put(`/teachers/${editingTeacher.value.id_teachers}`, {
        onSuccess: () => {
            teacherForm.reset('name', 'faculty_id');
            isTeacherEditOpen.value = false;
        },
        preserveScroll: true,
    });
};

const deleteTeacher = () => {
    if (!deletingTeacher.value) {
        return;
    }

    teacherForm.delete(`/teachers/${deletingTeacher.value.id_teachers}`, {
        onSuccess: () => {
            deletingTeacher.value = null;
            isTeacherDeleteOpen.value = false;
        },
        preserveScroll: true,
    });
};

const selectBuilding = (id: number) => {
    selectedBuildingId.value = id;
    selectedRoomId.value = null;
    buildingMenuOpen.value = false;
};

const selectRoom = (id: number) => {
    selectedRoomId.value = id;
    roomMenuOpen.value = false;
};

const selectFaculty = (id: number) => {
    selectedFacultyId.value = id;
    selectedTeacherId.value = null;
    facultyMenuOpen.value = false;
};

const selectTeacher = (id: number) => {
    selectedTeacherId.value = id;
    teacherMenuOpen.value = false;
};

const toggleBuildingMenu = () => {
    buildingMenuOpen.value = !buildingMenuOpen.value;
    if (buildingMenuOpen.value) {
        roomMenuOpen.value = false;
    }
};

const toggleRoomMenu = () => {
    roomMenuOpen.value = !roomMenuOpen.value;
    if (roomMenuOpen.value) {
        buildingMenuOpen.value = false;
        facultyMenuOpen.value = false;
        teacherMenuOpen.value = false;
    }
};

const toggleFacultyMenu = () => {
    facultyMenuOpen.value = !facultyMenuOpen.value;
    if (facultyMenuOpen.value) {
        buildingMenuOpen.value = false;
        roomMenuOpen.value = false;
        teacherMenuOpen.value = false;
    }
};

const toggleTeacherMenu = () => {
    teacherMenuOpen.value = !teacherMenuOpen.value;
    if (teacherMenuOpen.value) {
        buildingMenuOpen.value = false;
        roomMenuOpen.value = false;
        facultyMenuOpen.value = false;
    }
};

const closeMenus = () => {
    buildingMenuOpen.value = false;
    roomMenuOpen.value = false;
    facultyMenuOpen.value = false;
    teacherMenuOpen.value = false;
};
</script>

<template>
    <div class="year-page">
        <div class="page-grid">
            <div class="left-side">
                <section class="card top-card" @click="closeMenus">
                    <h2>Выбор корпуса и аудитории</h2>
                    <div
                        v-if="page.props.flash?.success"
                        class="flash-message success"
                    >
                        {{ page.props.flash.success }}
                    </div>
                    <div
                        v-if="page.props.flash?.error"
                        class="flash-message error"
                    >
                        {{ page.props.flash.error }}
                    </div>
                    <div class="custom-filters">
                        <div class="dropdown-block">
                            <div class="dropdown-label">Здание</div>
                            <button
                                type="button"
                                class="dropdown-trigger"
                                @click.stop="toggleBuildingMenu"
                            >
                                {{
                                    selectedBuilding?.name ?? 'Выберите здание'
                                }}
                                <span class="dropdown-arrow">▾</span>
                            </button>
                            <div v-if="buildingMenuOpen" class="dropdown-menu">
                                <button
                                    type="button"
                                    class="dropdown-item create-item"
                                    @click="openBuildingCreateModal"
                                >
                                    + Создать здание
                                </button>
                                <div
                                    v-for="building in buildings"
                                    :key="building.id_buildings"
                                    class="dropdown-item"
                                >
                                    <button
                                        type="button"
                                        class="item-label"
                                        @click="
                                            selectBuilding(
                                                building.id_buildings,
                                            )
                                        "
                                    >
                                        {{ building.name }}
                                        <span class="badge">
                                            {{ building.rooms.length }} ауд.
                                        </span>
                                    </button>
                                    <div class="item-actions">
                                        <button
                                            type="button"
                                            class="action-button"
                                            @click.stop="
                                                openBuildingEditModal(building)
                                            "
                                        >
                                            ✎
                                        </button>
                                        <button
                                            type="button"
                                            class="action-button"
                                            @click.stop="
                                                openBuildingDeleteModal(
                                                    building,
                                                )
                                            "
                                        >
                                            🗑
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-block">
                            <div class="dropdown-label">Аудитория</div>
                            <button
                                type="button"
                                class="dropdown-trigger"
                                :class="{ disabled: !selectedBuildingId }"
                                @click.stop="
                                    selectedBuildingId && toggleRoomMenu()
                                "
                                :disabled="!selectedBuildingId"
                            >
                                {{ selectedRoom?.name ?? 'Выберите аудиторию' }}
                                <span class="dropdown-arrow">▾</span>
                            </button>
                            <div v-if="roomMenuOpen" class="dropdown-menu">
                                <button
                                    type="button"
                                    class="dropdown-item create-item"
                                    @click="openRoomCreateModal"
                                >
                                    + Создать аудиторию
                                </button>
                                <div
                                    v-for="room in rooms"
                                    :key="room.id_rooms"
                                    class="dropdown-item"
                                >
                                    <button
                                        type="button"
                                        class="item-label"
                                        @click="selectRoom(room.id_rooms)"
                                    >
                                        {{ room.name }}
                                    </button>
                                    <div class="item-actions">
                                        <button
                                            type="button"
                                            class="action-button"
                                            @click.stop="
                                                openRoomEditModal(room)
                                            "
                                        >
                                            ✎
                                        </button>
                                        <button
                                            type="button"
                                            class="action-button"
                                            @click.stop="
                                                openRoomDeleteModal(room)
                                            "
                                        >
                                            🗑
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="schedule-preview content-scroll">
                        <div class="schedule-preview-header">
                            <span>Расписание аудитории</span>
                            <span class="selected-room">
                                {{
                                    selectedRoom?.name ?? 'Аудитория не выбрана'
                                }}
                            </span>
                        </div>
                        <div class="schedule-preview-body">
                            <ScheduleTable
                                v-if="selectedRoomId"
                                :lessons="selectedRoomLessons"
                            />
                            <div v-else class="empty-schedule">
                                Выберите аудиторию справа сверху, чтобы увидеть
                                её расписание.
                            </div>
                        </div>
                    </div>
                </section>

                <Modal v-model="isBuildingCreateOpen">
                    <template #header>Создать здание</template>
                    <form @submit.prevent="createBuilding" class="modal-form">
                        <label>Название корпуса</label>
                        <input
                            v-model="buildingForm.name"
                            type="text"
                            placeholder="Например, Главный корпус"
                        />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isBuildingEditOpen">
                    <template #header>Редактировать здание</template>
                    <form @submit.prevent="updateBuilding" class="modal-form">
                        <label>Название корпуса</label>
                        <input v-model="buildingForm.name" type="text" />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isBuildingDeleteOpen">
                    <template #header>Удалить здание</template>
                    <div>
                        <p>
                            Вы действительно хотите удалить здание «{{
                                deletingBuilding?.name
                            }}»?
                        </p>
                        <p class="warning-text">
                            При удалении корпуса будут удалены все его аудитории
                            и занятия в них.
                        </p>
                        <div class="modal-actions">
                            <button
                                type="button"
                                @click="isBuildingDeleteOpen = false"
                            >
                                Отмена
                            </button>
                            <button type="button" @click="deleteBuilding">
                                Удалить
                            </button>
                        </div>
                    </div>
                </Modal>

                <Modal v-model="isRoomCreateOpen">
                    <template #header>Создать аудиторию</template>
                    <form @submit.prevent="createRoom" class="modal-form">
                        <label>Название аудитории</label>
                        <input
                            v-model="roomForm.name"
                            type="text"
                            placeholder="Например, 101"
                        />
                        <label>Здание</label>
                        <select v-model="roomForm.id_buildings">
                            <option value="" disabled>Выберите здание</option>
                            <option
                                v-for="building in buildings"
                                :key="building.id_buildings"
                                :value="building.id_buildings"
                            >
                                {{ building.name }}
                            </option>
                        </select>
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isRoomEditOpen">
                    <template #header>Редактировать аудиторию</template>
                    <form @submit.prevent="updateRoom" class="modal-form">
                        <label>Название аудитории</label>
                        <input v-model="roomForm.name" type="text" />
                        <label>Здание</label>
                        <select v-model="roomForm.id_buildings">
                            <option value="" disabled>Выберите здание</option>
                            <option
                                v-for="building in buildings"
                                :key="building.id_buildings"
                                :value="building.id_buildings"
                            >
                                {{ building.name }}
                            </option>
                        </select>
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isRoomDeleteOpen">
                    <template #header>Удалить аудиторию</template>
                    <div>
                        <p>
                            Вы действительно хотите удалить аудиторию «{{
                                deletingRoom?.name
                            }}»?
                        </p>
                        <div class="modal-actions">
                            <button
                                type="button"
                                @click="isRoomDeleteOpen = false"
                            >
                                Отмена
                            </button>
                            <button type="button" @click="deleteRoom">
                                Удалить
                            </button>
                        </div>
                    </div>
                </Modal>

                <section class="card bottom-card" @click="closeMenus">
                    <h2>Выбор факультета и преподавателя</h2>
                    <div class="custom-filters">
                        <div class="dropdown-block">
                            <div class="dropdown-label">Кафедра</div>
                            <button
                                type="button"
                                class="dropdown-trigger"
                                @click.stop="toggleFacultyMenu"
                            >
                                {{
                                    selectedFaculty?.name ?? 'Выберите кафедру'
                                }}
                                <span class="dropdown-arrow">▾</span>
                            </button>
                            <div v-if="facultyMenuOpen" class="dropdown-menu">
                                <div
                                    v-for="faculty in faculties"
                                    :key="faculty.id_faculties"
                                    class="dropdown-item"
                                >
                                    <button
                                        type="button"
                                        class="item-label"
                                        @click="
                                            selectFaculty(faculty.id_faculties)
                                        "
                                    >
                                        {{ faculty.name }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-block">
                            <div class="dropdown-label">Преподаватель</div>
                            <button
                                type="button"
                                class="dropdown-trigger"
                                :class="{ disabled: !selectedFacultyId }"
                                @click.stop="
                                    selectedFacultyId && toggleTeacherMenu()
                                "
                                :disabled="!selectedFacultyId"
                            >
                                {{
                                    selectedTeacher?.name ??
                                    'Выберите преподавателя'
                                }}
                                <span class="dropdown-arrow">▾</span>
                            </button>
                            <div v-if="teacherMenuOpen" class="dropdown-menu">
                                <div
                                    v-for="teacher in teachers"
                                    :key="teacher.id_teachers"
                                    class="dropdown-item"
                                >
                                    <button
                                        type="button"
                                        class="item-label"
                                        @click="
                                            selectTeacher(teacher.id_teachers)
                                        "
                                    >
                                        {{ teacher.name }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="schedule-preview content-scroll">
                        <div class="schedule-preview-header">
                            <span>Расписание преподавателя</span>
                            <span class="selected-room">
                                {{
                                    selectedTeacher?.name ??
                                    'Преподаватель не выбран'
                                }}
                            </span>
                        </div>
                        <div class="schedule-preview-body">
                            <ScheduleTable
                                v-if="selectedTeacherId"
                                :lessons="selectedTeacherLessons"
                            />
                            <div v-else class="empty-schedule">
                                Выберите преподавателя слева сверху, чтобы
                                увидеть его расписание.
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="right-side">
                <section class="card schedule-card">
                    <div class="schedule-header-block">
                        <h2>Расписание</h2>
                        <p class="schedule-note">
                            Строки — пары 1–8, столбцы — дни недели. Верхняя
                            половина — чётная неделя, нижняя — нечётная.
                        </p>
                    </div>
                    <ScheduleTable :lessons="lessons" />
                </section>
            </div>
        </div>
    </div>
</template>

<style scoped>
.year-page {
    padding: 24px;
}

.page-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 1fr;
    gap: 24px;
    min-height: calc(100vh - 100px);
    height: calc(100vh - 100px);
}

.left-side {
    display: grid;
    grid-template-rows: 1fr 1fr;
    gap: 24px;
    min-height: 0;
}

.right-side {
    display: grid;
    gap: 24px;
    min-height: 0;
}

.left-side > section,
.right-side > section {
    min-height: 0;
}

.top-card,
.bottom-card,
.schedule-card {
    display: flex;
    flex-direction: column;
    min-height: 0;
}

.schedule-card {
    height: 100%;
}

.left-side {
    grid-template-rows: 1fr 1fr;
}

.page-grid {
    grid-template-columns: 1fr 1fr;
}

.card {
    background: #ffffff;
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 24px 64px rgba(15, 23, 42, 0.06);
    min-height: 0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.top-card,
.bottom-card {
    overflow: visible;
}

.card > * {
    min-height: 0;
}

.card .content-scroll {
    overflow: auto;
    min-height: 0;
}

.schedule-preview {
    margin-top: 20px;
    min-height: calc(100% - 72px);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.schedule-preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.schedule-preview-body {
    flex: 1;
    min-height: 0;
    overflow: auto;
}

.empty-schedule {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 220px;
    color: #6b7280;
    text-align: center;
}

.custom-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    align-items: flex-end;
    font-weight: 600;
    margin-bottom: 10px;
}

.custom-filters > .dropdown-block {
    flex: 1 1 280px;
    min-width: 220px;
}

.dropdown-block {
    position: relative;
}

.dropdown-trigger {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 18px;
    border-radius: 16px;
    border: 1px solid #d1d5db;
    background: #f9fafb;
    font-size: 15px;
    color: #111827;
    cursor: pointer;
}

.dropdown-trigger.disabled {
    opacity: 0.55;
    cursor: not-allowed;
}

.dropdown-arrow {
    margin-left: 12px;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    margin-top: 10px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
    z-index: 999;
    overflow: hidden;
}

.dropdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 14px 18px;
    border-bottom: 1px solid #f3f4f6;
    background: white;
}

.dropdown-item:last-child {
    border-bottom: none;
}

.create-item {
    font-weight: 700;
    color: #16a34a;
    background: #ecfdf5;
}

.item-label {
    flex: 1;
    text-align: left;
    background: none;
    border: none;
    padding: 0;
    font-size: 15px;
    color: #111827;
    cursor: pointer;
}

.item-actions {
    display: flex;
    gap: 8px;
}

.action-button {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: none;
    background: #f3f4f6;
    cursor: pointer;
    display: grid;
    place-items: center;
}

.action-button:hover {
    background: #e2e8f0;
}

.modal-form {
    display: grid;
    gap: 16px;
}

.modal-form label {
    font-size: 14px;
    color: #374151;
}

.modal-form input,
.modal-form select {
    width: 100%;
    padding: 12px 14px;
    border-radius: 14px;
    border: 1px solid #d1d5db;
    background: #f9fafb;
}

.modal-form button {
    width: fit-content;
    padding: 12px 22px;
    border: none;
    border-radius: 9999px;
    background: #16a34a;
    color: white;
    cursor: pointer;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 18px;
}

.modal-actions button {
    padding: 10px 18px;
    border-radius: 9999px;
    border: none;
    cursor: pointer;
}

.modal-actions button:first-child {
    background: #f3f4f6;
    color: #111827;
}

.modal-actions button:last-child {
    background: #ef4444;
    color: white;
}

.full-width {
    min-height: 240px;
}
</style>
