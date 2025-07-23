<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';



interface Masterlist{
      id: number,
      title: string,
        doc_code: string,
        unit: string,
        type: string,
        seq: string,
}


const props = defineProps<{masterlist : Masterlist}>();
const form = useForm({
    title: props.masterlist.title,
    doc_code: props.masterlist.doc_code,
    unit: props.masterlist.unit,
    type: props.masterlist.type,
    seq: props.masterlist.seq,
});

const handleSubmit = () => {
    form.put(route('masterlist.update', {masterlist: props.masterlist}))
}
</script>

<template>
    <Head title="Edit a Document" />

    <AppLayout :breadcrumbs="[{title: 'Edit a Document', href: `/masterlist/${props.masterlist.id}/edit`}]">


         <div class="w-full max-w-3xl mx-auto p-6 space-y-6">
      <Card class="w-full shadow-lg  dark:border-gray-700">
        <CardHeader>
          <CardTitle class="text-2xl font-semibold">Update Document</CardTitle>
          <br>
          <CardDescription class="mt-2  mb-6 text-gray-600 dark:text-gray-400">
            Fill out the form below to add a new document to the master list.
          </CardDescription>
        </CardHeader>

        <CardContent >
    <form @submit.prevent="handleSubmit" class="w-8/12 space-y-4">
            <div class="space-y-2">
                <Label for="title"> Title</Label>
                <Input v-model="form.title" type="text" placeholder="title"></Input>
                <div class="text-sm text-red-600" v-if="form.errors.title">{{ form.errors.title }}</div>
            </div>
             <div class="space-y-2">
                <Label for="doc_code"> Document Code</Label>
                <Input v-model="form.doc_code" type="text" placeholder="doc_code"></Input>
                 <div class="text-sm text-red-600" v-if="form.errors.doc_code">{{ form.errors.title }}</div>
            </div>
             <div class="space-y-2">
                <Label for="unit"> Unit</Label>
                <Input v-model="form.unit" type="text" placeholder="unit"></Input>
                 <div class="text-sm text-red-600" v-if="form.errors.unit">{{ form.errors.title }}</div>
            </div>
             <div class="space-y-2">
                <Label for="type"> Type</Label>
                <Input v-model="form.type" type="text" placeholder="type"></Input>
                 <div class="text-sm text-red-600" v-if="form.errors.type">{{ form.errors.title }}</div>
            </div>
            <div class="space-y-2">
                <Label for="seq"> Sequence</Label>
                <Input v-model="form.seq" type="text" placeholder="seq"></Input>
                 <div class="text-sm text-red-600" v-if="form.errors.seq">{{ form.errors.title }}</div>
            </div>
            
             <div class="pt-4">
              <Button type="submit" :disabled="form.processing" class="w-full">
                {{ form.processing ? 'Updating...' : 'Update Document' }}
              </Button>
            </div>
         </form>
        </CardContent>
      </Card>
    </div>
       
    </AppLayout>
</template>
