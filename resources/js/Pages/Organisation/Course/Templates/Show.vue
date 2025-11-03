<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Course, Lesson } from "@/types";
import { Button } from "@/Components/ui/button";
import { Badge } from "@/Components/ui/badge";
import { CheckCircle, Circle } from "lucide-vue-next"; // Changed RadioButton to Circle

defineProps<{
    template: Course;
}>();

const LESSON_TYPE_QUIZ = "QUIZ";
</script>

<template>
    <Head :title="`Preview: ${template.title}`" />

    <AuthenticatedLayout :is-fullscreen="false">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Preview: {{ template.title }}
                </h2>
                <div class="flex gap-2">
                    <Link :href="route('organisation.course.template.index')">
                        <Button variant="outline">Back to Gallery</Button>
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
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Course Info -->
                <div class="bg-white shadow-sm rounded-lg p-6 mb-8">
                    <h3 class="text-2xl font-bold mb-2">
                        {{ template.title }}
                    </h3>
                    <p class="text-gray-600">
                        {{ template.description }}
                    </p>
                </div>

                <!-- Lessons List -->
                <div class="space-y-6">
                    <h4 class="text-xl font-semibold">Course Content</h4>
                    <div
                        v-for="(lesson, index) in template.lessons"
                        :key="lesson.id"
                        class="bg-white shadow-sm rounded-lg overflow-hidden"
                    >
                        <div class="p-6">
                            <div
                                class="flex justify-between items-center mb-4"
                            >
                                <h5 class="text-lg font-semibold">
                                    {{ index + 1 }}. {{ lesson.title }}
                                </h5>
                                <Badge
                                    :variant="
                                        lesson.type === LESSON_TYPE_QUIZ
                                            ? 'default'
                                            : 'secondary'
                                    "
                                    >{{ lesson.type }}</Badge
                                >
                            </div>

                            <!-- Text Lesson Content -->
                            <div
                                v-if="lesson.type !== LESSON_TYPE_QUIZ"
                                class="prose max-w-none"
                                v-html="lesson.content"
                            ></div>

                            <!-- Quiz Lesson Content -->
                            <div
                                v-if="
                                    lesson.type === LESSON_TYPE_QUIZ &&
                                    lesson.questions
                                "
                                class="space-y-6"
                            >
                                <div
                                    v-for="(
                                        question, qIndex
                                    ) in lesson.questions"
                                    :key="question.id"
                                    class="border-t pt-4 first:border-t-0 first:pt-0"
                                >
                                    <h6 class="font-semibold mb-2">
                                        Question {{ qIndex + 1 }}:
                                        {{ question.question }}
                                    </h6>
                                    <ul class="space-y-2 pl-4">
                                        <li
                                            v-for="option in question.options"
                                            :key="option.id"
                                            class="flex items-center gap-2"
                                            :class="{
                                                'text-green-600 font-bold':
                                                    option.is_correct,
                                                'text-gray-700':
                                                    !option.is_correct,
                                            }"
                                        >
                                            <CheckCircle
                                                v-if="option.is_correct"
                                                class="size-4"
                                            />
                                            <Circle
                                                v-else
                                                class="size-4 text-gray-400"
                                            />
                                            <span>{{
                                                option.option_text
                                            }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>