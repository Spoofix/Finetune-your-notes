<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import {Link} from "@inertiajs/vue3"
import { defineComponent } from 'vue';
import { ref, computed } from 'vue';

const showTable = ref(true);

const toggleTable = () => {
  showTable.value = !showTable.value;
};


defineComponent({
  components: {
    Link
  },
});
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 7000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})


const props = defineProps({
    organization: {
        type: Object,
    },
    spoofList: {
        type: Object,
    },
    domainList: {
        type:Object,
    },
});


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



const form = useForm({
    name: null,
});

function submit() {
    if (form.name == null) {
        Toast.fire({
            icon: 'error',
            title: 'All fields are required'
        })
        return;
    }

    form.post(route('organization'), {
        onFinish: (response) => {
            Toast.fire({
                icon: 'success',
                title: 'Organization Created Successfully'
            })

            console.log(response);
            //  search()

        },
        onSuccess: () => { },
    });

}

function cancel(id) {
    Swal.fire({
        title: 'Do you want to disable this organization?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: "No"
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            const cancel_form = useForm({
                org_id: id,
            });
            cancel_form.post(route('organization.cancel'), {
                onFinish: (response) => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Organization Updated Successfully'
                    })
                },
                onSuccess: () => { },
            });
        } else if (result.isDenied) {
            // Swal.fire('Changes are not saved', '', 'info')
        }
    })
}

function activate(id) {

    Swal.fire({
        title: 'Do you want to disable this organization?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: "No"
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            const activate_form = useForm({
                org_id: id,
            });
            activate_form.post(route('organization.activate'), {
                onFinish: (response) => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Organization Updated Successfully'
                    })
                },
                onSuccess: () => { },
            });
        } else if (result.isDenied) {
            // Swal.fire('Changes are not saved', '', 'info')
        }
    })

}
let spoofList = props.spoofList;
const itemsPerPage = 5; // Number of items per page
const currentPage = ref(1); // Current page, starts at 1

const paginatedSpoofList = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return spoofList.slice(start, end);
});

const totalPages = computed(() => Math.ceil(spoofList.length / itemsPerPage));

const changePage = (page) => {
  currentPage.value = page;
};

</script>

<template>
    <Head title="Scanned" />

    <AuthenticatedLayout class="overflow-scroll" style="height:100vh;"> 
        <div class="col-md-12">
            <div class="block block-rounded">
                <div class="px-5 block-header">
                    <h2 class="block-title">{{ domain }}</h2>
   
                
                </div>
   

<section class="py-1 bg-blueGray-50">
    <div class="relative flex-1 flex-grow w-full max-w-full px-4 mt-2 text-right">
        <Link class="px-3 py-1 mb-1 mr-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-indigo-500 rounded outline-none active:bg-indigo-600 focus:outline-none" type="button" :href="'/domains'"><i class="fa fa-arrow-left"></i> Back</Link>
    </div>
