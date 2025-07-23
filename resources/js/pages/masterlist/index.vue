<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Masterlist',
    href: '/masterlist',
  },
];
import { defineProps, ref, h, watchEffect, computed } from 'vue'
import type {
  ColumnFiltersState,
  ExpandedState,
  SortingState,
  VisibilityState,
} from '@tanstack/vue-table'
import {
  createColumnHelper,
  FlexRender,
  getCoreRowModel,
  getExpandedRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  useVueTable,
} from '@tanstack/vue-table'
import { ChevronDown, Rocket } from 'lucide-vue-next'
import { cn, valueUpdater } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Input } from '@/components/ui/input'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import DropdownAction from './DataTableDemoColumn.vue'
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from '@/components/ui/alert-dialog';

import { watch } from 'vue'
import Alert from '@/components/ui/alert/Alert.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import Dialog from '@/components/ui/dialog/Dialog.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectLabel from '@/components/ui/select/SelectLabel.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';

const viewDocument = (id: number) => {
  router.get(route('masterlist.edit', id))
}

const selectedIdToDelete = ref<number | null>(null)
const tableKey = ref(0)

const deleteDocument = async (id: number) => {
  router.delete(route('masterlist.destroy', id), {
    onSuccess: () => {
      data.value = data.value.filter(item => item.id !== id)
      tableKey.value++ // 👈 force full rerender
    },
  })
}

const confirmDelete = () => {
  if (selectedIdToDelete.value !== null) {
    deleteDocument(selectedIdToDelete.value)
    selectedIdToDelete.value = null
  }
}
// Define the shape of your Masterlist items
export interface Masterlist {
  id: number
  title: string
  doc_code: string
  unit: string
  type: string
  seq: number
  created_at: Date
  updated_at: Date
  username: string
}

const form = useForm({
  title: '',
  unit: '',
  seq: '',
  org_name: '',
});


type Unit = {
  unit: string;
  code: string;
}

const props = defineProps<{
  data: Masterlist[],
  units: Unit[],
  userLevel: Record<string, boolean> | string
}>()

// Make data reactive
const data = ref<Masterlist[]>(props.data)

watch(data, (newData) => {
  table.setOptions(prev => ({
    ...prev,
    data: newData,
  }))
})
// Create column helper for Masterlist
const columnHelper = createColumnHelper<Masterlist>()

// Define your columns based on model fields
const columns = [

  columnHelper.accessor('title', {
    header: 'Title',
    cell: info => h('div', info.getValue()),
  }),
  columnHelper.accessor('doc_code', {
    header: 'Document Code',
    cell: info => h('div', info.getValue()),
  }),
  columnHelper.accessor('unit', {
    header: 'Unit',
    cell: info => h('div', info.getValue()),
  }),

  columnHelper.accessor('type', {
    header: 'Type',
    cell: info => h('div', info.getValue()),
  }),
  columnHelper.accessor('seq', {
    header: 'Sequence',
    cell: info => h('div', info.getValue()),
  }),
  columnHelper.display({
    id: 'username',
    header: 'Created By',
    cell: ({ row }) =>
      h('div', { class: 'flex flex-col m-0 gap-2 items-start' }, [
        // Buttons row

        h(
          'div',
          { class: 'text-xs text-gray-500 mt-1 space-y-1' },
          [

            h('div', `Created By: ${row.original.username}`),
          ]
        ),

      ]),
  }),
  columnHelper.display({
    id: 'timstamps',
    header: 'Timestamps',
    cell: ({ row }) =>
      h('div', { class: 'flex flex-col m-0 gap-2 items-start' }, [
        // Buttons row

        h(
          'div',
          { class: 'text-xs text-gray-500 mt-1 space-y-1' },
          [
            h('div', `Created: ${row.original.created_at}`),
            h('div', `Updated: ${row.original.updated_at}`),
          ]
        ),

      ]),
  }),
  //   columnHelper.display({
  //   id: 'actions',
  //   header: 'Actions',
  //   cell: ({ row }) =>
  //     h('div', { class: 'flex flex-col m-0 gap-2 items-start' }, [
  //       // Buttons row
  //       h('div', { class: 'flex gap-2' }, [
  //         h(
  //           'button',
  //           {
  //             class: `
  //               bg-yellow-400 hover:bg-blue-300
  //               text-black px-3 py-1 rounded
  //               transition-colors duration-200
  //             `,
  //             onClick: () => viewDocument(row.original.id),
  //           },
  //           'View'
  //         ),

  //         // AlertDialog for Delete
  //         h(
  //           AlertDialog,
  //           null,
  //           {
  //             default: () => [
  //               h(
  //                 AlertDialogTrigger,
  //                 { asChild: true },
  //                 {
  //                   default: () =>
  //                     h(
  //                       'button',
  //                       {
  //                         class: `
  //                           bg-red-600 hover:bg-orange-500
  //                           text-white px-3 py-1 rounded
  //                           transition-colors duration-200
  //                         `,
  //                         onClick: () => (selectedIdToDelete.value = row.original.id),
  //                       },
  //                       'Delete'
  //                     ),
  //                 }
  //               ),
  //               h(AlertDialogContent, null, {
  //                 default: () => [
  //                   h(AlertDialogHeader, null, {
  //                     default: () => [
  //                       h(AlertDialogTitle, null, 'Are you absolutely sure?'),
  //                       h(
  //                         AlertDialogDescription,
  //                         null,
  //                         'This action cannot be undone. This will permanently delete the document.'
  //                       ),
  //                     ],
  //                   }),
  //                   h(AlertDialogFooter, null, {
  //                     default: () => [
  //                       h(AlertDialogCancel, null, 'Cancel'),
  //                       h(AlertDialogAction, { onClick: confirmDelete }, 'Continue'),
  //                     ],
  //                   }),
  //                 ],
  //               }),
  //             ],
  //           }
  //         ),

  //         // Dropdown actions
  //         h(DropdownAction, {
  //           payment: { doc_code: row.original.doc_code.toString() },
  //         }),

  //               h(
  //         'div',
  //         { class: 'text-xs text-gray-500 mt-1 space-y-1' },
  //         [
  //           h('div', `Created: ${row.original.created_at}`),
  //           h('div', `Updated: ${row.original.updated_at}`),
  //         ]
  //       ),
  //       ]),



  //       // Timestamps row

  //     ]),
  // }),


]

