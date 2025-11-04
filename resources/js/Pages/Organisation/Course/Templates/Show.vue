<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Course, Lesson, QuizQuestion, QuizOption } from "@/types";
import { Button } from "@/Components/ui/button";
import {
    BookOpen,
    CheckCircle,
    Circle,
    FileText,
    HelpCircle,
} from "lucide-vue-next";

defineProps<{
    template: Course & {
        lessons: (Lesson & {
            questions: (QuizQuestion & {
                options: QuizOption[];
            })[];
        })[];
    };
}>();

const getLessonIcon = (type: string) => {
    if (type === "QUIZ") {
        return HelpCircle;
    }
    return FileText;
};
</script>

<template>
    <Head :title="`Preview: ${template.title}`" />

    <AuthenticatedLayout :is-fullscreen="false">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <span class="font-semibold text-sm text-gray-500"
                        >Template Preview</span
                    >
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ template.title }}
                    </h2>
                </div>
                <!-- ADDED BUTTONS HERE -->
                <div class="flex items-center space-x-3">
                    <Link :href="route('organisation.course.template.index')">
                        <Button variant="outline">Go Back</Button>
                    </Link>
                    <Link
                        :href="
                            route(
                                'organisation.course.template.store',
                                template.id
                            )
                        "
                        method="post"
                        as="button"
                    >
                        <Button>Use this template</Button>
                    </Link>
                </div>
                <!-- END ADDED BUTTONS -->
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Left Column: Course Details & Lessons -->
                    <div class="md:col-span-2">
                        <!-- Course Description -->
                        <div class="bg-white shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-xl font-semibold mb-4">
                                About this template
                            </h3>
                            <div
                                v-if="template.description"
                                class="prose max-w-none text-gray-700"
                                v-html="template.description"
                            ></div>
                            <p v-else class="text-gray-500">
                                No description provided for this template.
                            </p>
                        </div>

                        <!-- Lessons List -->
                        <div class="bg-white shadow-sm sm:rounded-lg p-6 mt-6">
                            <h3 class="text-xl font-semibold mb-4">
                                Template Content
                            </h3>
                            <ul class="space-y-4">
                                <li
                                    v-for="lesson in template.lessons"
                                    :key="lesson.id"
                                >
                                    <div
                                        class="p-4 border rounded-lg flex items-start space-x-4"
                                    >
                                        <component
                                            :is="getLessonIcon(lesson.type)"
                                            class="h-6 w-6 text-gray-600 mt-1"
                                        />
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium">
                                                {{ lesson.title }}
                                            </h4>
                                            <!-- Render Lesson Content -->
                                            <div
                                                v-if="
                                                    lesson.type !== 'QUIZ' &&
                                                    lesson.content
                                                "
                                                class="prose prose-sm max-w-none text-gray-600 mt-2"
                                                v-html="lesson.content"
                                            ></div>

                                            <!-- Render Quiz Questions -->
                                            <div
                                                v-if="
                                                    lesson.type === 'QUIZ' &&
                                                    lesson.questions.length
                                                "
                                                class="mt-4 space-y-4"
                                            >
                                                <div
                                                    v-for="(
                                                        question, qIndex
                                                    ) in lesson.questions"
                                                    :key="question.id"
                                                    class="border-t pt-4"
                                                >
                                                    <p
                                                        class="font-semibold text-gray-800"
                                                    >
                                                        {{ qIndex + 1 }}.
                                                        <!-- *** FIX: Changed .text to .question *** -->
                                                        {{ question.question }}
                                                    </p>
                                                    <ul class="mt-2 space-y-2">
                                                        <li
                                                            v-for="option in question.options"
                                                            :key="option.id"
                                                            class="flex items-center space-x-3"
                                                        >
                                                            <component
                                                                :is="
                                                                    option.is_correct
                                                                        ? CheckCircle
                                                                        : Circle
                                                                "
                                                                class="h-5 w-5"
                                                                :class="{
                                                                    'text-green-600':
                                                                        option.is_correct,
                                                                    'text-gray-400':
                                                                        !option.is_correct,
                                                                }"
                                                            />
                                                            <span
                                                                :class="{
                                                                    'font-semibold text-gray-900':
                                                                        option.is_correct,
                                                                    'text-gray-700':
                                                                        !option.is_correct,
                                                                }"
                                                                >{{
                                                                    option.option_text
                                                                }}</span
                                                            >
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Right Column: Meta Info -->
                    <div class="md:col-span-1">
                        <div
                            class="bg-white shadow-sm sm:rounded-lg p-6 sticky top-6"
                        >
                            <h3 class="text-xl font-semibold mb-4">
                                At a glance
                            </h3>
                            <div
                                class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg"
                            >
                                <BookOpen class="h-8 w-8 text-blue-600" />
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Lessons
                                    </p>
                                    <p class="text-2xl font-bold">
                                        {{ template.lessons.length }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ✅ Bottom Action Buttons -->
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-10">
    <div class="bg-white shadow-sm sm:rounded-lg p-6 flex justify-end space-x-3">
        <Link :href="route('organisation.course.template.index')">
            <Button variant="outline">Go Back</Button>
        </Link>
        <Link
            :href="route('organisation.course.template.store', template.id)"
            method="post"
            as="button"
        >
            <Button>Use this template</Button>
        </Link>
    </div>
</div>

    </AuthenticatedLayout>
</template>

