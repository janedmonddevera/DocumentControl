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
import { ChevronDown, ChevronsUpDown, FilePenLine, Pen, Rocket, Search } from 'lucide-vue-next'
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
import DropdownMenuLabel from '@/components/ui/dropdown-menu/DropdownMenuLabel.vue';
import DropdownMenuSeparator from '@/components/ui/dropdown-menu/DropdownMenuSeparator.vue';
import DropdownMenuRadioGroup from '@/components/ui/dropdown-menu/DropdownMenuRadioGroup.vue';
import DropdownMenuRadioItem from '@/components/ui/dropdown-menu/DropdownMenuRadioItem.vue';
import { DropdownMenuCheckboxItemProps } from 'reka-ui';
import Combobox from '@/components/ui/combobox/Combobox.vue';
import ComboboxAnchor from '@/components/ui/combobox/ComboboxAnchor.vue';
import ComboboxInput from '@/components/ui/combobox/ComboboxInput.vue';
import ComboboxTrigger from '@/components/ui/combobox/ComboboxTrigger.vue';
import ComboboxList from '@/components/ui/combobox/ComboboxList.vue';
import ComboboxEmpty from '@/components/ui/combobox/ComboboxEmpty.vue';
import ComboboxGroup from '@/components/ui/combobox/ComboboxGroup.vue';
import ComboboxItem from '@/components/ui/combobox/ComboboxItem.vue';
import ComboboxItemIndicator from '@/components/ui/combobox/ComboboxItemIndicator.vue';

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
  type_name: string
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


// Create column helper for Masterlist
const columnHelper = createColumnHelper<Masterlist>()

