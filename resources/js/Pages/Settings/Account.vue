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
const activeOne = ref('Profile');
const menu =(now) => {
  activeOne.value = now;
}

</script>

<template>
  <Head title="Account" />

 <SettingsLayout  class="overflow-scroll fontFamily" style="height:100vh; background: #FFF;"> <!-- v-if=" props.userid === userId" -->
   <div class="flex justify-between mt-6 border-b-4 border-black w-100 ">
    <div class="relative ml-6 ">
      <button @click="menu('Profile')" class="absolute w-40 h-12 px-3 rounded-tr-full tabsText" :class="activeOne === 'Profile' ? 'bg-dark' : 'bg-gray-300'" style="">Profile</button>
      <button @click="menu('Organization')" class="w-56 h-12 pr-4 rounded-tr-full tabsText pl-9" style="margin-left: 106px;" :class="activeOne === 'Organization' ? 'bg-dark' : 'bg-gray-300'" >Organization</button>
    </div>
      <Link class="my-auto buttons buttonsText mr-9" ><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link>
   </div>

    <div
      class="flex items-center justify-between mx-4 my-2 mr-6 cursor-pointer bigDropdownBg h-14"
        style="border-radius: 6px; "
        id="myDiv"
    >
      <div class="ml-5 text-2xl text-black">
          <h3 class="orgDomain text-capitalize ">Personal Profile</h3>
      </div>
    </div>

    <div class="flex flex-row justify-between mx-4 mt-2" style=" height: 35vh; border-radius: 6px; border: 1px solid #BFBFBF; ">
    <div class="w-full" style="width: 74%;">
        <form action="">
            <div class="flex mt-3 mr-20 ml-9 ">
                <label for="FirstName" class="my-auto font-bold" style="width: 40%;" v-if="activeOne === 'Profile'">First Name</label>
                                <label for="FirstName" class="my-auto font-bold" style="width: 40%;" v-else>Organization Name</label>
                <input type="text" class=" ml-14 rounded-3xl" style="width: 60%;" name="FirstName" placeholder="John"/>
            </div>
              <div class="flex mt-3 mr-20 ml-9 " v-if="activeOne === 'Profile'">
                <label for="LastName" class="my-auto font-bold" style="width: 40%;"  >Last Name</label>
                <input type="text" class=" ml-14 rounded-3xl" style="width: 60%;" name="LastName" placeholder="Doe"/>
            </div>
              <div class="flex mt-3 mr-20 ml-9 ">
                <label for="PhoneNumber" class="my-auto font-bold" style="width: 40%;" >Phone Number</label>
                <input type="number" class=" ml-14 rounded-3xl" style="width: 60%;" name="PhoneNumber" placeholder="+ 1 888-888-8888"/>
            </div>
              <div class="flex mt-3 mr-20 ml-9 ">
                <label for="EmailAddress" class="my-auto font-bold" style="width: 40%;" >Email Address</label>
                <input type="email" class=" ml-14 rounded-3xl" style="width: 60%;" name="EmailAddress" placeholder="johdooe@abc.com"/>
            </div>
            <div class="pl-12 mt-1 ml-96">Did not get a verification email?  <Link class="text-gray-400">Send again</Link></div>

        </form>
    </div>
    <div class="ml-1 bg-yellow-100" style=" width: 40%; max-width: 365px: min-width: 350px;">
      <div class="w-56 h-56 mx-auto my-2 bg-black rounded-full"> 
        <div style="font-size: 88px; padding: 60px;">
          <h1 class="text-yellow-300 text-bold">JD</h1>
        </div>
         <div class="float-right w-5 h-5 mr-3 -mt-16 bg-yellow-300 rounded-full">
          <i class="ml-1 -mt-1 text-lg font-bold text-black fa-solid fa-xmark"></i>
          </div>
      </div>
     <form class="mx-auto w-fit">
      <label for="custom-file-input" class="-mt-5 custom-file-upload">
        <input type="file" id="custom-file-input" accept=".jpg, .jpeg, .png" />
        <div style="font-size: 22px;">
          <i class="fa-regular fa-image"></i> Upload Image
        </div>
         <small class="ml-6 text-small">
          Resolution (520pxl x 520Pxl)
         </small>
      </label>
    </form>
    </div>
   </div>

    <div
      class="flex items-center justify-between mx-4 my-2 mr-6 cursor-pointer bigDropdownBg h-14"
        style="border-radius: 6px; "
        id="myDiv">
      <div class="ml-5 text-2xl text-black">
          <h3 class="orgDomain text-capitalize ">Password</h3>
      </div>
    </div>
   <div class="relative justify-between pt-3 w-100"><!-- flex -->
      <div class="flex flex-row justify-between mx-4 mb-3" style=" height: 7vh; border-radius: 6px; border: 1px solid #BFBFBF; ">
        <div class="my-auto font-bold ml-9" style="width: 34%;" >Reset Password</div>
        <small class="my-auto mr-48">Reset link will be sent to your email address. Please check your junk folder.</small>
        <Link class="my-auto bg-yellow-300 buttons buttonsText mr-7"  > Reset link</Link>
      </div>
    <div class="flex justify-between float-right h-16 mr-6 w-60">
       <button class="my-auto bg-gray-300 buttons buttonsText" @click="openModal" > Reset Item</button>
        <button class="my-auto bg-yellow-300 buttons buttonsText"> Save Changes </button>
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
                  <div class="my-auto">Are you sure you want to reset data?</div>
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
    </transition>
  </SettingsLayout>
</template>

<style scoped>

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










 