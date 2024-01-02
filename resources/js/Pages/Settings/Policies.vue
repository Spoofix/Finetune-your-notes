<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { Link } from "@inertiajs/vue3"
import { defineComponent } from 'vue';
import { defineProps, onMounted } from 'vue';
import { ref, watch } from 'vue';

const isModalVisible = ref(false);

const openModal = (adding) => {
  form.Accepted_or_declined = adding;
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


defineComponent({
  components: {
    Link
  },

});
const props = defineProps({
  organization: {
    type: Object
  },
   theUser: {
    type: Object
  }
})
// console.log(props.theUser)
const activeOne = ref('Terms &Conditions');
const menu =(now) => {
  activeOne.value = now;
}
const activeCards = ref('Free');
const cards =(now) => {
  activeCards.value = now;
}
const array = ref( ["none",'','']);

if (props.organization.terms_and_conditions){
const string = props.organization.terms_and_conditions;
console.log(string);

array.value = JSON.parse(string);
  // .replace(/['\[\]]/g, '')
  // .split(', ');
console.log(array[0])

}else{
array.value = ["none",'',''];
}

// console.log(array);
const toggle = ref(false);
function myToggle(){
 toggle.value = !toggle.value;
}

const form = useForm({
    Accepted_or_declined: ''
});

function submit() {
    if (form.Accepted_or_declined == null) {
        Toast.fire({
            icon: 'error',
            title: 'All fields are required'
        })
        return;
    }

    router.post('/settings/terms_and_conditions', form)
    const string = props.organization.terms_and_conditions;

    array.value = JSON.parse(string);
    
    if (form.Accepted_or_declined == 'accepted'){
            Toast.fire({
          icon: 'success',
          title: 'Terms and Conditions Accepted!'
      })
    }else{
          Toast.fire({
          icon: 'error',
          title: 'Terms and Conditions Declined!'
          })
    }
    // location.reload();
    router.reload();

}
</script>

<template>
  <Head title="Policies" />

 <SettingsLayout  class="overflow-scroll fontFamily" style="height:100vh; background: #FFF;"> <!-- v-if=" props.userid === userId" -->
   <div class="flex justify-between mt-6 ml-0 mr-8 border-b-4 border-black max-w-100 lg:ml-5 md:ml-6">
    <div class="relative ">
      <button @click="menu('Terms &Conditions')" class="absolute h-12 px-3 rounded-tr-full w-60 tabsText" :class="activeOne === 'Terms &Conditions' ? 'bg-dark' : 'bg-gray-300'" style="">Terms &Conditions</button>
      <button @click="menu('Privacy')" class="h-12 pr-6 rounded-tr-full w-60 pl-36 tabsText" style="margin-left: 106px;" :class="activeOne === 'Privacy' ? 'bg-dark' : 'bg-gray-300'" >Privacy</button>
    </div>
      <!-- <Link class="my-auto buttons buttonsText mr-9" ><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link> -->
   </div>
   <!-- Terms &Conditions -->
   <div class="flex justify-between mt-2 bg-yellow-100 lg:mr-3 md:mr-4 lg:mx-4 md:mx-4" style="height: 60px; width: 96%;" v-if="activeOne === 'Terms &Conditions' && array[0] == 'accepted' && !toggle" >
    <p class="m-3 h5">Terms and Conditions <span class="capitalize">{{array[0]}}</span></p>
    <p class="m-3 capitalize h5">{{array[2]}}</p>
    <p class="m-3 capitalize h5"> {{theUser[0]}} {{theUser[1]}}</p>
    <button class="my-auto mr-4 text-xl bg-yellow-300 buttons buttonsText" @click="myToggle"  v-if="activeOne === 'Terms &Conditions' && array[0] == 'accepted'"> <span class="mt-3 h5">view</span> </button>


   </div>
   <div class="mt-2 bg-yellow-100 lg:mr-4 md:mr-4 lg:mx-4 md:mx-4" style="min-height: 80vh; width: 96%;" v-if="activeOne === 'Terms &Conditions' && array[0] != 'accepted' || activeOne === 'Terms &Conditions' && array[0] == 'accepted' && toggle"  >
      <h1 class="pt-3 mx-auto font-light text-black h2 max-w-fit">Terms and Conditions</h1>
      <h3 class="text-black ml-9 h4">Terms </h3>
      <p class="mx-auto font-bold text-black" style="width: 94%;">Terms and Conditions agreement acts as a legal contract between you {{organization.name}} and the user. It's where you maintain your rights to exclude users from your app in the event that they abuse your website/app, set out the rules for using your service and note other important details and disclaimers.
          Having a Terms and Conditions agreement is completely optional. No laws require you to have one. Not even the super-strict and wide-reaching General Data Protection Regulation (GDPR).
          Your Terms and Conditions agreement will be uniquely yours. While some clauses are standard and commonly seen in pretty much every Terms and Conditions agreement, it's up to you to set the rules and guidelines that the user must agree to.
        </p>
      <h3 class="mt-4 text-black ml-9 h4">Privacy </h3>
      <p class="mx-auto font-bold text-black" style="width: 94%;">Terms and Conditions agreement acts as a legal contract between you {{organization.name}} and the user. It's where you maintain your rights to exclude users from your app in the event that they abuse your website/app, set out the rules for using your service and note other important details and disclaimers.
          Having a Terms and Conditions agreement is completely optional. No laws require you to have one. Not even the super-strict and wide-reaching General Data Protection Regulation (GDPR).
          Your Terms and Conditions agreement will be uniquely yours. While some clauses are standard and commonly seen in pretty much every Terms and Conditions agreement, it's up to you to set the rules and guidelines that the user must agree to.
        </p>
      <h3 class="mt-4 text-black ml-9 h4">Use License </h3>
      <p class="mx-auto font-bold text-black" style="width: 94%;">Terms and Conditions agreement acts as a legal contract between you {{organization.name}} and the user. It's where you maintain your rights to exclude users from your app in the event that they abuse your website/app, set out the rules for using your service and note other important details and disclaimers.
          Having a Terms and Conditions agreement is completely optional. No laws require you to have one. Not even the super-strict and wide-reaching General Data Protection Regulation (GDPR).
          Your Terms and Conditions agreement will be uniquely yours. While some clauses are standard and commonly seen in pretty much every Terms and Conditions agreement, it's up to you to set the rules and guidelines that the user must agree to.
        </p>
    <div class="flex justify-between float-right h-16 mb-5 mr-6 w-44">
       <button class="my-auto bg-gray-300 buttons buttonsText" @click="openModal('declined')"  v-if="activeOne === 'Terms &Conditions' && array[0] != 'accepted'"> Decline</button>
        <button class="my-auto bg-yellow-300 buttons buttonsText" @click="openModal('accepted')"  v-if="activeOne === 'Terms &Conditions' && array[0] != 'accepted' " > Accept </button>
        <!--  v-bind="form.Accepted_or_declined = accepted"> -->
        <button class="my-auto bg-yellow-300 buttons buttonsText ml-9 " @click="myToggle"  v-if="activeOne === 'Terms &Conditions' && array[0] == 'accepted'"> <span class="mt-3 h5">close</span> </button>

    </div>
   </div>
    <div class="mt-2 bg-yellow-100 lg:mr-4 md:mr-4 lg:mx-4 md:mx-4" style="min-height: 80vh; width: 96%;" v-if="activeOne === 'Privacy'">
      <h1 class="pt-3 mx-auto font-light text-black h2 max-w-fit">Privacy Policies</h1>
      <h3 class="text-black ml-9 h4">Policy </h3>
      <p class="mx-auto font-bold text-black" style="width: 94%;">Terms and Conditions agreement acts as a legal contract between you {{organization.name}} and the user. It's where you maintain your rights to exclude users from your app in the event that they abuse your website/app, set out the rules for using your service and note other important details and disclaimers.
          Having a Terms and Conditions agreement is completely optional. No laws require you to have one. Not even the super-strict and wide-reaching General Data Protection Regulation (GDPR).
          Your Terms and Conditions agreement will be uniquely yours. While some clauses are standard and commonly seen in pretty much every Terms and Conditions agreement, it's up to you to set the rules and guidelines that the user must agree to.
        </p>
      <h3 class="mt-4 text-black ml-9 h4">Privacy </h3>
      <p class="mx-auto font-bold text-black" style="width: 94%;">Terms and Conditions agreement acts as a legal contract between you {{organization.name}} and the user. It's where you maintain your rights to exclude users from your app in the event that they abuse your website/app, set out the rules for using your service and note other important details and disclaimers.
          Having a Terms and Conditions agreement is completely optional. No laws require you to have one. Not even the super-strict and wide-reaching General Data Protection Regulation (GDPR).
          Your Terms and Conditions agreement will be uniquely yours. While some clauses are standard and commonly seen in pretty much every Terms and Conditions agreement, it's up to you to set the rules and guidelines that the user must agree to.
        </p>
      <h3 class="mt-4 text-black ml-9 h4">Use License </h3>
      <p class="mx-auto font-bold text-black" style="width: 94%;">Terms and Conditions agreement acts as a legal contract between you {{organization.name}} and the user. It's where you maintain your rights to exclude users from your app in the event that they abuse your website/app, set out the rules for using your service and note other important details and disclaimers.
          Having a Terms and Conditions agreement is completely optional. No laws require you to have one. Not even the super-strict and wide-reaching General Data Protection Regulation (GDPR).
          Your Terms and Conditions agreement will be uniquely yours. While some clauses are standard and commonly seen in pretty much every Terms and Conditions agreement, it's up to you to set the rules and guidelines that the user must agree to.
        </p>
   </div>
    <!-- <div class="flex justify-between float-right h-16 mb-5 mr-6 w-44">
       <button class="my-auto bg-gray-300 buttons buttonsText" @click="openModal"  v-if="activeOne === 'Terms &Conditions'"> Decline</button>
        <button class="my-auto bg-yellow-300 buttons buttonsText" @click="openModal"  v-if="activeOne === 'Terms &Conditions'"> Accept </button>
    </div> -->
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
                   
                   <img v-if="form.Accepted_or_declined == 'accepted'"  class="pr-3 " :src="'/assets/systemImages/Promo.svg'"/>
                   <img v-else class="pr-3 " :src="'/assets/systemImages/Promo1.svg'"/>
                   <img v-if="form.Accepted_or_declined == 'accepted'"  class="absolute pt-2 mt-4 ml-2" :src="'/assets/systemImages/document-texti.svg'"/>
                   <img v-else class="absolute pt-2 mt-4 ml-2" :src="'/assets/systemImages/document-text.svg'"/>
                  <div v-if="form.Accepted_or_declined == 'accepted'" class="my-auto">By clicking this you have read the terms and conditions and accepted on behalf of {{organization.name}}.<br>Do you confirm?</div>
                  <div v-else class="my-auto">By clicking this you have read the terms and conditions and <span class="text-red-700">declined</span> on behalf of {{organization.name}}.<br>Do you confirm?</div>
                </div>
                <div class="relative w-100" >
                   <button class="float-right px-4 bg-yellow-300 buttons buttonsText " @click="submit" >confirm</button>
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
.bigText{
/* Bold/Heading 3/Bold */
font-family: Poppins;
font-size: 36px;
font-style: normal;
font-weight: 700;
line-height: 120%; /* 43.2px */
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
  min-height: 237px;
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
/* Add more custom styles as needed */
</style>










 