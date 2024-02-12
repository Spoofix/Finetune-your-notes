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
  plan: {
    type: Object,
  },
});

const activeOne = ref('Pricing');
const menu =(now) => {
  activeOne.value = now;
}
const activeCards = ref(props.plan.plan);
console.log(props.plan.plan);
const cards =(now) => {
  // activeCards.value = now;
}

const freePlan = ['monthly scans',' 1 domain', 'risk assessment', '1 organization user account', 'helpcenter', 'takedowns', 'takedown on behalf', 'automatic takedowns'];
const basicPlan = ['weekly scans', '2 domains', 'risk assessment' , '2 organization user accounts','helpcenter', '10 takedowns', '5 takedown on behalf', 'automatic takedowns'];
const premiumPlan = ['daily scans', '4 domains', 'risk assessment', '5 organization user accounts', 'helpcenter', '50 takedowns', '20 takedown on behalf', 'automatic takedowns'];

</script>

<template>
  <Head title="Pricing And Billing" />

 <SettingsLayout  class="overflow-scroll fontFamily" style="height:100vh; background: #FFF;"> <!-- v-if=" props.userid === userId" -->
  <div class=" row" v-if="$page.props.auth.user.role_id == 2 || props.isAdminSwitched" style="height: 100vh;">
    <div class="mx-auto ">
        <h1 class="font-extrabold text-center flicker mt-9 h1"><span class="text-yellow-300">spoo</span>fix pricing</h1>
        <h1 class="text-center h3 seesaw"> coming soon!</h1>
    </div>
