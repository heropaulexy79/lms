<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Course } from "@/types";
import PublicCourseCard from "@/Pages/Course/Partials/PublicCourseCard.vue";
import { Button } from "@/Components/ui/button";

defineProps<{
    templates: Course[];
}>();
</script>

<template>
    <Head title="Create Course from Template" />

    <!-- 
        FIX 1: Added the required :is-fullscreen="false" prop to the layout.
        This will resolve the "Missing required prop" warning.
    -->
    <AuthenticatedLayout :is-fullscreen="false">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Course
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h3 class="text-2xl font-bold">Create from a Template</h3>
                    <p class="text-gray-600">
                        Select a template to get started. A new, editable course
                        will be copied to your account.
                    </p>
                </div>

                <div
                    v-if="templates.length > 0"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="template in templates"
                        :key="template.id"
                        class="flex flex-col"
                    >
                        <!-- Reuse your existing card component -->
                        <PublicCourseCard
                            :course="template"
                            class="flex-grow"
                        />
                        <!-- Add the "Use Template" button -->
                        <Link
                            :href="
                                route(
                                    'organisation.course.template.store',
                                    template.id
                                )
                            "
                            method="post"
                            as="button"
                            class="mt-4 w-full"
                        >
                            <Button class="w-full">Use this template</Button>
                        </Link>
                    </div>
                </div>
                <div v-else>
                    <p class="text-gray-500 text-center">
                        No course templates are available at this time.
                    </p>
                </div>

                <div class="mt-12 text-center">
                    <!-- 
                        FIX 3: Changed route from 'organisation.course.index' 
                        to 'course.index' to match your routes/web.php file.
                        This will resolve the Ziggy route error.
                    -->
                    <Link :href="route('course.index')">
                        <Button variant="outline"
                            >Or go back to course list</Button
                        >
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

