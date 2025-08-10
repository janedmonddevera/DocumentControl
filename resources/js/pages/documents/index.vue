<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import Documents from '@/pages/documents/Documents.vue';
import DocumentsLayout from '@/layouts/documents/Documents.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Documents',
    href: '/documents',
  },
];

const props = defineProps({
    data: Array,
    filter: Array,
    departments: Array,
 
});

</script>

<template>

    <Head title="Documents" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <DocumentsLayout :department="departments">
            <Documents :data="data" :filter="filter" :departments="departments" />

        </DocumentsLayout>
        <div class="px-4 py-6">
           
            

        </div>
    </AppLayout>
</template>
