<script setup >
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import {Link} from "@inertiajs/vue3"
import { defineComponent, onMounted } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';



// Run the function every second (1000 milliseconds)
// var intervalId = setInterval(updatePageTitle, 1000);

// function updatePageTitle() 
// onMounted(() => {
//   // Load Google Charts library
//   if (typeof google === 'undefined' || typeof google.charts === 'undefined') {
//     // If Google Charts library is not loaded, load it dynamically
//     const script = document.createElement('script');
//     script.type = 'text/javascript';
//     script.src = 'https://www.gstatic.com/charts/loader.js';
//     script.onload = () => {
//       google.charts.load('current', {'packages': ['corechart']});
//       google.charts.setOnLoadCallback(drawChart);
//     };
//     document.head.appendChild(script);
//   } else {
//     // If Google Charts library is already loaded, proceed to draw the chart
//     drawChart();
//   }

//   function drawChart() {
//     var data = google.visualization.arrayToDataTable([
//       ['Day', 'new domains', 'High Risk'],
//       ['monday',  50,      10],
//       ['tuesday',  100,      20],
//       ['wednesday',  100,       20],
//       ['thursday',  80,      10],
//       ['friday', 40, 10],
//       ['saturday', 30, 5],
//       ['sunday', 70, 30]
//     ]);

//     var options = {
//       title: 'Domains In Days',
//       legend: { position: 'bottom' }
//     };

//     var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
//     chart.draw(data, options);
//   }
// });


defineComponent({
   name: 'App',
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
    domainList: {
        type:Object,
    }
    
});

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
                        title: 'Domain Updated Successfully'
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
    <Head title="Dashboard" />

    <AuthenticatedLayout style="height: 100vh;">

        <div class="mt-6 ml-4 row" v-if="$page.props.auth.user.role_id == 1" >