<!-- testing place -->
    <div class="mr-12">
      <div
        class="relative flex items-center justify-between w-full mx-4 my-2 cursor-pointer h-14 "  style="border-radius: 6px; background: var(--yellow-yellow-50, #FFFAE6);"
        @click="toggleTable"
      >
        <div class="ml-5 text-2xl font-semibold text-blueGray-700">
          <div class="relative flex-1 flex-grow w-full max-w-full px-4" v-for="(domain, index) in domainList" :key="index" >
              <div v-for="(spoof, index) in spoofList" :key="index">
              <div  v-if="domain.id == spoof.domain_id && index === 0">
                  <h3 class="orgDomain text-capitalize ">{{ domain.domain_name }}</h3>
            <!-- ml-2 text-3xl font-semibold text-blueGray-700 -->
              </div>
          </div>

          </div>
        </div>
        <div class="mr-5 botton">
          <i
            class="fa"
            :class="showTable ? 'fa-chevron-down' : 'fa-chevron-left'"
            aria-hidden="true"
          ></i>
           <div>
            Spoofix sites 
          </div>
        </div>
      </div>

      <table v-if="showTable" class="items-center w-full ml-6 bg-transparent border-collapse">
          <thead>
            <tr >
              <th class="px-2 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-solid bg-blueGray-50 text-blueGray-500 border-blueGray-100 whitespace-nowrap">
                            Domain
                          </th>
                                            <th class="px-2 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-solid bg-blueGray-50 text-blueGray-500 border-blueGray-100 whitespace-nowrap">
                            screenshot rating
                            </th>
                            <th class="px-2 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-solid bg-blueGray-50 text-blueGray-500 border-blueGray-100 whitespace-nowrap">
                              html rating
                              </th>
                          <th class="px-2 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-solid bg-blueGray-50 text-blueGray-500 border-blueGray-100 whitespace-nowrap">
                              content rating
                              </th>
                          <th class="px-2 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-solid bg-blueGray-50 text-blueGray-500 border-blueGray-100 whitespace-nowrap">
                              colorscheme rating
                              </th>
                              <th class="px-2 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-solid bg-blueGray-50 text-blueGray-500 border-blueGray-100 whitespace-nowrap">
                                Domain rating
                                </th>
                              <th class="px-2 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-solid bg-blueGray-50 text-blueGray-500 border-blueGray-100 whitespace-nowrap">
                                  age
                              </th>
                  <th class="px-2 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-solid bg-blueGray-50 text-blueGray-500 border-blueGray-100 whitespace-nowrap">
                      Rating
                      </th>
        
             <th class="px-2 py-3 text-xs font-semibold text-left text-red-500 uppercase align-middle border border-l-0 border-r-0 border-solid bg-blueGray-50 border-blueGray-100 whitespace-nowrap">
                            Report
                          </th>
          
            </tr>
          </thead>

          <tbody>
            <tr v-for="(spoof, index) in paginatedSpoofList" :key="index"  :style="{ background: index % 2 !== 0 ? 'white' : 'rgba(245, 245, 241)' }" class="transition-colors duration-300 hover:!bg-gray-300" >
              <th class="p-4 px-2 text-xs text-left align-middle border-t-0 border-l-0 border-r-0 whitespace-nowrap text-blueGray-700 ">
                {{ spoof.spoofed_domain }}
              </th>
              <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-if="spoof.phashes == 'processing'">
                <div role="status">
                  <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-400" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                      <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                  </svg>
                  <span class="sr-only">Loading...</span>
                </div>
        </td>
        <td class="p-4 px-2 text-xs align-middle border-t-0 border-l-0 border-r-0 whitespace-nowrap" v-else-if="spoof.phashes == 'failed'">
          <!-- <i class="mr-4 fas fa-arrow-up text-emerald-500"></i> -->
          none
        </td>
              <td class="p-4 px-2 text-xs align-middle border-t-0 border-l-0 border-r-0 whitespace-nowrap" v-else>
                <!-- <i class="mr-4 fas fa-arrow-up text-emerald-500"></i> -->
                {{ spoof.phashes }}
              </td>
              <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-if="spoof.htmls == 'processing'">
                      <div role="status">
                        <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                        </svg>
                        <span class="sr-only">Loading...</span>
                      </div>
              </td>
              <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-else-if="spoof.htmls !== ''">
                  {{ spoof.htmls }}
                </td>
                <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-else>
                  none
                </td>
                <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-if="spoof.domainsimilarityrate == 'processing'">
                  <div role="status">
                    <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                  </div>
              </td>
              <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-else>
                  {{ spoof.domainsimilarityrate }}
                </td>
                <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-if="spoof.csscolor == 'processing'">
                  <div role="status">
                    <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                  </div>
          </td>
                <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-else>
                  {{ spoof.csscolor }}
                </td>
                <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-if="spoof.domain_rating == 'processing'">
                  <div role="status">
                    <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                  </div>
          </td>
                <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-else>
                  {{ spoof.domain_rating }}
                </td>
                <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-if="spoof.registrationDate == 'processing'">
                  <div role="status">
                    <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                  </div>
          </td>
                <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-else>
                  {{ calculateTimeDifference(spoof.registrationDate) }} 
                  <!-- {{ spoof.registrationDate }} -->
                </td>
              <td class="p-4 px-2 text-xs align-middle border-t-0 border-l-0 border-r-0 whitespace-nowrap" :style="{
                color: spoof.rating == 'high' ? 'red' :
                  spoof.rating == 'critical' ? 'orange' :
                    spoof.rating == 'medium' ? '#8B8000' :
                      spoof.rating == 'none' ? 'blue' :
                        spoof.rating == 'low' ? 'green' :
                          'gray'
              }"
                >
                <!-- <i class="mr-4 fas fa-arrow-up text-emerald-500"></i> -->
                {{ spoof.rating }}
              </td>
              <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap">
                <Link :href="'/spoof/report/' + spoof.id" class="px-1 py-1 mb-1 mr-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-red-400 rounded outline-none active:bg-red-700 hover:bg-red-700 hover:text-black focus:outline-none visited:bg-pink-800" type="button">
                  <span class=""  type="button"><i class="fa fa-cancel"></i> Report</span>
                </Link>
                <Link  :href="'/spoof/view/' + spoof.id" class="px-1 py-1 mb-1 mr-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-blue-400 rounded outline-none visited:bg-green-500 active:bg-blue-700 hover:bg-blue-700 hover:text-black focus:outline-none">
                      <span class=""  type="button"><i class="fa fa-eye"></i> View</span>
              </Link>
              </td>
          
            </tr>
          </tbody>
         
     
      </table>
     <!-- Pagination controls -->
          <div class="flex justify-center mt-4 ">
            <button class="px-3 py-2 mr-2 cursor-pointer round gray"  @click="changePage(--page)">
              <i class="fa fa-chevron-left" aria-hidden="true"></i>
            </button>
            <button
              v-for="page in totalPages"
              :key="page"
              @click="changePage(page)"
              :class="{ 'primaryColor paginationButtons': page === currentPage, 'gray text-gray-700': page !== currentPage }"
              class="px-3 py-2 mr-2 rounded cursor-pointer"
            >
              {{ page }}
            </button >
             
            <button class="px-3 py-2 mr-2 cursor-pointer round gray"  @click="changePage(++page)">
              <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </button>
          </div>
    </div>
</section>
                
            
            </div>
        </div> 
       
    </AuthenticatedLayout>
</template>
<style>
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