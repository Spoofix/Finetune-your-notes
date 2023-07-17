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
    domain: {
        type: Object,
    },
    details: {
        type: Object,
    },
    spoofData: {
        type: Object,
    },
});


</script>

<template>
    <Head title="Domain" />

    <AuthenticatedLayout>
<div class="relative mt-6 d-flex">
  <div class="text-3xl ml-9">
    {{ spoofData.spoofed_domain }}
  </div>
  <div class="absolute right-0 mt-2"><Link class="px-3 py-1 mb-1 mr-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-indigo-500 rounded outline-none active:bg-indigo-600 focus:outline-none" type="button" :href="'/spoof/'+spoofData.domain_id"><i class="fa fa-arrow-left"></i> Back</Link></div>
</div>
    <div class="flex flex-col mt-1">
        <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
          <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
              <table class="min-w-full">
                <thead class="bg-gray-200 border-b">
                  <tr class="mt-2">
            
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      id
                    </th>
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      registrar
                    </th>
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      registrationDate
                    </th>
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      risk
                    </th>
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      country
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="transition duration-300 ease-in-out bg-white border-b hover:bg-gray-100" >
             
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ spoofData.id }}</td>
                    <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.registrar }} 
                    </td>
                    <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.registrationDate }}
                    </td>
                    <td class="px-6 py-4 text-sm font-light whitespace-nowrap" :style="{
                      color: spoofData.rating == 'high' ? 'red' :
                             spoofData.rating == 'critical' ? 'orange' :
                             spoofData.rating == 'medium' ? '#8B8000' :
                             spoofData.rating == 'none' ? 'blue' :
                             spoofData.rating == 'low' ? 'green' :
                             'gray'
                    }">
                      {{ spoofData.rating }}
                    </td>
                    <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.country }}
                    </td>
                  </tr>
                
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="w-full px-4 xl:w-8/12">    
          <img class="w-full" :src="spoofData.screenshot" alt="...">     
     </div>

    </AuthenticatedLayout>
</template>
