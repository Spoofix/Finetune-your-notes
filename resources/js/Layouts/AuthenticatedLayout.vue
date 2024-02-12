<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

// const currentPage = ref('');

// onBeforeMount(() => {
//   currentPage.value = $route.name;
// });

// watch('$route.name', (newRoute) => {
//   currentPage.value = newRoute;
// });

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


//active
let pageTitle = ref();
// var pageTitle = document.title;
function updatePageTitle() {
  pageTitle.value = document.title;
  // console.log(document);
}

// // Run the function every second (1000 milliseconds)
var intervalId = setInterval(updatePageTitle, 10);

setTimeout(function() {
  clearInterval(intervalId);
}, 200); 
// console.log($page);
//
// function getCookie(name) {
//     const cookieValue = document.cookie
//         .split('; ')
//         .find(row => row.startsWith(name + '='));

//     return cookieValue ? cookieValue.split('=')[1] : null;
// }

// const uuid = getCookie('uuid');
// console.log(uuid);
</script>


<template>
    <div class="" style="min-height: 100vh; max-height: auto; overflow-y: hidden;" >
        <div class="relative flex flex-row" id="page-container">   <!-- id="page-container class="sidebar-o"" -->
         <!-- sidebar hear-->
        <div class="hidden1" >
          <nav class="absolute top-0 bottom-0 left-0 flex py-2 transition-all duration-300 ease-in-out transform shadow-xl smooth bg-blue flex-nowrap md:z-10 z-9999 md:translate-x-0"  style="z-index: 3; background: var(--dark-neutral-dark-neutral-10, #262626); top-0; min-height: 100vh;  position: fixed;" :class="{'navWidth px-2 ': isSidebarOpen, 'navWidthColups w-24 -mx-3 -translate-x-full ': !isSidebarOpen }" >
            <!-- id="sidebar" -->

            <!-- Sidebar Content -->
            <div class="sticky top-0 sidebar-content" id="smoothTextId">
              <!-- Side Header -->
              <div class="content-header justify-content-lg-between">
                <div>
                   <div class="justify-between w-full d-flex"  v-if="isSidebarOpen" >
                    <!-- Logo -->
                    <Link class="pl-9 logo-div" >
                      <span class="logo">Spoo</span>
                      <span class="logo-fix">fix</span>
                    </Link>
                  </div>
                    <div class="justify-between d-flex" v-else>
                     <Link class="-mt-5 pl-7"  >
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
                          <i class="justify-center pt-1 my-2 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa fa-house-user hover:active-bg" :class="pageTitle === 'Dashboard - Spoofix' ? 'active-bg' : 'nav-icons-background'"></i>
                        <span class="nav-main-link-name sidenav_text hover:!active-text" v-if="isSidebarOpen" :class="pageTitle === 'Dashboard - Spoofix' ? 'active-text sidenav_text' : 'sidenav_text'">Dashboard</span>
                      </Link>
                    </li>
                    <li class="nav-main-item" :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }" v-if="$page.props.auth.user.role_id == 2">
                        <Link class="nav-main-link" :href="route('domains')" @click="active('Active', 'Active2')">
                        <i class="justify-center pt-1 my-2 text-xl text-black rounded h-9 align-center w-9 nav-main-link-icon fa fa-server nav-icons-background" :class="pageTitle === 'Scanned - Spoofix' || pageTitle === 'RequestAuthorization - Spoofix' || pageTitle === 'Domain - Spoofix' ? 'active-bg' : 'nav-icons-background'"></i>
                        <span class="nav-main-link-name sidenav_text hover:active-text" :class="pageTitle === 'Scanned - Spoofix' || pageTitle === 'RequestAuthorization - Spoofix' || pageTitle === 'Domain - Spoofix' ? 'active-text sidenav_text' : 'sidenav_text'">Scanned</span>
                      </Link>
                    </li>
                    <li class="nav-main-item"  :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }" v-if="$page.props.auth.user.role_id == 2">
                      <Link class="nav-main-link" :href="route('ReportForm')" @click="active('Scanned')">
                        <i class="justify-center pt-1 my-2 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa-regular fa-pen-to-square" :class="pageTitle === 'ReportForm - Spoofix' ? 'active-bg' : 'nav-icons-background'"></i>
                        <span class="nav-main-link-name sidenav_text hover:active-text min-w-max" v-if="isSidebarOpen" :class="pageTitle === 'ReportForm - Spoofix' ? 'active-text sidenav_text' : 'sidenav_text'">Report Form</span>
                      </Link>
                    </li>
                       <li class="nav-main-item"  :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }" v-if="$page.props.auth.user.role_id == 2">
                      <Link class="nav-main-link" :href="route('InProgress')" @click="active('Scanned')">
                        <i class="justify-center pt-1 my-2 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa-solid fa-square-poll-vertical" :class="pageTitle === 'InProgress - Spoofix'|| pageTitle === 'ScannedInProgress - Spoofix' ? 'active-bg' : 'nav-icons-background'"></i>
                        <span class="nav-main-link-name sidenav_text hover:active-text min-w-max" v-if="isSidebarOpen" :class="pageTitle === 'InProgress - Spoofix' || pageTitle === 'ScannedInProgress - Spoofix' ? 'active-text sidenav_text' : 'sidenav_text'">In Progress</span>
                      </Link>
                    </li>
                       <li class="nav-main-item"  :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }" v-if="$page.props.auth.user.role_id == 2">
                      <Link class="nav-main-link" :href="route('Completed')">
                        <i class="justify-center pt-1 my-2 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa fa-check" :class="pageTitle === 'Completed - Spoofix' || pageTitle === 'ScannedCompleted - Spoofix' ? 'active-bg' : 'nav-icons-background'"></i>
                        <span class="nav-main-link-name sidenav_text hover:active-text" v-if="isSidebarOpen" :class="pageTitle === 'Completed - Spoofix' || pageTitle === 'ScannedCompleted - Spoofix'? 'active-text sidenav_text' : 'sidenav_text'">Completed</span>
                      </Link>
                    </li>
                       <li class="nav-main-item "  :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }">
                      <Link class="nav-main-link" :href="route('Messages')">
                        <i class="justify-center pt-1 my-2 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa fa-message" :class="pageTitle === 'Messages - Spoofix' ? 'active-bg' : 'nav-icons-background'"></i>
                        <span class="nav-main-link-name sidenav_text hover:active-text" v-if="isSidebarOpen" :class="pageTitle === 'Messages - Spoofix' ? 'active-text sidenav_text' : 'sidenav_text'">Messages</span>
                      </Link>
                    </li>
                    <!-- <li class="nav-main-item" v-if="$page.props.auth.user.role_id == 1">
                      <Link class="nav-main-link" :href="route('users.list')">
                        <i class="text-xl nav-main-link-icon fa fa-grip-vertical"></i>
                        <span class="nav-main-link-name sidenav_text" v-if="isSidebarOpen">Users</span>
                      </Link>
                    </li> -->
                <!-- <li class="text-yellow-300">{{$page.props.user_switch_data}} </li> -->
                   
                  </ul>
                  </div>
                   <div class=" content-side content-side-full">
                  <ul class="nav-main " v-if="$page.props.auth.user.role_id == 2">
                     <div class="absolute bottom-0 mb-3 float" style="position: fixed;">
                      
                  <li class="nav-main-item"  :class="{'pl-4': isSidebarOpen, 'pl-3 w-6': !isSidebarOpen }">
                      <Link class="nav-main-link" :href="route('domains')">
                        <i class="justify-center pt-1 my-2 text-xl text-black bg-white rounded h-9 align-center w-9 nav-main-link-icon fa-solid fa-circle-question"></i>
                        <span class="nav-main-link-name sidenav_text min-w-max" v-if="isSidebarOpen">Help Center</span>
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
          <div class="bg-white small w-100 " :class="{'div2Opened smallScreen ': isSidebarOpen, 'div2Closed': !isSidebarOpen }" >
            <!-- style="padding-left:296px;" -->
            <!-- Header -->
          <header class="flex justify-between positioning container-fluid headerr WidthOnSmall "  style="z-index: 2;"> 
            <!-- style="padding-left:296px;" -->
            <!-- dropdown -->
              <div class="flex pl-3 my-auto" >
                <div class="mt-2 text-2xl font-bold" @click="toggleSidebar">
                  <i class="fa fa-bars bars hover:bounce"  aria-hidden="true"></i>
                </div>
                 <!-- User Dropdown -->
                <div class="dropdown d-inline-block">
                  <button type="button" class="w-40 border-none md:w-52 btn btn-sm hover:bg-yellow-300 active:bg-yellow-300 d-flex" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    
                    <img
                    v-if="!$page.props.auth.user.profile_pic"
                    class="h-8 w-8 rounded-full object-cover d-lg=none mx-3"
                    src="https://randomuser.me/api/portraits/men/30.jpg"
                    alt=""
                  />
                    <img v-else 
                    :src="$page.props.auth.user.profile_pic"
                     alt="Profile Picture"
                    class="h-8 w-8 rounded-full object-cover d-lg=none mx-3"
                    />             
                  <div class="my-auto userName text-capitalize" style="">
                    <span class="">{{ $page.props.auth.user.name }}</span>
                    
                  </div>
                  </button>
                  <div class="p-0 ml-10 dropdown-menu dropdown-menu-md dropdown-menu-end" aria-labelledby="page-header-user-dropdown">
                    <div class="px-2 py-3 bg-yellow-100 rounded-top">
                      <h5 class="mb-0 text-center h6 text-capitalize">
                        welcome {{ $page.props.auth.user.name }}
                      </h5>
                    </div>
                    <div class="p-2 ">
                      <Link class="space-x-1 font-black dropdown-item d-flex align-items-center justify-content-between hover:bg-yellow-100" :href="route('settings.account')" v-if="$page.props.auth.user.role_id == 2">
                        <span>Profile</span>
                        <i class="opacity-50 hover:opacity-25 fa fa-fw fa-user"></i>
                      </Link>
                      <div class="dropdown-divider"></div>
                      <Link class="space-x-1 font-black dropdown-item d-flex align-items-center justify-content-between hover:bg-yellow-100" as="button" :href="route('logout')" method="post" >
                        <span>Log Out</span>
                        <i class="opacity-50 hover:opacity-25 fa fa-fw fa-sign-out-alt"></i>
                      </Link>
                    </div>
                  </div>
                </div>
                <!-- END User Dropdown -->

              </div>
            <!-- header buttons -->
              <div class="flex flex-row" :class="{ 'mt-5': $page.props.auth.user.role_id === 1 }">
                <Link class="-mr-8 nav-main-link" :href="route('settings.notifications')" v-if="$page.props.auth.user.role_id == 2">
                    <i class="justify-center pt-1 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa fa-bell"></i>
                  </Link>
                <Link class="nav-main-link" :href="route('settings.account')" v-if="$page.props.auth.user.role_id == 2">
                  <i class="justify-center pt-1 text-xl text-black rounded nav-icons-background h-9 align-center w-9 nav-main-link-icon fa fa-gear"></i>
                </Link>
              </div>
          </header>
          <!-- END Header -->

          <!-- Main Container -->
          <main id="" >
            <!-- Page Content -->
            <div class="relative overflow-auto -mt-14 WidthOnSmall" >
              <!-- style="margin-left: 295px;" -->
              <!-- && uuid -->
              <!-- && uuid -->
              <div v-if="$page.props.user_switch_data " >
             <div v-if="$page.props.auth.user.role_id == 2 && $page.props.auth.user.id === $page.props.user_switch_data.user_id " 
                   class="fixed bottom-0 right-0 z-10 flex items-center p-4 m-4 text-lg font-extrabold text-black bg-yellow-400 rounded-lg shadow-2xl hover:shadow-yellow-800">
            <Link :href="'/back_to_admin/' + $page.props.user_switch_data.admin_id" as="button">
                <!-- {{$page.props.user_switch_data.admin_ip}} -->
                <span class="capitalize">Exit {{$page.props.auth.user.name}}</span>
                <i class="ml-4 fas fa-arrow-left"></i>
              </Link>
            </div>

            </div>
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
.positioning{
  position: sticky;
  top: 0%;
}
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
    .smallScreen{
      margin-left: -225px;
    }
    .smallScreenNav{
      position: absolute;
    }
  /**  .positioning{
      
    } **/
    .no_position{
      padding: 0%;
    }
    .sm_display_none{
      display: none;
    }
}
</style>