<!--            admin content-->
<div class="flex sm:flex-col lg:flex-row ">
    <div class="overflow-x-auto lg:w-1/2 sm:-mx-3 lg:-mx-4">
      <div class="inline-block min-w-full py-2 sm:px-3 lg:px-4">
        <div class="overflow-hidden">
          <table class="min-w-full text-sm font-light text-left">
            <thead class="font-medium border-b dark:border-neutral-500">
              <tr>
                <th scope="col" class="px-3 py-4">Spoofix Rating Model</th>
                <th scope="col" class="px-3 py-4">From</th>
                <th scope="col" class="px-3 py-4">To</th>
               
              </tr>
            </thead>
            <tbody>
              <tr class="border-b dark:border-neutral-500">
                <td class="px-3 py-2 font-medium whitespace-nowrap">
                    <div class="relative ml-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="flex items-center whitespace-nowrap rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] motion-reduce:transition-none dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] justify-items-between"
                                    >
                                       <small>change category</small>  
                                       <i class="fa fa-angle-down"></i>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink class="bg-red-400"> Very High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-pink-300"> High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-200"> Medium Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-100"> Low Risk</DropdownLink>
                                <DropdownLink class="-mt-6 -mb-6 bg-pink-100"> Very Low Risk</DropdownLink> 
                                <!-- :href="route('...')" -->

                            </template>
                        </Dropdown>
                    </div>
                </td>
                <td class="px-3 py-2 whitespace-nowrap">100%</td>
                <td class="px-3 py-2 whitespace-nowrap">90%</td>
              
              </tr>
              <tr class="border-b dark:border-neutral-500">
                <td class="px-3 py-2 font-medium whitespace-nowrap">
                    <div class="relative ml-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="flex items-center whitespace-nowrap rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] motion-reduce:transition-none dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] justify-items-between"
                                    >
                                       <small>change category</small>  
                                       <i class="fa fa-angle-down"></i>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink class="bg-red-400"> Very High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-pink-300"> High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-200"> Medium Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-100"> Low Risk</DropdownLink>
                                <DropdownLink class="-mt-6 -mb-6 bg-pink-100"> Very Low Risk</DropdownLink> 
                                <!-- :href="route('...')" -->

                            </template>
                        </Dropdown>
                    </div>
                </td>
                <td class="px-3 py-2 whitespace-nowrap">90%</td>
                <td class="px-3 py-2 whitespace-nowrap">80%</td>
         
              </tr>
              <tr class="border-b dark:border-neutral-500">
                <td class="px-3 py-2 font-medium whitespace-nowrap">
                    <div class="relative ml-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="flex items-center whitespace-nowrap rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] motion-reduce:transition-none dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] justify-items-between"
                                    >
                                       <small>change category</small>  
                                       <i class="fa fa-angle-down"></i>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink class="bg-red-400"> Very High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-pink-300"> High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-200"> Medium Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-100"> Low Risk</DropdownLink>
                                <DropdownLink class="-mt-6 -mb-6 bg-pink-100"> Very Low Risk</DropdownLink> 
                                <!-- :href="route('...')" -->

                            </template>
                        </Dropdown>
                    </div>
                </td>
                <td class="px-3 py-2 whitespace-nowrap">80%</td>
                <td class="px-3 py-2 whitespace-nowrap">70%</td>
          
              </tr>
              <tr class="border-b dark:border-neutral-500">
                <td class="px-3 py-2 font-medium whitespace-nowrap">
                    <div class="relative ml-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="flex items-center whitespace-nowrap rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] motion-reduce:transition-none dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] justify-items-between"
                                    >
                                       <small>change category</small>  
                                       <i class="fa fa-angle-down"></i>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink class="bg-red-400"> Very High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-pink-300"> High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-200"> Medium Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-100"> Low Risk</DropdownLink>
                                <DropdownLink class="-mt-6 -mb-6 bg-pink-100"> Very Low Risk</DropdownLink> 
                                <!-- :href="route('...')" -->

                            </template>
                        </Dropdown>
                    </div>
                </td>
                <td class="px-3 py-2 whitespace-nowrap">70%</td>
                <td class="px-3 py-2 whitespace-nowrap">60%</td>
          
              </tr>
              <tr class="border-b dark:border-neutral-500">
                <td class="px-3 py-2 font-medium whitespace-nowrap">
                    <div class="relative ml-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="flex items-center whitespace-nowrap rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] motion-reduce:transition-none dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] justify-items-between"
                                    >
                                       <small>change category</small>  
                                       <i class="fa fa-angle-down"></i>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink class="bg-red-400"> Very High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-pink-300"> High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-200"> Medium Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-100"> Low Risk</DropdownLink>
                                <DropdownLink class="-mt-6 -mb-6 bg-pink-100"> Very Low Risk</DropdownLink> 
                                <!-- :href="route('...')" -->

                            </template>
                        </Dropdown>
                    </div>
                </td>
                <td class="px-3 py-2 whitespace-nowrap">60%</td>
                <td class="px-3 py-2 whitespace-nowrap">50%</td>
          
              </tr>
              <tr class="border-b dark:border-neutral-500">
                <td class="px-3 py-2 font-medium whitespace-nowrap">
                    <div class="relative ml-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="flex items-center whitespace-nowrap rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] motion-reduce:transition-none dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] justify-items-between"
                                    >
                                       <small>change category</small>  
                                       <i class="fa fa-angle-down"></i>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink class="bg-red-400"> Very High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-pink-300"> High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-200"> Medium Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-100"> Low Risk</DropdownLink>
                                <DropdownLink class="-mt-6 -mb-6 bg-pink-100"> Very Low Risk</DropdownLink> 
                                <!-- :href="route('...')" -->

                            </template>
                        </Dropdown>
                    </div>
                </td>
                <td class="px-3 py-2 whitespace-nowrap">50%</td>
                <td class="px-3 py-2 whitespace-nowrap">40%</td>
          
              </tr>
              <tr class="border-b dark:border-neutral-500">
                <td class="px-3 py-2 font-medium whitespace-nowrap">
                    <div class="relative ml-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="flex items-center whitespace-nowrap rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] motion-reduce:transition-none dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] justify-items-between"
                                    >
                                       <small>change category</small>  
                                       <i class="fa fa-angle-down"></i>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink class="bg-red-400"> Very High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-pink-300"> High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-200"> Medium Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-100"> Low Risk</DropdownLink>
                                <DropdownLink class="-mt-6 -mb-6 bg-pink-100"> Very Low Risk</DropdownLink> 
                                <!-- :href="route('...')" -->

                            </template>
                        </Dropdown>
                    </div>
                </td>
                <td class="px-3 py-2 whitespace-nowrap">40%</td>
                <td class="px-3 py-2 whitespace-nowrap">30</td>
          
              </tr>
              <tr class="border-b dark:border-neutral-500">
                <td class="px-3 py-2 font-medium whitespace-nowrap">
                    <div class="relative ml-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="flex items-center whitespace-nowrap rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] motion-reduce:transition-none dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] justify-items-between"
                                    >
                                       <small>change category</small>  
                                       <i class="fa fa-angle-down"></i>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink class="bg-red-400"> Very High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-pink-300"> High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-200"> Medium Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-100"> Low Risk</DropdownLink>
                                <DropdownLink class="-mt-6 -mb-6 bg-pink-100"> Very Low Risk</DropdownLink> 
                                <!-- :href="route('...')" -->

                            </template>
                        </Dropdown>
                    </div>
                </td>
                <td class="px-3 py-2 whitespace-nowrap">30%</td>
                <td class="px-3 py-2 whitespace-nowrap">20%</td>
          
              </tr>
              <tr class="border-b dark:border-neutral-500">
                <td class="px-3 py-2 font-medium whitespace-nowrap">
                    <div class="relative ml-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="flex items-center whitespace-nowrap rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] motion-reduce:transition-none dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] justify-items-between"
                                    >
                                       <small>change category</small>  
                                       <i class="fa fa-angle-down"></i>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink class="bg-red-400"> Very High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-pink-300"> High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-200"> Medium Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-100"> Low Risk</DropdownLink>
                                <DropdownLink class="-mt-6 -mb-6 bg-pink-100"> Very Low Risk</DropdownLink> 
                                <!-- :href="route('...')" -->

                            </template>
                        </Dropdown>
                    </div>
                </td>
                <td class="px-3 py-2 whitespace-nowrap">20%</td>
                <td class="px-3 py-2 whitespace-nowrap">10%</td>
          
              </tr>
              <tr class="border-b dark:border-neutral-500">
                <td class="px-3 py-2 font-medium whitespace-nowrap">
                    <div class="relative ml-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="flex items-center whitespace-nowrap rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] motion-reduce:transition-none dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] justify-items-between"
                                    >
                                       <small>change category</small>  
                                       <i class="fa fa-angle-down"></i>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink class="bg-red-400"> Very High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-pink-300"> High Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-200"> Medium Risk</DropdownLink>
                                <DropdownLink class="-mt-6 bg-red-100"> Low Risk</DropdownLink>
                                <DropdownLink class="-mt-6 -mb-6 bg-pink-100"> Very Low Risk</DropdownLink> 
                                <!-- :href="route('...')" -->

                            </template>
                        </Dropdown>
                    </div>
                </td>
                <td class="px-3 py-2 whitespace-nowrap">10%</td>
                <td class="px-3 py-2 whitespace-nowrap">0%</td>
          
              </tr>
            </tbody>
            
          </table>
          <div class="mx-auto w-max">
            <button
                type="button"
                class="mx-4 inline-block rounded bg-primary px-2 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                cancel
                <i class="fa fa-cancel"></i>
            </button>
            <button
                type="button"
                class="mx-5 inline-block rounded bg-primary px-2 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                Edit
                <i class="fa fa-pencil"></i>
            </button>
            <button
                type="button"
                class="mx-4 inline-block rounded bg-primary px-2 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                Save
                <i class="fa fa-save"></i>
             </button>
        </div>
        </div>
      </div>
    </div>
    <div class="mt-4 lg:w-1/2">
        <h4 class="ml-6 font-bold text-black">Factors vs Weights</h4>
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-3 lg:-mx-4">
              <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                  <table
                    class="min-w-full text-sm font-light text-center border dark:border-neutral-500">
                    <thead class="font-medium border-b dark:border-neutral-500">
                      <tr>
                        <th
                          scope="col"
                          class="px-3 py-3 border-r dark:border-neutral-500">
                          Factors
                        </th>
                        <th
                          scope="col"
                          class="px-3 py-3 border-r dark:border-neutral-500">
                          Weight
                        </th>
                       
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="border-b dark:border-neutral-500">
                        <td
                          class="px-3 py-3 font-medium border-r whitespace-nowrap dark:border-neutral-500">
                          UI Rating
                        </td>
                        <td
                          class="px-3 py-3 border-r whitespace-nowrap dark:border-neutral-500">
                          10.0%
                        </td>
                      </tr>
                      
                    </tbody>
                  </table>
                  <div class="mx-auto w-max">
                    <button
                        type="button"
                        class="mx-4 inline-block rounded bg-primary px-2 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                        Add New
                        <i class="fa fa-add"></i>
                    </button>
                    <button
                        type="button"
                        class="mx-5 inline-block rounded bg-primary px-2 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                        Cancel
                        <i class="fa fa-cancel"></i>
                    </button>
                    <button
                        type="button"
                        class="mx-4 inline-block rounded bg-primary px-2 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                        Edit
                        <i class="fa fa-pencil"></i>
                     </button>
                     <button
                        type="button"
                        class="mx-4 inline-block rounded bg-primary px-2 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                        Save
                        <i class="fa fa-save"></i>
                  </button>
                </div>
                </div>
              </div>
            </div>
          </div>
    </div>
  </div>
  <div class="mt-4 lg:w-1/2">
    <h4 class="ml-6 font-bold text-black">Rating Scale</h4>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-3 lg:-mx-4">
          <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
              <table
                class="min-w-full text-sm font-light text-center border dark:border-neutral-500">
                <thead class="font-medium border-b dark:border-neutral-500">
                  <tr>
                    <th
                      scope="col"
                      class="px-3 py-3 border-r dark:border-neutral-500">
                      Factors
                    </th>
                    <th
                      scope="col"
                      class="px-3 py-3 border-r dark:border-neutral-500">
                      Rating
                    </th>
                   
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-b dark:border-neutral-500">
                    <td
                      class="px-3 py-3 font-medium border-r whitespace-nowrap dark:border-neutral-500">
                      75% to 100%
                    </td>
                    <td
                      class="px-3 py-3 border-r whitespace-nowrap dark:border-neutral-500">
                      High Risk
                    </td>
                  </tr>
                  
                </tbody>
              </table>
              <div class="mx-auto w-max">
                <button
                    type="button"
                    class="mx-4 inline-block rounded bg-primary px-2 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                    Add New
                    <i class="fa fa-add"></i>
                </button>
                <button
                    type="button"
                    class="mx-5 inline-block rounded bg-primary px-2 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                    Cancel
                    <i class="fa fa-cancel"></i>
                </button>
                <button
                    type="button"
                    class="mx-4 inline-block rounded bg-primary px-2 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                    Edit
                    <i class="fa fa-pencil"></i>
                 </button>
                 <button
                    type="button"
                    class="mx-4 inline-block rounded bg-primary px-2 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                    Save
                    <i class="fa fa-save"></i>
              </button>
            </div>
            </div>
          </div>
        </div>
      </div>
