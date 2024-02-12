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
  <div class="text-3xl border-b-4 border-orange-400 ml-9">
    {{ spoofData.spoofed_domain }}
  </div>
  <div class="absolute right-0 mt-2"><Link class="px-3 py-2 mb-1 mr-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-indigo-500 rounded outline-none active:bg-indigo-600 focus:outline-none" type="button" :href="'/spoof/'+spoofData.domain_id"><i class="fa fa-arrow-left"></i> Back</Link></div>
</div>
<section class="-mt-16">
  <div class="flex lg:flex-row">
    <!-- info -->
    <div class="flex lg:flex-row">
      <!-- the info -->
      <div class="overflow-y-auto bg-white h-96" style="width: 970px; height: 600px;">
          <!-- heading -->
      <div class="ml-3 w-fit">
        <h3 class="font-normal h3 w-fit">Domain Info:</h3>
      </div>
      <div class="flex justify-around lg:flex-row ">
          <!-- part one -->
          <div >
            <!-- cards -->
            <div class="pl-1 " style="width: 450px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">Registrar:</h5>
              <p class="mt-3 ml-5">{{ spoofData.registrar }} </p>
            </div>
             <!-- cards -->
             <div class="pl-1 mt-3" style="width: 450px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black"> RegistrationDate:</h5>
              <p class="mt-3 ml-5">{{ spoofData.registrationDate }}</p>
            </div>
             <!-- cards -->
             <div class="pl-1 mt-3" style="width: 450px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black"> Country:</h5>
              <p class="mt-3 ml-5">{{ spoofData.country }}</p>
            </div>
             <!-- cards -->
             <div class="pl-1 mt-3" style="width: 450px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">UpdateDate:</h5>
              <p class="mt-3 ml-5">{{ spoofData.update_date }}</p>
            </div>
             <!-- cards -->
             <div class="pl-1 mt-3" style="width: 450px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">AbuseEmail:</h5>
              <p class="mt-3 ml-5"> {{ spoofData.emails}}</p>
            </div>
             <!-- cards -->
             <div class="pl-1 mt-3" style="width: 450px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">Name:</h5>
              <p class="mt-3 ml-5">{{ spoofData.name }}</p>
            </div>
             <!-- cards -->
             <div class="pl-1 mt-3" style="width: 450px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">Org:</h5>
              <p class="mt-3 ml-5">{{ spoofData.org}}</p>
            </div>
             <!-- cards -->
             <div class="pl-1 mt-3" style="width: 450px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">Address:</h5>
              <p class="mt-3 ml-5"> {{ spoofData.address }}</p>
            </div>
             <!-- cards -->
             <div class="pl-1 mt-3" style="width: 450px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">City:</h5>
              <p class="mt-3 ml-5"> {{ spoofData.city }}</p>
            </div>
             <!-- cards -->
             <div class="pl-1 mt-3" style="width: 450px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">CssColor:</h5>
              <p class="mt-3 ml-5">{{ spoofData.csscolor}}</p>
            </div>
          </div>
          <!-- part two -->
          <div>
            <!-- cards -->
            <div class="pr-3" style="width: 459px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">Rating:</h5>
              <p class="mt-3 ml-5">{{ spoofData.rating }}</p>
            </div>
             <!-- cards -->
             <div class="pr-3 mt-3" style="width: 459px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">ContenthtmlRating:</h5>
              <p class="mt-3 ml-5">{{ spoofData.htmls }}</p>
            </div>
             <!-- cards -->
             <div class="pr-3 mt-3" style="width: 459px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">ScreenshotRating:</h5>
              <p class="mt-3 ml-5">{{ spoofData.phashes }}</p>
            </div>
             <!-- cards -->
             <div class="pr-3 mt-3" style="width: 459px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">WhoisServer:</h5>
              <p class="mt-3 ml-5"> {{ spoofData.whois_server }}</p>
            </div> <!-- cards -->
            <div class="pr-3 mt-3" style="width: 459px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black"> ExpirationDate:</h5>
              <p class="mt-3 ml-5">{{ spoofData.expiration_date }}</p>
            </div> <!-- cards -->
            <div class="pr-3 mt-3" style="width: 459px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">State:</h5>
              <p class="mt-3 ml-5">{{ spoofData.state}}</p>
            </div>
            <!-- cards -->
            <div class="pr-3 mt-3" style="width: 459px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">NameServers:</h5>
              <p class="mt-3 ml-5">{{ spoofData.name_servers }}</p>
            </div>
            <!-- cards -->
            <div class="pr-3 mt-3" style="width: 459px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">RegistrantPostalCode:</h5>
              <p class="mt-3 ml-5">{{ spoofData.registrant_postal_code }}</p>
            </div>
             <!-- cards -->
             <div class="pr-3 mt-3" style="width: 459px; height: 68px; background: #D9D9D9;">
              <h5 class="pl-1 font-bold text-black">DomainSimilarityRate:</h5>
              <p class="mt-3 ml-5"> {{ spoofData.domainsimilarityrate }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- images -->
    <div class="mx-auto bg-transprent" style="width: 400px; height: 600px;">
      <!-- imageone -->
      <div class="bg-white ">
        <h3 class="ml-2 font-black border-b-2 border-orange-500 text-bold"> Mirror Domain <span class="text-orange-500">ScreenShot</span></h3>
        <div class="ml-2"   style="width: 390px; height: 239px; margin-auto;">    
          <img class="w-full" :src="'/assets/screenshots/'+spoofData.spoofed_domain+'.png'" alt="...">
        </div>
        <button class="m-2 btn btn-primary"  data-bs-toggle="modal" data-bs-target="#modal-normal">full size</button> 
      </div>
      <!-- image2 -->
      <div class="mt-3 bg-white">
        <h3 class="ml-2 font-black border-b-2 border-orange-500 text-bold">Your Domain <span class="text-orange-500">ScreenShot</span></h3>
        <div class="ml-2"   style="width: 390px; height: 239px; margin-auto;">    
          <img class="w-full" :src="'/assets/screenshots/'+spoofData.spoofed_domain+'.png'" alt="...">
        </div>
        <button class="m-2 btn btn-primary"  data-bs-toggle="modal" data-bs-target="#modal-normal">full-size</button> 
      </div>
    </div>
  </div>  
  <!-- report btn part -->
  <div class="flex" style="width: 970px; justify-content: space-between">
    <button class="m-2 text-white bg-orange-400 btn hover:bg-orange-300">Mark as suspect</button>
    <button class="m-2 btn btn-danger" >Report <i class="fa fa-cancel"></i></button>
  </div>
</section> 
<footer class="justify-center w-full bg-white h-14" style="position: fixed; bottom: 0; left: 0; text-align: center;">
  <div class="m-auto mt-2 w-fit">copyright@2023</div>
</footer>
    <!-- notification i will use later -->
              
    <div class="modal" id="modal-normal" tabindex="-1" role="dialog" aria-labelledby="modal-normal" aria-hidden="true"> 
      <div class="" role="document">
        <!-- <Link  :href="'/spoof/view/'+spoof.id">
          
  </Link> -->
          <div class="modal-content">
              <div class="mx-auto mb-0 overflow-y-auto bg-white shadow-none mt-7" style="width: 1000px; max-height: 600px;">
                  <div class="block-content fs-sm ">
                    <div class=""   style="margin-auto;">    
                      <img class="w-100" :src="'/assets/screenshots/'+spoofData.spoofed_domain+'.png'" alt="...">
                    </div>
                  </div>
              </div>
          </div>
      </div> 
  </div>   
    </AuthenticatedLayout>
</template>
