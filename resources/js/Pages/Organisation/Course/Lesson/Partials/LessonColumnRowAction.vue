<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import type { Lesson } from '@/types'; // Assuming you have a Lesson type

import { MoreHorizontal, Pencil, Trash2 } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/Components/ui/alert-dialog';

const props = defineProps<{
    lesson: Lesson;
}>();

const showDeleteAlert = ref(false);
const isDeleting = ref(false);

// Function to navigate to the edit page
const editLesson = () => {
    router.get(route('lesson.edit', { course: props.lesson.course_id, lesson: props.lesson.id }));
};

// Function to call the delete route
const deleteLesson = () => {
    isDeleting.value = true;
    router.delete(route('lesson.destroy', { course: props.lesson.course_id, lesson: props.lesson.id }), {
        // We removed `preserveScroll: true` to force a reload + scroll to top
        onSuccess: () => {
            showDeleteAlert.value = false;
            window.location.reload();
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
};
</script>

<template>
    <!-- Delete Confirmation Dialog -->
    <AlertDialog :open="showDeleteAlert" @update:open="showDeleteAlert = $event">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
                <AlertDialogDescription>
                    This action cannot be undone. This will permanently delete the lesson
                    "{{ lesson.title }}" and all associated student progress and quiz data.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel :disabled="isDeleting">Cancel</AlertDialogCancel>
                <AlertDialogAction
                    @click="deleteLesson"
                    :disabled="isDeleting"
                    class="bg-red-600 hover:bg-red-700"
                >
                    {{ isDeleting ? 'Deleting...' : 'Delete' }}
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>

    <!-- Row Action Dropdown Menu -->
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="w-8 h-8 p-0">
                <span class="sr-only">Open menu</span>
                <MoreHorizontal class="w-4 h-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuLabel>Actions</DropdownMenuLabel>
            <DropdownMenuItem @click="editLesson" class="cursor-pointer">
                <Pencil class="w-4 h-4 mr-2" />
                Edit
            </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem
                @click="showDeleteAlert = true"
                class="text-red-600 cursor-pointer focus:text-red-600 focus:bg-red-50"
            >
                <Trash2 class="w-4 h-4 mr-2" />
                Delete
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>

