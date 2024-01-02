<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { Link } from "@inertiajs/vue3"
import { defineComponent } from 'vue';
import { defineProps, onMounted } from 'vue';
import { ref, watch } from 'vue';


const isModalVisible = ref(false);

const openModal = () => {
  isModalVisible.value = true;
};

const closeModal = () => {
  isModalVisible.value = false;
};


const props = defineProps({
    domainList: {
        type:Object,
    },
});
console.log(props.domainList)

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

const form = useForm({
    abuse_type: '',
    evidence_urls: '',
    additional_notes: '',
    observed_date: '',
    attachments: '',
    carbon_copy_request_to: '',
    reported_via: 'report_form',
    Confirm: '',
    targetDomain: '',
});

function submit() {
    // Validate the form fields
    if (!form.abuse_type || !form.evidence_urls || !form.Confirm) {
        Toast.fire({
            icon: 'error',
            title: 'All fields are required',
        });
        return;
    }
    console.log(form);
    const formData = new FormData();
    formData.append('abuse_type', form.abuse_type);
    formData.append('evidence_urls', form.evidence_urls);
    formData.append('additional_notes', form.additional_notes);
    formData.append('observed_date', form.observed_date);
    formData.append('targetDomain', form.targetDomain);
    // Append the file to the FormData object if it's not an empty string
    if (form.attachments !== '') {
        formData.append('attachments', file);
    }

    formData.append('carbon_copy_request_to', form.carbon_copy_request_to);
    
    // Send a POST request using Inertia
    // inertia.post('/ReportSpoofySite', form);
    router.post('/ReportSpoofySite', form)
        // Handle the success response
        Toast.fire({
            icon: 'success',
            title: 'Reported Successfully',
        });

        // You can also redirect to another page if needed
        // router.push('/some-other-page');
   
}

</script>

