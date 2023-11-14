<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const isSidebarOpen = ref(true);

if (window.innerWidth < 1200) {
  isSidebarOpen.value = false;
  // console.log("The screen is less than a tablet screen.");
} else {
  isSidebarOpen.value = true;
  // console.log("The screen is a tablet screen or larger.");
}



function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

const toggleSidebar = async () => {
    // location.reload();
    // await delay(3000);
    isSidebarOpen.value = !isSidebarOpen.value;
    let width = 0;
    const element = document.getElementById('smoothTextId');
    if (element) {
        if (isSidebarOpen.value) {
            element.classList.add('hiddenClass');
            await delay(360);
            element.classList.remove('hiddenClass');
            element.classList.add('smoothText');
            while (width < 250) {
                await delay(0); // Await the promise to introduce the delay
                width += 70;
                element.style.maxWidth = width + 'px'; // Update the max-width property
            }
        } else if(!isSidebarOpen.value) {
            element.classList.remove('smoothText');
        } else {
            element.classList.remove('smoothText');
        }
    }
};
const showingNavigationDropdown = ref(false);

const isActive = ref(false);

const active = (idNo, idNo2) => {
  const element = document.getElementById(idNo);
  const element2 = document.getElementById(idNo2);
  if (element && element2) {
    element.classList.add('active-text');
    element2.classList.add('active-bg');
    isActive.value = true; 
  }
};

</script>