// Table state refs
const sorting = ref<SortingState>([])
const columnFilters = ref<ColumnFiltersState>([])
const columnVisibility = ref<VisibilityState>({

  unit: false,
  type: false,
  seq: false,
})
const rowSelection = ref({})
const expanded = ref<ExpandedState>({})

// Initialize table
const table = useVueTable({
  data: data.value,
  columns,
  getCoreRowModel: getCoreRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
  getSortedRowModel: getSortedRowModel(),
  getFilteredRowModel: getFilteredRowModel(),
  getExpandedRowModel: getExpandedRowModel(),
  onSortingChange: updaterOrValue => valueUpdater(updaterOrValue, sorting),
  onColumnFiltersChange: updaterOrValue => valueUpdater(updaterOrValue, columnFilters),
  onColumnVisibilityChange: updaterOrValue => valueUpdater(updaterOrValue, columnVisibility),
  onRowSelectionChange: updaterOrValue => valueUpdater(updaterOrValue, rowSelection),
  onExpandedChange: updaterOrValue => valueUpdater(updaterOrValue, expanded),
  state: {
    get sorting() { return sorting.value },
    get columnFilters() { return columnFilters.value },
    get columnVisibility() { return columnVisibility.value },
    get rowSelection() { return rowSelection.value },
    get expanded() { return expanded.value },
    columnPinning: { left: ['seq'] },
  },
})

const page = usePage()

// Reactive flag for visibility
const visible = ref(false)

// Watch flash message and auto-hide after 3s
watchEffect(() => {
  if (page.props.flash?.message) {
    visible.value = true
    setTimeout(() => {
      visible.value = false
    }, 3000)
  }
})



const modalOpen = ref(false)
const selectedRow = ref(null)

function openModal() {
  modalOpen.value = true;


}


const type_name = ref('')
const type = ref('')
const autoGenerate = ref(true)

// Watch title and auto-generate acronym
watch([type_name, autoGenerate], ([newTypeName, isAuto]) => {
  if (isAuto && newTypeName) {
    type.value = generateAcronym(newTypeName);
  }
});
function generateAcronym(text) {
  const ignoreWords = ['of', 'and', 'the', 'in', 'on', 'at', 'for', 'to', 'with'];

  return text
    .split(' ')
    .filter(word => word.trim() !== '' && !ignoreWords.includes(word.toLowerCase()))
    .map(word => word[0].toUpperCase())
    .join('')
    .slice(0, 3); // Limit to 3 characters
}
const onSuccess = () => {
  modalOpen.value = false;
};

const handleSubmit = () => {
  form.post(route('masterlist.store', {
    type: type.value,
    type_name: type_name.value
  },
    {
      onSuccess
    }

  ))
}
const userLevel = page.props.user_level;
const units = page.props.units;


