<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create a Document',
        href: '/masterlist/create',
    },
];

const form = useForm({
    title: '',
    doc_code: '',
    unit: '',
    type: '',
    seq: '',
});

const handleSubmit = () => {
    form.post(route('masterlist.store'))
}
</script>
<template>
  <Head title="Create a Document" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="w-full max-w-3xl mx-auto p-6 space-y-6">
      <Card class="w-full shadow-lg  dark:border-gray-700">
        <CardHeader>
          <CardTitle class="text-2xl font-semibold">Create New Document</CardTitle>
          <br>
          <CardDescription class="mt-2  mb-6 text-gray-600 dark:text-gray-400">
            Fill out the form below to add a new document to the master list.
          </CardDescription>
        </CardHeader>

        <CardContent >
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <!-- Title -->
            <div class="mt-4">
              <Label for="title">Title</Label>
              <Input
                v-model="form.title"
                type="text"
                id="title"
                placeholder="Enter document title"
              />
              <p v-if="form.errors.title" class="text-sm text-red-500 mt-1">
                {{ form.errors.title }}
              </p>
            </div>

            <!-- Document Code -->
            <div>
              <Label for="doc_code">Document Code</Label>
              <Input
                v-model="form.doc_code"
                type="text"
                id="doc_code"
                placeholder="e.g., DOC-001"
              />
              <p v-if="form.errors.doc_code" class="text-sm text-red-500 mt-1">
                {{ form.errors.doc_code }}
              </p>
            </div>

            <!-- Unit -->
            <div>
              <Label for="unit">Unit</Label>
              <Input
                v-model="form.unit"
                type="text"
                id="unit"
                placeholder="e.g., HR, Finance"
              />
              <p v-if="form.errors.unit" class="text-sm text-red-500 mt-1">
                {{ form.errors.unit }}
              </p>
            </div>

            <!-- Type -->
            <div>
              <Label for="type">Type</Label>
              <Input
                v-model="form.type"
                type="text"
                id="type"
                placeholder="e.g., Policy, Memo"
              />
              <p v-if="form.errors.type" class="text-sm text-red-500 mt-1">
                {{ form.errors.type }}
              </p>
            </div>

            <!-- Sequence -->
            <div>
              <Label for="seq">Sequence</Label>
              <Input
                v-model="form.seq"
                type="number"
                id="seq"
                placeholder="Enter display order"
              />
              <p v-if="form.errors.seq" class="text-sm text-red-500 mt-1">
                {{ form.errors.seq }}
              </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
              <Button type="submit" :disabled="form.processing" class="w-full">
                {{ form.processing ? 'Saving...' : 'Add Document' }}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
