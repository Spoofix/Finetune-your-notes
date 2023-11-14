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

// Add a watcher to close the modal after a delay
watch(isModalVisible, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
      // Close the modal after the fade-out transition
      closeModal();
    }, 300); // Adjust the delay to match the transition duration
  }
});


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
  userid: {
    type: Object,
  },
  spoofList: {
    type: Object,
  }
  
});
const heading = ref('Screenshots');
const changePageContent = (buttonCLicked) =>{
    heading.value = buttonCLicked;
}
 
// Data
const bladeViewUrl = ref("");

// Set the URL of your Blade view when the component is mounted
onMounted(() => {
  bladeViewUrl.value = "http://127.0.0.1:5500/resources/views/map.html"; // Replace with the actual relative path
});

const userId = props.domain[0].user_id;

// const nextId = props.spoofList[1].id;
const nextId = ref(props.spoofData.id + 1);
const nextId2 = ref(props.spoofData.id - 1);
// console.log(props.spoofList);
// console.log("hello");
const changeID = (activeId) => {
  let index = props.spoofList.length - 1; 
  console.log("hello");
  console.log(props.spoofList.length - 1);
  while(index > 0){
     console.log(props.spoofList[index].id);
    if(activeId === props.spoofList[index].id){
      index--;
      nextId.value = props.spoofList[index].id;
      break; 
    }else{
      index--;
    }
  }
}

const changeIDPlus = (activeId) => {
  let index = 0; 
  console.log("hello");
  console.log(props.spoofList[index].id);
  while(index < props.spoofList.length){
    console.log(props.spoofList[index].id);
    if(activeId === props.spoofList[index].id){
      index++;
      nextId.value = props.spoofList[index].id;
      break; 
    }else{
      index++;
    }
  }
}
const domainDetails = Array('registrar', 'registrationDate', 'country', 'emails', 'update_date', 'name', 'city', 'whois_server', 'expiration_date', 'name_servers', 'status', 'dnssec', 'org', 'address', 'state', 'registrant-postal_code', 'spoof_status');
const ScanDetails = Array('created_at', 'phash', 'htmls', 'id' );
const RiskRating = Array('country', 'phashes', 'htmls', 'domainsimilarityrate', 'csscolor', 'domain_rating', 'rating');


// function checkImageExists(imageUrlL) {
//   let imageUrl = `http://127.0.0.1:8000/assets/screenshots/${imageUrlL}.png`;
//   fetch(imageUrl, { method: 'HEAD' })
//     .then((response) => {
//      if(response.status === 200){

//      }
// }
const makeToArray = (theString) => {
  try {
    const data = JSON.parse(theString);
    return Array.isArray(data) ? data : [data]; // Ensure it's always an array
  } catch (error) {
    console.error('Invalid JSON format:', error);
    return theString;
  }
}
</script>

