<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import {Link} from "@inertiajs/vue3"
import { defineComponent } from 'vue';

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
    }
});

const calculateTimeDifference = (dateString) => {
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


</script>

<template>
    <Head title="Organization" />

    <AuthenticatedLayout>
        <div class="col-md-12">
            <div class="block block-rounded">
                <div class="px-5 block-header">
                    <h2 class="block-title">Spoff for {{ domain }}</h2>
                    <!-- <a  class="mr-3 btn btn-info text-capitalize"   data-bs-toggle="modal" data-bs-target="#modal-normal">Add Organization</a> -->
                    <!-- <a  class="mr-3 btn btn-info text-capitalize"  >Generate all</a>
                    <a  class="mr-3 btn btn-info text-capitalize"  >Generate 24hrs</a> -->
                    <!-- notification i will use later -->
                    <!-- <div class="modal" id="modal-normal" tabindex="-1" role="dialog" aria-labelledby="modal-normal" aria-hidden="true"> -->
                        <!-- <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="block mb-0 shadow-none block-rounded">
                                    <div class="block-header block-header-default">
                                        <h3 class="block-title">Add Organization</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content fs-sm">
                                        <form  @submit.prevent="submit">

                                            <div class="col-md-12">
                                                <div class="mb-4 row">
                                                    <div class="col-12">
                                                        <label class="form-label">Organization</label>
                                                        <input type="text" class="form-control form-control-lg"  v-model="form.name" placeholder="Enter your organization name..">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <button class="btn btn-primary btn-primary-custom" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                    <span class="spinner-border text-dark " v-if="form.processing"></span>
                                                    <i class="opacity-50 fa fa-check me-1"></i>Create Organization
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    <!-- </div> -->
                
                </div>
                <!-- <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css"> -->
<!-- <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css"> -->


<section class="py-1 bg-blueGray-50">
<div class="w-full px-4 mx-auto mt-3 mb-12 xl:w-12/12 xl:mb-0">
  <div class="relative flex flex-col w-full min-w-0 mb-6 break-words bg-white rounded shadow-lg ">
    <div class="px-4 py-3 mb-0 border-0 rounded-t">
      <div class="flex flex-wrap items-center">
        <div class="relative flex-1 flex-grow w-full max-w-full px-4" v-for="(domain, index) in domainList" :key="index" >
            <div v-for="(spoof, index) in spoofList" :key="index">
            <div  v-if="domain.id == spoof.domain_id && index === 0">
                <h3 class="ml-2 text-3xl font-semibold text-blueGray-700">{{ domain.domain_name}}</h3>
            
            </div>
           
        </div>

        </div>
        <div class="relative flex-1 flex-grow w-full max-w-full px-4 text-right">
          <Link class="px-3 py-1 mb-1 mr-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-indigo-500 rounded outline-none active:bg-indigo-600 focus:outline-none" type="button" :href="'/domains'"><i class="fa fa-arrow-left"></i> Back</Link>
        </div>
      </div>
    </div>

    <div class="block w-full overflow-x-auto">
      <table class="items-center w-full bg-transparent border-collapse">
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
          <tr v-for="(spoof, index) in spoofList" :key="index"  :style="{ background: index % 2 !== 0 ? 'white' : 'rgba(245, 245, 241)'}" class="transition-colors duration-300 hover:!bg-gray-300" >
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
            <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-else-if="spoof.htmls !== '' ">
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
              <td class="p-4 px-2 text-xs border-t-0 border-l-0 border-r-0 align-between whitespace-nowrap" v-if="spoof.csscolor== 'processing'">
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
              <span class="px-1 py-1 mb-1 mr-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-red-400 rounded outline-none active:bg-red-700 hover:bg-red-700 hover:text-black focus:outline-none" type="button">Report</span>
              <Link  :href="'/spoof/view/'+spoof.id" class="px-1 py-1 mb-1 mr-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-blue-400 rounded outline-none visited:bg-green-500 active:bg-blue-700 hover:bg-blue-700 hover:text-black focus:outline-none">
                    <span class=""  type="button"><i class="fa fa-eye"></i> View</span>
            </Link>
            </td>
          
          </tr>
        </tbody>

      </table>
    </div>
  </div>
</div>
<footer class="relative pt-8 pb-6 mt-16">
  <div class="container px-4 mx-auto">
    <div class="flex flex-wrap items-center justify-center md:justify-between">
      <div class="w-full px-4 mx-auto text-center md:w-6/12">
        <div class="py-1 text-sm font-semibold text-blueGray-500">
          <!-- Made with <a href="https://www.creative-tim.com/product/notus-js" class="text-blueGray-500 hover:text-gray-800" target="_blank">Notus JS</a> by <a href="https://www.creative-tim.com" class="text-blueGray-500 hover:text-blueGray-800" target="_blank"> Creative Tim</a>. -->
        </div>
      </div>
    </div>
  </div>
</footer>
</section>
                
            
            </div>
        </div>
    </AuthenticatedLayout>
</template>