<template>
    <div class="" style="min-height: 100vh; max-height: auto; overflow-y: hidden;" >
        <div class="relative flex flex-row" id="page-container">   <!-- id="page-container class="sidebar-o"" -->
         <!-- sidebar hear-->
        <div class="hidden1" >
          <nav class="absolute top-0 bottom-0 left-0 flex py-2 transition-all duration-300 ease-in-out transform -translate-x-full shadow-xl smooth bg-blue flex-nowrap md:z-10 z-9999 md:translate-x-0"  style="background: var(--dark-neutral-dark-neutral-10, #262626); top-0; min-height: 100vh; position: fixed;" :class="{'navWidth px-2': isSidebarOpen, 'navWidthColups w-24 -mx-3': !isSidebarOpen }" >
            <!-- id="sidebar" -->

            <!-- Sidebar Content -->
            <div class="sticky top-0 sidebar-content" id="smoothTextId">
              <!-- Side Header -->
              <div class="content-header justify-content-lg-between">
                <div>
                   <div class="justify-between w-full d-flex"  v-if="isSidebarOpen" >
                    <!-- Logo -->
                    <Link class="pl-9 logo-div" :href="route('dashboard')">
                      <span class="logo">Spoo</span>
                      <span class="logo-fix">fix</span>
                    </Link>
                  </div>
                    <div class="justify-between d-flex" v-else>
                     <Link class="-mt-5 pl-7"  :href="route('dashboard')" >
                      <span class="logo">S</span>
                      <span class="logo-fix">f</span>
                    </Link>
                      <!-- END Logo -->
                </div>
              </div>
            </div>
             
            <div @click="toggleSidebar">
              <i class="text-xl fa-solid" style="color:#FFCC00;" :class="{'fa-angles-left -mr-9 float-right': isSidebarOpen, 'fa-angles-right ml-16': !isSidebarOpen }"></i>
            </div>
              <div class="relative">
                <!-- js-sidebar-scroll -->
                <div class=" content-side content-side-full">
                  <ul class="nav-main">
                    <li class="nav-main-items"  :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }">
                      <Link class="nav-main-link" :href="route('dashboard')" @click="active('Scanned')">
                          <i class="justify-center pt-1 my-2 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa fa-house-user"></i>
                        <span class="nav-main-link-name sidenav_text" v-if="isSidebarOpen">Dashboard</span>
                      </Link>
                    </li>
                    <li class="nav-main-item" :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }">
                        <Link class="nav-main-link" :href="route('domains')" @click="active('Active', 'Active2')">
                        <i class="justify-center pt-1 my-2 text-xl text-black rounded h-9 align-center w-9 nav-main-link-icon fa fa-server nav-icons-background" id="Active2"></i>
                        <span class="nav-main-link-name sidenav_text" :class="{'active-text': isActive, 'inactive-text': !isActive}" id="Active">Scanned</span>
                      </Link>
                    </li>
                    <li class="nav-main-item"  :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }">
                      <Link class="nav-main-link" :href="route('ReportForm')" @click="active('Scanned')">
                        <i class="justify-center pt-1 my-2 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa-regular fa-pen-to-square"></i>
                        <span class="nav-main-link-name sidenav_text" v-if="isSidebarOpen">Report Form</span>
                      </Link>
                    </li>
                       <li class="nav-main-item"  :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }">
                      <Link class="nav-main-link" :href="route('InProgress')" @click="active('Scanned')">
                        <i class="justify-center pt-1 my-2 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa-solid fa-square-poll-vertical"></i>
                        <span class="nav-main-link-name sidenav_text" v-if="isSidebarOpen">In Progress</span>
                      </Link>
                    </li>
                       <li class="nav-main-item"  :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }">
                      <Link class="nav-main-link" :href="route('Completed')">
                        <i class="justify-center pt-1 my-2 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa fa-check"></i>
                        <span class="nav-main-link-name sidenav_text" v-if="isSidebarOpen">Completed</span>
                      </Link>
                    </li>
                       <li class="nav-main-item "  :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }">
                      <Link class="nav-main-link" :href="route('Messages')">
                        <i class="justify-center pt-1 my-2 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa fa-message"></i>
                        <span class="nav-main-link-name sidenav_text" v-if="isSidebarOpen">Messages</span>
                      </Link>
                    </li>
                    <li class="nav-main-item" v-if="$page.props.auth.user.role_id == 1">
                      <Link class="nav-main-link" :href="route('users.list')">
                        <i class="text-xl nav-main-link-icon fa fa-grip-vertical"></i>
                        <span class="nav-main-link-name sidenav_text" v-if="isSidebarOpen">Users</span>
                      </Link>
                    </li>
                 
                  </ul>
                  </div>
                   <div class=" content-side content-side-full">
                  <ul class="nav-main ">
                     <div class="absolute bottom-0 mb-3 float" style="position: fixed;">
                      
                  <li class="nav-main-item"  :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }">
                      <Link class="nav-main-link" :href="route('domains')">
                        <i class="justify-center pt-1 my-2 text-xl text-black bg-white rounded h-9 align-center w-9 nav-main-link-icon fa-solid fa-circle-question"></i>
                        <span class="nav-main-link-name sidenav_text" v-if="isSidebarOpen">Help Center</span>
                      </Link>
                    </li>
                     <li class="nav-main-item"  :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }">
                      <Link class="nav-main-link" :href="route('domains')">
                        <i class="justify-center pt-1 my-2 text-xl text-black bg-white rounded h-9 align-center w-9 nav-main-link-icon fa fa-lightbulb"></i>
                        <span class="nav-main-link-name sidenav_text" v-if="isSidebarOpen">Feedback</span>
                      </Link>
                    </li>
                  </div>
                </ul>
                </div>
                
                <!-- END Side Navigation -->
              </div>
              <!-- END Sidebar Scrolling -->
            </div>
            <!-- Sidebar Content -->
         </nav>
         <!-- nav colups -->
        
        </div>
          <!-- END Sidebar -->

          
        <!-- div 2 -->
          <div class="bg-white small w-100 " :class="{'div2Opened': isSidebarOpen, 'div2Closed': !isSidebarOpen }" >
            <!-- style="padding-left:296px;" -->
            <!-- Header -->
          <header class="sticky top-0 flex justify-between container-fluid headerr WidthOnSmall" > 
            <!-- style="padding-left:296px;" -->
            <!-- dropdown -->
              <div class="flex pl-3 my-auto" >
                <div class="mt-2 text-xl font-bold">
                  <i class="fa fa-bars bars" aria-hidden="true"></i>
                </div>
                <!-- User Dropdown -->
                <div class="dropdown d-inline-block">
                  <button type="button" class="border-none btn btn-sm btn-alt-secondary d-flex" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    
                    <img
                    class="h-9 w-9 rounded-full object-cover d-lg=none mx-3"
                    src="https://randomuser.me/api/portraits/men/30.jpg"
                    alt=""
                  />
                  <div class="my-auto userName text-capitalize" style="">
                    <span class="">{{ $page.props.auth.user.name }}</span>
                    
                  </div>
                  </button>
                  <div class="p-0 ml-7 dropdown-menu dropdown-menu-md dropdown-menu-end" aria-labelledby="page-header-user-dropdown">
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
            <!-- header buttons -->
              <div class="flex flex-row ">
                <Link class="-mr-8 nav-main-link" :href="route('settings.notifications')">
                    <i class="justify-center pt-1 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa fa-bell"></i>
                  </Link>
                <Link class="nav-main-link" :href="route('settings.account')">
                  <i class="justify-center pt-1 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa fa-gear"></i>
                </Link>
              </div>
          </header>
          <!-- END Header -->

          <!-- Main Container -->
          <main id="" >
            <!-- Page Content -->
            <div class="overflow-auto -mt-14 WidthOnSmall" >
              <!-- style="margin-left: 295px;" -->


                <slot />

            </div>
            <!-- END Page Content -->
          </main>
          <!-- END Main Container -->

     
     <footer class="justify-center w-full pb-1 bg-white" style="position: fixed; bottom: 0; left: 0; text-align: center;">
      <div class="m-auto mt-1 w-fit copyright">Copyright  Spoofix @2023  All Rights Reserved</div>
    </footer>
        </div>
        </div>
    </div>