// Define your columns based on model fields
const columns = [

  columnHelper.accessor('title', {
    header: 'Title',
    cell: info => h('div', info.getValue()),
    enableColumnFilter: true,
  }),
  columnHelper.accessor('doc_code', {
    header: 'Document Code',
    cell: info => h('div', info.getValue()),
    enableColumnFilter: true,
  }),
  columnHelper.accessor('unit', {
    header: 'Unit',
    cell: info => h('div', info.getValue()),
    enableColumnFilter: true,
  }),

  columnHelper.accessor('type', {
    header: 'Type',
    cell: info => h('div', info.getValue()),
  }),
  columnHelper.accessor('type_name', {
    header: 'Document Type',
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
watch(
  () => page.props.flash?.message,
  (newMsg) => {
    if (newMsg) {
      visible.value = true
      setTimeout(() => {
        visible.value = false
      }, 3000)
    }
  }
)

const data1 = computed(() => props.data);

// 👇 Watch for backend reload
watch(() => data1.value, (newData) => {
  table.setOptions((prev) => ({
    ...prev,
    data: newData,
  }));
});


const modalOpen = ref(false)
const selectedRow = ref(null)

const isCreating = ref(false);
const isUpdating = ref(false);

function Create() {
  isUpdating.value = false;
  isCreating.value = true;
  form.title = '';
  type.value = '';
  type_name.value = '';
  form.unit = '';

  openModal();

}
function Update(row?: any) {
  isUpdating.value = true;
  isCreating.value = false;
  openModal();
  if (row) {
    // For editing an existing user
    selectedRow.value = row;
    console.log(selectedRow.value)

    form.title = row.title;
    type.value = row.type;
    type_name.value = row.type_name;
    form.unit = row.unit;

  }
}
function openModal(row?: any) {
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
const resetTable = () => {
  sorting.value = [];

  rowSelection.value = {};
  expanded.value = {};
  table.setPagination({ pageIndex: 0, pageSize: 10 }); // or your default
};
const onSuccess = () => {
  modalOpen.value = false;
  form.reset();
  router.reload({ only: ['data'] }); // 👈 triggers Laravel controller to return fresh data
  resetTable();

};
const handleSubmit = () => {
  let routeName = '';
  const userId = selectedRow.value?.id;

  const payload = {
    title: form.title,
    type: type.value,
    type_name: type_name.value,
    unit: form.unit,
  };


  if (isUpdating.value && userId) {
    if (isUpdating.value) {
      routeName = `/masterlist/${userId}`;
      router.put(routeName, payload, {
        onSuccess,

      })
    };
  } else if (isCreating.value) {
    if (isCreating.value) {
      routeName = '/masterlist';
      router.post(routeName, payload, {
        onSuccess,
      })
    };
  }

};
const userLevel = page.props.user_level;
// const units = page.props.units;


const allowedUnits = computed(() => {
  const parsed =
    typeof userLevel === 'string' ? JSON.parse(userLevel) : userLevel;

  return props.units.filter(u => parsed[u.unit]); // match by `unit` name
});

const value = ref<String>()

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
            <Button @click="() => Create()" class=" bg-green-400 hover:bg-green-800">
              <ClipboardPlus />Create New
            </Button>
            <Input class="max-w-sm" placeholder="Filter titles..."
              :model-value="table.getColumn('title')?.getFilterValue() as string"
              @update:model-value="val => table.getColumn('unit')?.setFilterValue(val)" />
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
            <!-- <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="outline" class="ml-auto">
                  Filter by Unit
                  <ChevronDown class="ml-2 h-4 w-4" />
                </Button>
              </DropdownMenuTrigger>

              <DropdownMenuContent align="end">
                <DropdownMenuLabel>Select Unit</DropdownMenuLabel>
                <DropdownMenuSeparator />

                <DropdownMenuRadioGroup v-model="selectedUnit"
                  @update:modelValue="val => table.getColumn('unit')?.setFilterValue(val)">
                  <DropdownMenuRadioItem v-for="unit in props.units" :key="unit.code" :value="unit.code">
                    {{ unit.unit }}
                  </DropdownMenuRadioItem>

                  <!-- Optional: Clear filter -->
            <!-- <DropdownMenuRadioItem value="">
              All Units
            </DropdownMenuRadioItem>
            </DropdownMenuRadioGroup>
            </DropdownMenuContent>
            </DropdownMenu> -->

            <Combobox v-model="value" by="label">
              <ComboboxAnchor as-child>
                <ComboboxTrigger as-child>
                  <Button variant="outline" class="justify-between">
                    {{ value?.unit ?? 'Select Department' }}

                    <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                  </Button>
                </ComboboxTrigger>
              </ComboboxAnchor>

              <ComboboxList>
                <div class="relative w-full max-w-sm items-center">
                  <ComboboxInput class="pl-9 focus-visible:ring-0 border-0 border-b rounded-none h-10"
                    placeholder="Select framework..." />
                  <span class="absolute start-0 inset-y-0 flex items-center justify-center px-3">
                    <Search class="size-4 text-muted-foreground" />
                  </span>
                </div>

                <ComboboxEmpty>
                  No framework found.
                </ComboboxEmpty>

                <ComboboxGroup>
                  <ComboboxItem v-for="unit in props.units" :key="unit.unit" :value="unit">
                    {{ unit.unit }}

                    <ComboboxItemIndicator>
                      <Check :class="cn('ml-auto h-4 w-4')" />
                    </ComboboxItemIndicator>
                  </ComboboxItem>
                </ComboboxGroup>
              </ComboboxList>
            </Combobox>
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
                  <TableRow @click="() => Update(row.original)" class="cursor-pointer hover:bg-muted">
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
        <DialogTitle v-if="isCreating">Create new code</DialogTitle>
        <DialogTitle v-if="isUpdating">Update Code</DialogTitle>
        <hr>
      </DialogHeader>

      <div v-if="isUpdating" class="flex items-end justify-end align-end mb-0">
        <Button class="w-3/6 mb-0 bg-blue-500 hover:bg-cyan-500 text-white hover:text-black">
          <Search />View Document
        </Button>
      </div>

      <form @submit.prevent="handleSubmit" class="flex flex-col gap-6">



        <div class="">
          <Label for="title">Title</Label>
          <Input v-model="form.title" type="text" id="title" placeholder="Enter document title" />
          <p v-if="form.errors.title" class="text-sm text-red-500 mt-1">
            {{ form.errors.title }}
          </p>
        </div>
        <div>
          <Label for="unit">Unit</Label>
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
        </div>
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
            <Input class="w-4/6" v-model="type_name" type="text" id="type_name" placeholder="e.g., Policy, Memo" />
            <Input class="w-3/6" v-model="type" type="text" id="type" placeholder="e.g., PCD, POC" />
          </div>


        </div>




        <AlertDialog>
          <AlertDialogTrigger type="button">
            <Button type="button" class="mt-2 w-full bg-green-400">
              <span v-if="isCreating" class="flex items-center justify-center gap-2">
                <Pen class="w-4 h-4" /> Create
              </span>
              <span v-else-if="isUpdating" class="flex items-center justify-center gap-2">
                <FilePenLine class="w-4 h-4" /> Update
              </span>
            </Button type="button">
          </AlertDialogTrigger>


          <AlertDialogContent>
            <AlertDialogHeader>
              <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
              <AlertDialogDescription>
                This action cannot be undone. This will update the document code.
              </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
              <AlertDialogCancel>Cancel</AlertDialogCancel>
              <AlertDialogAction @click="handleSubmit" class=" bg-green-400 hover:bg-green-800" tabindex="5"
                :disabled="form.processing">



                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin " />

                <span v-if="isCreating">
                  Create
                </span>
                <span v-else-if="isUpdating">
                  Update
                </span>

              </AlertDialogAction>
            </AlertDialogFooter>
          </AlertDialogContent>
        </AlertDialog>
      </form>

    </DialogContent>
  </dialog>






</template>