</div>


<!-- later -->
    <div>
            <div class="block block-rounded" style="height: 100vh;">
        <!-- hello -->
               <div class="pt-20 pb-3 bg-lightBlue-500" style="">
                  <div class="w-full px-1 mx-auto lg:px-4 md:px-6">
                     <div>
                        <div class="flex flex-wrap">
                           <div class="w-full lg:w-6/12 xl:w-4/12">
                              <div class="relative flex flex-col min-w-0 mb-3 break-words bg-yellow-100 rounded-lg shadow-lg xl:mb-0">
                                 <div class="flex-auto p-4">
                                    <div class="flex flex-wrap">
                                       <div class="relative flex-1 flex-grow w-full max-w-full pr-4">
                                          <h5 class="text-xs font-bold uppercase text-blueGray-400">Organization Domains</h5>
                                          <span class="text-xl font-bold">3</span>
                                       </div>
                                       <div class="relative flex-initial w-auto pl-4">
                                          <div class="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white bg-red-500 rounded-full shadow-lg"><i class="far fa-chart-bar"></i></div>
                                       </div>
                                    </div>
                                    <p class="mt-4 text-sm text-blueGray-500"><span class="mr-2 text-emerald-500"></span><span class="whitespace-nowrap">view domains</span></p>
                                 </div>
                              </div>
                           </div>
                             <div class="w-full lg:w-6/12 xl:w-4/12">
                              <div class="relative flex flex-col min-w-0 mb-3 break-words bg-pink-100 rounded-lg shadow-lg xl:mb-0">
                                 <div class="flex-auto p-4">
                                    <div class="flex flex-wrap">
                                       <div class="relative flex-1 flex-grow w-full max-w-full pr-4">
                                          <h5 class="text-xs font-bold uppercase text-blueGray-400">Organization Domains</h5>
                                          <span class="text-xl font-bold">3</span>
                                       </div>
                                       <div class="relative flex-initial w-auto pl-4">
                                          <div class="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white bg-red-500 rounded-full shadow-lg"><i class="far fa-chart-bar"></i></div>
                                       </div>
                                    </div>
                                    <p class="mt-4 text-sm text-blueGray-500"><span class="mr-2 text-emerald-500"></span><span class="whitespace-nowrap">view domains</span></p>
                                 </div>
                              </div>
                           </div>
                           <div class="w-full lg:w-6/12 xl:w-4/12">
                              <div class="relative flex flex-col min-w-0 mb-3 break-words bg-black rounded-lg shadow-lg xl:mb-0">
                                 <div class="flex-auto p-4">
                                    <div class="flex flex-wrap">
                                       <div class="relative flex-1 flex-grow w-full max-w-full pr-4">
                                          <h5 class="text-xs font-bold uppercase text-blueGray-400">new Domains found for you.</h5>
                                          <span class="text-xl font-bold">23</span>
                                       </div>
                                       <div class="relative flex-initial w-auto pl-4">
                                          <div class="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white bg-orange-500 rounded-full shadow-lg"><i class="fas fa-chart-pie"></i></div>
                                       </div>
                                    </div>
                                    <p class="mt-4 text-sm text-blueGray-500"><span class="mr-2 text-red-500"><i class="fas fa-arrow-up"></i> 3.48%</span><span class="whitespace-nowrap">Since last week</span></p>
                                 </div>
                              </div>
                           </div>
                           <div class="w-full lg:w-12/12 xl:w-12/12">
                              <div class="relative flex flex-col min-w-0 mb-3 break-words bg-green-100 rounded-lg shadow-lg xl:mb-0">
                                 <div class="flex-auto p-4">
                                    <div class="flex flex-wrap">
                                       <div class="relative flex-1 flex-grow w-full max-w-full pr-4">
                                          <h5 class="text-xs font-bold uppercase text-blueGray-400">...</h5>
                                          <span class="text-xl font-bold">...</span>
                                       </div>
                                       <div class="relative flex-initial w-auto pl-4">
                                          <div class="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white bg-pink-500 rounded-full shadow-lg"><i class="fas fa-users"></i></div>
                                       </div>
                                    </div>
                                    <p class="mt-4 text-sm text-blueGray-500"><span class="mr-2 text-orange-500"> ...</span><span class="whitespace-nowrap">...</span></p>
                                 </div>
                              </div>
                           </div>
                          
                        </div>
                     </div>
                  </div>
               </div>
               <!-- <div class="w-full px-4 mx-auto -mt-24 md:px-6"> -->
                   <div class="flex flex-wrap">
                     <div class="w-full px-1 lg:px-4 xl:w-8/12">
                        <div class="relative flex flex-col w-full min-w-0 mb-3 break-words bg-yellow-100 rounded-lg shadow-sm">
                           <div class="px-4 py-3 mb-0 bg-transparent rounded-t">
                              <div class="flex flex-wrap items-center">
                                 <div class="relative flex-1 flex-grow w-full max-w-full">
                                    <h6 class="mb-1 text-xs font-semibold uppercase text-blueGray-200">Domains</h6>
                                    <h2 class="text-xl font-semibold text-red-500">domain statistics</h2>
                                 </div>
                              </div>
                           </div>
                           <div class="flex-auto p-4">
                              <div class="relative h-350-px">
                                 <!-- <canvas id="myChart"></canvas> -->
                                   <div id="curve_chart" style="width: 900px; height: 500px"></div>
                                 <!-- <canvas width="221" height="291" style="display: block; box-sizing: border-box; height: 350px; width: 265.7px;" id="bar-chart"></canvas> -->
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- <canvas id="myChart"></canvas> -->
                     <div class="w-full px-1 lg:px-4 xl:w-4/12">
                        <div class="relative flex flex-col w-full min-w-0 mb-3 break-words bg-black rounded-lg shadow-sm">
                           <div class="px-4 py-3 mb-0 bg-transparent rounded-t">
                              <div class="flex flex-wrap items-center">
                                 <div class="relative flex-1 flex-grow w-full max-w-full">
                                    <h6 class="mb-1 text-xs font-semibold uppercase text-blueGray-500">Graph here</h6>
                                    <h2 class="text-xl font-semibold text-blueGray-800">---</h2>
                                 </div>
                              </div>
                           </div>
                           <div class="flex-auto p-4">
                              <div class="relative h-350-px">
                                 <canvas id="myChart"></canvas><!-- <canvas width="221" height="291" style="display: block; box-sizing: border-box; height: 350px; width: 265.7px;" id="bar-chart"></canvas> -->
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                    </div>
        </div>
        </div> 
<!--user-->
    <div class=" row" v-if="$page.props.auth.user.role_id == 2" style="height: 100vh;">
        <div class="mx-auto ">
            <h1 class="font-extrabold text-center flicker mt-9 h1"><span class="text-yellow-300">spoo</span>fix dashboard</h1>
            <h1 class="text-center h3 seesaw"> coming soon!</h1>
        </div>
    </div>
    </AuthenticatedLayout>
</template>
<style>
@keyframes flickerAnimation {
    0%, 18%, 22%, 25%, 53%, 57%, 100% {
        opacity: 1;
    }

    20%, 24%, 55% {
        opacity: 0.2;
        color: #facd5b;
    }
}

.flicker {
    animation: flickerAnimation 7s infinite;
}
@keyframes seesawAnimation {
    0%, 100% {
        transform: rotate(-15deg);
    }

    50% {
        transform: rotate(15deg);
    }
}

.seesaw {
    transform-origin: top center;
    animation: seesawAnimation 3s infinite;
}



</style>
    <!-- </div>
    </AuthenticatedLayout>
</template> -->
