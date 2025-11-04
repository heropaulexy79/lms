<script lang="ts" setup>
import GlobalLayout from "./GlobalLayout.vue";
import OrganisationLayout from "./OrganisationLayout.vue";

withDefaults(defineProps<{ isFullscreen: boolean }>(), {
    isFullscreen: false,
});
</script>

<template>
    <GlobalLayout>
        <OrganisationLayout :is-fullscreen="isFullscreen">
            <!-- ✅ Add header slot support -->
            <template #header>
                <slot name="header" />
            </template>

            <!-- ✅ Default page body -->
            <slot />
        </OrganisationLayout>
    </GlobalLayout>
</template>
