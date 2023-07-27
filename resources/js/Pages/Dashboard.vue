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

        </div> 

<!--            user-->
            <div class="row" v-if="$page.props.auth.user.role_id == 2">
                <div>
                    <div class="block block-rounded">

       
        <!-- hello -->
        
               <div class="relative pt-20 pb-3 bg-lightBlue-500">
                  <div class="w-full px-4 mx-auto md:px-6">
                     <div>
                        <div class="flex flex-wrap">
                           <div class="w-full px-4 lg:w-6/12 xl:w-4/12">
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
                           <div class="w-full px-4 lg:w-6/12 xl:w-4/12">
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
                           <div class="w-full px-4 lg:w-6/12 xl:w-4/12">
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
                  

     
                
                   
                    </div>
                </div>
            </div>
 

            <!-- Row #4 -->

            <!-- END Row #4 -->


          
    </AuthenticatedLayout>
</template>
