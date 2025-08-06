<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
// import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';

// const breadcrumbs: BreadcrumbItem[] = [
//   {
//     title: 'Documents',
//     href: '/documents',
//   },


// ];


import {
  FlexRender,
  getCoreRowModel,
  getExpandedRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  useVueTable,
} from '@tanstack/vue-table'
import { ArrowUpDown, ChevronDown, ClipboardPlus, FilePenLine, LoaderCircle, Search } from 'lucide-vue-next'
import { computed, h, ref, watch } from 'vue'
import { valueUpdater } from '@/lib/utils'

import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
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
import DropdownAction from '@/pages/documents/DataTableDemoColumn.vue'


import { ChevronRightIcon, ChevronLeftIcon, DoubleArrowLeftIcon, DoubleArrowRightIcon } from "@radix-icons/vue";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";



const props = defineProps({
  data: Array,
  departments: Array,
  user_level: String,
  filter: Array,
})

const data = props.data.data;
//const data = ref<Document[]>(props.data.data)

const columns = [

  {
    accessorKey: 'title',
    header: 'Title',
    cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('title')),
  },
  {
    accessorKey: 'doc_code',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Document Code', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => h('div', { class: 'lowercase' }, row.getValue('doc_code')),
  },
  {
    accessorKey: 'type_name',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Type ', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => h('div', { class: 'lowercase' }, row.getValue('type_name')),
  },
  {
    accessorKey: 'unit',
    header: 'Unit',
    cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('unit')),
  },
  
  {
    id: 'actions',
    enableHiding: false,
    cell: ({ row }) => {
      const payment = row.original

      return h(DropdownAction, {
        payment,
        onExpand: row.toggleExpanded,
      })
    },
  },
]

const sorting = ref([])
const columnFilters = ref(props.filter ?? [])
const columnVisibility = ref({})
const rowSelection = ref({})
const expanded = ref({})
const pageSizes = [1, 2, 3, 5, 10, 15, 30, 40, 50, 100,];
const pagination = ref({
  pageIndex: props.data.current_page - 1,
  pageSize: props.data.per_page,
})


const table = useVueTable({
  data,
  columns,
  getCoreRowModel: getCoreRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
  getSortedRowModel: getSortedRowModel(),
  getFilteredRowModel: getFilteredRowModel(),
  getExpandedRowModel: getExpandedRowModel(),
  pageCount: props.data.last_page,
  manualPagination: true,
  manualSorting: true,
  manualFiltering: true,
  onPaginationChange: updater => {
    if (typeof updater === 'function') {
      pagination.value = updater(pagination.value);
    } else {
      pagination.value = updater;
    }
    router.get(
      route('masterlist.index'),
      {
        page: pagination.value.pageIndex + 1,
        per_page: pagination.value.pageSize,
        sort_field: sorting.value[0]?.id,
        sort_direction: sorting.value.length == 0 ? undefined : (sorting.value[0]?.desc ? "desc" : "asc"),
      },
      { preserveState: false, preserveScroll: true }
    );
  },
  onSortingChange: updaterOrValue => {
    if (typeof updaterOrValue === 'function') {
      sorting.value = updaterOrValue(sorting.value)
    } else {
      sorting.value = updaterOrValue
    }
    let filters = {};
    if (columnFilters.value) {
      filters = columnFilters.value.reduce((acc, filter) => {
        acc[filter.id] = filter.value
        return acc
      }, {})
    }
    router.get(
      route('masterlist.index'),
      {
        page: pagination.value.pageIndex + 1,
        per_page: pagination.value.pageSize,
        sort_field: sorting.value[0]?.id,
        sort_direction: sorting.value.length == 0 ? undefined : (sorting.value[0]?.desc ? "desc" : "asc"),
        ...filters
      },
      { preserveState: false, preserveScroll: true }
    );
  },
  onColumnFiltersChange: updaterOrValue => {
    if (typeof updaterOrValue === 'function') {
      columnFilters.value = updaterOrValue(columnFilters.value)
    } else {
      columnFilters.value = updaterOrValue
    }
    let filters = {};
    if (columnFilters.value) {
      filters = columnFilters.value.reduce((acc, filter) => {
        acc[filter.id] = filter.value
        return acc
      }, {})
    }
    router.get(
      route('masterlist.index'),
      {
        page: pagination.value.pageIndex + 1,
        per_page: pagination.value.pageSize,
        sort_field: sorting.value[0]?.id,
        sort_direction: sorting.value.length == 0 ? undefined : (sorting.value[0]?.desc ? "desc" : "asc"),
        ...filters

      },
      { preserveState: false, preserveScroll: true }
    );
  },
  onColumnVisibilityChange: updaterOrValue => valueUpdater(updaterOrValue, columnVisibility),
  onRowSelectionChange: updaterOrValue => valueUpdater(updaterOrValue, rowSelection),
  onExpandedChange: updaterOrValue => valueUpdater(updaterOrValue, expanded),
  state: {
    get sorting() { return sorting?.value },
    get columnFilters() { return columnFilters?.value },
    get columnVisibility() { return columnVisibility?.value },
    get rowSelection() { return rowSelection?.value },
    get expanded() { return expanded?.value },
    get pagination() { return pagination?.value },
  },
})