const allowedUnits = computed(() => {
  const parsed =
    typeof userLevel === 'string' ? JSON.parse(userLevel) : userLevel;

  return props.units.filter(u => parsed[u.unit]); // match by `unit` name
});

</script>


<template>

  <Head title="Masterlist" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 py-6">
      <HeadingSmall title="Masterlist" description="List of document codes" />
      <div class="flex h-full w-5/6 m-auto items-center flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
        <div class="w-full">

          <Transition name="fade">
            <div v-if="visible">
              <Alert
                class="fixed bottom-4 right-4 bg-green-600 text-white shadow-lg p-4 rounded-lg flex items-start gap-3 w-[300px] z-50">
                <Rocket class="h-5 w-5 mt-1" />
                <div>
                  <AlertTitle class="font-semibold">Heads up!</AlertTitle>
                  <AlertDescription class="text-black">
                    {{ $page.props.flash?.message }}
                  </AlertDescription>
                </div>
              </Alert>
            </div>
          </Transition>

          <div class="flex gap-2 items-center py-4">
            <Button @click="() => openModal()">
              Create New
            </Button>
            <Input class="max-w-sm" placeholder="Filter titles..."
              :model-value="table.getColumn('title')?.getFilterValue() as string"
              @update:model-value="val => table.getColumn('title')?.setFilterValue(val)" />
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="outline" class="ml-auto">
                  Columns
                  <ChevronDown class="ml-2 h-4 w-4" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuCheckboxItem v-for="column in table.getAllColumns().filter(c => c.getCanHide())"
                  :key="column.id" class="capitalize" :model-value="column.getIsVisible()"
                  @update:model-value="value => column.toggleVisibility(!!value)">
                  {{ column.id }}
                </DropdownMenuCheckboxItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>

          <div class="rounded-md border">

            <Table :key="tableKey">
              <TableHeader>
                <TableRow v-for="hg in table.getHeaderGroups()" :key="hg.id">
                  <TableHead v-for="header in hg.headers" :key="header.id" :data-pinned="header.column.getIsPinned()"
                    :class="cn(
                      { 'sticky bg-background/95': header.column.getIsPinned() },
                      header.column.getIsPinned() === 'left' ? 'left-0' : 'right-0',
                    )">
                    <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header"
                      :props="header.getContext()" />
                  </TableHead>
                </TableRow>
              </TableHeader>

              <TableBody>

                <template v-for="row in table.getRowModel().rows" :key="row.id">
                  <TableRow @click="() => openModal()" class="cursor-pointer hover:bg-muted">
                    <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id"
                      :data-pinned="cell.column.getIsPinned()" :class="cn(
                        { 'sticky bg-background/95': cell.column.getIsPinned() },
                        cell.column.getIsPinned() === 'left' ? 'left-0' : 'right-0',
                      )">
                      <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                    </TableCell>
                  </TableRow>

                </template>
              </TableBody>
            </Table>
          </div>

          <div class="flex items-center justify-end py-4 space-x-2">
            <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()" @click="table.previousPage()">
              Previous
            </Button>
            <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()" @click="table.nextPage()">
              Next
            </Button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>


  <Dialog v-model:open="modalOpen">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Create new code</DialogTitle>
      </DialogHeader>


      <form @submit.prevent="handleSubmit" class="flex flex-col gap-6">



        <div class="mt-4">
          <Label for="title">Title</Label>
          <Input v-model="form.title" type="text" id="title" placeholder="Enter document title" />
          <p v-if="form.errors.title" class="text-sm text-red-500 mt-1">
            {{ form.errors.title }}
          </p>
        </div>

        <Select v-model="form.unit">
          <SelectTrigger>
            <SelectValue placeholder="Select unit" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectLabel>Unit</SelectLabel>
              <hr />
              <SelectItem v-for="unit in props.units" :key="unit.unit" :value="unit.code">
                {{ unit.unit }}
              </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>

        <!-- Type -->
        <div>
          <div class="flex items-center">
            <Label for="type">Type</Label>
            <div class="items-end justify-end flex align-end m-auto mr-0">
              <Checkbox id="terms" v-model="autoGenerate" />
              <label for="terms"
                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                Auto Generate Acronym
              </label>
            </div>

          </div>
          <div class="flex items-center space-x-2">
            <Input class="w-4/6" v-model="type_name" type="text" id="type" placeholder="e.g., Policy, Memo" />
            <Input class="w-3/6" v-model="type" type="text" id="type" placeholder="e.g., Policy, Memo" />
          </div>


        </div>

        <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
          <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
          Update account
        </Button>

      </form>

    </DialogContent>
  </dialog>

</template>
