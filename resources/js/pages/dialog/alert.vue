<!-- AlertToast.vue -->
<template>
  <Transition name="fade">
    <div v-if="visible">
      <Alert
        class="fixed bottom-4 right-4 bg-green-600 text-white shadow-lg p-4 rounded-lg flex items-start gap-3 w-[300px] z-50"
      >
        <Rocket class="h-5 w-5 mt-1" />
        <div>
          <AlertTitle class="font-semibold">Success</AlertTitle>
          <AlertDescription class="text-sm">
            {{ message }}
          </AlertDescription>
        </div>
      </Alert>
    </div>
  </Transition>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Rocket } from 'lucide-vue-next'
import AlertTitle from '@/components/ui/alert/AlertTitle.vue'

const visible = ref(false)
const page = usePage()

const message = page.props.flash?.message || ''

watch(
  () => page.props.flash?.message,
  (newMessage) => {
    if (newMessage) {
      visible.value = true
      setTimeout(() => (visible.value = false), 4000)
    }
  },
  { immediate: true }
)
</script>
