<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// import { Head } from '@inertiajs/vue3';
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
<table class="min-w-full border-collapse block md:table">
    <thead class="block md:table-header-group">
        <tr class="border border-grey-500 md:border-none block md:table-row absolute -top-full md:top-auto -left-full md:left-auto  md:relative ">
            <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">Name</th>
            <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">User Name</th>
            <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">Email Address</th>
            <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">Mobile</th>
            <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">Actions</th>
        </tr>
    </thead>
    <tbody class="block md:table-row-group">
        <tr class="bg-gray-300 border border-grey-500 md:border-none block md:table-row">
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Name</span>Jamal Rios</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">User Name</span>jrios1</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Email Address</span>jrios@icloud.com</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Mobile</span>582-3X2-6233</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                <span class="inline-block w-1/3 md:hidden font-bold">Actions</span>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 border border-blue-500 rounded">Edit</button>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 border border-red-500 rounded">Delete</button>
            </td>
        </tr>
        <tr class="bg-white border border-grey-500 md:border-none block md:table-row">
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Name</span>Erwin Campbell</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">User Name</span>ecampbell088</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Email Address</span>ecampbell088@hotmail.com</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Mobile</span>318-685-X414</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                <span class="inline-block w-1/3 md:hidden font-bold">Actions</span>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 border border-blue-500 rounded">Edit</button>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 border border-red-500 rounded">Delete</button>
            </td>
        </tr>
        <tr class="bg-gray-300 border border-grey-500 md:border-none block md:table-row">
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Name</span>Lillie Clark</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">User Name</span>lillie</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Email Address</span>lillie.clark@gmail.com</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Mobile</span>505-644-84X4</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                <span class="inline-block w-1/3 md:hidden font-bold">Actions</span>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 border border-blue-500 rounded">Edit</button>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 border border-red-500 rounded">Delete</button>
            </td>
        </tr>
        <tr class="bg-white border border-grey-500 md:border-none block md:table-row">
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Name</span>Maribel Koch</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">User Name</span>maribelkoch</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Email Address</span>mkoch@yahoo.com</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Mobile</span>582-400-3X36</td>
            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                <span class="inline-block w-1/3 md:hidden font-bold">Actions</span>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 border border-blue-500 rounded">Edit</button>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 border border-red-500 rounded">Delete</button>
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
<!--                        <div class="block-header block-header-default">-->
<!--                            <h3 class="block-title">Domain List</h3>-->
<!--                            <div class="block-options">-->
<!--                            </div>-->
<!--                        </div>-->
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

        <section class="container px-4 mx-auto">
            <a  class="mb-2 mr-3 btn btn-primary text-capitalize"   data-bs-toggle="modal" data-bs-target="#modal-normal">Add Domain</a>
            <div class="flex flex-col">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center gap-x-3">
                                                    <span>Number</span>
                                            </div>
                                        </th>
        
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            Domains
                                        </th>
        
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            Status
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
                                    <tr v-for="(item, index) in domain" :key="index" :style="{ background: index % 2 === 0 ? 'white' : 'rgba(25, 0, 0, 0.1)'}">
                                        <td class="px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                            <div class="inline-flex items-center gap-x-3">
                                           1.
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                            facebook.com
                                        </td>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                            <div class="inline-flex items-center px-3 py-1 rounded-full gap-x-2 text-emerald-500 bg-emerald-100/60 dark:bg-gray-800">
                                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 3L4.5 8.5L2 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
        
                                                <h2 class="text-sm font-normal">Ready</h2>
                                            </div>
                                        </td>
                                        
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                            view
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div class="flex items-center gap-x-6">
                                                <button class="text-gray-500 transition-colors duration-200 dark:hover:text-indigo-500 dark:text-gray-300 hover:text-indigo-500 focus:outline-none">
                                                    Re-scan
                                                </button>
        
                                                <button class="text-red-600 transition-colors duration-200 hover:text-red-500 focus:outline-none">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
        
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                            <div class="inline-flex items-center gap-x-3">
                                               2.
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                            google.com
                                        </td>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                            <div class="inline-flex items-center px-3 py-1 text-red-500 rounded-full gap-x-2 bg-red-100/60 dark:bg-gray-800">
                                               
        
                                                <h2 class="text-sm font-normal">Processing</h2>
                                            </div>
                                        </td>
                                        
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                           view
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div class="flex items-center gap-x-6">
                                                <button class="text-gray-500 transition-colors duration-200 dark:hover:text-indigo-500 dark:text-gray-300 hover:text-indigo-500 focus:outline-none">
                                                    Rescan
                                                </button>
        
                                                <button class="text-red-600 transition-colors duration-200 hover:text-red-500 focus:outline-none">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
        
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                            <div class="inline-flex items-center gap-x-3">
                                               
        
                                                3
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                           amazon.com
                                        </td>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                            <div class="inline-flex items-center px-3 py-1 rounded-full gap-x-2 text-emerald-500 bg-emerald-100/60 dark:bg-gray-800">
                                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 3L4.5 8.5L2 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
        
                                                <h2 class="text-sm font-normal">Ready</h2>
                                            </div>
                                        </td>
                                      
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                           view
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div class="flex items-center gap-x-6">
                                                <button class="text-gray-500 transition-colors duration-200 dark:hover:text-indigo-500 dark:text-gray-300 hover:text-indigo-500 focus:outline-none">
                                                    rescan
                                                </button>
        
                                                <button class="text-red-600 transition-colors duration-200 hover:text-red-500 focus:outline-none">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
        
               
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                            <div class="inline-flex items-center gap-x-3">
                                              
        
                                                4
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Jan 4, 2022</td>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                            <div class="inline-flex items-center px-3 py-1 text-gray-500 rounded-full gap-x-2 bg-gray-100/60 dark:bg-gray-800">
                                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4.5 7L2 4.5M2 4.5L4.5 2M2 4.5H8C8.53043 4.5 9.03914 4.71071 9.41421 5.08579C9.78929 5.46086 10 5.96957 10 6.5V10" stroke="#667085" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
        
                                                <h2 class="text-sm font-normal">Re-scan</h2>
                                            </div>
                                        </td>
                                       
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">Monthly subscription</td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div class="flex items-center gap-x-6">
                                                <button class="text-gray-500 transition-colors duration-200 dark:hover:text-indigo-500 dark:text-gray-300 hover:text-indigo-500 focus:outline-none">
                                                    rescan
                                                </button>
        
                                                <button class="text-red-600 transition-colors duration-200 hover:text-red-500 focus:outline-none">
                                                    Delete
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
        </section>
                
                    <!-- <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full text-sm font-light text-left">
                                    <thead class="font-medium border-b dark:border-neutral-500">
                                      <tr>
                                        <th scope="col" class="px-6 py-4">#</th>
                                        <th scope="col" class="px-6 py-4">Your domain</th>
                                        <th scope="col" class="px-6 py-4">View</th>
                                        <th scope="col" class="px-6 py-4">Status</th>
                                        <th scope="col" class="px-6 py-4">Re-scan</th>
                                        <th scope="col" class="px-6 py-4">Delete</th>
                                      
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr  class="border-b dark:border-neutral-500" v-for="(item, index) in domain" :key="index" :style="{ background: index % 2 === 0 ? 'white' : 'rgba(25, 0, 0, 0.1)'}">
                                        <td class="px-6 py-4 font-medium whitespace-nowrap">1</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                      
                                      </tr>
                                      <tr class="border-b dark:border-neutral-500">
                                        <td class="px-6 py-4 font-medium whitespace-nowrap ">2</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>

                                      </tr>
                                      <tr class="border-b ">
                                        <td class="px-6 py-4 font-medium whitespace-nowrap ">3</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Cell</td>
                                     
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>-->
                    </div>
                </div>
            </div>
 

            <!-- Row #4 -->