<template>
  <Head title="Domain" />

 <AuthenticatedLayout v-if=" props.userid === userId" class="overflow-scroll fontFamily" style="height:100vh; background: #FFF;"> 
   <div class="flex justify-between mt-6 w-100">
    <div class="relative ml-6">
      <button class="absolute w-40 h-12 px-3 rounded-tr-full bg-dark tabsText" style="">Domain</button>
      <button class="w-56 h-12 pr-4 bg-gray-300 rounded-tr-full tabsText pl-9" style="margin-left: 106px;">Social Media</button>
    </div>
      <Link class="my-auto buttons buttonsText mr-9" :href="'/domains'"><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link>
   </div>
   <div class="flex flex-row mx-4 mt-3 bg-yellow-100 h-14 rounded-t-xl">
    <h2 class="my-auto text-gray-600 pl-7 h3 riskpush">{{ spoofData.spoofed_domain }}</h2>
    <h2 class="my-auto risk h3">High Risk</h2>
   </div>
    <div class="flex flex-row justify-between mx-4 mt-2">
    <div class="" style="width: 35%; height: 65vh;">
      <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('Scan Details')" :class="{'bg-gray-200': heading === 'Scan Details', 'bg-yellow-50': heading !== 'Scan Details'}">
        <h2 class="my-auto ml-4 detailsNav">Scan Details</h2>
        <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa " :class="heading === 'Scan Details' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
      </div>
       <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('Page Statistics')" :class="{'bg-gray-200': heading === 'Page Statistics', 'bg-yellow-50': heading !== 'Page Statistics'}">
        <h2 class="my-auto ml-4 detailsNav">Page Statistics</h2>
        <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa " :class="heading === 'Page Statistics' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
      </div>
       <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('Domain Details')" :class="{'bg-gray-200': heading === 'Domain Details', 'bg-yellow-50': heading !== 'Domain Details'}">
        <h2 class="my-auto ml-4 detailsNav">Domain Details</h2>
        <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa " :class="heading === 'Domain Details' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
      </div>
       <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('Locations')" :class="{'bg-gray-200': heading === 'Locations', 'bg-yellow-50': heading !== 'Locations'}">
        <h2 class="my-auto ml-4 detailsNav">Locations</h2>
       <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa " :class="heading === 'Locations' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
      </div>
       <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('Screenshots')" :class="{'bg-gray-200': heading === 'Screenshots', 'bg-yellow-50': heading !== 'Screenshots'}">
        <h2 class="my-auto ml-4 detailsNav">Screenshots</h2>
        <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa" :class="heading === 'Screenshots' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
      </div>
       <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('HTTP Redirects')" :class="{'bg-gray-200': heading === 'HTTP Redirects', 'bg-yellow-50': heading !== 'HTTP Redirects'}">
        <h2 class="my-auto ml-4 detailsNav">HTTP Redirects</h2>
        <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa " :class="heading === 'HTTP Redirects' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
      </div>
       <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;"  @click="changePageContent('Risk Rating')" :class="{'bg-gray-200': heading === 'Risk Rating', 'bg-yellow-50': heading !== 'Risk Rating'}">
        <h2 class="my-auto ml-4 detailsNav">Risk Rating</h2>
        <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa " :class="heading === 'Risk Rating' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
    </div>
    </div>
    <div class="ml-1 overflow-y-auto box-style" style=" height: 65vh; width: 64%;">
      <div class="align-middle bg-gray-100 rounded-t-lg" style="height: 45px; width: 100%;">
        <div class="py-3 pl-3 chechdetails" v-if="heading == 'Domain Details'">
          <span class="ml-1">Area</span>
          <span class="pl-16 ml-56 ">Details</span>
        </div>
        <div class="py-3 pl-3 chechdetails" v-else>{{ heading }}</div>
        <!-- will do js here -->
          <div class="w-full overflow-auto" @click="openModal" v-if=" heading ==  'Screenshots'">
              <img class="w-full" :src="'/assets/screenshots/' + spoofData.spoofed_domain + '.png'" alt="SORRY ( : ,IMAGE NOT FOUND" v-if="spoofData.screenshot !== null">
              <img class="w-full" :src="'/assets/systemImages/screenshotplaceholder.png'" alt="..." v-if="spoofData.screenshot === null">
          </div>
          <div class="w-full overflow-auto" v-if=" heading ==  'Locations'">
               <iframe :src="bladeViewUrl" width="100%" height="500"></iframe>
             <!-- <iframe src="../../../views/map.html" width="100%" height="500px" frameborder="0"></iframe> -->
          </div>
           <div class="w-full overflow-auto" v-if=" heading ==  'Domain Details'">
            <div class="flex mt-1 DetailsTableRow"  v-for="(domainDetail, index) in domainDetails" :key="index">
              <div class="mt-3 ml-3 DetailsTableRowText">{{domainDetail}}</div>
              <div class="mt-3 ml-60 w-100 DetailsTableRowText" v-if="Array.isArray(makeToArray(spoofData[domainDetail]) )">
                <div class="mt-2" v-for="(data, index) in  makeToArray(spoofData[domainDetail])" :key="index">
                  {{data}}
                </div>
              </div>
              <div class="mt-3 ml-60 w-100 DetailsTableRowText" v-else >{{ makeToArray(spoofData[domainDetail]) }}</div>  
            </div>
          </div>
          <!-- scan Details -->
           <div class="w-full overflow-auto" v-if=" heading ==  'Scan Details'">
            <div class="flex mt-1 DetailsTableRow"  v-for="(ScanDetail, index) in ScanDetails" :key="index">
              <div class="mt-3 ml-3 DetailsTableRowText">{{ScanDetail}}</div>
              <div class="mt-3 ml-60 w-100 DetailsTableRowText">
                <!-- <div class="mt-2" v-for="(data, index) in  makeToArray(spoofData[ScanDetail])" :key="index"> -->
                  {{spoofData[ScanDetail]}}
                <!-- </div> -->
              </div>
              <!-- <div class="mt-3 ml-60 w-100 DetailsTableRowText" v-else >{{ makeToArray(spoofData[domainDetail]) }}</div>   -->
            </div>
          </div>
          <!-- <div class="w-full overflow-auto">
              <img class="w-full" :src="'/assets/screenshots/' + spoofData.spoofed_domain + '.png'" alt="...">/spoof//{spoofId}
          </div> -->
      </div>
    </div>
   </div>
   <div class="flex justify-between pt-3">
    <div class="flex justify-between w-96 pl-7">
       <Link class="my-2 buttons buttonsText">Ignore</Link>
        <Link class="my-auto bg-yellow-100 buttons buttonsText">Monitor</Link>
         <Link class="my-auto bg-yellow-300 buttons buttonsText" :href="'/spoof/requestAuthorization/' + spoofData.id">Take Down</Link>
    </div>
    <div class="flex justify-between mr-6 w-80">
       <Link class="my-auto buttons buttonsText" @click="changeID(spoofData.id)" :href="'/spoof/view/' + nextId2"><i class="pr-2 fa fa-chevron-left" aria-hidden="true"></i> Previous Item</Link>
        <Link class="my-auto buttons buttonsText" @click="changeIDPlus(spoofData.id)" :href="'/spoof/view/' + nextId ">Next Item<i class="pr-2 fa fa-chevron-right" aria-hidden="true"></i> </Link>
    </div>
   </div>
   <!-- Modal -->
    <transition name="modal-fade" >
      <div v-if="isModalVisible && spoofData.screenshot !== null" class="backlight" @click="closeModal">
        <div
          role="dialog"
          aria-modal="true"
          class="fade image-modal dark modal show backlight"
          tabindex="-1"
          style="padding-left: 14px; display: block;"
        >
          <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 70vw;">
            <div class="modal-content">
              <div class="image-modal-content">
                <img
                  class="card-img full-screen-image add-white-background"
                  :src="'/assets/screenshots/' + spoofData.spoofed_domain + '.png'"
                  alt="scan result screenshot"
                  style="max-height: 78vh; margin-top: 100px;"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
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
.DetailsTableRow{
  width: 99%;
  margin-top: 3px;
  margin: auto;
min-height: 45px;
flex-shrink: 0;
border-radius: 6px;
background: var(--yellow-yellow-50, #FFFAE6);
box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
}
.DetailsTableRowText{
  width: 105px;
  color: var(--dark-neutral-dark-neutral-9, #454545);

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










 