import Filter from '@/pages/documents/Filter.vue'
import Dialog from '@/components/ui/dialog/Dialog.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import AlertDialog from '@/components/ui/alert-dialog/AlertDialog.vue';
import AlertDialogTrigger from '@/components/ui/alert-dialog/AlertDialogTrigger.vue';
import AlertDialogContent from '@/components/ui/alert-dialog/AlertDialogContent.vue';
import AlertDialogHeader from '@/components/ui/alert-dialog/AlertDialogHeader.vue';
import AlertDialogTitle from '@/components/ui/alert-dialog/AlertDialogTitle.vue';
import AlertDialogDescription from '@/components/ui/alert-dialog/AlertDialogDescription.vue';
import AlertDialogFooter from '@/components/ui/alert-dialog/AlertDialogFooter.vue';
import AlertDialogCancel from '@/components/ui/alert-dialog/AlertDialogCancel.vue';
import AlertDialogAction from '@/components/ui/alert-dialog/AlertDialogAction.vue';

const filter_status = {
  title: 'Department',
  column: 'unit',
  data: [

    ...props.departments.map((dept) => ({
      value: dept.code,
      label: dept.unit,
    })),

  ]
}

const filter_toolbar = [
  filter_status,
];


const form = useForm({
  doc_code: '',
  doc_name: '',
  remarks:'',
  unit: '',

})



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
function Update() {
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
const userLevel = props.user_level;
// const units = page.props.units;


const allowedUnits = computed(() => {
  const parsed =
    typeof userLevel === 'string' ? JSON.parse(userLevel) : userLevel;

  return props.units.filter(u => parsed[u.unit]); // match by `unit` name
});


</script>

<template>
  <div class="w-full">
    <div class="flex items-center py-4 space-x-2">
      <Button @click="() => Create()" class=" bg-green-400 hover:bg-green-800">
        <ClipboardPlus />Create New
      </Button>
      <Input class="max-w-sm" placeholder="Filter name..." :model-value="table.getColumn('title')?.getFilterValue()"
        @update:model-value=" table.getColumn('title')?.setFilterValue($event)" />
      <div v-for="filter in filter_toolbar" :key="filter.title">
        <Filter :column="table.getColumn(filter.column)" :title="filter.title" :options="filter.data"></Filter>
      </div>

      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <Button variant="outline" class="ml-auto">
            Columns
            <ChevronDown class="ml-2 h-4 w-4" />
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
          <DropdownMenuCheckboxItem v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
            :key="column.id" class="capitalize" :model-value="column.getIsVisible()" @update:model-value="(value) => {
              column.toggleVisibility(!!value)
            }">
            {{ column.id }}
          </DropdownMenuCheckboxItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
    <div class="rounded-md border">
      <Table>
        <TableHeader>
          <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
            <TableHead v-for="header in headerGroup.headers" :key="header.id">
              <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header"
                :props="header.getContext()" />
            </TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <template v-if="table.getRowModel().rows?.length">
            <template v-for="row in table.getRowModel().rows" :key="row.id">
              <TableRow :data-state="row.getIsSelected() && 'selected'" @click="() => Create()">
                <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                  <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                </TableCell>
              </TableRow>
              <TableRow v-if="row.getIsExpanded()" @click="() => Create()">
                <TableCell :colspan="row.getAllCells().length">
                  {{ JSON.stringify(row.original) }}
                </TableCell>
              </TableRow>
            </template>
          </template>

          <TableRow v-else>
            <TableCell :colspan="columns.length" class="h-24 text-center">
              No results.
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>

    <div class="flex items-center justify-end space-x-2 py-4">
      <div class="flex-1 text-sm text-muted-foreground">

        {{ table.getFilteredSelectedRowModel().rows?.length }} of
        {{ table.getFilteredRowModel().rows?.length }} row(s) selected.


      </div>
      <div class="flex items-center space-x-2">
        <p class="text-sm font-medium">Rows per page</p>
        <Select :model-value="table.getState().pagination.pageSize.toString()"
          @update:model-value="(value) => table.setPageSize(Number(value))">
          <SelectTrigger class="h-8 w-[70px]">
            <SelectValue :placeholder="table.getState().pagination.pageSize.toString()" />
          </SelectTrigger>
          <SelectContent side="top">
            <SelectItem v-for="pageSize in pageSizes" :key="pageSize" :value="pageSize.toString()">
              {{ pageSize }}
            </SelectItem>
          </SelectContent>
        </Select>
      </div>
      <div class="space-x-2">
        <div class="flex items-center space-x-2">
          <Button variant="outline" class="hidden h-8 w-8 p-0 lg:flex" :disabled="!table.getCanPreviousPage()"
            @click="table.setPageIndex(0)">
            <DoubleArrowLeftIcon class="h-4 w-4" />
          </Button>
          <Button variant="outline" class="h-8 w-8 p-0" :disabled="!table.getCanPreviousPage()"
            @click="table.previousPage()">
            <ChevronLeftIcon class="h-4 w-4" />
          </Button>
          <Button variant="outline" class="h-8 w-8 p-0" :disabled="!table.getCanNextPage()" @click="table.nextPage()">
            <ChevronRightIcon class="h-4 w-4" />
          </Button>
          <Button variant="outline" class="hidden h-8 w-8 p-0 lg:flex" :disabled="!table.getCanNextPage()"
            @click="table.setPageIndex(table.getPageCount() - 1)">
            <DoubleArrowRightIcon class="h-4 w-4" />
          </Button>
        </div>

        <!-- <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()" @click="table.previousPage()">
              Previous
            </Button>
            <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()" @click="table.nextPage()">
              Next
            </Button> -->
      </div>
    </div>
  </div>

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
                <SelectItem v-for="unit in props.departments" :key="unit.unit" :value="unit.code">
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
