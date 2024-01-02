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
// const Toast = Swal.mixin({
//   toast: true,
//   position: 'top-end',
//   showConfirmButton: false,
//   timer: 7000,
//   timerProgressBar: true,
//   didOpen: (toast) => {
//     toast.addEventListener('mouseenter', Swal.stopTimer)
//     toast.addEventListener('mouseleave', Swal.resumeTimer)
//   }
// })


// const props = defineProps({
//   domain: {
//     type: Object,
//   },
//   details: {
//     type: Object,
//   },
//   spoofData: {
//     type: Object,
//   },
//   userid: {
//     type: Object,
//   },
//   spoofList: {
//     type: Object,
//   }
  
// });
// const heading = ref('Screenshots');
// const changePageContent = (buttonCLicked) =>{
//     heading.value = buttonCLicked;
// }
 
// Data
// const bladeViewUrl = ref("");

// Set the URL of your Blade view when the component is mounted
// onMounted(() => {
//   bladeViewUrl.value = "http://127.0.0.1:5500/resources/views/map.html"; // Replace with the actual relative path
// });

// const userId = props.domain[0].user_id;

// const nextId = props.spoofList[1].id;
// const nextId = ref(props.spoofData.id + 1);
// const nextId2 = ref(props.spoofData.id - 1);
// console.log(props.spoofList);
// console.log("hello");
// const changeID = (activeId) => {
//   let index = props.spoofList.length - 1; 
//   console.log("hello");
//   console.log(props.spoofList.length - 1);
//   while(index > 0){
//      console.log(props.spoofList[index].id);
//     if(activeId === props.spoofList[index].id){
//       index--;
//       nextId.value = props.spoofList[index].id;
//       break; 
//     }else{
//       index--;
//     }
//   }
// }

// const changeIDPlus = (activeId) => {
//   let index = 0; 
//   console.log("hello");
//   console.log(props.spoofList[index].id);
//   while(index < props.spoofList.length){
//     console.log(props.spoofList[index].id);
//     if(activeId === props.spoofList[index].id){
//       index++;
//       nextId.value = props.spoofList[index].id;
//       break; 
//     }else{
//       index++;
//     }
//   }
// }

// function checkImageExists(imageUrlL) {
//   let imageUrl = `http://127.0.0.1:8000/assets/screenshots/${imageUrlL}.png`;
//   fetch(imageUrl, { method: 'HEAD' })
//     .then((response) => {
//      if(response.status === 200){

//      }
// }

</script>

<template>
  <Head title="Messages" />

 <AuthenticatedLayout  class="overflow-scroll fontFamily" style="height:100vh; background: #FFF;"> <!-- v-if=" props.userid === userId" -->
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
      <div class="ml-5 text-2xl font-semibold text-blueGray-700">
          <h3 class="orgDomain text-capitalize ">domain.id</h3>
      </div>
      <div class="mr-5 botton ">
        <i
          class="fa fa-chevron-down"
          
          aria-hidden="true"
        ></i>
          <div>
          Spoofing sites 
        </div>
      </div>
    </div>
    <div class="justify-between mx-4 mt-2 lg:flex lg:flex-row">
    <div class="" style="min-width: 67%; height: 65vh;">
       <button class="my-2 mb-2 bg-yellow-100 buttons buttonsText h-14"><i class="mx-1 fa fa-plus"></i>Compose Message </button>
       <table class="w-full mt-3 text-sm">
                  <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 tablehead">
                      <tr class="container justify-evenly">
                          <th  class="py-3 pl-2" >
                              Date
                          </th>
                          <th  class="py-3 text-center">
                              From
                          </th>
                          <th  class="py-3 text-center">
                              To
                          </th>
                          <th  class="py-3 text-center">
                              Subject
                          </th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr class=" tableRow
                      transition-colors duration-300 hover:!bg-yellow-50"
                          >
                          <td class="pl-3 overflow-auto py-auto" style="max-width: 90px;">
                              Oct 13, 23
                          </td>
                          <td class="text-center py-auto">
                              John E.
                          </td>
                          <td class="text-center py-auto">
                              Peter P.
                          </td>
                          <td class="text-center py-auto">
                              Take Down Instructions
                          </td>
                      </tr>
                  </tbody>
              </table>
    </div>
    <div class="ml-1 box-style" style=" height: 65vh; min-width: 50%; margin-top: 60px;">
      <div class="align-middle rounded-t-lg " style="height: 45px; width: 100%; ">
        <!-- will do js here -->
            From: [Your Name] <br>
            To: [Recipient's Name] <br>
            Subject: [Email Subject] <br>
            Reference: [Reference Number] <br>
            Date/Time: [Date and Time] <br>

            Dear [Recipient's Name], <br>

            I hope this email finds you well. I am writing to inform you about [brief description of the purpose of the email]. We have noted your recent inquiry regarding [specific topic/issue], and I wanted to provide you with the following information: <br>

            [Body of the Email - Provide detailed information, instructions, or responses to the recipient's query.] <br>

            If you have any further questions or concerns, please feel free to reach out to us. We appreciate your [mention any specific action by the recipient, if applicable]. <br>

            Thank you for your attention and cooperation. <br>

            Best regards, <br>

            [Your Full Name] <br>
            [Your Position] <br>
            [Your Company] <br>
            [Your Email Address] <br>
        
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
  background: var(--yellow-yellow-100, #FFEFB0);
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










 