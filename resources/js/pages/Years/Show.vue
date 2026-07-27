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
    first_name: string;
    last_name: string;
    middle_name: string | null;
    full_name: string;
    email: string | null;
    telephone: string | null;
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

interface Worksheet {
    id_worksheets: number;
    name: string;
    groups: Group[];
}

interface Subgroup {
    id_subgroups: number;
    name: string;
}

interface Subject {
    id_subjects: number;
    name: string;
}

interface Group {
    id_groups: number;
    id_worksheets: number;
    id_subgroups: number | null;
    name: string;
    stud_num: number | null;
    subgroup?: Subgroup | null;
}

const props = defineProps({
    year: Object as () => Year,
    buildings: Array as () => Building[],
    faculties: Array as () => Faculty[],
    worksheets: Array as () => Worksheet[],
    subgroups: Array as () => Subgroup[],
    subjects: Array as () => Subject[],
    lessons: Array as () => any[],
});

const lessons = computed(() => props.lessons || []);

const page = usePage();

defineOptions({
    name: 'YearShow',
    layout: MainLayout,
});

const days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];

const selectedBuildingId = ref<number | null>(null);
const selectedRoomId = ref<number | null>(null);
const selectedFacultyId = ref<number | null>(null);
const selectedTeacherId = ref<number | null>(null);
const selectedWorksheetId = ref<number | null>(null);
const selectedGroupId = ref<number | null>(null);
const buildingSearch = ref('');
const roomSearch = ref('');
const facultySearch = ref('');
const teacherSearch = ref('');
const worksheetSearch = ref('');
const groupSearch = ref('');
const buildingMenuOpen = ref(false);
const roomMenuOpen = ref(false);
const facultyMenuOpen = ref(false);
const teacherMenuOpen = ref(false);
const worksheetMenuOpen = ref(false);
const groupMenuOpen = ref(false);
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
const isWorksheetCreateOpen = ref(false);
const isWorksheetEditOpen = ref(false);
const isWorksheetDeleteOpen = ref(false);
const isGroupCreateOpen = ref(false);
const isGroupEditOpen = ref(false);
const isGroupDeleteOpen = ref(false);
const editingBuilding = ref<Building | null>(null);
const deletingBuilding = ref<Building | null>(null);
const editingRoom = ref<Room | null>(null);
const deletingRoom = ref<Room | null>(null);
const editingFaculty = ref<Faculty | null>(null);
const deletingFaculty = ref<Faculty | null>(null);
const editingTeacher = ref<Teacher | null>(null);
const deletingTeacher = ref<Teacher | null>(null);
const editingWorksheet = ref<Worksheet | null>(null);
const deletingWorksheet = ref<Worksheet | null>(null);
const editingGroup = ref<Group | null>(null);
const deletingGroup = ref<Group | null>(null);

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
    first_name: '',
    last_name: '',
    middle_name: '',
    email: '',
    telephone: '',
    faculty_id: '',
});

const worksheetForm = useForm({
    name: '',
});

const groupForm = useForm({
    name: '',
    id_worksheets: '',
    id_subgroups: '',
    stud_num: '',
});

const buildings = computed(() => {
    const q = buildingSearch.value.trim().toLowerCase();
    const all = props.buildings || [];
    if (!q) return all;
    return all.filter((b) => b.name.toLowerCase().includes(q));
});

const rooms = computed(() => {
    const q = roomSearch.value.trim().toLowerCase();
    const building = buildings.value.find(
        (item) => item.id_buildings === selectedBuildingId.value,
    );
    const all = building ? building.rooms : [];
    if (!q) return all;
    return all.filter((r) => r.name.toLowerCase().includes(q));
});