</div>
<div v-else>


   <div class="flex justify-between mt-6 mr-8 border-b-4 border-black max-w-100 lg:ml-6 md:ml-6">
    <div class="relative ml-0 ">
      <button @click="menu('Pricing')" class="absolute h-10 rounded-tr-full lg:h-12 md:h-12 w-36 lg:px-3 lg:w-40 tabsText md:w-40" :class="activeOne === 'Pricing' ? 'bg-dark' : 'bg-gray-300'" style="">Pricing</button>
      <button @click="menu('Billing')" class="h-10 pl-5 rounded-tr-full md:h-12 lg:h-12 lg:pr-4 w-44 lg:w-56 tabsText lg:pl-9 md:pl-9 md:w-56 rightTab" style="margin-left: 106px;" :class="activeOne === 'Billing' ? 'bg-dark' : 'bg-gray-300'" >Billing</button>
    </div>
      <!-- <Link class="my-auto buttons buttonsText mr-9" ><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link> -->
   </div>

    <div
      class="flex items-center justify-between mx-3 my-2 mr-3 h-14"
      v-if="activeOne === 'Pricing'"
    >
    
         <div
       class="flex items-center justify-between my-2 cursor-pointer lg:mx-3 sm:mx-2 sm:mr-4 bigDropdownBg h-14 w-100"
        style="border-radius: 6px; "
        id="myDiv"
    >
      <div class="ml-5 text-2xl text-black">
          <h3 class="orgDomain text-capitalize ">Plans and Prices</h3>
      </div>
    </div>
    </div>
    <div class="flex flex-wrap justify-around flex-auto m-auto lg:ml-7 " style="height: 75vh; width: 95%;" v-if="activeOne === 'Pricing'">
      <!-- free -->
      <div class="relative mb-3 bg-white widthy" style="border-radius: 6px; border: 1px solid #BFBFBF; " @click="cards('Free')">
        <div class="w-full" style="height: 100px; border-radius: 6px;" :style="activeCards === 'Free' ? 'background: var(--dark-neutral-dark-neutral-8, #595959);' : 'background: var(--dark-neutral-dark-neutral-5, #D9D9D9);'">
          <h1 class="p-4 text-4xl font-extrabold bigText" :class="activeCards === 'Free' ? 'text-white' :'text-black'">Free</h1>
        </div>
        <div class="absolute mx-16 -mt-8 rounded w-60" :class="activeCards === 'Free' ? 'bg-yellow-300' :'bg-yellow-100'">
          <h1 class="p-4 text-5xl font-extrabold text-black ml-7 bigText">$ 0.00</h1>
        </div>
        <div class="h-10 mx-4 mt-20 mb-2 rounded" :class="activeCards === 'Free' ? 'bg-yellow-300' :'bg-yellow-100'">
          <h1 class="pt-2 ml-16 text-black h5">Monthly Offers</h1>
        </div>
        <div v-for="(freePlans, index) in freePlan" :key="index" >
          <!-- <div class="flex mx-16 mt-20">
            <i class="mr-6 text-black fa fa-check" style="border-radius: 6px; border: 2px solid #000;"></i>
            <h1 class="h-1 text-black h5">{{freePlans}}</h1>
          </div> -->
        <div class="flex mx-16 mt-2">
          <i v-if="freePlans === 'automatic takedowns' || freePlans ===  'helpcenter' || freePlans ===  'takedowns'  || freePlans === 'takedown on behalf'" class="mr-6 text-black fa fa-x" style="border-radius: 6px; border: 4px solid red;"></i>
          <i v-else class="mr-6 text-black fa fa-check" style="border-radius: 6px; border: 4px solid yellow;"></i>

          <h1  v-if="freePlans === 'automatic takedowns' || freePlans ===  'helpcenter' || freePlans ===  'takedowns' || freePlans === 'takedown on behalf'"  class="h-1 text-black line-through capitalize min-w-max h5">{{freePlans}}</h1>
          <h1 v-else class="h-1 text-black capitalize h5 min-w-max">{{freePlans}}</h1>
        </div>
        </div>

        <div class="relative w-full mt-3">
          <Link class="float-right my-auto mr-6 w-28 buttons buttonsText" :class="activeCards === 'Free' ? 'bg-yellow-300' :'bg-yellow-100'" href=" "> Current Plan </Link>
        </div>
        
      </div>
      <!-- basic -->
       <div class="relative mb-3 bg-white widthy" style="border-radius: 6px; border: 1px solid #BFBFBF;" @click="cards('Basic')">
        <div class="w-full" style="height: 100px; border-radius: 6px; " :style="activeCards === 'Basic' ? 'background: var(--dark-neutral-dark-neutral-8, #595959);' : 'background: var(--dark-neutral-dark-neutral-5, #D9D9D9);'">
          <h1 class="p-4 text-4xl font-extrabold bigText" :class="activeCards === 'Basic' ? 'text-white' :'text-black'">Basic</h1>
        </div>
        <div class="absolute mx-16 -mt-8 rounded w-60" :class="activeCards === 'Basic' ? 'bg-yellow-300' :'bg-yellow-100'">
          <h1 class="p-4 text-5xl font-extrabold text-black ml-7 bigText">$ 15.99</h1>
        </div>
        <div class="h-10 mx-4 mt-20 rounded" :class="activeCards === 'Basic' ? 'bg-yellow-300' :'bg-yellow-100'">
          <h1 class="pt-2 ml-16 text-black h5">Monthly Offers</h1>
        </div>
        <div v-for="(basicPlans, index) in basicPlan" :key="index" >
           <div class="flex mx-16 mt-2">

          <i v-if="basicPlans === 'automatic takedowns'" class="mr-6 font-bold text-black align-middle fa fa-x" style="border-radius: 6px; border: 4px solid red;"></i>
          <i v-else class="mr-6 font-bold text-black fa fa-check" style="border-radius: 6px; border: 4px solid #fefcf8;"></i>

          <h1 v-if="basicPlans === 'automatic takedowns'" class="h-1 text-black capitalize h5 min-w-max" style="text-decoration-line: line-through;">{{basicPlans}}</h1>
          <h1 v-else class="h-1 text-black capitalize min-w-max h5">{{basicPlans}}</h1>
          
        </div>
        </div>
       
      
        <div class="relative w-full mt-3">
          <Link href=" " class="float-right my-auto mr-6 w-28 buttons buttonsText" :class="activeCards === 'Basic' ? 'bg-yellow-300' :'bg-yellow-100'"> Buy Now </Link>
        </div>
        <!-- premium -->
      </div>
       <div class="relative mb-3 bg-white widthy" style="border-radius: 6px; border: 1px solid #BFBFBF;" @click="cards('Premium')">
        <div class="w-full" style="height: 100px; border-radius: 6px;" :style="activeCards === 'Premium' ? 'background: var(--dark-neutral-dark-neutral-8, #595959);' : 'background: var(--dark-neutral-dark-neutral-5, #D9D9D9);'">
          <h1 class="p-4 text-4xl font-extrabold bigText" :class="activeCards === 'Premium' ? 'text-white' :'text-black'">Premium</h1>
        </div>
        <div class="absolute mx-16 -mt-8 rounded w-60" :class="activeCards === 'Premium' ? 'bg-yellow-300' :'bg-yellow-100'">
          <h1 class="p-4 text-5xl font-extrabold text-black ml-7 bigText">$ 25.99</h1>
        </div>
        <div class="h-10 mx-4 mt-20 rounded" :class="activeCards === 'Premium' ? 'bg-yellow-300' :'bg-yellow-100'">
          <h1 class="pt-2 ml-16 text-black h5">Monthly Offers</h1>
        </div>
        <div v-for="(premiumPlans,index) in premiumPlan" :key="index">
          <div class="flex mx-16 mt-2">
          <i class="mr-6 text-black fa fa-check" style="border-radius: 6px; border: 4px solid #fefce8;"></i>
          <h1 class="h-1 text-black capitalize h5 min-w-max">{{premiumPlans}}</h1>
        </div>
        </div>
        
        <div class="relative w-full mt-3">
          <Link href=" " class="float-right my-auto mr-6 w-28 buttons buttonsText" :class="activeCards === 'Premium' ? 'bg-yellow-300' :'bg-yellow-100'" >Buy Now </Link>
        </div>
        
      </div>
    </div>
   <!-- billing -->
   <div v-if="activeOne === 'Billing'">
        <div
      class="flex items-center justify-between mx-4 my-2 mr-6 cursor-pointer bigDropdownBg h-14"
        style="border-radius: 6px; "
        id="myDiv"
    >
      <div class="ml-5 text-2xl text-black">
          <h3 class="orgDomain text-capitalize ">Plan Subscription</h3>
      </div>
    </div>

    <div class="flex flex-row justify-between mx-4 mt-2" style=" height: 20vh; border-radius: 6px; border: 1px solid #BFBFBF; ">
    <div class="w-full" style="width: 74%;">
        <form action="">
            <div class="mt-3 mr-20 lg:flex md:flex ml-9 ">
                  <label for="FirstName" class="my-auto font-bold" style="width: 40%;">Plan</label>
                <input type="text" class=" ml-14 rounded-3xl widthy2" style="" name="FirstName" placeholder="premium"/>
            </div>
              <div class="mt-3 mr-20 lg:flex md:flex ml-9 ">
                <label for="PhoneNumber" class="my-auto font-bold" style="width: 40%;" >Payment</label>
                <input type="number" class=" ml-14 rounded-3xl widthy2" style="" name="Payment" placeholder="$ 25.99"/>
            </div>

        </form>
    </div>
  
   </div>

    <div
      class="flex items-center justify-between mx-4 my-2 mr-6 cursor-pointer bigDropdownBg h-14"
        style="border-radius: 6px; "
        id="myDiv">
      <div class="ml-5 text-2xl text-black">
          <h3 class="orgDomain text-capitalize ">Payment Method</h3>
      </div>
    </div>
   <div class="relative justify-between pt-3 w-100"><!-- flex -->
      <div class="flex flex-row justify-between mx-4 -mt-2" style=" min-height: 25vh; border-radius: 6px; border: 1px solid #BFBFBF; ">
    <div class="w-full" style="width: 74%;">
        <form action="">
            <div class="mt-3 mr-20 lg:flex md:flex ml-9 ">
              <div class="flex">
                <input type="checkbox" class="mx-4 my-auto text-6xl text-yellow-300 border-black rounded-full h-7 w-7">
                <img  class="my-auto" style="width: 80px;" src="/assets/systemImages/visa.png" />
              </div>
                <input type="text" class="mt-3 ml-14 rounded-3xl" style="" name="FirstName" placeholder="premium"/>
            </div>
              <div class="mt-3 mr-20 lg:flex md:flex ml-9 ">
                <div class="flex">
                  <input type="checkbox" class="mx-4 my-auto text-6xl text-yellow-300 border-black rounded-full h-7 w-7">
                <img  class="my-auto" style="width: 80px;" src="/assets/systemImages/mastercard.png" />
                </div>
                <input type="number" class="mt-3 ml-14 rounded-3xl" style="" name="PhoneNumber" placeholder="$ 25.99"/>
            </div>
        <div class="flex flex-row h-10 mx-5 mt-3 mb-3 rounded w-80"  :class="activeCards === 'Basic' ? 'bg-yellow-300' :'bg-yellow-100'">
          <i class="my-auto ml-6 fa-solid fa-plus"></i>
          <h1 class="pt-2 ml-8 text-black h5">New Payment Method</h1>
        </div>

        </form>
    </div>
  
   </div>
    <div class="flex justify-between float-right w-48 h-16 mr-6">
       <button class="my-auto bg-gray-300 buttons buttonsText" @click="openModal" > Reset</button>
        <button class="my-auto bg-yellow-300 buttons buttonsText" @click="openModal"> Pay Here </button>
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
  </div>
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

.widthy{
  width: 32%;
  min-width: 360px;
  max-width: 400px;
  min-height: 500px;
}
@media (max-width: 767px) {
.widthy{
  width: 96%;
  margin-top: 20px;
 
}
}
</style>










 