<template>
  <Head title="ReportForm" />

 <AuthenticatedLayout  class="overflow-scroll fontFamily" style="height:100vh; background: #FFF;"> 
  <!-- v-if=" props.userid === userId" -->
   <div class="flex justify-between mt-6 mr-7 border-b-4 border-black max-w-100 lg;ml-6 md:ml-6 ml-0">
    <div class="relative">
      <button class="absolute w-40 h-12 px-3 rounded-tr-full bg-dark tabsText" style="">Domain</button>
      <button class="w-56 h-12 pr-4 bg-gray-300 rounded-tr-full tabsText pl-9" style="margin-left: 106px;">Social Media</button>
    </div>
      <!-- <Link class="my-auto buttons buttonsText mr-9"  :href="'/spoof/view/' + spoofData.id"><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link> -->
   </div>
   <!-- <div class="flex flex-row mx-4 mt-3 bg-yellow-100 h-14 rounded-t-xl"> -->
    <!-- <h2 class="my-auto text-gray-600 pl-7 h3 riskpush">{{ spoofData.spoofed_domain }}</h2> -->
    <!-- <h2 class="my-auto risk h3">High Risk</h2> -->
   <!-- </div> -->
    <div class="justify-between mx-4 mt-2 lg:flex-row lg:flex">
    <form class="" style="lg:width: 67%; md:width:67%; height: 65vh;"  @submit.prevent="submitForm">
        <div class="flex pt-2 w-100 justify-evenly">
        <label for="abuseType" class="p-3 w-50 reportFormText">Abuse Type</label>
          <div class="relative w-50">
              <select id="abuseType" name="abuseType" class="w-full h-10 pl-3 pr-10 text-gray-300 bg-yellow-100 border border-gray-300 rounded-md reportFormText" v-model="form.abuse_type" >
                  <option value="" disabled selected class="text-black ">...Choose Abuse Type...</option>
                  <option value="Spoofing" class="text-black">Spoofing</option>
                  <option value="Phishing" class="text-black">Phishing</option>
              </select>
              <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                  <!-- <i class="fa fa-chevron-down"></i> -->
              </div>
          </div>
      </div>
        <div class="flex pt-2 w-100 justify-evenly">
          <label for="Other URLs" class="p-3 w-50 reportFormText">
            Evidence URLs
          </label>
          <div class="mr-1 w-50">
            <textarea type="url" id="fname" name="fname" class="text-gray-700 bg-yellow-100 border-none h-100 w-100 reportFormText" placeholder="provide URL" v-model="form.evidence_urls" ></textarea>
          </div>
        </div>
        <div class="flex pt-2 w-100 justify-evenly">
        <label for="targetDomain" class="p-3 w-50 reportFormText">Target Domain</label>
          <div class="relative w-50">
              <select id="targetDomain" name="targetDomain" class="w-full h-10 pl-3 pr-10 text-gray-300 bg-yellow-100 border border-gray-300 rounded-md reportFormText" v-model="form.targetDomain" >
                  <option value="" disabled selected class="text-black ">...Choose Targeted domain...</option>
                <option  v-for="(domain, index) in domainList" :key="index">
                  <option :value="domain.domain_name" class="text-black">{{domain.domain_name}}</option>
                </option>
                  <!-- <option value="Phishing" class="text-black">Phishing</option> -->
              </select>
              <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                  <!-- <i class="fa fa-chevron-down"></i> -->
              </div>
          </div>
      </div>
        <div class="flex pt-2 w-100 justify-evenly">
          <label for="Other URLs" class="p-3 w-50 reportFormText">
            Additional Notes
          </label>
          <div class="flex justify-around mr-1 bg-yellow-100 w-50">
             <textarea type="url" id="fname" name="Additional Notes" class="text-gray-700 bg-yellow-100 border-none h-100 w-100 reportFormText" rows="7" placeholder="Please provide details of abuse" v-model="form.additional_notes" ></textarea>
          </div>
        </div>
        <div class="flex pt-2 w-100 justify-evenly">
          <label for="Other URLs" class="p-3 w-50 reportFormText">
            Observed  Date
          </label>
          <div class="mr-1 w-50">
            <input type="date" id="fname" name="fname" class="text-gray-700 bg-yellow-100 border-none h-100 w-100 reportFormText" v-model="form.observed_date" />
          </div>
        </div>
        <div class="flex pt-2 w-100 justify-evenly">
          <label class="p-3 w-50 reportFormText">
            Attachments
          </label>
          <div class="flex mr-1 bg-yellow-100 w-50">
            <i class="mx-3 mt-2 text-xl fa fa-paperclip"></i>
            <input type="file" class="pt-2 h-100 w-100" placeholder="Attachment.dox" @change="handleFileChange" tooltip="Attachment.dox"/>
          </div>
        </div>
        <div class="flex pt-2 w-100 justify-evenly">
          <label class="p-3 w-50 reportFormText">
            Carbon Copy (CC) the Request 
          </label>
          <div class="flex justify-around mr-1 bg-yellow-100 w-50">
            <input type="email" id="fname" name="Carbon Copy (CC) the Request" class="placeholder-gray-700 bg-yellow-100 border-none h-100 w-100 reportFormText" v-model="form.carbon_copy_request_to" placeholder="james.name@example.com"/>
          </div>
        </div>
        <div class="flex pt-2 w-100 justify-evenly">
          <label for="Other URLs" class="p-3 w-50 reportFormText">
            Please Confirm
          </label>
          <div class="flex mr-1 text-gray-700 bg-yellow-100 border-none w-50 reportFormText">
            <input type="checkbox" id="fname" name="fname" class="mx-3 text-yellow-300" v-model="form.Confirm"/>
            <div>I confirm that the website I am reporting is a fake, phishing, or spoofing site. I understand that false reports can 
              harm legitimate websites and agree to report only suspicious websites that I am certain about. 
              I take full responsibility for this report
            </div>
          </div>
        </div>
           <!-- Modal -->
    <transition name="modal-fade" >
      <div v-if="isModalVisible" class="backlight" @click="closeModal">
        <div
          role="dialog"
          aria-modal="true"
          class="fade image-modal dark modal show backlight"
          tabindex="-1"
          style="padding-left: 14px; display: block;"
        >
          <div class="modal-dialog modal-xl modal-dialog-centered" style="">
            <div class="modal-content">
              <div class="">
                <div
                  class="mx-auto bg-white modelStyle"
                  style=""
                >
                <div class="relative w-100"> 
                  <img class="float-right" :src="'/assets/systemImages/Exit.svg'"/>
                </div>
                <div class="relative flex my-2 modelText">
                   <img class="pr-3 " :src="'/assets/systemImages/Promo.svg'"/>
                   <img class="absolute m-2" :src="'/assets/systemImages/bookmark.svg'"/>
                  <div class="my-auto">Do you want to Report <span class="text-yellow-500">{{form.evidence_urls}}</span>?</div>
                </div>
                <div class="relative w-100" >
                   <button class="float-right px-4 bg-yellow-300 buttons buttonsText" type="submit" @click="submit()">confirm</button>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
      </form>
    <div class="ml-1 box-style sm_hidden" style=" height: 65vh; width: 100%; ">
      <div class="align-middle bg-gray-100 rounded-t-lg" style="height: 45px; width: 100%;">
        <div class="py-3 pl-3 chechdetails">Definitions</div>
        <!-- will do js here -->
        
      </div>
    </div>
   </div>
   <div class="relative justify-between pt-3 w-100"><!-- flex -->
    <div class="flex justify-between float-right w-64 pr-14">
       <Link class="my-2 buttons buttonsText" @click="reload">cancel</Link>
        <button class="my-auto bg-yellow-300 buttons buttonsText" @click="openModal">confirm</button>
    </div>
    <!-- <div class="flex justify-between mr-6 w-80"> -->
       <!-- <Link class="my-auto buttons buttonsText" @click="changeID(spoofData.id)" :href="'/spoof/view/' + nextId2"><i class="pr-2 fa fa-chevron-left" aria-hidden="true"></i> Previous Item</Link> -->
        <!-- <Link class="my-auto buttons buttonsText" @click="changeIDPlus(spoofData.id)" :href="'/spoof/view/' + nextId ">Next Item<i class="pr-2 fa fa-chevron-right" aria-hidden="true"></i> </Link> -->
    <!-- </div> -->
   </div>

  </AuthenticatedLayout>
