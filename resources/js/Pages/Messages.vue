<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { Link } from "@inertiajs/vue3"
import { defineComponent } from 'vue';
import { defineProps, onMounted } from 'vue';
import { ref, watch } from 'vue';

// const isModalVisible = ref(false);

// const openModal = () => {
//   isModalVisible.value = true;
// };

// const closeModal = () => {
//   isModalVisible.value = false;
// };

// Add a watcher to close the modal after a delay
// watch(isModalVisible, (newValue) => {
//   if (!newValue) {
//     setTimeout(() => {
//       // Close the modal after the fade-out transition
//       closeModal();
//     }, 300); // Adjust the delay to match the transition duration
//   }
// });


defineComponent({
  components: {
    Link
  },

});


const props = defineProps({
  domains: {
    type: Object,
  },
  info: {
    type: Object,
  },
  messages: {
    type: Object,
  },
});

function date(inputDateString) {
  const options = { month: 'short', day: 'numeric', year: '2-digit' };
  const inputDate = new Date(inputDateString);
  const formattedDate = new Intl.DateTimeFormat('en-US', options).format(inputDate);

  // const yearSubstring = formattedDate.slice(-2);

  return `${formattedDate}`;
}
const messageRef = ref(props.messages[0]);
const messaging = (index, messageId) => {
   console.log('hello');
    if(props.messages[index].id === messageId){
      messageRef.value = props.messages[index];
    } 
}
</script>

