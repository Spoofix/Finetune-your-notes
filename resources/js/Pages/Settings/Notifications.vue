<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue';
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


const props = defineProps({
  notificationSettings: {
    type: Object,
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


const activeOne = ref(props.notificationSettings.all_notifications);
const active2 = ref(props.notificationSettings.takedown_requests);
const active3 = ref(props.notificationSettings.takedown_completed);
const active4 = ref(props.notificationSettings.news_and_updates);
const active5 = ref(props.notificationSettings.reminders);
const menu =(now, index) => {
  if(index == 1){
    activeOne.value = now;
    console.log(activeOne.value);
  }else if(index == 2){
    active2.value = now;
  }else if(index == 3){
    active3.value = now;
  }else if(index == 4){
    active4.value = now;
  }else if(index == 5){
    active5.value = now;
  }
   
}
console.log('form');
console.log(activeOne.value);

const form = useForm({
    all_notifications: activeOne._value,
    takedown_requests: active2._value,
    takedown_completed: active3._value,
    news_and_updates: active4._value,
    reminders: active5._value,
});

function submit() {
  form.all_notifications = activeOne._value;
  form.takedown_requests = active2._value;
  form.takedown_completed = active3._value;
  form.news_and_updates = active4._value;
  form.reminders = active5._value;
 console.log(activeOne._value); 
    // console.log('form');
    // console.log(form);
 
    router.post('/notification-update', form)
   
    Toast.fire({
        icon: 'success',
        title: 'Notifications Settings Updates Successfully'
    })
    //  location.reload();
}

</script>

<template>
  <Head title="Notifications" />

 <SettingsLayout  class="overflow-scroll fontFamily" style="height:100vh; width: 100vh background: #FFF;"> <!-- v-if=" props.userid === userId" -->
   <div class="flex justify-between mt-6 ml-0 mr-8 border-b-4 border-black lg:h-14 md:h-14 max-w-100 lg:ml-6 md:ml-6 ">
    <div class="mt-2 ">
      <button @click="menu('Profile')" class="w-40 h-10 px-3 rounded-tr-full sm:mb-4 md:h-12 lg:h-12 tabsText bg-dark">Notifications</button>
    </div>
      <!-- <Link class="my-auto buttons buttonsText mr-9" ><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link> -->
   </div>

    <div
       class="flex items-center justify-between my-2 cursor-pointer lg:mx-6 sm:mx-2 sm:mr-3 bigDropdownBg h-14"
        style="border-radius: 6px; "
        id="myDiv"
    >
      <div class="ml-5 text-2xl text-black">
          <h3 class="orgDomain text-capitalize ">Email Notifications</h3>
      </div>
    </div>

    <div class="mt-2 lg:mx-6 sm:mx-2 sm:mr-3" style=" height: 35vh; border-radius: 6px; border: 1px solid #BFBFBF; ">


      <div class="flex mx-4 my-4 mb-3 " style="max-height: 7vh; min-width: 600px;">
        <div class="flex" >
        <div class="my-auto font-bold lg:ml-9 md:ml-9" style="width: 200px;" >All Notifications</div>
        <div class="my-auto" style="min-width: 200px;">
            <div class="mx-auto my-auto activeBtn" @click="menu('false',1)" v-if="activeOne === 'true' ">
          <div class="my-auto ml-5 bg-yellow-300 rounded-full btnSwitch" >
            <!-- button -->
          </div>
        </div>
        <div class="mx-auto my-auto activeBtn"  @click="menu('true',1)" v-if="activeOne === 'false'">
          <div class="my-auto bg-gray-300 rounded-full btnSwitch">
            <!-- button -->
          </div>
        </div>
        </div>
        </div>
        <div class="my-auto ml-8 lg:mt-0 md:ml-6 lg:ml-8" style="width: 300px;">Receive all system notifications </div>
      </div>
      
      <div class="flex mx-4 my-5 mb-3 " style="max-height: 7vh; min-width: 600px;">
        <div class="flex" >
        <div class="my-auto font-bold lg:ml-9 md:ml-9" style="width: 200px;" >Takedown Requests</div>
        <div class="my-auto" style="min-width: 200px;">
            <div class="mx-auto my-auto activeBtn" @click="menu('false',2)" v-if="active2 === 'true' ">
          <div class="my-auto ml-5 bg-yellow-300 rounded-full btnSwitch" >
            <!-- button -->
          </div>
        </div>
        <div class="mx-auto my-auto activeBtn"  @click="menu('true',2)" v-if="active2 === 'false'">
          <div class="my-auto bg-gray-300 rounded-full btnSwitch">
            <!-- button -->
          </div>
        </div>
        </div>
        </div>
        <div class="my-auto ml-8 lg:mt-0 md:ml-6 lg:ml-8" style="width: 300px;">Recieve all takedown requests processed. </div>
      </div>

      <div class="flex mx-4 my-5 mb-3 " style="max-height: 7vh; min-width: 600px;">
        <div class="flex" >
        <div class="my-auto font-bold lg:ml-9 md:ml-9" style="width: 200px;" >Takedown Completed</div>
        <div class="my-auto" style="min-width: 200px;">
            <div class="mx-auto my-auto activeBtn" @click="menu('false',3)" v-if="active3 === 'true' ">
          <div class="my-auto ml-5 bg-yellow-300 rounded-full btnSwitch" >
            <!-- button -->
          </div>
        </div>
        <div class="mx-auto my-auto activeBtn"  @click="menu('true',3)" v-if="active3 === 'false'">
          <div class="my-auto bg-gray-300 rounded-full btnSwitch">
            <!-- button -->
          </div>
        </div>
        </div>
        </div>
        <div class="my-auto ml-8 lg:mt-0 md:ml-6 lg:ml-8" style="width: 300px;">Receive all takedown completed confirmations. </div>
      </div>

   </div>

    <div
      class="flex items-center justify-between my-2 cursor-pointer lg:mx-6 sm:mx-2 sm:mr-3 bigDropdownBg h-14"
        style="border-radius: 6px; "
        id="myDiv">
      <div class="ml-5 text-2xl text-black">
          <h3 class="orgDomain text-capitalize ">Push Notifications</h3>
      </div>
    </div>
  <div class="mt-2 lg:mx-6 sm:mx-2 sm:mr-3" style=" height: 20vh; border-radius: 6px; border: 1px solid #BFBFBF; ">
       
      <div class="flex mx-4 my-3 mb-3 " style="max-height: 7vh; min-width: 600px;">
        <div class="flex" >
        <div class="my-auto font-bold lg:ml-9 md:ml-9" style="width: 200px;" >News And Updates</div>
        <div class="my-auto" style="min-width: 200px;">
            <div class="mx-auto my-auto activeBtn" @click="menu('false',4)" v-if="active4 === 'true' ">
          <div class="my-auto ml-5 bg-yellow-300 rounded-full btnSwitch" >
            <!-- button -->
          </div>
        </div>
        <div class="mx-auto my-auto activeBtn"  @click="menu('true',4)" v-if="active4 === 'false'">
          <div class="my-auto bg-gray-300 rounded-full btnSwitch">
            <!-- button -->
          </div>
        </div>
        </div>
        </div>
        <div class="my-auto ml-8 lg:mt-0 md:ml-6 lg:ml-8" style="width: 300px;">Receive all updates notifications  </div>
      </div>

            <div class="flex mx-4 my-5 mb-3 " style="max-height: 7vh; min-width: 600px;">
        <div class="flex" >
        <div class="my-auto font-bold lg:ml-9 md:ml-9" style="width: 200px;" >Reminders</div>
        <div class="my-auto" style="min-width: 200px;">
            <div class="mx-auto my-auto activeBtn" @click="menu('false',5)" v-if="active5 === 'true' ">
          <div class="my-auto ml-5 bg-yellow-300 rounded-full btnSwitch" >
            <!-- button -->
          </div>
        </div>
        <div class="mx-auto my-auto activeBtn"  @click="menu('true',5)" v-if="active5 === 'false'">
          <div class="my-auto bg-gray-300 rounded-full btnSwitch">
            <!-- button -->
          </div>
        </div>
        </div>
        </div>
        <div class="my-auto ml-8 lg:mt-0 md:ml-6 lg:ml-8" style="width: 300px;">Recieve all takedown requests processed. </div>
      </div>
      
   </div>
       <div class="flex justify-between float-right h-16 mr-6 w-60">
       <Link class="my-auto bg-gray-300 buttons buttonsText" href=" " > Reset</Link>
        <button class="my-auto bg-yellow-300 buttons buttonsText" @click="openModal"> Save Changes </button>
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
                  <div class="my-auto">Are you sure you want to you want to change notification settings?</div>
                </div>
                <div class="relative w-100" >
                   <button class="float-right px-4 bg-yellow-300 buttons buttonsText" @click="submit()">confirm</button>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </SettingsLayout>
</template>

<style scoped>
.btnSwitch{
width: 21.124px;
height: 18px;
flex-shrink: 0;
fill: var(--Test1, rgb(181, 180, 174));
}
.activeBtn{
  width: 43.934px;
height: 19.672px;
flex-shrink: 0;
border-radius: 30px;
border: 1px solid #151414;
background: var(--dark-neutral-dark-neutral-9, #454545);
}
.custom-file-upload {
  display: inline-block;
  padding: 10px 20px;
  background: var(--yellow-yellow-100);
  color: rgb(114, 49, 6);
  border: none;
  cursor: pointer;
  border-radius: 4px;
}

.custom-file-upload input {
  display: none; /* Hide the actual file input */
}

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
  max-width: 624px;
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
 max-width: 474px;
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
.arWidth{
  width: 50%;
}
@media (max-width: 1200px) {
  .arWidth{
  width: 90%;
}
}
</style>










 