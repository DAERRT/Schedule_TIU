<template>
    <div class="schedule-table">
        <div class="schedule-header">
            <div class="schedule-cell schedule-cell--header"></div>
            <div
                v-for="day in days"
                :key="day"
                class="schedule-cell schedule-cell--header"
            >
                {{ day }}
            </div>
        </div>

        <div v-for="row in rows" :key="row" class="schedule-row">
            <div class="schedule-cell schedule-cell--row-label">{{ row }}</div>

            <div
                v-for="day in days"
                :key="`${day}-${row}`"
                class="schedule-cell schedule-cell--lesson"
            >
                <div class="schedule-half schedule-half--even">
                    <div
                        v-if="cellLessons(day, row).groupsEven.length"
                        class="subgroup-grid"
                        :style="{
                            gridTemplateColumns: `repeat(${cellLessons(day, row).groupsEven.length}, minmax(0, 1fr))`,
                        }"
                    >
                        <div
                            v-for="group in cellLessons(day, row).groupsEven"
                            :key="group.subgroup"
                            class="subgroup-column"
                        >
                            <div class="parity-label">Чётная</div>
                            <div
                                v-for="lesson in group.lessons"
                                :key="lesson.id_lessons"
                                class="lesson-block"
                            >
                                <div class="lesson-title">
                                    {{ lesson.subject.name }}
                                </div>
                                <div class="lesson-meta">
                                    {{ lesson.room.name }} ·
                                    {{ lesson.teacher.name }}
                                </div>
                                <div class="lesson-subgroup">
                                    {{ lesson.subgroup?.name ?? 'Общая' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="schedule-half schedule-half--odd">
                    <div
                        v-if="cellLessons(day, row).groupsOdd.length"
                        class="subgroup-grid"
                        :style="{
                            gridTemplateColumns: `repeat(${cellLessons(day, row).groupsOdd.length}, minmax(0, 1fr))`,
                        }"
                    >
                        <div
                            v-for="group in cellLessons(day, row).groupsOdd"
                            :key="group.subgroup"
                            class="subgroup-column"
                        >
                            <div class="parity-label">Нечётная</div>
                            <div
                                v-for="lesson in group.lessons"
                                :key="lesson.id_lessons"
                                class="lesson-block"
                            >
                                <div class="lesson-title">
                                    {{ lesson.subject.name }}
                                </div>
                                <div class="lesson-meta">
                                    {{ lesson.room.name }} ·
                                    {{ lesson.teacher.name }}
                                </div>
                                <div class="lesson-subgroup">
                                    {{ lesson.subgroup?.name ?? 'Общая' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface LessonRow {
    id_lessons: number;
    week_day: number;
    order_number: number;
    week_parity: 'odd' | 'even' | 'both';
    subject: { name: string };
    room: { name: string };
    teacher: { name: string };
    subgroup: { name: string } | null;
}

const props = defineProps<{
    lessons: LessonRow[];
}>();

const days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
const rows = [1, 2, 3, 4, 5, 6, 7, 8];

const groupedLessons = computed(() => {
    const cells = new Map<
        string,
        {
            groupsEven: { subgroup: string; lessons: LessonRow[] }[];
            groupsOdd: { subgroup: string; lessons: LessonRow[] }[];
        }
    >();

    const groupBySubgroup = (lessons: LessonRow[]) => {
        const map = new Map<string, LessonRow[]>();

        lessons.forEach((lesson) => {
            const subgroup = lesson.subgroup?.name || 'Общая';
            if (!map.has(subgroup)) {
                map.set(subgroup, []);
            }
            map.get(subgroup)!.push(lesson);
        });

        return Array.from(map, ([subgroup, lessons]) => ({
            subgroup,
            lessons,
        }));
    };

    props.lessons.forEach((lesson) => {
        const key = `${lesson.week_day}-${lesson.order_number}`;
        if (!cells.has(key)) {
            cells.set(key, {
                groupsEven: [],
                groupsOdd: [],
            });
        }

        const cell = cells.get(key)!;

        const evenLessons: LessonRow[] = [];
        const oddLessons: LessonRow[] = [];

        if (lesson.week_parity === 'even' || lesson.week_parity === 'both') {
            evenLessons.push(lesson);
        }
        if (lesson.week_parity === 'odd' || lesson.week_parity === 'both') {
            oddLessons.push(lesson);
        }

        if (evenLessons.length) {
            cell.groupsEven = groupBySubgroup([
                ...cell.groupsEven.flatMap((g) => g.lessons),
                ...evenLessons,
            ]);
        }
        if (oddLessons.length) {
            cell.groupsOdd = groupBySubgroup([
                ...cell.groupsOdd.flatMap((g) => g.lessons),
                ...oddLessons,
            ]);
        }
    });

    return cells;
});

const cellLessons = (day: string | number, order: number) => {
    const dayIndex = days.indexOf(day as string) + 1;
    const key = `${dayIndex}-${order}`;
    return groupedLessons.value.get(key) || { groupsEven: [], groupsOdd: [] };
};
</script>

<style scoped>
.schedule-table {
    display: grid;
    gap: 0;
    border: 1px solid #d1d5db;
    border-radius: 20px;
    overflow: hidden;
}

.schedule-header,
.schedule-row {
    display: grid;
    grid-template-columns: 80px repeat(7, minmax(0, 1fr));
}

.schedule-cell {
    border-bottom: 1px solid #e5e7eb;
    border-right: 1px solid #e5e7eb;
    padding: 12px;
    min-height: 120px;
    background: #ffffff;
}

.schedule-cell:last-child {
    border-right: none;
}

.schedule-cell--header {
    background: #f8fafc;
    font-weight: 700;
    text-align: center;
}

.schedule-cell--row-label {
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.schedule-cell--lesson {
    display: grid;
    grid-template-rows: 1fr 1fr;
    padding: 0;
    min-height: 160px;
}

.schedule-half {
    border-top: 1px solid #e5e7eb;
    display: flex;
    align-items: stretch;
    justify-content: stretch;
    position: relative;
    overflow: hidden;
}

.schedule-half:first-child {
    border-top: none;
}

.schedule-half--even {
    border-bottom: 1px solid #e5e7eb;
}

.schedule-half--odd {
    border-bottom: none;
}

.half-content {
    display: grid;
    grid-auto-rows: minmax(0, auto);
    gap: 8px;
    padding: 8px;
    width: 100%;
}

.lesson-block {
    background: #eef2ff;
    border-radius: 12px;
    padding: 10px;
    display: grid;
    gap: 4px;
    min-height: 40px;
}

.lesson-title {
    font-weight: 700;
    font-size: 14px;
}

.lesson-meta,
.lesson-subgroup {
    font-size: 12px;
    color: #4b5563;
}
</style>
