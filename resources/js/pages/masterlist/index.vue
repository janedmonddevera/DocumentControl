<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';

import masterlist from '@/pages/masterlist/masterlist.vue';
import { filterFns } from '@tanstack/vue-table';
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Documents',
    href: '/documents',
  },
];

const props = defineProps({
    data: Array,
    units: Array,
    user_level: String,
    filter: Array,
    
});

</script>

<template>

    <Head title="Documents" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="px-4 py-6">
           <masterlist :data="data" :departments="units" :user_level="user_level" :filter="filter">
            
           </masterlist>
            

        </div>
    </AppLayout>
</template>