const allRooms = computed(() => {
    return props.buildings?.flatMap((b) => b.rooms) || [];
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

const faculties = computed(() => {
    const q = facultySearch.value.trim().toLowerCase();
    const all = props.faculties || [];
    if (!q) return all;
    return all.filter((f) => f.name.toLowerCase().includes(q));
});

const teachers = computed(() => {
    const q = teacherSearch.value.trim().toLowerCase();
    const faculty = faculties.value.find(
        (item) => item.id_faculties === selectedFacultyId.value,
    );
    const all = faculty ? faculty.teachers : [];
    if (!q) return all;
    return all.filter((t) => {
        const full = `${t.last_name ?? ''} ${t.first_name ?? ''} ${t.middle_name ?? ''}`.toLowerCase();
        return full.includes(q);
    });
});

const allTeachers = computed(() => {
    return props.faculties?.flatMap((f) => f.teachers) || [];
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

const worksheets = computed(() => {
    const q = worksheetSearch.value.trim().toLowerCase();
    const all = props.worksheets || [];
    if (!q) return all;
    return all.filter((ws) => ws.name.toLowerCase().includes(q));
});

const groups = computed(() => {
    const q = groupSearch.value.trim().toLowerCase();
    const worksheet = worksheets.value.find(
        (item) => item.id_worksheets === selectedWorksheetId.value,
    );
    const all = worksheet ? worksheet.groups : [];
    if (!q) return all;
    return all.filter((g) => g.name.toLowerCase().includes(q));
});

const selectedWorksheet = computed(() =>
    worksheets.value.find(
        (item) => item.id_worksheets === selectedWorksheetId.value,
    ),
);

const selectedGroup = computed(() =>
    groups.value.find((item) => item.id_groups === selectedGroupId.value),
);

const selectedGroupLessons = computed(() =>
    lessons.value.filter(
        (lesson) => lesson.id_groups === selectedGroupId.value,
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
    teacherForm.last_name = teacher.last_name;
    teacherForm.first_name = teacher.first_name;
    teacherForm.middle_name = teacher.middle_name ?? '';
    teacherForm.email = teacher.email ?? '';
    teacherForm.telephone = teacher.telephone ?? '';
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
            teacherForm.reset();
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
            teacherForm.reset();
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
    worksheetMenuOpen.value = false;
    groupMenuOpen.value = false;
    contextMenu.value = null;
};

const openWorksheetCreateModal = () => {
    worksheetForm.reset();
    isWorksheetCreateOpen.value = true;
    worksheetMenuOpen.value = false;
};

const openWorksheetEditModal = (worksheet: Worksheet) => {
    editingWorksheet.value = worksheet;
    worksheetForm.name = worksheet.name;
    isWorksheetEditOpen.value = true;
    worksheetMenuOpen.value = false;
};

const openWorksheetDeleteModal = (worksheet: Worksheet) => {
    deletingWorksheet.value = worksheet;
    isWorksheetDeleteOpen.value = true;
    worksheetMenuOpen.value = false;
};

const createWorksheet = () => {
    worksheetForm.post('/worksheets', {
        onSuccess: () => {
            worksheetForm.reset('name');
            isWorksheetCreateOpen.value = false;
        },
        preserveScroll: true,
    });
};

const updateWorksheet = () => {
    if (!editingWorksheet.value) {
        return;
    }

    worksheetForm.put(`/worksheets/${editingWorksheet.value.id_worksheets}`, {
        onSuccess: () => {
            worksheetForm.reset('name');
            isWorksheetEditOpen.value = false;
        },
        preserveScroll: true,
    });
};

const deleteWorksheet = () => {
    if (!deletingWorksheet.value) {
        return;
    }

    worksheetForm.delete(`/worksheets/${deletingWorksheet.value.id_worksheets}`, {
        onSuccess: () => {
            deletingWorksheet.value = null;
            isWorksheetDeleteOpen.value = false;
        },
        preserveScroll: true,
    });
};

const openGroupCreateModal = () => {
    groupForm.reset();
    groupForm.id_worksheets = selectedWorksheetId.value
        ? selectedWorksheetId.value.toString()
        : '';
    groupForm.id_subgroups = '';
    isGroupCreateOpen.value = true;
    groupMenuOpen.value = false;
};

const openGroupEditModal = (group: Group) => {
    editingGroup.value = group;
    groupForm.name = group.name;
    groupForm.id_worksheets = group.id_worksheets.toString();
    groupForm.id_subgroups = group.id_subgroups?.toString() ?? '';
    groupForm.stud_num = group.stud_num?.toString() ?? '';
    isGroupEditOpen.value = true;
    groupMenuOpen.value = false;
};

const openGroupDeleteModal = (group: Group) => {
    deletingGroup.value = group;
    isGroupDeleteOpen.value = true;
    groupMenuOpen.value = false;
};

const createGroup = () => {
    groupForm.post('/groups', {
        onSuccess: () => {
            groupForm.reset('name', 'id_worksheets', 'id_subgroups', 'stud_num');
            isGroupCreateOpen.value = false;
        },
        preserveScroll: true,
    });
};

const updateGroup = () => {
    if (!editingGroup.value) {
        return;
    }

    groupForm.put(`/groups/${editingGroup.value.id_groups}`, {
        onSuccess: () => {
            groupForm.reset('name', 'id_worksheets', 'id_subgroups', 'stud_num');
            isGroupEditOpen.value = false;
        },
        preserveScroll: true,
    });
};

const deleteGroup = () => {
    if (!deletingGroup.value) {
        return;
    }

    groupForm.delete(`/groups/${deletingGroup.value.id_groups}`, {
        onSuccess: () => {
            deletingGroup.value = null;
            isGroupDeleteOpen.value = false;
        },
        preserveScroll: true,
    });
};

const selectWorksheet = (id: number) => {
    selectedWorksheetId.value = id;
    selectedGroupId.value = null;
    worksheetMenuOpen.value = false;
};

const selectGroup = (id: number) => {
    selectedGroupId.value = id;
    groupMenuOpen.value = false;
};

const toggleWorksheetMenu = () => {
    worksheetMenuOpen.value = !worksheetMenuOpen.value;
    if (worksheetMenuOpen.value) {
        groupMenuOpen.value = false;
    }
};

const toggleGroupMenu = () => {
    groupMenuOpen.value = !groupMenuOpen.value;
    if (groupMenuOpen.value) {
        buildingMenuOpen.value = false;
        roomMenuOpen.value = false;
        facultyMenuOpen.value = false;
        teacherMenuOpen.value = false;
        worksheetMenuOpen.value = false;
    }
};

const isLessonCreateOpen = ref(false);
const isLessonEditOpen = ref(false);
const isLessonDeleteOpen = ref(false);
const editingLesson = ref<any>(null);
const deletingLesson = ref<any>(null);

const contextMenu = ref<{ lesson: any; x: number; y: number } | null>(null);

const isSubgroupCreateOpen = ref(false);
const isSubgroupEditOpen = ref(false);
const isSubgroupDeleteOpen = ref(false);
const editingSubgroup = ref<Subgroup | null>(null);
const deletingSubgroup = ref<Subgroup | null>(null);
const subgroupForm = useForm({
    name: '',
});

const isSubjectCreateOpen = ref(false);
const subjectForm = useForm({
    name: '',
});

const lessonForm = useForm({
    id_groups: '',
    id_years: '',
    id_subgroups: '',
    id_rooms: '',
    id_teachers: '',
    id_subjects: '',
    week_parity: 'both',
    week_day: 1,
    order_number: 1,
    isZaoch: false,
    start_date: '',
    end_date: '',
});

const cellClick = (day: string | number, order: number) => {
    if (!selectedGroupId.value) {
        return;
    }
    const dayIndex = days.indexOf(day as string) + 1;
    lessonForm.id_groups = selectedGroupId.value.toString();
    lessonForm.id_years = props.year.id_years.toString();
    lessonForm.id_subgroups = selectedGroup?.value?.id_subgroups?.toString() ?? '';
    lessonForm.week_day = dayIndex;
    lessonForm.order_number = order;
    lessonForm.reset('id_rooms', 'id_teachers', 'id_subjects', 'start_date', 'end_date');
    editingLesson.value = null;
    isLessonCreateOpen.value = true;
};

const openLessonEditModal = (lesson: any) => {
    editingLesson.value = lesson;
    lessonForm.id_groups = lesson.id_groups.toString();
    lessonForm.id_years = lesson.id_years.toString();
    lessonForm.id_subgroups = lesson.id_subgroups.toString();
    lessonForm.id_rooms = lesson.id_rooms.toString();
    lessonForm.id_teachers = lesson.id_teachers.toString();
    lessonForm.id_subjects = lesson.id_subjects.toString();
    lessonForm.week_parity = lesson.week_parity;
    lessonForm.week_day = lesson.week_day.toString();
    lessonForm.order_number = lesson.order_number.toString();
    lessonForm.isZaoch = lesson.isZaoch ?? false;
    lessonForm.start_date = lesson.start_date ?? '';
    lessonForm.end_date = lesson.end_date ?? '';
    isLessonEditOpen.value = true;
};

const openLessonDeleteModal = (lesson: any) => {
    deletingLesson.value = lesson;
    isLessonDeleteOpen.value = true;
};

const onLessonClick = (lesson: any) => {
    openLessonEditModal(lesson);
};

const onLessonContextMenu = (lesson: any, event: MouseEvent) => {
    contextMenu.value = {
        lesson,
        x: event.clientX,
        y: event.clientY,
    };
};

const closeContextMenu = () => {
    contextMenu.value = null;
};

const editFromContextMenu = () => {
    if (contextMenu.value?.lesson) {
        openLessonEditModal(contextMenu.value.lesson);
    }
    contextMenu.value = null;
};

const deleteFromContextMenu = () => {
    if (contextMenu.value?.lesson) {
        openLessonDeleteModal(contextMenu.value.lesson);
    }
    contextMenu.value = null;
};

const createLesson = () => {
    lessonForm.post('/lessons', {
        onSuccess: () => {
            lessonForm.reset('id_groups', 'id_years', 'id_subgroups', 'id_rooms', 'id_teachers', 'id_subjects', 'start_date', 'end_date');
            lessonForm.week_parity = 'both';
            lessonForm.week_day = 1;
            lessonForm.order_number = 1;
            lessonForm.isZaoch = false;
            isLessonCreateOpen.value = false;
        },
        preserveScroll: true,
    });
};

const updateLesson = () => {
    if (!editingLesson.value) {
        return;
    }

    lessonForm.put(`/lessons/${editingLesson.value.id_lessons}`, {
        onSuccess: () => {
            lessonForm.reset('id_groups', 'id_years', 'id_subgroups', 'id_rooms', 'id_teachers', 'id_subjects', 'start_date', 'end_date');
            lessonForm.week_parity = 'both';
            lessonForm.week_day = 1;
            lessonForm.order_number = 1;
            lessonForm.isZaoch = false;
            isLessonEditOpen.value = false;
        },
        preserveScroll: true,
    });
};

const deleteLesson = () => {
    if (!deletingLesson.value) {
        return;
    }

    lessonForm.delete(`/lessons/${deletingLesson.value.id_lessons}`, {
        onSuccess: () => {
            deletingLesson.value = null;
            isLessonDeleteOpen.value = false;
        },
        preserveScroll: true,
    });
};

const openSubgroupCreateModal = () => {
    subgroupForm.reset();
    isSubgroupCreateOpen.value = true;
};

const openSubjectCreateModal = () => {
    subjectForm.reset();
    isSubjectCreateOpen.value = true;
};

const createSubgroup = () => {
    subgroupForm.post('/subgroups', {
        onSuccess: () => {
            subgroupForm.reset();
            isSubgroupCreateOpen.value = false;
        },
        preserveScroll: true,
    });
};

const openSubgroupEditModal = (subgroup: Subgroup) => {
    editingSubgroup.value = subgroup;
    subgroupForm.name = subgroup.name;
    isSubgroupEditOpen.value = true;
};

const openSubgroupDeleteModal = (subgroup: Subgroup) => {
    deletingSubgroup.value = subgroup;
    isSubgroupDeleteOpen.value = true;
};

const updateSubgroup = () => {
    if (!editingSubgroup.value) {
        return;
    }

    subgroupForm.put(`/subgroups/${editingSubgroup.value.id_subgroups}`, {
        onSuccess: () => {
            subgroupForm.reset();
            isSubgroupEditOpen.value = false;
        },
        preserveScroll: true,
    });
};

const deleteSubgroup = () => {
    if (!deletingSubgroup.value) {
        return;
    }

    subgroupForm.delete(`/subgroups/${deletingSubgroup.value.id_subgroups}`, {
        onSuccess: () => {
            deletingSubgroup.value = null;
            isSubgroupDeleteOpen.value = false;
        },
        preserveScroll: true,
    });
};

const createSubject = () => {
    subjectForm.post('/subjects', {
        onSuccess: () => {
            subjectForm.reset();
            isSubjectCreateOpen.value = false;
        },
        preserveScroll: true,
    });
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
                                <div class="dropdown-search" @click.stop>
                                    <input
                                        v-model="buildingSearch"
                                        type="text"
                                        placeholder="Поиск..."
                                    />
                                </div>
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
                                <div class="dropdown-search" @click.stop>
                                    <input
                                        v-model="roomSearch"
                                        type="text"
                                        placeholder="Поиск..."
                                    />
                                </div>
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
                                compact
                                @cell-click="cellClick"
                                @lesson-click="onLessonClick"
                                @lesson-contextmenu="onLessonContextMenu"
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
                                <div class="dropdown-search" @click.stop>
                                    <input
                                        v-model="facultySearch"
                                        type="text"
                                        placeholder="Поиск..."
                                    />
                                </div>
                                <button
                                    type="button"
                                    class="dropdown-item create-item"
                                    @click="openFacultyCreateModal"
                                >
                                    + Создать кафедру
                                </button>
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
                                    <div class="item-actions">
                                        <button
                                            type="button"
                                            class="action-button"
                                            @click.stop="
                                                openFacultyEditModal(faculty)
                                            "
                                        >
                                            ✎
                                        </button>
                                        <button
                                            type="button"
                                            class="action-button"
                                            @click.stop="
                                                openFacultyDeleteModal(
                                                    faculty,
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
                                <div class="dropdown-search" @click.stop>
                                    <input
                                        v-model="teacherSearch"
                                        type="text"
                                        placeholder="Поиск..."
                                    />
                                </div>
                                <button
                                    type="button"
                                    class="dropdown-item create-item"
                                    @click="openTeacherCreateModal"
                                >
                                    + Создать преподавателя
                                </button>
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
                                    <div class="item-actions">
                                        <button
                                            type="button"
                                            class="action-button"
                                            @click.stop="
                                                openTeacherEditModal(teacher)
                                            "
                                        >
                                            ✎
                                        </button>
                                        <button
                                            type="button"
                                            class="action-button"
                                            @click.stop="
                                                openTeacherDeleteModal(
                                                    teacher,
                                                )
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
                                compact
                                @cell-click="cellClick"
                                @lesson-click="onLessonClick"
                                @lesson-contextmenu="onLessonContextMenu"
                            />
                            <div v-else class="empty-schedule">
                                Выберите преподавателя слева сверху, чтобы
                                увидеть его расписание.
                            </div>
                        </div>
                    </div>
                </section>

                <Modal v-model="isFacultyCreateOpen">
                    <template #header>Создать кафедру</template>
                    <form @submit.prevent="createFaculty" class="modal-form">
                        <label>Название кафедры</label>
                        <input
                            v-model="facultyForm.name"
                            type="text"
                            placeholder="Например, Математический"
                        />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isFacultyEditOpen">
                    <template #header>Редактировать кафедру</template>
                    <form @submit.prevent="updateFaculty" class="modal-form">
                        <label>Название кафедры</label>
                        <input v-model="facultyForm.name" type="text" />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isFacultyDeleteOpen">
                    <template #header>Удалить кафедру</template>
                    <div>
                        <p>
                            Вы действительно хотите удалить кафедру «{{
                                deletingFaculty?.name
                            }}»?
                        </p>
                        <p class="warning-text">
                            При удалении кафедры будут удалены все связанные
                            преподаватели.
                        </p>
                        <div class="modal-actions">
                            <button
                                type="button"
                                @click="isFacultyDeleteOpen = false"
                            >
                                Отмена
                            </button>
                            <button type="button" @click="deleteFaculty">
                                Удалить
                            </button>
                        </div>
                    </div>
                </Modal>

                <Modal v-model="isTeacherCreateOpen">
                    <template #header>Создать преподавателя</template>
                    <form @submit.prevent="createTeacher" class="modal-form">
                        <label>Фамилия</label>
                        <input
                            v-model="teacherForm.last_name"
                            type="text"
                            placeholder="Например, Иванов"
                        />
                        <label>Имя</label>
                        <input
                            v-model="teacherForm.first_name"
                            type="text"
                            placeholder="Например, Иван"
                        />
                        <label>Отчество</label>
                        <input
                            v-model="teacherForm.middle_name"
                            type="text"
                            placeholder="Например, Иванович"
                        />
                        <label>Email</label>
                        <input
                            v-model="teacherForm.email"
                            type="email"
                            placeholder="example@tyuiu.ru"
                        />
                        <label>Телефон</label>
                        <input
                            v-model="teacherForm.telephone"
                            type="text"
                            placeholder="+7 (XXX) XXX-XX-XX"
                        />
                        <label>Кафедра</label>
                        <select v-model="teacherForm.faculty_id">
                            <option value="" disabled>Выберите кафедру</option>
                            <option
                                v-for="faculty in faculties"
                                :key="faculty.id_faculties"
                                :value="faculty.id_faculties"
                            >
                                {{ faculty.name }}
                            </option>
                        </select>
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isTeacherEditOpen">
                    <template #header>Редактировать преподавателя</template>
                    <form @submit.prevent="updateTeacher" class="modal-form">
                        <label>Фамилия</label>
                        <input v-model="teacherForm.last_name" type="text" />
                        <label>Имя</label>
                        <input v-model="teacherForm.first_name" type="text" />
                        <label>Отчество</label>
                        <input v-model="teacherForm.middle_name" type="text" />
                        <label>Email</label>
                        <input v-model="teacherForm.email" type="email" />
                        <label>Телефон</label>
                        <input v-model="teacherForm.telephone" type="text" />
                        <label>Кафедра</label>
                        <select v-model="teacherForm.faculty_id">
                            <option value="" disabled>Выберите кафедру</option>
                            <option
                                v-for="faculty in faculties"
                                :key="faculty.id_faculties"
                                :value="faculty.id_faculties"
                            >
                                {{ faculty.name }}
                            </option>
                        </select>
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isTeacherDeleteOpen">
                    <template #header>Удалить преподавателя</template>
                    <div>
                        <p>
                            Вы действительно хотите удалить преподавателя «{{
                                deletingTeacher?.name
                            }}»?
                        </p>
                        <div class="modal-actions">
                            <button
                                type="button"
                                @click="isTeacherDeleteOpen = false"
                            >
                                Отмена
                            </button>
                            <button type="button" @click="deleteTeacher">
                                Удалить
                            </button>
                        </div>
                    </div>
                </Modal>

                <Modal v-model="isWorksheetCreateOpen">
                    <template #header>Создать рабочий лист</template>
                    <form @submit.prevent="createWorksheet" class="modal-form">
                        <label>Название рабочего листа</label>
                        <input
                            v-model="worksheetForm.name"
                            type="text"
                            placeholder="Например, Бригада №1"
                        />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isWorksheetEditOpen">
                    <template #header>Редактировать рабочий лист</template>
                    <form @submit.prevent="updateWorksheet" class="modal-form">
                        <label>Название рабочего листа</label>
                        <input v-model="worksheetForm.name" type="text" />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isWorksheetDeleteOpen">
                    <template #header>Удалить рабочий лист</template>
                    <div>
                        <p>
                            Вы действительно хотите удалить рабочий лист «{{
                                deletingWorksheet?.name
                            }}»?
                        </p>
                        <p class="warning-text">
                            При удалении рабочего листа будут удалены все
                            связанные с ним группы.
                        </p>
                        <div class="modal-actions">
                            <button
                                type="button"
                                @click="isWorksheetDeleteOpen = false"
                            >
                                Отмена
                            </button>
                            <button type="button" @click="deleteWorksheet">
                                Удалить
                            </button>
                        </div>
                    </div>
                </Modal>

                <Modal v-model="isGroupCreateOpen">
                    <template #header>Создать группу</template>
                    <form @submit.prevent="createGroup" class="modal-form">
                        <label>Название группы</label>
                        <input
                            v-model="groupForm.name"
                            type="text"
                            placeholder="Например, 101-ОИС"
                        />
                        <label>Рабочий лист</label>
                        <select v-model="groupForm.id_worksheets">
                            <option value="" disabled>Выберите рабочий лист</option>
                            <option
                                v-for="ws in worksheets"
                                :key="ws.id_worksheets"
                                :value="ws.id_worksheets"
                            >
                                {{ ws.name }}
                            </option>
                        </select>
                        <label>Подгруппа</label>
                        <select v-model="groupForm.id_subgroups">
                            <option value="">Без подгруппы</option>
                            <option
                                v-for="subgroup in subgroups"
                                :key="subgroup.id_subgroups"
                                :value="subgroup.id_subgroups"
                            >
                                {{ subgroup.name }}
                            </option>
                        </select>
                        <button
                            type="button"
                            class="create-subject-button"
                            @click="openSubgroupCreateModal"
                        >
                            + Создать подгруппу
                        </button>
                        <label>Количество студентов</label>
                        <input
                            v-model="groupForm.stud_num"
                            type="number"
                            placeholder="Например, 25"
                        />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isGroupEditOpen">
                    <template #header>Редактировать группу</template>
                    <form @submit.prevent="updateGroup" class="modal-form">
                        <label>Название группы</label>
                        <input v-model="groupForm.name" type="text" />
                        <label>Рабочий лист</label>
                        <select v-model="groupForm.id_worksheets">
                            <option value="" disabled>Выберите рабочий лист</option>
                            <option
                                v-for="ws in worksheets"
                                :key="ws.id_worksheets"
                                :value="ws.id_worksheets"
                            >
                                {{ ws.name }}
                            </option>
                        </select>
                        <label>Подгруппа</label>
                        <select v-model="groupForm.id_subgroups">
                            <option value="">Без подгруппы</option>
                            <option
                                v-for="subgroup in subgroups"
                                :key="subgroup.id_subgroups"
                                :value="subgroup.id_subgroups"
                            >
                                {{ subgroup.name }}
                            </option>
                        </select>
                        <button
                            type="button"
                            class="create-subject-button"
                            @click="openSubgroupCreateModal"
                        >
                            + Создать подгруппу
                        </button>
                        <label>Количество студентов</label>
                        <input v-model="groupForm.stud_num" type="number" />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isGroupDeleteOpen">
                    <template #header>Удалить группу</template>
                    <div>
                        <p>
                            Вы действительно хотите удалить группу «{{
                                deletingGroup?.name
                            }}»?
                        </p>
                        <div class="modal-actions">
                            <button
                                type="button"
                                @click="isGroupDeleteOpen = false"
                            >
                                Отмена
                            </button>
                            <button type="button" @click="deleteGroup">
                                Удалить
                            </button>
                        </div>
                    </div>
                </Modal>

                <Modal v-model="isLessonCreateOpen">
                    <template #header>Создать занятие</template>
                    <form @submit.prevent="createLesson" class="modal-form">
                        <label>Группа</label>
                        <input
                            v-model="lessonForm.id_groups"
                            type="text"
                            disabled
                        />
                        <label>Учебный год</label>
                        <input
                            v-model="lessonForm.id_years"
                            type="text"
                            disabled
                        />
                        <label>Подгруппа</label>
                        <select v-model="lessonForm.id_subgroups">
                            <option value="">Без подгруппы</option>
                            <option
                                v-for="subgroup in subgroups"
                                :key="subgroup.id_subgroups"
                                :value="subgroup.id_subgroups"
                            >
                                {{ subgroup.name }}
                            </option>
                        </select>
                        <label>Аудитория</label>
                        <select v-model="lessonForm.id_rooms">
                            <option value="" disabled>Выберите аудиторию</option>
                            <option
                                v-for="room in allRooms"
                                :key="room.id_rooms"
                                :value="room.id_rooms"
                            >
                                {{ room.name }}
                            </option>
                        </select>
                        <label>Преподаватель</label>
                        <select v-model="lessonForm.id_teachers">
                            <option value="" disabled>Выберите преподавателя</option>
                            <option
                                v-for="teacher in allTeachers"
                                :key="teacher.id_teachers"
                                :value="teacher.id_teachers"
                            >
                                {{ teacher.name }}
                            </option>
                        </select>
                        <label>Предмет</label>
                        <select v-model="lessonForm.id_subjects">
                            <option value="" disabled>Выберите предмет</option>
                            <option
                                v-for="subject in subjects"
                                :key="subject.id_subjects"
                                :value="subject.id_subjects"
                            >
                                {{ subject.name }}
                            </option>
                        </select>
                        <button
                            type="button"
                            class="create-subject-button"
                            @click="openSubjectCreateModal"
                        >
                            + Создать предмет
                        </button>
                        <label>Чётность недели</label>
                        <select v-model="lessonForm.week_parity">
                            <option value="both">Каждая неделя</option>
                            <option value="odd">Нечётная</option>
                            <option value="even">Чётная</option>
                        </select>
                        <label>День недели</label>
                        <select v-model="lessonForm.week_day">
                            <option :value="1">Понедельник</option>
                            <option :value="2">Вторник</option>
                            <option :value="3">Среда</option>
                            <option :value="4">Четверг</option>
                            <option :value="5">Пятница</option>
                            <option :value="6">Суббота</option>
                            <option :value="7">Воскресенье</option>
                        </select>
                        <label>Номер пары</label>
                        <select v-model="lessonForm.order_number">
                            <option :value="1">1</option>
                            <option :value="2">2</option>
                            <option :value="3">3</option>
                            <option :value="4">4</option>
                            <option :value="5">5</option>
                            <option :value="6">6</option>
                            <option :value="7">7</option>
                            <option :value="8">8</option>
                        </select>
                        <label>
                            <input
                                v-model="lessonForm.isZaoch"
                                type="checkbox"
                            />
                            Заочное обучение
                        </label>
                        <label>Начало (опционально)</label>
                        <input
                            v-model="lessonForm.start_date"
                            type="date"
                        />
                        <label>Окончание (опционально)</label>
                        <input
                            v-model="lessonForm.end_date"
                            type="date"
                        />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isLessonEditOpen">
                    <template #header>Редактировать занятие</template>
                    <form @submit.prevent="updateLesson" class="modal-form">
                        <label>Группа</label>
                        <input
                            v-model="lessonForm.id_groups"
                            type="text"
                            disabled
                        />
                        <label>Учебный год</label>
                        <input
                            v-model="lessonForm.id_years"
                            type="text"
                            disabled
                        />
                        <label>Подгруппа</label>
                        <select v-model="lessonForm.id_subgroups">
                            <option value="">Без подгруппы</option>
                            <option
                                v-for="subgroup in subgroups"
                                :key="subgroup.id_subgroups"
                                :value="subgroup.id_subgroups"
                            >
                                {{ subgroup.name }}
                            </option>
                        </select>
                        <label>Аудитория</label>
                        <select v-model="lessonForm.id_rooms">
                            <option value="" disabled>Выберите аудиторию</option>
                            <option
                                v-for="room in allRooms"
                                :key="room.id_rooms"
                                :value="room.id_rooms"
                            >
                                {{ room.name }}
                            </option>
                        </select>
                        <label>Преподаватель</label>
                        <select v-model="lessonForm.id_teachers">
                            <option value="" disabled>Выберите преподавателя</option>
                            <option
                                v-for="teacher in allTeachers"
                                :key="teacher.id_teachers"
                                :value="teacher.id_teachers"
                            >
                                {{ teacher.name }}
                            </option>
                        </select>
                        <label>Предмет</label>
                        <select v-model="lessonForm.id_subjects">
                            <option value="" disabled>Выберите предмет</option>
                            <option
                                v-for="subject in subjects"
                                :key="subject.id_subjects"
                                :value="subject.id_subjects"
                            >
                                {{ subject.name }}
                            </option>
                        </select>
                        <label>Чётность недели</label>
                        <select v-model="lessonForm.week_parity">
                            <option value="both">Каждая неделя</option>
                            <option value="odd">Нечётная</option>
                            <option value="even">Чётная</option>
                        </select>
                        <label>День недели</label>
                        <select v-model="lessonForm.week_day">
                            <option :value="1">Понедельник</option>
                            <option :value="2">Вторник</option>
                            <option :value="3">Среда</option>
                            <option :value="4">Четверг</option>
                            <option :value="5">Пятница</option>
                            <option :value="6">Суббота</option>
                            <option :value="7">Воскресенье</option>
                        </select>
                        <label>Номер пары</label>
                        <select v-model="lessonForm.order_number">
                            <option :value="1">1</option>
                            <option :value="2">2</option>
                            <option :value="3">3</option>
                            <option :value="4">4</option>
                            <option :value="5">5</option>
                            <option :value="6">6</option>
                            <option :value="7">7</option>
                            <option :value="8">8</option>
                        </select>
                        <label>
                            <input
                                v-model="lessonForm.isZaoch"
                                type="checkbox"
                            />
                            Заочное обучение
                        </label>
                        <label>Начало (опционально)</label>
                        <input
                            v-model="lessonForm.start_date"
                            type="date"
                        />
                        <label>Окончание (опционально)</label>
                        <input
                            v-model="lessonForm.end_date"
                            type="date"
                        />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isLessonDeleteOpen">
                    <template #header>Удалить занятие</template>
                    <div>
                        <p>
                            Вы действительно хотите удалить занятие?
                        </p>
                        <div class="modal-actions">
                            <button
                                type="button"
                                @click="isLessonDeleteOpen = false"
                            >
                                Отмена
                            </button>
                            <button type="button" @click="deleteLesson">
                                Удалить
                            </button>
                        </div>
                    </div>
                </Modal>

                <Modal v-model="isSubjectCreateOpen">
                    <template #header>Создать предмет</template>
                    <form @submit.prevent="createSubject" class="modal-form">
                        <label>Название предмета</label>
                        <input
                            v-model="subjectForm.name"
                            type="text"
                            placeholder="Например, Математика"
                        />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isSubgroupCreateOpen">
                    <template #header>Создать подгруппу</template>
                    <form @submit.prevent="createSubgroup" class="modal-form">
                        <label>Название подгруппы</label>
                        <input
                            v-model="subgroupForm.name"
                            type="text"
                            placeholder="Например, А"
                        />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isSubgroupEditOpen">
                    <template #header>Редактировать подгруппу</template>
                    <form @submit.prevent="updateSubgroup" class="modal-form">
                        <label>Название подгруппы</label>
                        <input v-model="subgroupForm.name" type="text" />
                        <button type="submit">Сохранить</button>
                    </form>
                </Modal>

                <Modal v-model="isSubgroupDeleteOpen">
                    <template #header>Удалить подгруппу</template>
                    <div>
                        <p>
                            Вы действительно хотите удалить подгруппу «{{
                                deletingSubgroup?.name
                            }}»?
                        </p>
                        <div class="modal-actions">
                            <button
                                type="button"
                                @click="isSubgroupDeleteOpen = false"
                            >
                                Отмена
                            </button>
                            <button type="button" @click="deleteSubgroup">
                                Удалить
                            </button>
                        </div>
                    </div>
                </Modal>

                <!-- Context Menu -->
                <Teleport to="body">
                    <div
                        v-if="contextMenu"
                        class="lesson-context-menu"
                        :style="{ left: contextMenu.x + 'px', top: contextMenu.y + 'px' }"
                        @click.stop
                    >
                        <button type="button" class="context-menu-item" @click="editFromContextMenu">
                            <span>✎</span>
                            <span>Редактировать</span>
                        </button>
                        <button type="button" class="context-menu-item" @click="deleteFromContextMenu">
                            <span>🗑</span>
                            <span>Удалить</span>
                        </button>
                    </div>
                </Teleport>
            </div>

            <div class="right-side">
                <section class="card schedule-card" @click="closeMenus">
                    <div class="schedule-header-block">
                        <h2>Расписание</h2>
                        <p class="schedule-note">
                            Строки — пары 1–8, столбцы — дни недели. Верхняя
                            половина — чётная неделя, нижняя — нечётная.
                        </p>
                    </div>
                    <div class="custom-filters">
                        <div class="dropdown-block">
                            <div class="dropdown-label">Рабочий лист</div>
                            <button
                                type="button"
                                class="dropdown-trigger"
                                @click.stop="toggleWorksheetMenu"
                            >
                                {{
                                    selectedWorksheet?.name ?? 'Выберите рабочий лист'
                                }}
                                <span class="dropdown-arrow">▾</span>
                            </button>
                            <div v-if="worksheetMenuOpen" class="dropdown-menu">
                                <div class="dropdown-search" @click.stop>
                                    <input
                                        v-model="worksheetSearch"
                                        type="text"
                                        placeholder="Поиск..."
                                    />
                                </div>
                                <button
                                    type="button"
                                    class="dropdown-item create-item"
                                    @click="openWorksheetCreateModal"
                                >
                                    + Создать рабочий лист
                                </button>
                                <div
                                    v-for="worksheet in worksheets"
                                    :key="worksheet.id_worksheets"
                                    class="dropdown-item"
                                >
                                    <button
                                        type="button"
                                        class="item-label"
                                        @click="selectWorksheet(worksheet.id_worksheets)"
                                    >
                                        {{ worksheet.name }}
                                        <span class="badge">
                                            {{ worksheet.groups.length }} гр.
                                        </span>
                                    </button>
                                    <div class="item-actions">
                                        <button
                                            type="button"
                                            class="action-button"
                                            @click.stop="openWorksheetEditModal(worksheet)"
                                        >
                                            ✎
                                        </button>
                                        <button
                                            type="button"
                                            class="action-button"
                                            @click.stop="openWorksheetDeleteModal(worksheet)"
                                        >
                                            🗑
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-block">
                            <div class="dropdown-label">Группа</div>
                            <button
                                type="button"
                                class="dropdown-trigger"
                                :class="{ disabled: !selectedWorksheetId }"
                                @click.stop="selectedWorksheetId && toggleGroupMenu()"
                                :disabled="!selectedWorksheetId"
                            >
                                {{
                                    selectedGroup?.name ?? 'Выберите группу'
                                }}
                                <span class="dropdown-arrow">▾</span>
                            </button>
                            <div v-if="groupMenuOpen" class="dropdown-menu">
                                <div class="dropdown-search" @click.stop>
                                    <input
                                        v-model="groupSearch"
                                        type="text"
                                        placeholder="Поиск..."
                                    />
                                </div>
                                <button
                                    type="button"
                                    class="dropdown-item create-item"
                                    @click="openGroupCreateModal"
                                >
                                    + Создать группу
                                </button>
                                <div
                                    v-for="group in groups"
                                    :key="group.id_groups"
                                    class="dropdown-item"
                                >
                                    <button
                                        type="button"
                                        class="item-label"
                                        @click="selectGroup(group.id_groups)"
                                    >
                                        {{ group.name }}
                                        <span v-if="group.subgroup" class="badge">
                                            {{ group.subgroup.name }}
                                        </span>
                                    </button>
                                    <div class="item-actions">
                                        <button
                                            type="button"
                                            class="action-button"
                                            @click.stop="openGroupEditModal(group)"
                                        >
                                            ✎
                                        </button>
                                        <button
                                            type="button"
                                            class="action-button"
                                            @click.stop="openGroupDeleteModal(group)"
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
                            <span>Расписание группы</span>
                            <span class="selected-room">
                                {{
                                    selectedGroup?.name ?? 'Группа не выбрана'
                                }}
                            </span>
                        </div>
                        <div class="schedule-preview-body">
                            <ScheduleTable
                                v-if="selectedGroupId"
                                :lessons="selectedGroupLessons"
                                @cell-click="cellClick"
                                @lesson-click="onLessonClick"
                                @lesson-contextmenu="onLessonContextMenu"
                            />
                            <div v-else class="empty-schedule">
                                Выберите группу выше, чтобы увидеть её расписание.
                            </div>
                        </div>
                    </div>
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
    margin-top: 10px;
    min-height: calc(100% - 60px);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.schedule-preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
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
    min-height: 120px;
    color: #6b7280;
    text-align: center;
    font-size: 13px;
}

.custom-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: flex-end;
    font-weight: 600;
    margin-bottom: 8px;
}

.custom-filters > .dropdown-block {
    flex: 1 1 200px;
    min-width: 160px;
}

.dropdown-block {
    position: relative;
}

.dropdown-trigger {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    background: #f9fafb;
    font-size: 13px;
    color: #111827;
    cursor: pointer;
}

.dropdown-trigger.disabled {
    opacity: 0.55;
    cursor: not-allowed;
}

.dropdown-arrow {
    margin-left: 6px;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    margin-top: 8px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
    z-index: 999;
    overflow: hidden;
    animation: dropdownIn 0.18s ease-out;
    transform-origin: top center;
}

@keyframes dropdownIn {
    from {
        opacity: 0;
        transform: translateY(-6px) scale(0.97);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.dropdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 6px;
    width: 100%;
    padding: 8px 12px;
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

.create-subject-button {
    width: fit-content;
    padding: 8px 18px;
    border: none;
    border-radius: 9999px;
    background: #2563eb;
    color: white;
    cursor: pointer;
    font-size: 13px;
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

.lesson-context-menu {
    position: fixed;
    z-index: 10000;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.15);
    padding: 8px;
    min-width: 180px;
    display: flex;
    flex-direction: column;
    animation: contextMenuIn 0.15s ease-out;
    transform-origin: top left;
}

@keyframes contextMenuIn {
    from {
        opacity: 0;
        transform: scale(0.92) translateY(-4px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.context-menu-item {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 10px 14px;
    border: none;
    background: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    color: #111827;
    text-align: left;
    transition: background 0.12s ease;
}

.context-menu-item:hover {
    background: #f3f4f6;
}

.context-menu-item:active {
    background: #e5e7eb;
}
</style>
