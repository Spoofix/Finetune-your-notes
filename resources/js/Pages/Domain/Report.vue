<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import {Link} from "@inertiajs/vue3"
import { defineComponent } from 'vue';
import Editor from '@tinymce/tinymce-vue'
import { MagicString } from 'vue/compiler-sfc';



defineComponent({
  components: {
    Link
  },
  name: 'app',
  components: {
  'editor': Editor
}
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
    initValueHere: {
        type: String,
    }
});



const form = useForm({
    domains: null,
});

function submit() {
    if (form.report == null) {
        Toast.fire({
            icon: 'error',
            title: 'Add at least one place to report'
        })
        return;
    }

    form.post(route('report'), {
        onFinish: (response) => {
            Toast.fire({
                icon: 'success',
                title: 'Reported'
            })

            // console.log(response);
            //  search()
        },
        onSuccess: () => { },
    });

}


</script>

<template>
    <Head title="Domain" />

    <AuthenticatedLayout>
<div class="relative mt-6 d-flex">
 
  <div class="absolute right-0 mt-2 "><Link class="px-3 py-1 mb-1 mr-1 text-xs font-bold text-white uppercase transition-all duration-150 ease-linear bg-indigo-500 rounded outline-none active:bg-indigo-600 focus:outline-none" type="button" :href="'/spoof/'+spoofData.domain_id"><i class="fa fa-arrow-left"></i> Back</Link></div>
 </div>
 <!-- here -->
   <div class="mt-5">
   
  
   </div>
 <!-- <form > -->

    <div id="app">
      <!-- <img alt="Vue logo" src="./assets/.png"> -->
     
      <editor
        api-key="9oj3gwaxetv26w50l19uiuwh3yxa6a6t8efa7zupst18nj5i"
        :init="{
          height: 650,
          menubar: false,
          plugins: [
            'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
            'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
            'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
          ],
          toolbar:
            'undo redo | casechange blocks | bold italic backcolor | \
            alignleft aligncenter alignright alignjustify | \
            bullist numlst checklist outdent indent | removeformat | a11ycheck code table help'
        }"
        :initial-value="initValueHere"
      />
    </div>
    <div class="relative">
        <Link class="ml-4 text-black bg-orange-500 btn hover:bg-orange-400" :href="'/report'"> Report To Us</Link>
        <button class="float-right mr-4 text-black bg-red-600 btn hover:bg-red-400"  data-bs-toggle="modal" data-bs-target="#modal-normal">Report <i class="fa fa-cancel"></i></button>
    </div>
  <!-- </form> -->
    <!-- notification i will use later -->
              
    <div class="modal" id="modal-normal" tabindex="-1" role="dialog" aria-labelledby="modal-normal" aria-hidden="true"> 
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block mb-0 shadow-none block-rounded">
                <div class="text-black block-header block-header-default">
                    <h3 class="text-red-600 block-title" >Report <small class="text-gray-900">{{ spoofData.spoofed_domain }}</small></h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content fs-sm">

                    <form  @submit.prevent="submit">
                      <div class="relative flex flex-row justify-between">
                        <h4 class="h4">Send Report To: </h4>
                        <small class="float-right mt-2">click <i class="fas fa-flag" ></i> to fill form manually</small>
                      </div>
                      <div class="mb-4">
                        <input type="checkbox" class="rounded" checked v-model="form.report1" >
                        <span class="group"> Google SafeBrowsing  <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://safebrowsing.google.com/safebrowsing/report_phish/"><i class="fas fa-flag" ></i></a>  </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report2">
                        <span class="group"> Microsoft   <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://www.microsoft.com/en-us/wdsi/support/report-unsafe-site-guest"><i class="fas fa-flag"></i></a></span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report3" >
                        <span class="group"> Fortiguard    <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://www.fortiguard.com/webfilter"><i class="fas fa-flag"></i></a></span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report4">
                        <span class="group"> BrightCloud   <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://www.brightcloud.com/tools/url-ip-lookup.php"><i class="fas fa-flag"></i></a> </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report5">
                        <span class=" group"> CRDF  <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://threatcenter.crdf.fr/submit_url.html"><i class="fas fa-flag"></i></a>  </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report6">
                        <span class=" group"> Netcraft  <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://report.netcraft.com/report"><i class="fas fa-flag"></i></a>  </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report7">
                        <span class="group"> Palo Alto Networks   <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://urlfiltering.paloaltonetworks.com/"><i class="fas fa-flag"></i></a> </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report8">
                        <span class="group"> ESET  <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://phishing.eset.com/en-us/report"><i class="fas fa-flag"></i></a>  </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report9">
                        <span class="group">  Trend Micro  <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://global.sitesafety.trendmicro.com/index.php"><i class="fas fa-flag"></i></a> </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report10">
                        <span class="group"> BitDefender  <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://www.bitdefender.com/consumer/support/answer/29358/"><i class="fas fa-flag"></i></a>  </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report11">
                        <span class="group"> McAfee   <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://sitelookup.mcafee.com/"><i class="fas fa-flag"></i></a> </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report12">
                        <span class="group"> Forcepoint   <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://csi.forcepoint.com/"><i class="fas fa-flag"></i></a> </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report13">
                        <span class="group"> Symantec  <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://sitereview.symantec.com/#/"><i class="fas fa-flag"></i></a>  </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report14">
                        <span class="group"> Spam404  <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://www.spam404.com/report.html"><i class="fas fa-flag"></i></a>  </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report15">
                        <span class="group">  Kaspersky  <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://opentip.kaspersky.com/"><i class="fas fa-flag"></i></a></span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report16">
                        <span class="group"> Cisco Talos  <a class="hidden ml-2 text-gray-500 transition-opacity duration-300 ease-in-out group-hover:inline-block" href="https://talosintelligence.com/reputation_center"><i class="fas fa-flag"></i></a> </span><br>
                        <input type="checkbox" class="rounded" checked v-model="form.report17" v-if="spoofData.registrar">
                        <span class="text-red-500">  {{ spoofData.registrar }} </span><br>
                      </div>
                        <div class="col-md-12">
                            <div class="mb-4 row">
                                <div class="col-12">
                                    <label class="form-label">Add Other place to report</label>
                                    <input type="text" class="form-control form-control-lg"  v-model="form.report" placeholder="Enter email">
                                </div>
                                
                            </div>
                        </div>

                        <div class="mb-4">
                            <button class="bg-red-700 btn-danger btn hover:bg-red-600" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                <span class="spinner-border text-dark " v-if="form.processing"></span>
                                Report <i class="opacity-50 fa fa-cancel me-1"></i>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div> 
</div>
<footer class="justify-center w-full bg-white h-14" style="position: fixed; bottom: 0; left: 0; text-align: center;">
  <div class="m-auto mt-2 w-fit">copyright@2023</div>
</footer>

    </AuthenticatedLayout>
   
</template>