</template>

<style scoped>
/*animation*/
/* Add your custom CSS here */

.backlight {
  position: fixed;
  top: 0;
  left: 0;
  max-width: 100%;
  max-height: 100%;
  background-color: rgba(0, 0, 0, 0.606);
    /*background-color: rgba(217, 217, 217, 0.60);*/
  
  /* 0.5 represents 50% opacity (adjust as needed) */
  z-index: 9999;
  /* Make sure it's above other elements */
}
.modal-fade-enter-active, .modal-fade-leave-active {
  transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
}


.modal-fade-enter, .modal-fade-leave-to /* .modal-fade-leave-active in <2.1.8 */ {
  opacity: 0;
  transform: translateY(-20px);
}

.image-container {
  
  max-width: 390px;
  max-height: 239px;
  margin: auto;
}
.modelStyle{
  width: 624px;
  height: 237px;
  display: flex;
  padding: 30px 40px;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  gap: 30px;
  border-radius: 20px;
  background: #FFF;
  box-shadow: 0px 4px 50px 0px rgba(0, 0, 0, 0.25);
}
.reportFormText{
  color: #000;

/* Regular/Base/Regular */
font-family: Poppins;
font-size: 14px;
font-style: normal;
font-weight: 400;
line-height: 120%; /* 16.8px */
}
.riskpush{
  width: 36%;
}
.shadow-{
box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);

}
.modelText{
width: 474px;
color: #2A2A2A;

/* Regular/Heading 5/Regular */
font-family: Poppins;
font-size: 20px;
font-style: normal;
font-weight: 400;
line-height: 120%; /* 24px */
background: #FFF;


}
.box-style{
  background: var(--dark-neutral-dark-neutral-2, #FCFCFC);
box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
}
.chechdetails{
color: var(--dark-neutral-dark-neutral-8, #595959);
/* Semibold/Base/Semibold */
font-family: Poppins;
font-size: 14px;
font-style: normal;
font-weight: 600;
line-height: 120%; /* 16.8px */
}
.fontFamily{
  font-family: Poppins;
}
.risk{
  display: flex;
width: 99.908px;
height: 12.574px;
flex-direction: column;
justify-content: center;
flex-shrink: 0;
color: #ED0707;
/* Semibold/Heading 5/Semibold */
font-family: Poppins;
font-size: 20px;
font-style: normal;
font-weight: 600;
line-height: 120%; /* 24px */
}
.buttons{
  display: flex;
height: 39px;
padding: 10px 16px;
justify-content: center;
align-items: center;
gap: 21px;
border-radius: 30px;
box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
}
.buttonsText{
  color: #000;
font-family: Poppins;
font-size: 12px;
font-style: normal;
font-weight: 600;
line-height: 120%; /* 14.4px */
}
.tabsText{
color: var(--default-white, #FFF);
/* Semibold/Heading 5/Semibold */
font-family: Poppins;
font-size: 20px;
font-weight: 600;
}
.detailsNav{
color: var(--dark-neutral-dark-neutral-8, #595959);

/* Semibold/Large/Semibold */
font-family: Poppins;
font-size: 16px;
font-weight: 600;
}
.detailsButton{
  display: flex;
height: 39px;
padding: 10px 16px;
justify-content: center;
align-items: center;
gap: 21px;
border-radius: 30px;
background: var(--yellow-yellow-300, #FFDD54);
color: #000;
/**buttontext/
/* Semibold/Small/Semibold */
font-family: Poppins;
font-size: 12px;
font-style: normal;
font-weight: 600;
line-height: 120%; /* 14.4px */
}
.info-card {
  max-width: 100%;
  max-width: 450px;
  height: 68px;
  background: #f0f0f0;
  /* Use your preferred background color */
  margin-bottom: 10px;
  padding: 10px;
  border-radius: 5px;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}
/* Add more custom styles as needed */
</style>










 