<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';



import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: '/settings/users',
    },
];


import { defineProps, ref, h, watchEffect, reactive } from 'vue'
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

import { watch } from 'vue'
import Switch from '@/components/ui/switch/Switch.vue';
import Label from '@/components/ui/label/Label.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import Dialog from '@/components/ui/dialog/Dialog.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue';
import InputError from '@/components/InputError.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectLabel from '@/components/ui/select/SelectLabel.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';

import CustomAlert from '@/pages/dialog/alert.vue';
import Alert from '@/pages/dialog/alert.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';


const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    org_name: '',
    user_level: '',
});


export interface Users {
    name: string,
    email: string,
    created_at: Date,
    updated_at: Date,
    user_level: string,
}
export interface Department {
    //department
    id: number
    unit: string
    code: string

}
export interface Organization {
    //organization
    id: number
    org_name: string

}

// Receive `data` from Laravel via Inertia
const props = defineProps<{
    data: Users[],
    dept: Department[],
    org: Organization[],

}>()

const data = ref<Users[]>(props.data)




watch(data, (newData) => {
    table.setOptions(prev => ({
        ...prev,
        data: newData,
    }))
})
// Create column helper for Users
const columnHelper = createColumnHelper<Users>()

// Define your columns based on model fields
const columns = [
    columnHelper.accessor('name', {
        header: () => 'Name',
        cell: info => h('div', info.getValue().toString()),
    }),
    columnHelper.accessor('email', {
        header: 'Email',
        cell: info => h('div', info.getValue()),
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







]

// Table state refs
const sorting = ref<SortingState>([])
const columnFilters = ref<ColumnFiltersState>([])
const columnVisibility = ref<VisibilityState>({
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



const deptSwitches = reactive<Record<string, boolean>>({})


// Make data reactive

const modalOpen = ref(false)
const selectedRow = ref(null)



props.dept.forEach(d => {
    deptSwitches[d.unit] = false; // default to false
});
watch(
    () => deptSwitches,
    (newVal) => {
        form.user_level = JSON.stringify(newVal);
    },
    { deep: true }
);


function openModal(row?: any) {
  modalOpen.value = true;

  if (row) {
    // For editing an existing user
    selectedRow.value = row;

    form.name = row.name;
    form.email = row.email;
    form.org_name = row.org_name;
    form.user_level = row.user_level;

    let parsed = {};
    try {
      parsed = JSON.parse(row.user_level || '{}');
    } catch (e) {
      parsed = {};
    }

    // Populate switches with user_level data
    props.dept.forEach(d => {
      deptSwitches[d.unit] = parsed[d.unit] ?? false;
    });
  } else {
    // For creating a new user
    selectedRow.value = null;

    form.name = '';
    form.email = '';
    form.password = '';
    form.org_name = '';
    form.user_level = '';

    // Reset switches
    props.dept.forEach(d => {
      deptSwitches[d.unit] = false;
    });
  }
}


function handleSubmit() {
  const userId = selectedRow.value?.id;

  const payload = {
    name: form.name,
    email: form.email,
    org_name: form.org_name,
    password: form.password,
    password_confirmation: form.password_confirmation,
    user_level: form.user_level,
  };

  const onSuccess = () => {
    modalOpen.value = false;
  };

  const onFinish = () => {
    form.reset('password', 'password_confirmation');
  };

  if (userId) {
    router.put(`/settings/users/${userId}`, payload, {
      onSuccess,
      onFinish,
    });
  } else {
    router.post(`/settings/users`, payload, {
      onSuccess,
      onFinish,
    });
  }
}


</script>

<template>

    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Appearance settings" />


        <SettingsLayout>


            <HeadingSmall title="Users Setting" description="Update user accounts" />
            <div class="md:flex h-full  flex-col gap-4 rounded-xl p-4 mx-4 px-4">
                <div class="md:flex  md:gap-2 ">


                    <div class="w-full h-100 bg-sidebar rounded-md px-4">

                        <div class="flex gap-2 items-center py-4">
                            <Button @click="() => openModal()">
                                Create New
                            </Button>
                            <Input class="max-w-sm" placeholder="Filter titles..."
                                :model-value="table.getColumn('name')?.getFilterValue() as string"
                                @update:model-value="val => table.getColumn('name')?.setFilterValue(val)" />
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="outline" class="ml-auto">
                                        Columns
                                        <ChevronDown class="ml-2 h-4 w-4" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuCheckboxItem
                                        v-for="column in table.getAllColumns().filter(c => c.getCanHide())"
                                        :key="column.id" class="capitalize" :model-value="column.getIsVisible()"
                                        @update:model-value="value => column.toggleVisibility(!!value)">
                                        {{ column.id }}
                                    </DropdownMenuCheckboxItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>

                        <div class="rounded-md border">

                            <Table>
                                <TableHeader>
                                    <TableRow v-for="hg in table.getHeaderGroups()" :key="hg.id">
                                        <TableHead v-for="header in hg.headers" :key="header.id"
                                            :data-pinned="header.column.getIsPinned()" :class="cn(
                                                { 'sticky bg-background/95': header.column.getIsPinned() },
                                                header.column.getIsPinned() === 'left' ? 'left-0' : 'right-0',
                                            )">
                                            <FlexRender v-if="!header.isPlaceholder"
                                                :render="header.column.columnDef.header" :props="header.getContext()" />
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>

                                <TableBody>
                                    <template v-if="table.getRowModel().rows.length">
                                        <template v-for="row in table.getRowModel().rows" :key="row.id">
                                            <TableRow @click="() => openModal(row.original)"
                                                class="cursor-pointer hover:bg-muted">
                                                <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id"
                                                    :data-pinned="cell.column.getIsPinned()" :class="cn(
                                                        { 'sticky bg-background/95': cell.column.getIsPinned() },
                                                        cell.column.getIsPinned() === 'left' ? 'left-0' : 'right-0',
                                                    )">
                                                    <FlexRender :render="cell.column.columnDef.cell"
                                                        :props="cell.getContext()" />
                                                </TableCell>
                                            </TableRow>

                                        </template>

                                        <!-- Modal -->
                                        <Dialog v-model:open="modalOpen">
                                            <DialogContent>
                                                <DialogHeader>
                                                    <DialogTitle>User Details</DialogTitle>
                                                </DialogHeader>


                                                <form class="flex flex-col gap-6" @submit.prevent="handleSubmit">
                                                    <div class="grid gap-6">
                                                        <div class="grid gap-2 w-full">
                                                            <Label for="org_name">Organization</Label>
                                                            <Select v-model="form.org_name">
                                                                <SelectTrigger>
                                                                    <SelectValue placeholder="Select an organization"
                                                                        v-model="form.org_name" />
                                                                </SelectTrigger>
                                                                <SelectContent>
                                                                    <SelectGroup>
                                                                        <SelectLabel>Organizations</SelectLabel>
                                                                        <hr>
                                                                        <SelectItem v-for="orgn in org"
                                                                            :key="orgn.org_name" :value="orgn.org_name"
                                                                            v-model="form.org_name" id="org_name">
                                                                            {{ orgn.org_name }}
                                                                        </SelectItem>
                                                                    </SelectGroup>
                                                                </SelectContent>
                                                            </Select>

                                                        </div>
                                                        <div class="grid gap-2">
                                                            <Label for="name">Name</Label>
                                                            <Input id="name" type="text" required autofocus
                                                                :tabindex="1" autocomplete="name" v-model="form.name"
                                                                placeholder="Full name" />
                                                            <InputError :message="form.errors.name" />
                                                        </div>
                                                        <div class="grid gap-2">
                                                            <Label for="email">Email Address</Label>
                                                            <Input id="email" type="text" required autofocus
                                                                :tabindex="1" autocomplete="username"
                                                                v-model="form.email" placeholder="example@domain.com" />
                                                            <InputError :message="form.errors.email" />
                                                        </div>
                                                        <div class="flex gap-2">
                                                            <div class="grid gap-2">
                                                                <Label for="password">Password</Label>
                                                                <Input id="password" type="password" autofocus :tabindex="1"
                                                                    v-model="form.password" placeholder="" />
                                                                <InputError :message="form.errors.password" />
                                                            </div>
                                                            <div class="grid gap-2">
                                                                <Label for="password_confirmation">Confirm Password</Label>
                                                                <Input id="password_confirmation" type="password" autofocus
                                                                    :tabindex="1" v-model="form.password_confirmation"
                                                                    placeholder="" />
                                                                <InputError :message="form.errors.password_confirmation" />
                                                            </div>
                                                        </div>
                                                        <hr>

                                                        <div class=" p-2 rounded-md ">
                                                            <Label for="department">Select User Access Level</Label>
                                                            <div class="flex">
                                                                <div v-for="deptm in dept" :key="deptm.code"
                                                                    class="justify-middle items-center space-y-2 space-x-2 px-2 mx-1 my-2">
                                                                    <span class="text-xs text-white">{{ deptm.unit
                                                                        }}</span>
                                                                    <Switch v-model="deptSwitches[deptm.unit]"
                                                                        :id="`dept-${deptm.unit}`"
                                                                        class=" align-middle ">

                                                                    </Switch>
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <hr>

                                                        <Button type="submit" class="mt-2 w-full" tabindex="5"
                                                            :disabled="form.processing">
                                                            <LoaderCircle v-if="form.processing"
                                                                class="h-4 w-4 animate-spin" />
                                                            Update account
                                                        </Button>
                                                    </div>

                                                    <!-- <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">Log in</TextLink>
            </div> -->
                                                </form>

                                            </DialogContent>
                                        </Dialog>
                                    </template>


                                    <TableRow v-else>
                                        <TableCell :colspan="columns.length" class="h-24 text-center">
                                            No results.
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>

                        <div class="flex items-center justify-end py-4 space-x-2">
                            <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()"
                                @click="table.previousPage()">
                                Previous
                            </Button>
                            <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()"
                                @click="table.nextPage()">
                                Next
                            </Button>
                        </div>
                    </div>

                </div>


            </div>


        </SettingsLayout>
    </AppLayout>


</template>
