<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';

import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Eclipse, Files, Folder, House, LayoutGrid, Logs, PackageSearch } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import Button from './ui/button/Button.vue';
import DrawerTrigger from './ui/drawer/DrawerTrigger.vue';
import DrawerContent from './ui/drawer/DrawerContent.vue';
import DrawerHeader from './ui/drawer/DrawerHeader.vue';
import DrawerTitle from './ui/drawer/DrawerTitle.vue';
import DrawerDescription from './ui/drawer/DrawerDescription.vue';
import DrawerFooter from './ui/drawer/DrawerFooter.vue';
import DrawerClose from './ui/drawer/DrawerClose.vue';
import Drawer from './ui/drawer/Drawer.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
      {
        title: 'Recently Added',
        href: '/recents',
        icon: Eclipse ,
    },
    {
        title: 'Masterlist',
        href: '/masterlist',
        icon: PackageSearch,
    },
    {
        title: 'Documents',
        href: '/documents',
        icon: Files ,
    },

    {
        title: 'Departments',
        href: '/departments',
        icon: House  ,
    },
];


const footerNavItems: NavItem[] = [
    // {
    //     title: 'Github Repo',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: Folder,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits#vue',
    //     icon: BookOpen,
    // },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                        <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>



 <Drawer>
    <DrawerTrigger as-child>
      <Button variant="ghost">
         <Logs /> See Logs
      </Button>
    </DrawerTrigger>
    <DrawerContent  >
      <div class="mx-auto w-full max-w-sm">
        <DrawerHeader>
          <DrawerTitle>Move Goal</DrawerTitle>
          <DrawerDescription>Set your daily activity goal.</DrawerDescription>
        </DrawerHeader>
        
         
        <DrawerFooter>
          <Button>Submit</Button>
          <DrawerClose as-child>
            <Button variant="outline">
              Cancel
            </Button>
          </DrawerClose>
        </DrawerFooter>
      </div>
    </DrawerContent>
  </Drawer>
        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
       
    </Sidebar>
    <slot />
</template>
