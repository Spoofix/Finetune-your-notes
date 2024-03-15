<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Link} from "@inertiajs/vue3"
// import { defineComponent, onMounted } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { defineComponent } from 'vue';
import { ref, computed } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineComponent({
   name: 'App',
  components: {
    Link
  },
  
});

const props = defineProps({
     testList: {
        type: Object,
    },
    domainList: {
        type:Object,
    },
    spoofList: {
        type: Object,
    },
    // reportDetails:{
    //   type: Object,
    // }

});

console.log(props.spoofList)
// const one = props.spoofList[0];
// spoofList.forEach(() => {
//   if(spoofList === one){
//     pop(spoofList);
//   }
// });
const calculateTimeDifference = (dateString) => {
  try{
      dateString = JSON.parse(dateString);
  }catch(e){}
  if (typeof dateString === "string") {
    const firstDate = new Date(dateString);
    const currentDate = new Date();

    // Calculate the time difference in milliseconds
    const diffInMilliseconds = currentDate - firstDate;

    // Calculate the time difference in years, months, days, and hours
    const diffInYears = Math.round(diffInMilliseconds / (365 * 24 * 60 * 60 * 1000));
    const diffInMonths = Math.round(diffInMilliseconds / (30 * 24 * 60 * 60 * 1000));
    const diffInDays = Math.round(diffInMilliseconds / (24 * 60 * 60 * 1000));
    const diffInHours = Math.round(diffInMilliseconds / (60 * 60 * 1000));

    // Determine the appropriate unit based on the rounded time difference
    let unit, timeDiff;
    if (diffInYears > 0) {
      unit = "year";
      timeDiff = diffInYears;
    } else if (diffInMonths > 0) {
      unit = "month";
      timeDiff = diffInMonths;
    } else if (diffInDays > 0) {
      unit = "day";
      timeDiff = diffInDays;
    } else if (diffInHours) {
      unit = "hour";
      timeDiff = diffInHours;
    }else {
      unit = "hour";
      timeDiff = 'past';
    }

    // Handle plural form if time difference is not 1
    if (timeDiff !== 1) {
      unit += "s";
    }

    return ` ${timeDiff} ${unit}`;
  } else if (Array.isArray(dateString)) {
    // If the dateString is an array, get the first date from the array
    const firstDateString = dateString[0];
    // console.log(firstDateString);
    if (typeof firstDateString === "string") {
      // Calculate the time difference for the first date in the array
      return calculateTimeDifference(firstDateString);
    }
  }
  return 'none';
};

const filteredSpoofListCurrentValue = ref([]); 


function filteredSpoofList(domainid) {
  const spoofList = props.spoofList;
  const filteredSpoofListValue = [];
  for (const spoof of spoofList) {
    if (spoof.domain_id === domainid) {
      filteredSpoofListValue.push(spoof);
    }
  }
  filteredSpoofListCurrentValue.value = filteredSpoofListValue;
  return filteredSpoofListValue;
}


const showTable = ref(true);
const indexStore = ref(null);

