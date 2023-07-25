<script setup >
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import {Link} from "@inertiajs/vue3"
import { defineComponent, onMounted } from 'vue';
import Chart from 'chart.js/auto';





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

// const ctx = document.getElementById('myChart');

// onMounted(() => {
// new Chart(ctx, {
//   type: 'bar',
//   data: {
//     labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
//     datasets: [{
//       label: '# of Votes',
//       data: [12, 19, 3, 5, 2, 3],
//       borderWidth: 1
//     }]
//   },
//   options: {
//     scales: {
//       y: {
//         beginAtZero: true
//       }
//     }
//   }
// });
   
// })




// window.onload = function () {

// //Better to construct options first and then pass it as a parameter
// var options = {
// 	title: {
// 		text: "Column Chart in jQuery CanvasJS"              
// 	},
// 	data: [              
// 	{
// 		// Change type to "doughnut", "line", "splineArea", etc.
// 		type: "column",
// 		dataPoints: [
// 			{ label: "apple",  y: 10  },
// 			{ label: "orange", y: 15  },
// 			{ label: "banana", y: 25  },
// 			{ label: "mango",  y: 30  },
// 			{ label: "grape",  y: 28  }
// 		]
// 	}
// 	]
// };

// $("#chartContainer").CanvasJSChart(options);
// }


</script>


<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <!-- <div class="block rounded bg-gd-dusk">
        <div class="block-content bg-white-5">
            <div class="text-center">
            <h1 class="text-white h2 fw-bold ">{{ ($page.props.auth.user.role_id == 1) ? "Admin Dashboard" : "User Dashboard" }}</h1>
            </div>
        </div>
        </div> -->

        <div class="row" v-if="$page.props.auth.user.role_id == 1">
<!--            admin content-->
<table class="block min-w-full border-collapse md:table">
    <thead class="block md:table-header-group">
        <tr class="absolute block border border-grey-500 md:border-none md:table-row -top-full md:top-auto -left-full md:left-auto md:relative ">
            <th class="block p-2 font-bold text-left text-white bg-gray-600 md:border md:border-grey-500 md:table-cell">Name</th>
            <th class="block p-2 font-bold text-left text-white bg-gray-600 md:border md:border-grey-500 md:table-cell">User Name</th>
            <th class="block p-2 font-bold text-left text-white bg-gray-600 md:border md:border-grey-500 md:table-cell">Email Address</th>
            <th class="block p-2 font-bold text-left text-white bg-gray-600 md:border md:border-grey-500 md:table-cell">Mobile</th>
            <th class="block p-2 font-bold text-left text-white bg-gray-600 md:border md:border-grey-500 md:table-cell">Actions</th>
        </tr>
    </thead>
    <tbody class="block md:table-row-group">
        <tr class="block bg-gray-300 border border-grey-500 md:border-none md:table-row">
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">Name</span>Jamal Rios</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">User Name</span>jrios1</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">Email Address</span>jrios@icloud.com</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">Mobile</span>582-3X2-6233</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell">
                <span class="inline-block w-1/3 font-bold md:hidden">Actions</span>
                <button class="px-2 py-1 font-bold text-white bg-blue-500 border border-blue-500 rounded hover:bg-blue-700">Edit</button>
                <button class="px-2 py-1 font-bold text-white bg-red-500 border border-red-500 rounded hover:bg-red-700">Delete</button>
            </td>
        </tr>
        <tr class="block bg-white border border-grey-500 md:border-none md:table-row">
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">Name</span>Erwin Campbell</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">User Name</span>ecampbell088</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">Email Address</span>ecampbell088@hotmail.com</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">Mobile</span>318-685-X414</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell">
                <span class="inline-block w-1/3 font-bold md:hidden">Actions</span>
                <button class="px-2 py-1 font-bold text-white bg-blue-500 border border-blue-500 rounded hover:bg-blue-700">Edit</button>
                <button class="px-2 py-1 font-bold text-white bg-red-500 border border-red-500 rounded hover:bg-red-700">Delete</button>
            </td>
        </tr>
        <tr class="block bg-gray-300 border border-grey-500 md:border-none md:table-row">
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">Name</span>Lillie Clark</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">User Name</span>lillie</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">Email Address</span>lillie.clark@gmail.com</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">Mobile</span>505-644-84X4</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell">
                <span class="inline-block w-1/3 font-bold md:hidden">Actions</span>
                <button class="px-2 py-1 font-bold text-white bg-blue-500 border border-blue-500 rounded hover:bg-blue-700">Edit</button>
                <button class="px-2 py-1 font-bold text-white bg-red-500 border border-red-500 rounded hover:bg-red-700">Delete</button>
            </td>
        </tr>
        <tr class="block bg-white border border-grey-500 md:border-none md:table-row">
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">Name</span>Maribel Koch</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">User Name</span>maribelkoch</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">Email Address</span>mkoch@yahoo.com</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell"><span class="inline-block w-1/3 font-bold md:hidden">Mobile</span>582-400-3X36</td>
            <td class="block p-2 text-left md:border md:border-grey-500 md:table-cell">
                <span class="inline-block w-1/3 font-bold md:hidden">Actions</span>
                <button class="px-2 py-1 font-bold text-white bg-blue-500 border border-blue-500 rounded hover:bg-blue-700">Edit</button>
                <button class="px-2 py-1 font-bold text-white bg-red-500 border border-red-500 rounded hover:bg-red-700">Delete</button>
            </td>
        </tr>			
    </tbody>
