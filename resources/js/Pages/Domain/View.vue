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
                    <!-- <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      screenshot rating
                    </th>
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      contenthtml rating
                    </th>
                    
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      whois server
                    </th>
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      update_date
                    </th>
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      expiration_date
                    </th>
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      name servers
                    </th>
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      abuse email' (s)
                    </th>
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      name
                    </th>
                    org
                  </tr>
                  <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                   address
                  </th><th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                    city
                  </th><th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                    state
                  </th>
                  <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                    registrant_postal_code
                  </th>
                  <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      domainsimilarityrate
                    </th>
                    <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                      csscolor
                    </th> -->
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
                    <!-- <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.phashes }}
                    </td>  <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.htmls }}
                    </td>  <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.whois_server }}
                    </td>  <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.name_servers }}
                    </td>  <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.emails}}
                    </td>  <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.name }}
                    </td>  <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.org}}
                    </td>  <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.address }}
                    </td>  <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.city }}
                    </td>  <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.state}}
                    </td>  <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.registrant_postal_code }}
                    </td>  <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.domainsimilarityrate }}
                    </td>  <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                      {{ spoofData.csscolor}}
                    </td>  -->
                  </tr>
                
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="w-full px-4 xl:w-8/12">    
          <img class="w-full" :src="spoofData.screenshot" alt="...">    
          <!-- <img class="w-full" src="../../../../screenshots/yieldexchange/1689759800/d0e16b1c_yieldexch.ange.ca.png" alt="..." > -->
          <!-- <img class="w-full" src="../../../../../../../../home/kiromo/Desktop/Domain/public/assets/screenshots/yieldexchange.ca/1689763307/ee73106b_yielde.xchange.ca.png" alt="..."> -->
     </div>
   
    </AuthenticatedLayout>
</template>
