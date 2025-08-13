<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import RecentsLayout from '@/layouts/recents/recents.vue';
import Recents from '@/pages/recents/recents.vue';
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Recently Added',
    href: '/recents',
  },
];

const props = defineProps({
    
 data: Array
});

</script>

<template>

    <Head title="Recents" />

    <AppLayout :breadcrumbs="breadcrumbs">
            <RecentsLayout>
                <Recents :data="data"></Recents>
            </RecentsLayout>
        <div class="px-4 py-6">
           
            

        </div>
    </AppLayout>
</template>
