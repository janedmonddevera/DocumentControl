<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import RecentsLayout from '@/layouts/recents/recents.vue';
import Recents from '@/pages/recents/recents.vue';
import Heading from '@/components/Heading.vue';
import Button from '@/components/ui/button/Button.vue';
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Recently Added',
    href: '/recents',
  },
];

const props = defineProps({

  data: Array,
  color: Array,

});

</script>

<template>

  <Head title="Recents" />

  <AppLayout :breadcrumbs="breadcrumbs">


    <div class="px-4 py-6 d-flex">

      <div class="flex items-center">
        <Heading title="Masterlist" description="Recently added document codes" />
        <div class="ml-auto">
         
        </div>
      </div>
      <Recents :data="data" :color="color"></Recents>



      <div class="flex items-center">
        <Heading title="Documents" description="Recently added document " class="mt-8" />
        <div class="ml-auto">
          <Button>
            See all
          </Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
