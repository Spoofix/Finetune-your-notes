<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { Link } from "@inertiajs/vue3"
import { defineComponent } from 'vue';
import { defineProps, onMounted } from 'vue';
import { ref, watch } from 'vue';
import 'intl-tel-input/build/css/intlTelInput.css';

const isModalVisible = ref(false);

const openModal = () => {
  isModalVisible.value = true;
  setTimeout(() => {
    closeModal();
  }, 7000);
};

const closeModal = () => {
  isModalVisible.value = false;
};


watch(isModalVisible, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
      // Close the modal
      closeModal();
    }, 300); 
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

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

defineProps({
    users: {
        type: Object,
    },
    // page: {
    //     type: String,
    // },
});


const approve = (id) => {

    const form = useForm({
        user_id: id,
    });

    form.post(route('user.approve'), {
        onFinish: () => {
            Toast.fire({
                icon: 'success',
                title: 'User Activated Successfully'
            })
        },
        onSuccess: () => {},
    });

};


const lock = (id) => {

    const form = useForm({
        user_id: id,
    });

    form.post(route('user.lock'), {
        onFinish: () => {
            Toast.fire({
                icon: 'success',
                title: 'User Deactivated Successfully'
            })
        },
        onSuccess: () => { },
    });

};

const form = useForm({
    name: '',
    second_name: '',
    phone_number: '',
    email: '',
    user_role: '',
});

function submit() {
    // Validate the form fields
    if (!form.name || !form.second_name || !form.phone_number || !form.email || !form.user_role) {
        Toast.fire({
            icon: 'error',
            title: 'All fields are required',
        });
        return;
    }
    // console.log(form);
    const formData = new FormData();
    formData.append('name', form.abuse_type);
    formData.append('second_name', form.evidence_urls);
    formData.append('phone_number', form.additional_notes);
    formData.append('email', form.observed_date);
    formData.append('user_role', form.targetDomain);

    router.post('/settings/addUser', form)
        Toast.fire({
            icon: 'success',
            title: 'Email to reset password has been sent to the new users email Successfully',
        });
        activeOne.value = 'Organization';
   
}

onMounted(async () => {
  // Load the intlTelInput library dynamically
  const { default: intlTelInput } = await import('intl-tel-input');
  
  const phoneInput = document.querySelector("#phone");
  
  if (phoneInput) {
    intlTelInput(phoneInput, {
      initialCountry: 'auto',
      geoIpLookup: (callback) => {
        fetch('https://ipapi.co/json')
          .then((res) => res.json())
          .then((data) => callback(data.country_code))
          .catch(() => callback('us'));
      },
      utilsScript: '/intl-tel-input/js/utils.js?1701962297307',
    });
  }

});
</script>

