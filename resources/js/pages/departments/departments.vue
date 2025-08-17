<script setup lang>
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';

import { Head, router } from '@inertiajs/vue3';

import { Captions, CircleUserRound, Code } from 'lucide-vue-next';

const props = defineProps({

    data: Array,
});
// console.log('props.color:', props.color)

</script>
<template>

    <Head title="Documents" />
<Button class="space-y-2 mb-4">
        Create New
    </Button>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
    
        <div v-for="i in data" :key="i.id"
            class="max-w-sm  border border-gray-200 rounded-lg shadow-sm dark:border-gray-700">
            <a href="#">
                <img class="rounded-t-lg" src="/docx-placeholder.png" alt="" />
            </a>
            <div class="p-5">

             
                <h5 class="mb-2 font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                    <Captions />
                    {{ i.unit }}
                </h5>

                <p class="flex items-center gap-2 text-gray-400 dark:text-gray-500">
                    <Code />
                    {{ i.code }}
                </p>

                <p class="flex items-center gap-2 text-gray-400 dark:text-gray-500 text-sm">
                    <CircleUserRound />
                    Created By: {{ i.username }}
                </p>

                
            </div>
        </div>
    </div>

    
</template>
