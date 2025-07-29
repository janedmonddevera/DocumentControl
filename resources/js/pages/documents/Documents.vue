<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
// import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';

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
import { ArrowUpDown, ChevronDown } from 'lucide-vue-next'
import { h, ref } from 'vue'
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
  filter: Array,
})

const data = props.data.data;
//const data = ref<Document[]>(props.data.data)

const columns = [

  {
    accessorKey: 'doc_code',
    header: 'Document Code',
    cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('doc_code')),
  },
  {
    accessorKey: 'doc_name',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Document Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => h('div', { class: 'lowercase' }, row.getValue('doc_name')),
  },
  {
    accessorKey: 'remarks',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Remarks ', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => h('div', { class: 'lowercase' }, row.getValue('remarks')),
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
      route('documents.index'),
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
      route('documents.index'),
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
      route('documents.index'),
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
const filter_status = {
  title: 'Department',
  column: 'unit',
  data: [
    {
      value: "00",
      label: "EXEC",
    },
    {
      value: "01",
      label: "HR",
    },

    {
      value: "02",
      label: "TECH",
    },

    {
      value: "03",
      label: "QMS",
    },


  ]
}

const filter_toolbar = [
  filter_status,
];

</script>

<template>
  <div class="w-full">
    <div class="flex items-center py-4">
         <Input class="max-w-sm" placeholder="Filter name..." :model-value="table.getColumn('doc_name')?.getFilterValue()" @update:model-value=" table.getColumn('doc_name')?.setFilterValue($event)" />
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
              <TableRow :data-state="row.getIsSelected() && 'selected'">
                <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                  <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                </TableCell>
              </TableRow>
              <TableRow v-if="row.getIsExpanded()">
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

</template>