<template>

  <Head title="Users" />

  <SettingsLayout class="overflow-scroll fontFamily" style="height:100vh; background: #FFF;"> <!-- v-if=" props.userid === userId" -->
    <div class="flex justify-between mt-6 mr-6 border-b-4 border-black lg:ml-6 md:ml-6">
      <div class="relative ">
        <button @click="menu('Profile')" class="absolute w-40 h-12 px-3 rounded-tr-full tabsText" :class="activeOne === 'Profile' ? 'bg-dark' : 'bg-gray-300'" style="">Profile</button>
        <button @click="menu('Organization')" class="w-56 h-12 pr-4 rounded-tr-full tabsText pl-9" style="margin-left: 106px;" :class="activeOne === 'Organization' ? 'bg-dark' : 'bg-gray-300'">Organization</button>
      </div>
      <!-- <Link class="my-auto buttons buttonsText mr-9" ><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link> -->
    </div>
    <div class="flex items-center justify-between mx-4 my-2 mr-6 cursor-pointer bigDropdownBg h-14" style="border-radius: 6px; " id="myDiv">
      <div class="ml-5 text-2xl text-black">
        <h3 class="orgDomain text-capitalize ">User Accounts</h3>
      </div>
    </div>
    <!-- User -->
    <div class="flex flex-wrap justify-between mt-0 lg:mx-4 lg:flex-row" v-if="activeOne === 'Profile'" style=" min-height: 24vh; overflow: auto;  border-radius: 6px; border: 1px solid #BFBFBF; ">
      <div class="w-full" style="width: 74%;">
        <h3 class="mt-4 ml-8 text-black h3">Add Another Account</h3>
        <form action="" @submit.prevent="submitForm">
          <div class="mt-3 lg:flex md:mr-12 md:flex lg:mr-20 ml-9">
            <label for="FirstName" class="my-auto font-bold" style="width: 40%;">First Name</label>
            <div class="relative ml-10" style="min-width: 60%;">
              <!-- <i class="absolute pl-2 m-2 text-2xl fa-brands fa-facebook-f" style="color: #1877F2;"></i> -->
              <input type="text" class="pl-10 rounded-3xl" style="width: 100%;" name="FirstName" placeholder="John" v-model="form.name" />
            </div>
          </div>
          <div class="mt-3 lg:mr-20 md:mr-12 lg:flex md:flex ml-9 " v-if="activeOne === 'Profile'">
            <label for="LastName" class="my-auto font-bold" style="width: 40%;">Last Name</label>
            <div class="relative ml-10" style="min-width: 60%;">
              <!-- <i class="absolute pl-2 m-2 text-2xl fa-brands fa-linkedin" style="color: #0A66C2;"></i> -->
              <input type="text" class="pl-10 rounded-3xl" style="width: 100%;" name="FirstName" placeholder="Doe" v-model="form.second_name" />
            </div>
          </div>
          <div class="mt-3 lg:mr-20 md:mr-12 lg:flex md:flex ml-9 ">
            <label for="PhoneNumber" class="my-auto font-bold" style="width: 40%;">Phone Number</label>
            <div class="relative ml-10" style="min-width: 60%;">
              <!-- <i class="absolute pl-2 m-2 text-2xl hover:text-blue-300 fa-brands fa-twitter" style="color: #1DA1F2;"></i> -->
              <input type="tel" id="phone" class="pl-10 rounded-3xl" ref="phoneInput" style="width: 137%;" name="FirstName" v-model="form.phone_number" />
              <!-- <input type="tel" id="phone" class=" ml-14 rounded-3xl" ref="phoneInput" style="min-width: 100%; !width:500%;" v-model="form.phone_number" /> -->
            </div>
          </div>
          <div class="mt-3 lg:mr-20 md:mr-12 lg:flex md:flex ml-9 ">
            <label for="EmailAddress" class="my-auto font-bold" style="width: 40%;"> Email Address</label>
            <div class="relative ml-10" style="min-width: 60%;">
              <!-- <i class="absolute pl-2 m-2 text-2xl fa-brands fa-square-instagram" style="color: #E4405F;"></i> -->
              <input type="email" class="pl-10 rounded-3xl" style="width: 100%;" name="FirstName" placeholder="adminUser@abc.com" v-model="form.email" />
            </div>
          </div>
          <div class="mt-3 lg:mr-20 md:mr-12 lg:flex md:flex ml-9 ">
            <label for="EmailAddress" class="my-auto font-bold" style="width: 40%;">User Role</label>
            <div class="relative ml-10" style="min-width: 60%;">
              <!-- <i class="absolute pl-2 m-2 text-2xl fa-brands fa-square-instagram" style="color: #E4405F;"></i> -->
              <!-- <input type="text" class="pl-10 rounded-3xl" style="width: 100%;" name="FirstName" placeholder="AdminUser"/>
                  <i class="absolute mt-2 text-xl -ml-9 fa-solid fa-chevron-down text-dark-500"></i>
                  <div class="mt-1 align-middle bg-yellow-100 rounded-full w-100 h-9"><span class="pl-10 mt-6 text-dark-500">AdminUser</span></div>
                  
                  <div class="mt-1 align-middle bg-yellow-100 rounded-full w-100 h-9"><span class="pl-10 mt-6 text-dark-500">User</span></div> -->

              <select id="abuseType" name="abuseType" class="w-full h-10 pl-3 pr-10 text-gray-300 border border-black rounded-3xl reportFormText" v-model="form.user_role">
                <option value="" disabled selected class="pl-10 mt-6 text-black border border-black text-dark-500">...Choose User Role...</option>
                <option class="mt-1 align-middle bg-yellow-100 rounded-full w-100 h-9" value="User">User</option>
                <option class="mt-1 align-middle bg-yellow-100 rounded-full w-100 h-9" value="AdminUser">AdminUser</option>
              </select>

            </div>
          </div>
          <!-- <div class="ml-1" style="width:100%;"> -->
          <button class="float-right w-56 my-2 bg-gray-300 lg:mr-20 buttons buttonsText md:mr-12 " type="submit" @click="submit()"><i class="my-auto fa-solid fa-plus"></i> Add Another Account </button>
          <!-- </div> -->
        </form>
      </div>
      <!-- <div class="ml-1" style=" width: 40%; max-width: 365px: min-width: 350px;">
     <Link class="bg-yellow-300 my-7 buttons buttonsText mx-14"> Manage Media Accounts </Link>
    </div> -->
    </div>
    <!-- uu -->

    <div class="flex justify-between float-right h-16 mr-6 w-60" v-if="activeOne === 'Profile'">
      <button class="my-auto bg-gray-300 buttons buttonsText" @click="openModal"> Reset Item</button>
      <button class="my-auto bg-yellow-300 buttons buttonsText" @click="openModal"> Save Changes </button>
    </div>

    <div class="block block-rounded" v-if="activeOne === 'Organization'">
      <div class="block-header block-header-default">
        <h3 class="pl-6 font-black block-title h1">Organization:{{ Organization }} Users list</h3>
        <Link :href="route('users.list.pending')" class="mr-3 bg-yellow-300 hover:text-yellow-500 btn hover:border-amber-300 " v-if="$page.props.page != 'pending'">Pending User</Link>
        <Link :href="route('users.list')" class="mr-3 bg-yellow-300 btn hover:text-yellow-500 hover:border-amber-300" v-if="$page.props.page != 'users'">All Users</Link>
        <Link :href="route('user.create')" class="mr-3 text-yellow-500 btn border-amber-400 hover:bg-yellow-300 hover:text-dark-500">Create User</Link>
      </div>
      <div class="block-content">
        <table class="table table-bordered table-striped table-vcenter">
          <thead>
            <tr>
              <th class="text-center"></th>
              <th>Name</th>
              <th class="d-none d-sm-table-cell">Email</th>
              <th class="d-none d-sm-table-cell" style="width: 15%;">Org User Role</th>
              <th class="text-center" style="width: 15%;">Status</th>
              <th class="text-center" style="width: 15%;">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in users" v-bind:key="user">
              <td class="text-center">{{ ++index }}</td>
              <td class="capitalize fw-semibold">{{ user.name }} {{ user.second_name}}</td>
              <td class="d-none d-sm-table-cell">{{ user.email }}</td>
              <td class="d-none d-sm-table-cell" v-if="user.org_role_id === 1">
                Admin
              </td>
              <td class="d-none d-sm-table-cell" v-else>
                User
              </td>
              <th class="text-center" style="width: 15%;">
                <span class="badge bg-success" v-if="user.status == 'ACTIVE'">{{ user.status }}</span>
                <span class="badge bg-warning" v-if="user.status == 'PENDING'">{{ user.status }}</span>
                <span class="badge bg-danger" v-if="user.status == 'LOCKED'">{{ user.status }}</span>
              </th>
              <td class="text-center">
                <Link @click="approve(user.id)" class="mr-1 btn btn-sm btn-success" data-bs-toggle="tooltip" title="Approve User" v-if="user.status != 'ACTIVE'">
                <i class="fa fa-check"></i>
                </Link>
                <Link @click="lock(user.id)" class="mr-1 btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Lock User" v-if="user.status == 'ACTIVE'">
                <i class="fa fa-times"></i>
                </Link>
                <Link :href="route('user.view', user.id)" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="View User">
                <i class="fa fa-eye"></i>
                </Link>
              </td>
            </tr>

          </tbody>
        </table>

      </div>
    </div>
    <!-- Modal -->
    <transition name="modal-fade">
      <div v-if="isModalVisible" class="backlight" @click="closeModal">
        <div role="dialog" aria-modal="true" class="fade image-modal dark modal show backlight" tabindex="-1" style="padding-left: 14px; display: block;">
          <div class="modal-dialog modal-xl modal-dialog-centered" style="">
            <div class="modal-content">
              <div class="">
                <div class="mx-auto bg-white modelStyle" style="">
                  <div class="relative w-100">
                    <img class="float-right" :src="'/assets/systemImages/Exit.svg'" />
                  </div>
                  <div class="relative flex my-2 modelText">
                    <img class="pr-3 " :src="'/assets/systemImages/Promo.svg'" />
                    <img class="absolute m-2" :src="'/assets/systemImages/bookmark.svg'" />
                    <div class="my-auto">Are you sure you want to reset data?</div>
                  </div>
                  <div class="relative w-100">
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
/* animation*/
  @keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
      transform: translateY(0);
      opacity: 96%
    }
    40% {
      transform: translateY(-20px);
      opacity: 98%
    }
    60% {
      transform: translateY(-10px);
      opacity: 100%
    }
  }

  .modal-content {
    animation: bounce 800ms 1;
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
/* Add more custom styles as needed */
</style>










 