<!--            <div class="col-md-4" >-->
<!--                <div class="block block-rounded block-transparent bg-primary">-->
<!--                <div class="block-content block-content-full">-->
<!--                    <div class="py-3 text-center">-->
<!--                    <div class="mb-3">-->
<!--                        <i class="fa fa-users fa-4x text-primary-lighter"></i>-->
<!--                    </div>-->
<!--                    <div class="text-white fs-4 fw-semibold">Total User</div>-->
<!--                    <div class="text-white-75">All registered user</div>-->
<!--                    <div class="pt-3">-->
<!--                        <a class="btn btn-alt-primary" href="javascript:void(0)">-->
<!--                        <i class="opacity-50 fa fa-users me-1"></i> View Users-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4">-->
<!--                <div class="block block-rounded block-transparent bg-info">-->
<!--                <div class="block-content block-content-full">-->
<!--                    <div class="py-3 text-center">-->
<!--                    <div class="mb-3">-->
<!--                        <i class="fa fa-envelope-open fa-4x text-info-light"></i>-->
<!--                    </div>-->
<!--                    <div class="text-white fs-4 fw-semibold">New Domain</div>-->
<!--                    <div class="text-white-75">Total domain From last Search</div>-->
<!--                    <div class="pt-3">-->
<!--                        <a class="btn btn-alt-info" href="javascript:void(0)">-->
<!--                        <i class="opacity-50 fa fa-envelope-open me-1"></i> View Domain-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-4">-->
<!--                <div class="block block-rounded block-transparent bg-success">-->
<!--                <div class="block-content block-content-full">-->
<!--                    <div class="py-3 text-center">-->
<!--                    <div class="mb-3">-->
<!--                        <i class="fa fa-check fa-4x text-success-light"></i>-->
<!--                    </div>-->
<!--                    <div class="text-white fs-4 fw-semibold">Total Domain</div>-->
<!--                    <div class=" text-white-75">All Domain</div>-->
<!--                    <div class="pt-3">-->
<!--                        <a class="btn btn-alt-success" href="javascript:void(0)">-->
<!--                        <i class="opacity-50 fa fa-arrow-up me-1"></i> List Domain-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                </div>-->
<!--            </div>-->
            <!-- END Row #4 -->



    </AuthenticatedLayout>
</template>
