<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import Documents from '@/pages/documents/Documents.vue';
import DocumentsLayout from '@/layouts/documents/Documents.vue';
<<<<<<< HEAD


=======
// interface Documents{
//       id: number,
     
// }

// const props = defineProps<{document : Documents}>();
>>>>>>> c65b8e557c451e0544ad37db626589ffa24bbc65

const props = defineProps({
    data: Array,
    filter: Array,
    departments: Array,
    documents: Array
});

</script>

<template>

<<<<<<< HEAD
    <Head title="Documents" />

    <AppLayout :breadcrumbs="[{title: 'Edit a Document', href: `/documents/departments/${props.departments?.unit}`}]">
=======
    <Head doc_code="Documents" />

    <AppLayout :breadcrumbs="[{title: 'Edit a Document', href: `/masterlist/${props.documents?.id}/edit`}]">
>>>>>>> c65b8e557c451e0544ad37db626589ffa24bbc65
        <DocumentsLayout :department="departments">
            <Documents :data="data" :filter="filter" />

        </DocumentsLayout>
        <div class="px-4 py-6">
           
            

        </div>
    </AppLayout>
</template>