</template>
<!-- /* Sidebar styles */ -->
<style>
.hiddenClass {
  display: none;
  opacity: 0;
  transition: opacity 300ms ease-in-out 0.9s;
}

/* Add a class to trigger the fade-in effect */
.hiddenClass.fade-in {
  opacity: 1;
}
.smoothText{
  max-height: 0;
  opacity: 1;
  transition: max-width 1s ease-in-out 20ms, opacity 1s ease-in-out 20ms;
  /*max-width: 250px;*/ 
}

.smooth{
   transition-duration: 1s;
 transition-delay: 20ms;
}
.div2Opened{
  padding-left:296px;
   transition-duration: 1s;
 transition-delay: 20ms;
}
.div2Closed{
 padding-left:76px;
 transition-duration: 1s;
 transition-delay: 20ms;
}
.navWidth{
  width:300px; 
}
.navwidthColups{
  max-width:80px; 
}
.bigDropdown{
  height: 68px;
  border-radius: 6px;
background: var(--yellow-yellow-50, #FFFAE6);
}
.userName{
  color: #000;

/* Semibold/Base/Semibold */
font-family: Poppins;
font-size: 14px;
font-style: normal;
font-weight: 600;
line-height: 120%; /* 16.8px */
}
.copyright{
  color: var(--dark-neutral-dark-neutral-10, #262626);

/* Medium/Extra Small/Medium */
font-family: Poppins;
font-size: 11px;
font-style: normal;
font-weight: 500;
line-height: 120%; /* 13.2px */
}
.nav-icons-background{
  background-color: #FFF;
}
.headerr{
max-width: full;
max-height: 68px;
flex-shrink: 0;
background: #FC0;
}
.sidenav_text{
color: var(--dark-neutral-dark-neutral-7, #8C8C8C);

/* Medium/Hedaing 5/Medium */
font-family: Poppins;
font-size: 20px;
font-style: normal;
font-weight: 500;
line-height: 120%; /* 24px */
}
.logo{
  color: #FC0;

/* Bold/Heading 2/Bold */
font-family: Poppins;
font-size: 45px;
font-style: normal;
font-weight: 700;
line-height: 120%; /* 54px */
}
.logo-div{
  width: 229px;
height: 74px;
flex-shrink: 0;
}
.logo-fix{
color: #FFF;
/* Bold/Heading 2/Bold */
font-family: Poppins;
font-size: 45px;
font-style: normal;
font-weight: 700;
line-height: 120%;
}
.active-bg{
  background-color:  #FC0; 
}
.active-text{
  color: #FC0;
}
  .bars{
      display: none;
    }
@media (max-width: 767px) {
   .hidden1{
     width: 0%; 
   }
    .small {
      width: 100vw;
    }
    .WidthOnSmall{
      width: 100vw;
      margin-left: -74px;
    }
    .bars{
      display: contents;
    }
}
</style>