<template>
  <Head title="Messages" />

 <AuthenticatedLayout  class="overflow-x-hidden overflow-y-scroll fontFamily" style="height:100vh; background: #FFF;"> <!-- v-if=" props.userid === userId" -->
   <div class="flex justify-between mt-6 mr-8 border-b-4 border-black max-w-100 lg;ml-6 md:ml-6 ml-0">
    <div class="relative ">
      <button class="absolute w-40 h-12 px-3 rounded-tr-full bg-dark tabsText" style="">Domain</button>
      <button class="w-56 h-12 pr-4 bg-gray-300 rounded-tr-full tabsText pl-9" style="margin-left: 106px;">Social Media</button>
    </div>
      <!-- <Link class="my-auto buttons buttonsText mr-9"  :href="'/spoof/view/' + spoofData.id"><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link> -->
   </div>
   <!-- <div class="flex flex-row mx-4 mt-3 bg-yellow-100 h-14 rounded-t-xl"> -->
    <!-- <h2 class="my-auto text-gray-600 pl-7 h3 riskpush">Domain.com</h2> -->
    <!-- <h2 class="my-auto risk h3">High Risk</h2> -->
   <!-- </div> -->
    <div
      class="flex items-center justify-between mx-4 my-2 mr-6 cursor-pointer bigDropdownBg h-14"
        style="border-radius: 6px; "
        id="myDiv"
    >
      <div class="w-full h-full text-2xl font-semibold text-blueGray-700 select_wrapper">
          <!-- <h3 class="orgDomain text-capitalize ">domain.id </h3> -->
          <div class="h-full bg-transparent border-none dropdown lg:w-full md:w-full orgDomain ">
            <button class="relative flex dropbtn bigDropdownBg">
              <p>_._._.com </p>
              <div class="absolute right-0 mr-5 botton min-w-fit ">
                  <i
                    class="text-sm fa fa-chevron-down"
                    
                    aria-hidden="true"
                  ></i>
                    <div class="text-sm font-normal">
                    Spoofing sites 
                  </div>
                </div>
            </button>
            <div class="dropdown-content bigDropdownBg" >
              <div v-for="(domain, index) in domains" :key="index">
              <a class="bigDropdownBg hover:bg-transparent">
                  {{domain.domain_name}}
              </a>
              </div>
            </div>
          </div>

      </div>
      <!-- <div class="mr-5 botton ">
        <i
          class="fa fa-chevron-down"
          
          aria-hidden="true"
        ></i>
          <div>
          Spoofing sites 
        </div>
      </div> -->
    </div>
    <div class="justify-between mx-4 mt-2 lg:flex lg:flex-row" style="">
    <div style="min-width: 57%; height: 65vh;">
       <button class="my-2 mb-2 bg-yellow-100 buttons buttonsText h-14"><i class="mx-1 fa fa-plus"></i>Compose Message </button>
       <!-- <table class="w-full mt-3 text-sm" style="max-height: 20px;"> -->
        <div class="max-h-full overflow-auto " >
        <table class="w-full mt-3 overflow-x-auto text-sm">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 tablehead">
                <tr class="container justify-evenly">
                    <th  class="py-3 pl-2" >
                        Date
                    </th>
                    <th  class="py-3 text-left">
                        From
                    </th>
                    <th  class="py-3 text-left">
                        To
                    </th>
                    <th  class="py-3 text-left">
                        Subject
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="transition-colors duration-300 cursor-pointer tableRow "
                  :class="{ 'bg-yellow-300': messageRef.id == message.id, 'bg-yellow-100 hover:bg-yellow-50': messageRef.id != message.id }"
                v-for="(message, index) in messages" :key="index"
                    @click="messaging(index, message.id)"
                    >
                    <td class="pl-3 overflow-auto py-auto" style="max-width: 90px;"  >
                        {{date(message.created_at)}}
                    </td>
                    <td class="text-left capitalize py-auto">
                        Spoofix
                    </td>
                    <td class="text-left capitalize py-auto">
                        {{info.name}} {{info.second_name}}
                    </td>
                    <td class="text-left py-auto">
                        {{message.subject}}
                    </td>
                </tr>
            </tbody>
        </table>
         </div>
    </div>
    <div class="w-full ml-2 box-style" style=" height: 65vh; min-width: 31%; margin-top: 60px; ">
      <div class="ml-2 align-middle rounded-t-lg" style="height: 45px;  ">
            From: info@spoofix.com <br>
            To: {{info.email}} <br>
            Subject: {{messageRef.subject}} <br>
            Reference: MSG0000{{messageRef.id}}<br>
            Date:  {{date(messageRef.created_at)}} <br>
            <br>
            Dear <span class="capitalize">{{info.name}} {{info.second_name}}</span>, <br>

            I hope this email finds you well. I am writing to inform you about [brief description of the purpose of the email]. We have noted your recent inquiry regarding '{{messageRef.subject}}', and I wanted to provide you with the following information: <br>
            <br>
           {{messageRef.messages}}<br>
            <br>
            If you have any further questions or concerns, please feel free to reach out to us. <br>

            Thank you for your attention and cooperation. <br>
            <br>
            Best regards, <br>
            <br>
            Spoofix Management, <br>
            <!-- [Your position], <br> -->
            Spoofix<br>
            info@spoofix.com <br>
      </div>
    </div>
   </div>
   <div class="relative justify-between pt-3 w-100"><!-- flex -->
    <div class="flex justify-between float-right w-64 pr-14">
       <!-- <button class="my-2 buttons buttonsText">cancel</button> -->
        <!-- <button class="my-auto bg-yellow-300 buttons buttonsText" @click="openModal">confirm</button> -->
    </div>
    <!-- <div class="flex justify-between mr-6 w-80"> -->
       <!-- <Link class="my-auto buttons buttonsText" @click="changeID(spoofData.id)" :href="'/spoof/view/' + nextId2"><i class="pr-2 fa fa-chevron-left" aria-hidden="true"></i> Previous Item</Link> -->
        <!-- <Link class="my-auto buttons buttonsText" @click="changeIDPlus(spoofData.id)" :href="'/spoof/view/' + nextId ">Next Item<i class="pr-2 fa fa-chevron-right" aria-hidden="true"></i> </Link> -->
    <!-- </div> -->
   </div>
   <!-- Modal -->
    <!-- <transition name="modal-fade" >
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
                  <div class="my-auto">Do you want to Report FakeSite.2?</div>
                </div>
                <div class="relative w-100" >
                   <Link class="float-right px-4 bg-yellow-300 buttons buttonsText">confirm</Link>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition> -->
  </AuthenticatedLayout>
</template>

<style scoped>
/*animation*/

/* Style The Dropdown Button */
.dropbtn {
  padding: 10px;
  border: none;
  cursor: pointer;
  width: 100%;
  text-align: left;
  border-radius: 10px;
  transition-duration: 2s;
  max-height: 100%;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
  position: relative;
  display: inline-block;
  transition-duration: 2s;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  opacity: 0;
  max-height: 0;
  overflow: hidden;
  position: absolute;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
  visibility: hidden;
  transition: opacity 0.3s ease, max-height 0.9s ease, visibility 0.9s;
  width: 100%;
}

/* Links inside the dropdown */
.dropdown-content a {
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #ffe386}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
    opacity: 1;
  max-height: 200px; 
  visibility: visible;
  overflow: auto;
  
}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {
  background-color: #ffe386;
}

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
.botton{
  display: flex;
width: 159px;
height: 34px;
justify-content: center;
align-items: center;
gap: 16px;
flex-shrink: 0;
border-radius: 30px;
background: var(--yellow-yellow-400, #FFD633);
}
.bigDropdownBg{
  background: #FFEFB0;
}
.tableRow{
  height: 45px;
}
.tablehead{
  background: var(--dark-neutral-dark-neutral-4, #F0F0F0);
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










 