function delay(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

// const element = document.getElementById("smoothDropDown");

// const toggleTable = async (index, domainid) => {
//   element.style.marginTop = -100 + '%'; 
//   if (index === indexStore.value) {
//     showTable.value = !showTable.value;
//     if (element) {
//       element.classList.add('.smoothDropDown');
//       element.style.marginTop = -100 + '%'; 
//       while (width < 0) {
//             await delay(1000); 
//             width += 10;
//             element.style.marginTop = width + '%'; 
//         }
//       // await delay(1000);
//   };
//   } else {
//     showTable.value = true;
//     console.log("here");
//   }
//   indexStore.value = index;
//   filteredSpoofList(domainid);
// };

const toggleTable = async (index, domainid) => {
  changePage(1); 
    if (index === indexStore.value) { 
      showTable.value = !showTable.value;
    } else {
      showTable.value = true;
      console.log("here");
    }
    indexStore.value = index;
    filteredSpoofList(domainid);
};

indexStore.value = null; 
const currentPage = ref(1);
const itemsPerPage = 7;



// const spooflist = filteredSpoofListCurrentValue.value;
const paginatedSpoofList = computed(() => {
  // console.log(filteredSpoofListCurrentValue.value);
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredSpoofListCurrentValue.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredSpoofListCurrentValue.value.length / itemsPerPage));

const changePage = (page) => {
  currentPage.value = page;
};
const active = toggleTable(0 , props.domainList[0].id);

 function scrollToElement(index) {
    const element = document.getElementById('myDiv');
    const margin = getComputedStyle(element).marginTop;

    // Calculate the desired scroll position
    const scrollPosition = element.offsetTop - parseInt(margin);

    // Scroll to the element
    window.scrollTo({
        top: scrollPosition,
        // behavior: 'smooth'
    });
}
const formating =(createdDate) =>{
  let options = { day: 'numeric', month: 'numeric', year: 'numeric' };
  let formattedDate = createdDate.toLocaleDateString(undefined, options);
  return formattedDate;
}


const timing =(inputDate) =>{
  const currentDate = new Date();
  const timeDifference = currentDate - inputDate;

  const months = Math.floor((timeDifference / (1000 * 60 * 60 * 24 * 30)));
  const days = Math.floor((timeDifference % (1000 * 60 * 60 * 24 * 30))/ (1000 * 60 * 60 * 24));
  const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

  const formattedDuration = `${months != 0 ? months+'M:' : ''}${days != 0 ? days+ 'D:': ''}${hours}H`;

  return formattedDuration;
}

function startDate(inputDateString) {
  const options = { month: 'short', day: 'numeric', year: '2-digit' };
  const inputDate = new Date(inputDateString);
  const formattedDate = new Intl.DateTimeFormat('en-US', options).format(inputDate);

  const yearSubstring = formattedDate.slice(-2);

  return `${formattedDate}`;
}
</script>

<template>

  <Head title="InProgress" />

  <AuthenticatedLayout class="overflow-scroll " style="height: 100vh;">
    <section class="py-1 pt-6 bg-blueGray-50">

      <!-- testing place -->
      <div class="lg:mr-7" v-for="(domain, index) in domainList" :key="index">
        <div class="flex items-center justify-between w-full my-2 cursor-pointer lg:mx-4 h-14 hover:bg-gray-300" style="border-radius: 6px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);" id="myDiv" :class="showTable && index == indexStore ? 'bigDropdownBgActive' : 'bigDropdownBg'" @click="toggleTable(index, domain.id), scrollToElement(index)">
          <div class="ml-5 text-2xl font-semibold text-blueGray-700">
            <h3 class="orgDomain ">{{ domain.domain_name }}</h3>
          </div>
          <div class="mr-5 botton ">
            <i class="fa" :class="showTable && index == indexStore ? 'fa-chevron-down' : 'fa-chevron-left'" aria-hidden="true"></i>
            <div>
              Spoofing sites
            </div>
          </div>
        </div>
        <div v-if="showTable && index === indexStore" class="items-center overflow-auto bg-transparent border-collapse lg:ml-6 md:ml-6 smooth" :class="{ 'smoothDropDown': showTable }" id="smoothDropDown">
          <div class="overflow-x-hidden hover:overflow-x-auto">
            <table class="w-full text-sm " style="min-width: 700px;" v-if="paginatedSpoofList.length !== 0">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr class="container justify-evenly">
                  <th class="py-3 pl-2 min-w-max">
                    Domain
                  </th>
                  <th class="py-3 text-left">
                    Source
                  </th>
                  <th class="py-3 text-left">
                    User
                  </th>
                  <th class="py-3 text-left">
                    Authorization
                  </th>
                  <th class="py-3 text-left">
                    Start Date
                  </th>
                  <th class="py-3 text-left">
                    Progress
                  </th>
                  <th class="py-3 text-left">
                    Time Elapsed
                  </th>
                  <th class="py-3 text-center">
                    Action
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr class=" tableRow
                      transition-colors duration-300 hover:!bg-yellow-50" v-for="(spoof, index) in paginatedSpoofList" :key="index" :style="{ background: index % 2 !== 1 ? '#FDE6E6' : '#F7E6E4' }">
                  <td class="pl-3 overflow-auto py-auto" style="max-width: 190px;">
                    {{ spoof.spoofed_domain }}
                  </td>
                  <td class="text-left py-auto" v-if="spoof.reported_via === 'report_form'">
                    Self Reported
                  </td>
                  <td class="text-left py-auto" v-else>
                    System Scan
                  </td>
                  <td class="text-left capitalize py-auto">
                    {{spoof.user_name}}
                  </td>
                  <td class="text-left py-auto">
                    <!-- {{formating(new Date(spoof.created_at))}} -->
                    {{startDate(new Date(spoof.created_at))}}

                  </td>
                  <td class="text-left py-auto">
                    {{startDate(new Date(spoof.created_at))}}
                  </td><!--Oct 13, 23-->
                  <td class="text-left py-auto">
                    Initiated
                  </td>
                  <td class="text-left py-auto">
                    {{timing(new Date(spoof.created_at))}}
                  </td>
                  <td class="py-1 ">
                    <Link :href="'/spoof/view/' + spoof.id2" class="w-16 py-1 mx-auto transition-all duration-150 ease-linear tableButton visited:bg-pink-300 active:bg-yellow-200 hover:bg-yellow-200 focus:outline-none" preserve-scroll>
                    <div class="px-4 my-auto">View</div>
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="bg-white" style="width: 100%; height: 330px" v-if="paginatedSpoofList.length === 0">
              <div class="mx-auto ">
                <h1 class="pt-6 text-center max-w-60 h6">No spoofing site takedown currently in progress for domain "{{domain.domain_name }}". <br>We'll update takedown progress here if any! If you have any questions or need further assistance, feel free to reach out.<br> Your satisfaction is our priority.</h1>
                <div style="display: flex; justify-content: center; align-items: center; height: 50px;">
                  <h1 class="border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
                </div>
              </div>
            </div>

          </div>

          <div class="flex justify-center mt-4" style="">

            <button v-if="currentPage > 1" class="px-3 py-2 mr-2 cursor-pointer round gray paginationButtons" @click="changePage(--currentPage)">
              <i class="fa fa-chevron-left" aria-hidden="true"></i>
            </button>
            <div v-for="page in totalPages" :key="page">
              <button v-if="page > currentPage - 4 && page < currentPage + 4" @click="changePage(page)" :class="{ 'primaryColor paginationButtons ': page === currentPage, 'paginationButtons gray text-gray-700': page !== currentPage }" class="px-3 py-2 mr-2 rounded cursor-pointer">
                {{ page }}
              </button>
            </div>
            <button v-if="currentPage < totalPages" class="px-3 py-2 mr-2 cursor-pointer round gray paginationButtons" @click="changePage(++currentPage)">
              <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </button>
          </div>
        </div>

      </div>
    </section>
  </AuthenticatedLayout>
</template>
<style>
.height{
  margin-top: -100%;
}
.height2{
  margin-top: 0%;
}
.smoothDropDown{
  transition-duration: 1s;
  transition-delay: 20ms;
}
.bigDropdownBgActive{
  background: var(--dark-neutral-dark-neutral-5, #D9D9D9);
}
.bigDropdownBg{
  background: #FFEFB0;
}
.tableRow{
  height: 45px;
  flex-shrink: 0;
  border: 1px solid var(--dark-neutral-dark-neutral-4, #F0F0F0);
  box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.10) inset;
  background: #dc8f8f;
}
.tableButton{
display: flex;
height: 39px;
justify-content: center;
align-items: center;
border-radius: 30px;
background: var(--error-e-75, #F89999);
}
.smooth{
   transition-duration: 1s;
 transition-delay: 20ms;
}
.gray{
  background-color: #BFBFBF;
}
.round{
  border-radius:24px ;
  
}
.paginationButtons{
  color: var(--dark-neutral-dark-neutral-9, #454545);
font-family: Poppins;
font-size: 12px;
font-style: normal;
font-weight: 600;
line-height: 120%; /* 14.4px */
}
.primaryColor{
  background-color: #FFE88A;
}
.botton{
  display: flex;
width: 159px;
height: 34px;
justify-content: center;
align-items: center;
gap: 16px;
flex-shrink: 0;
border-radius: 30px;
background: var(--yellow-yellow-400, #FFD633);
}
.orgDomain{
  color: var(--dark-neutral-dark-neutral-8, #595959);

/* Semibold/Heading 5/Semibold */
font-family: Poppins;
font-size: 20px;
font-style: normal;
font-weight: 600;
line-height: 120%; /* 24px */
}
  .bigDropdown{
  height: 68px;
  border-radius: 6px;
background: var(--yellow-yellow-50, #FFFAE6);
}
</style>