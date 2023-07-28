<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import {Link} from "@inertiajs/vue3"
import { defineComponent, onMounted } from 'vue';


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
    domainList: {
        type:Object,
    }
    
});

const form = useForm({
    domains: null,
});

function submit() {
    if (form.domains == null) {
        Toast.fire({
            icon: 'error',
            title: 'Domain Is Required'
        })
        return;
    }

    form.post(route('add_domain'), {
        onFinish: (response) => {
            Toast.fire({
                icon: 'success',
                title: 'Domain Added Successfully'
            })

            console.log(response);
            //  search()

        },
        onSuccess: () => { },
    });

}

// function cancel(id) {
//     Swal.fire({
//         title: 'Do you want to disable this organization?',
//         showDenyButton: true,
//         confirmButtonText: 'Yes',
//         denyButtonText: "No"
//     }).then((result) => {
//         /* Read more about isConfirmed, isDenied below */
//         if (result.isConfirmed) {
//             const cancel_form = useForm({
//                 org_id: id,
//             });
//             cancel_form.post(route('organization.cancel'), {
//                 onFinish: (response) => {
//                     Toast.fire({
//                         icon: 'success',
//                         title: 'Domain Updated Successfully'
//                     })
//                 },
//                 onSuccess: () => { },
//             });
//         } else if (result.isDenied) {
//             // Swal.fire('Changes are not saved', '', 'info')
//         }
//     })
// }

// function activate(id) {

//     Swal.fire({
//         title: 'Do you want to disable this organization?',
//         showDenyButton: true,
//         confirmButtonText: 'Yes',
//         denyButtonText: "No"
//     }).then((result) => {
//         /* Read more about isConfirmed, isDenied below */
//         if (result.isConfirmed) {
//             const activate_form = useForm({
//                 org_id: id,
//             });
//             activate_form.post(route('organization.activate'), {
//                 onFinish: (response) => {
//                     Toast.fire({
//                         icon: 'success',
//                         title: 'Organization Updated Successfully'
//                     })
//                 },
//                 onSuccess: () => { },
//             });
//         } else if (result.isDenied) {
//             // Swal.fire('Changes are not saved', '', 'info')
//         }
//     })

// } 
</script>

<template>
    <Head title="Domains" />

    <AuthenticatedLayout>
        <div class="px-4 mx-auto mt-6">
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
                                            <input type="text" class="form-control form-control-lg"  v-model="form.domains" placeholder="Enter domain to add..">
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
                                                <Link class="text-gray-500 transition-colors duration-200 dark:hover:text-indigo-500 dark:text-gray-300 hover:text-indigo-500 focus:outline-none"  v-if="domain.is_queued">
                                                    <div role="status">
                                                        <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                        </svg>
                                                        <span class="sr-only">Loading...</span>
                                                      </div>
                                                </Link>
                                                <Link class="text-gray-500 transition-colors duration-200 dark:hover:text-indigo-500 dark:text-gray-300 hover:text-indigo-500 focus:outline-none"  v-else :href="'/rescan-domain/'+domain.id">
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
    </AuthenticatedLayout>
</template>