</table>


            <!-- <div>
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Users List</h3>
                        <div class="block-options">
                        </div>
                    </div>
                    <div class="block-content">
                        <table class="table table-center">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"></th>
                                <th>User</th>
                                <th>Organization</th>
                                <th>Last seen</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in domain" :key="index" :style="{ background: index % 2 === 0 ? 'white' : 'rgba(25, 0, 0, 0.1)'}">
                                <th class="text-center" scope="row">{{ ++index }}</th>
                                <td>{{ item }}" hello"</td>
                                <td>{{ item }}</td>
                                <td>{{ item }}</td>
                                <td><div class="relative inline-block">
                                    <select class="block w-full px-4 py-2 pr-8 leading-tight bg-white border border-gray-300 rounded shadow appearance-none hover:border-gray-400 focus:outline-none focus:border-blue-500 focus:shadow-outline">
                                        <option>view profile</option>
                                        <option>view domains</option>
                                        <option>view user</option>

                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 pointer-events-none">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M10 12l-6-6-1.414 1.414L10 14.828l7.414-7.414L16 6z"/>
                                        </svg>
                                    </div>
                                </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>-->
        </div> 

<!--            user-->
            <div class="row" v-if="$page.props.auth.user.role_id == 2">
                <div>
                    <div class="block block-rounded">

          <!-- notification i will use later -->
                             
          <div class="modal" id="modal-normal" tabindex="-1" role="dialog" aria-labelledby="modal-normal" aria-hidden="true"> 
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="block mb-0 shadow-none block-rounded">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Add Domain</h3>
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
                                            <label class="form-label">Domain</label>
                                            <input type="text" class="form-control form-control-lg"  v-model="form.name" placeholder="Enter domain to add..">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <button class="btn btn-primary btn-primary-custom" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        <span class="spinner-border text-dark " v-if="form.processing"></span>
                                        <i class="opacity-50 fa fa-check me-1"></i>Add Domain
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div> 
        </div>

        <!-- hello -->
        
               <div class="relative pt-20 pb-3 bg-lightBlue-500">
                  <div class="w-full px-4 mx-auto md:px-6">
                     <div>
                        <div class="flex flex-wrap">
                           <div class="w-full px-4 lg:w-6/12 xl:w-3/12">
                              <div class="relative flex flex-col min-w-0 mb-3 break-words bg-white rounded-lg shadow-lg xl:mb-0">
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
                           <div class="w-full px-4 lg:w-6/12 xl:w-3/12">
                              <div class="relative flex flex-col min-w-0 mb-3 break-words bg-white rounded-lg shadow-lg xl:mb-0">
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
                           <div class="w-full px-4 lg:w-6/12 xl:w-3/12">
                              <div class="relative flex flex-col min-w-0 mb-3 break-words bg-white rounded-lg shadow-lg xl:mb-0">
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
                           <div class="w-full px-4 lg:w-6/12 xl:w-3/12">
                              <div class="relative flex flex-col min-w-0 mb-3 break-words bg-white rounded-lg shadow-lg xl:mb-0">
                                 <div class="flex-auto p-4">
                                    <div class="flex flex-wrap">
                                       <div class="relative flex-1 flex-grow w-full max-w-full pr-4">
                                          <h5 class="text-xs font-bold uppercase text-blueGray-400">Trend</h5>
                                          <span class="text-xl font-bold">10</span>
                                       </div>
                                       <div class="relative flex-initial w-auto pl-4">
                                          <div class="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white rounded-full shadow-lg bg-lightBlue-500"><i class="fas fa-percent"></i></div>
                                       </div>
                                    </div>
                                    <p class="mt-4 text-sm text-blueGray-500"><span class="mr-2 text-red-500"><i class="fas fa-arrow-up"></i> 12%</span><span class="whitespace-nowrap">Since last 24hrs</span></p>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- <div class="w-full px-4 mx-auto -mt-24 md:px-6"> -->
                   <div class="flex flex-wrap">
                     <div class="w-full px-4 xl:w-8/12">
                        <div class="relative flex flex-col w-full min-w-0 mb-8 break-words rounded-lg shadow-lg bg-blueGray-800">
                           <div class="px-4 py-3 mb-0 bg-transparent rounded-t">
                              <div class="flex flex-wrap items-center">
                                 <div class="relative flex-1 flex-grow w-full max-w-full">
                                    <h6 class="mb-1 text-xs font-semibold uppercase text-blueGray-200">Domains</h6>
                                    <h2 class="text-xl font-semibold text-white">Sales value</h2>
                                 </div>
                              </div>
                           </div>
                           <div class="flex-auto p-4">
                              <div class="relative h-350-px">
                                 <!-- <canvas id="myChart"></canvas> -->
                                 <canvas id="myChart"></canvas><!-- <canvas width="221" height="291" style="display: block; box-sizing: border-box; height: 350px; width: 265.7px;" id="bar-chart"></canvas> -->
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- <canvas id="myChart"></canvas> -->
                     <div class="w-full px-4 xl:w-4/12">
                        <div class="relative flex flex-col w-full min-w-0 mb-3 break-words bg-white rounded-lg shadow-lg">
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
                  

        <div class="px-4 mx-auto">
            <a  class="mb-2 mr-3 btn btn-primary text-capitalize hover:bounce"   data-bs-toggle="modal" data-bs-target="#modal-normal">Add Domain</a>
            <div class="flex flex-col">
                <div class="-mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center gap-x-3">
                                                    <!-- <span>Number</span> -->
                                            </div>
                                        </th>
        
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            Domains
                                        </th>
        
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            Status
                                        </th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            Last_scanned
                                        </th>
        
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            view
                                        </th>
        
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            Reload
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                                    <tr v-for="(domain, index) in domainList" :key="index" :style="{ background: index % 2 !== 0 ? 'white' : 'rgba(245, 245, 241)'}" class="transition-colors duration-300 hover:!bg-gray-300">
                                        <td class="px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                            <div class="inline-flex items-center gap-x-3">
                                           {{  index + 1 }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                            {{ domain.domain_name }}
                                        </td>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                            <div class="inline-flex items-center px-3 py-1 rounded-full gap-x-2 text-emerald-500 bg-emerald-100/60 dark:bg-gray-800">
                                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 3L4.5 8.5L2 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
        
                                                <h2 class="text-sm font-normal">{{ domain.status }}</h2>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap" >
                                            {{ domain.formated_updated_at }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                            <Link  :href="'/spoof/'+domain.id" class="px-1 py-1 mb-1 mr-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-blue-400 rounded outline-none active:fa-bounce hover:bg-blue-700 hover:text-black focus:outline-none visited:bg-green-500">
                                                <span class="" type="button"><i class="fa fa-eye "></i> View</span>
                                            </Link> 
                                           
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div class="flex items-center gap-x-6">
                                                <Link class="text-gray-500 transition-colors duration-200 dark:hover:text-indigo-500 dark:text-gray-300 hover:text-indigo-500 focus:outline-none" :href="'/rescan-domain/'+domain.id">
                                                    Re-scan
                                                </Link>
        
                                                <button class="text-gray-400 transition-colors duration-200 hover:text-red-500 focus:outline-none hover:fa-bounce">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="flex items-center justify-between mt-6">
                <a href="#" class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                    </svg>
        
                    <span>
                        previous
                    </span>
                </a>
        
                <div class="items-center hidden md:flex gap-x-3">
                    <a href="#" class="px-2 py-1 text-sm text-blue-500 rounded-md dark:bg-gray-800 bg-blue-100/60">1</a>
                    <a href="#" class="px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">...</a>
                    <a href="#" class="px-2 py-1 text-sm text-gray-500 rounded-md dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100">3</a>
                </div>
        
                <a href="#" class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
                    <span>
                        Next
                    </span>
        
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
                
                   
                    </div>
                </div>
            </div>
 

            <!-- Row #4 -->

            <!-- END Row #4 -->


          
    </AuthenticatedLayout>
</template>
