<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';

import Departments from '@/pages/departments/departments.vue';
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Departments',
    href: '/departments',
  },
];

const props = defineProps({
    
 data: Array
});

</script>

<template>

    <Head title="Recents" />

    <AppLayout :breadcrumbs="breadcrumbs">
            
        <div class="px-4 py-6">
           <Departments :data="data"></Departments>
          

        </div>
    </AppLayout>
</template>
