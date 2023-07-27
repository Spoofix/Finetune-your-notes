<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { defineComponent } from 'vue';


defineComponent({
  components: {
    Link
  },
});
const props = defineProps( {
    data() {
      return {
        isSidebarOpen: false,
      };
    },
    methods: {
      toggleSidebar() {
        this.isSidebarOpen = !this.isSidebarOpen;
      },
    },
    // watch: {
    //   isSidebarOpen() {
    //     document.body.classList.toggle('sidebar-open', this.isSidebarOpen);
    //   },
    // },
  });
const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

         <!-- sidebar was here -->
         <div class="relative">
          <nav class="absolute top-0 bottom-0 left-0 flex w-64 px-2 py-2 transition-all duration-300 ease-in-out transform -translate-x-full shadow-xl bg-blue flex-nowrap md:z-10 z-9999 md:translate-x-0" id="sidebar">
            
            <!-- Sidebar Content -->
            <div class="sidebar-content">
              <!-- Side Header -->
              <div class="content-header justify-content-lg-between">
                <!-- Logo -->
                <div>
                  <span class="tracking-wide smini-visible fw-bold fs-lg">
                    ye<span class="text-primary">s</span>
                  </span>
                  <div class="justify-between w-full d-flex">
                  <Link class="px-1 tracking-wide fw-bold " :href="route('dashboard')">
                    <span class="smini-hidden">
                      <span class="fs-4 text-dual">ye</span><span class="fs-4 text-primary">spoff</span>
                    </span>
                  </Link>
                  
                </div>
              </div>
              <div> <i class="pl-10 text-2xl font-extrabold fa fa-bars fa-bounce :hover" @click="toggleSidebar"></i></div>
                <!-- END Logo -->

                <!-- Options -->
                <div>

                  <button type="button" class="btn btn-sm btn-alt-danger d-lg-none" data-toggle="layout" data-action="sidebar_close">
                    <i class="fa fa-fw fa-times"></i>
                  </button>

                </div>
                <!-- END Options -->
              </div>
              <!-- END Side Header -->

              <!-- Sidebar Scrolling -->
              <div class="js-sidebar-scroll">

                <div class="content-side content-side-full">
                  <ul class="nav-main">
                    <li class="nav-main-item">
                      <Link class="nav-main-link" :href="route('dashboard')">
                        <i class="nav-main-link-icon fa fa-house-user"></i>
                        <span class="nav-main-link-name">Dashboard</span>
                      </Link>
                    </li>
                    <li class="nav-main-item">
                      <Link class="nav-main-link" :href="route('domains')">
                        <i class="nav-main-link-icon fa fa-server"></i>
                        <span class="nav-main-link-name">Domains</span>
                      </Link>
                    </li>
                    <li class="nav-main-item" v-if="$page.props.auth.user.role_id == 1">
                      <Link class="nav-main-link" :href="route('users.list')">
                        <i class="nav-main-link-icon fa fa-grip-vertical"></i>
                        <span class="nav-main-link-name">Users</span>
                      </Link>
                    </li>
                    <!-- <li class="nav-main-item">
                      <a class="nav-main-link" :href="route('domain.list')">
                        <i class="nav-main-link-icon fa fa-grip-vertical"></i>
                        <span class="nav-main-link-name">Domain Search</span>
                      </a>
                    </li> -->
                  </ul>
                </div>
                <!-- END Side Navigation -->
              </div>
              <!-- END Sidebar Scrolling -->
            </div>
            <!-- Sidebar Content -->
         </nav>
         </div>
          <!-- END Sidebar -->

          <!-- Header -->
          <header id="page-header">
            
            <!-- Header Content -->
            <div class="content-header">
              <div> <i class="px-5 text-2xl font-extrabold fa fa-bars :hover d-lg-none "></i></div>
              <!-- Left Section -->
              <div class="space-x-1">
                <!-- Toggle Sidebar -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="layout" data-action="sidebar_toggle" v-if="false">
                  <i class="fa fa-fw fa-bars"></i>
                </button>
                <!-- END Toggle Sidebar -->


              </div>
              <!-- END Left Section -->


              <!-- Right Section -->
            
              <div class="space-x-1">
               
                <!-- User Dropdown -->
                <div class="dropdown d-inline-block">
                  <button type="button" class="btn btn-sm btn-alt-secondary d-flex hover:text-gray-500 " id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user d-sm-none"></i>
                    <img
                    class="h-8 w-8 rounded-full object-cover d-lg=none mx-3"
                    src="https://randomuser.me/api/portraits/men/30.jpg"
                    alt=""
                  />
                    <span class="my-auto d-none d-sm-inline-block fw-semibold">{{ $page.props.auth.user.name }}</span>
                    <i class="my-auto opacity-50 fa fa-angle-down ms-1"></i>
                  </button>
                  <div class="p-0 dropdown-menu dropdown-menu-md dropdown-menu-end" aria-labelledby="page-header-user-dropdown">
                    <div class="px-2 py-3 bg-body-light rounded-top">
                      <h5 class="mb-0 text-center h6">
                        Welcome {{ $page.props.auth.user.name }}
                      </h5>
                    </div>
                    <div class="p-2">
                      <Link class="space-x-1 dropdown-item d-flex align-items-center justify-content-between" :href="route('profile')">
                        <span>Profile</span>
                        <i class="opacity-25 fa fa-fw fa-user"></i>
                      </Link>

                      <!-- <Link class="space-x-1 dropdown-item d-flex align-items-center justify-content-between" :href="route('organization')" data-toggle="layout" data-action="side_overlay_toggle" >
                        <span>Organization</span>
                        <i class="opacity-25 fa fa-fw fa-wrench"></i>
                      </Link> -->

                      <div class="dropdown-divider"></div>
                      <Link class="space-x-1 dropdown-item d-flex align-items-center justify-content-between" :href="route('logout')" method="post" >
                        <span>Log Out</span>
                        <i class="opacity-25 fa fa-fw fa-sign-out-alt"></i>
                      </Link>
                    </div>
                  </div>
                </div>
                <!-- END User Dropdown -->

              </div>
              <!-- END Right Section -->
            </div>
            <!-- END Header Content -->

          </header>
          <!-- END Header -->

          <!-- Main Container -->
          <main id="">
            <!-- Page Content -->
            <div class="">


                <slot />

            </div>
            <!-- END Page Content -->
          </main>
          <!-- END Main Container -->

          <!-- Footer -->
          <footer id="page-footer">
            <div class="py-3 content">
              <div class="row fs-sm">
                <div class="py-1 text-center col-sm-6 order-sm-2 text-sm-end">

                </div>
                <div class="py-1 text-center col-sm-6 order-sm-1 text-sm-start">

                </div>
              </div>
            </div>
          </footer>
          <!-- END Footer -->
        </div>



        <div class="min-h-screen bg-gray-100" v-if="false">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex items-center shrink-0">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block w-auto text-gray-800 fill-current h-9"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="relative ml-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="flex items-center -mr-2 sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500"
                            >
                                <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
<!-- /* Sidebar styles */ -->
<style>



</style>
