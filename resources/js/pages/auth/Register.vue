<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectLabel from '@/components/ui/select/SelectLabel.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

import { defineProps, reactive, ref, watch } from 'vue'
import { Masterlist } from '../masterlist/index.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    org_name: '',
    user_level: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

export interface Organization {
    //organization
    id: number
    org_name: string

}

export interface Department {
    //department
    id: number
    unit: string
    code: string

}

const props = defineProps<{
    org: Organization[],
    dept: Department[],

}>()

const deptSwitches = reactive<Record<string, boolean>>({})

props.dept.forEach(d => {
  deptSwitches[d.unit] = false
})
watch(
  () => deptSwitches,
  (newVal) => {
    form.user_level = JSON.stringify(newVal)
  },
  { deep: true }
)
</script>

<template>
    <AuthBase title="Create an account" description="Enter your details below to create your account">

        <Head title="Register" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">

                <div class="grid gap-2 w-full">
                    <Label for="org_name">Organization</Label>
                    <Select v-model="form.org_name">
                        <SelectTrigger>
                            <SelectValue placeholder="Select an organization" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Organizations</SelectLabel>
                                <SelectItem v-for="orgn in org" :key="orgn.org_name" :value="orgn.org_name"
                                    v-model="form.org_name" id="org_name">
                                    {{ orgn.org_name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>

                    <!-- <p class="text-sm text-muted-foreground">
                        Selected Org ID: {{ selectedOrg }}
                    </p> -->
                </div>

                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                        v-model="form.name" placeholder="Full name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email"
                        placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input id="password" type="password" required :tabindex="3" autocomplete="new-password"
                        v-model="form.password" placeholder="Password" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input id="password_confirmation" type="password" required :tabindex="4" autocomplete="new-password"
                        v-model="form.password_confirmation" placeholder="Confirm password" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>
                <hr>
                <div class=" p-2 rounded-md ">
                    <Label for="department">Select User Access Level</Label>
                    <div class="flex">
                        <div v-for="deptm in dept" :key="deptm.code" class="justify-middle items-center space-y-2 space-x-2 px-2 mx-1 my-2">
                            <span class="text-xs text-white">{{ deptm.unit }}</span>
                            <Switch v-model="deptSwitches[deptm.unit]" :id="`dept-${deptm.unit}`" class=" align-middle ">

                            </Switch>
                        </div>
                    </div>
                </div>
                <hr>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Create account
                </Button>
            </div>

            <!-- <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">Log in</TextLink>
            </div> -->
        </form>
    </AuthBase>
</template>
