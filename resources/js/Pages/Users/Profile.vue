<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { Link } from "@inertiajs/vue3"
import { defineComponent } from 'vue';
import { defineProps, onMounted } from 'vue';
import { ref, watch } from 'vue';
import DeleteUserForm from '../Profile/Partials/DeleteUserForm.vue';
import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '../Profile/Partials/UpdateProfileInformationForm.vue';
import 'intl-tel-input/build/css/intlTelInput.css';

const isModalVisible = ref(false);

const openModal = () => {
  isModalVisible.value = true;
      setTimeout(() => {
    closeModal();
  }, 5000);
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
    // status: {
    //     type: String,
    // },
    auth_user: {
        type: Object,
    },
});

const form = useForm({
    user_id: props.auth_user.id,
    name: props.auth_user.name,
    second_name: props.auth_user.second_name,
    phone_number: props.auth_user.phone_number,
    email: props.auth_user.email,
    attachment: props.attachment ? props.attachment : null,
});

const imageUrl = ref(null);

const handleFileChange = (event) => {
  form.attachments = event.target.files[0];
  const fileInput = event.target;

  if (fileInput.files.length > 0) {
    const uploadedFile = fileInput.files[0];
    form.attachment = uploadedFile;
    const reader = new FileReader();

    reader.onload = (e) => {
      imageUrl.value = e.target.result;

    };

    reader.readAsDataURL(uploadedFile);
  }
};

function submit() {
    if (form.name == null || form.phone_number == null || form.email == null || form.second_name == null) {
        Toast.fire({
            icon: 'error',
            title: 'All fields are required'
        })
        return;
    }
    const formData = new FormData();
    if (form.attachment !== null) {
        formData.append('attachment', form.attachment);
        console.log('it is not null')
    }else{
      console.log('it is null')
    }
    // router.post('/profile-update', form)
    const response = form.post(route('profile.update'));
    Toast.fire({
        icon: 'success',
        title: 'Profile Created Successfully'
    })
    imageUrl.value = null;
    form.attachment = null;
    reload();
}

function emailSent(){
     Toast.fire({
            icon: 'success',
            title: 'Email sent to reset password successfully',
        });
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

const profile_pic = props.auth_user.profile_pic;
console.log(profile_pic);
</script>


<template>

  <Head title="User Profile" />

  <AuthenticatedLayout>
    <div class="flex justify-between mt-6 w-100">
      <div class="relative ml-6">
      </div>
      <Link class="my-auto buttons buttonsText mr-9 hover:bg-yellow-300" :href="'/admin/Org_Users'"><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link>
    </div>

    <div class="flex items-center justify-between mt-2 cursor-pointer lg:mx-6 sm:mx-2 sm:mr-3 bigDropdownBg h-14" style="border-radius: 6px; " id="myDiv">
      <div class="ml-5 text-2xl text-black">
        <h3 class="orgDomain text-capitalize ">Personal Profile</h3>
      </div>
    </div>

    <form action="" @submit.prevent="submit">
      <div class="justify-between mt-2 lg:mx-6 sm:mx-2 sm:mr-3 lg:flex lg:flex-row sm:flex-col md:flex-col" style=" min-height: 35vh; border-radius: 6px; border: 1px solid #BFBFBF; ">
        <div class="w-full" style="min-width: 74%; ">
          <div>
            <div class="mt-3 mr-20 lg:flex md:flex ml-9 ">
              <label for="FirstName" class="my-auto font-bold" style="width: 50%;">First Name</label>
              <input type="text" class=" ml-14 rounded-3xl" style="width: 90%;" v-model="form.name" name="FirstName" placeholder="John" />

            </div>
            <div class="mt-3 mr-20 lg:flex md:flex ml-9 ">
              <label for="LastName" class="my-auto font-bold" style="width: 50%;">Last Name</label>
              <input type="text" class=" ml-14 rounded-3xl" style="width: 90%;" v-model="form.second_name" name="LastName" placeholder="Doe" />
            </div>
            <div class="mt-3 mr-20 lg:flex md:flex ml-9 ">
              <label for="PhoneNumber" class="my-auto font-bold" style="width: 40%;">Phone Number</label>
              <input type="tel" id="phone" class=" -ml-14 rounded-3xl" ref="phoneInput" style="width: 135%;" v-model="form.phone_number" name="PhoneNumber">
              <!-- <input type="tel" id="phone" class=" ml-14 rounded-3xl" ref="phoneInput" style="min-width: 90%; !width:500%;" v-model="form.phone_number" /> -->
            </div>
            <div class="mt-3 mr-20 lg:flex md:flex ml-9 ">
              <label for="EmailAddress" class="my-auto font-bold" style="width: 50%;">Email Address</label>
              <input type="email" class=" ml-14 rounded-3xl" style="width: 90%;" v-model="form.email" name="EmailAddress" placeholder="johdooe@abc.com" />
            </div>
          </div>
        </div>
        <div class="mx-auto ml-1 bg-yellow-100 " style=" width: 100%; max-width: 465px: min-width: 350px;">
          <div v-if="form.attachment" class="w-56 h-56 mx-auto my-2 overflow-hidden rounded-full">
            <img v-if="imageUrl" :src="imageUrl" alt="Please Upload Image!" style="min-height: 14rem; width: auto;" />
          </div>
          <div v-else-if="props.auth_user.profile_pic" class="w-56 h-56 mx-auto my-2 overflow-hidden rounded-full">
            <img :src="profile_pic" alt="Profile Picture" style="min-height: 14rem; width: auto;" />
          </div>
          <div v-else class="w-56 h-56 mx-auto my-2 bg-black rounded-full">
            <div v-if="imageUrl == null " style="font-size: 88px; padding: 60px;">
              <h1 class="flex text-yellow-300 text-bold"><span class="capitalize">{{props.auth_user.name[0]}}</span><span class="capitalize">{{props.auth_user.second_name[0]}}</span></h1>
            </div>
            <div v-if="imageUrl == null" class="float-right w-5 h-5 mr-3 -mt-16 bg-yellow-300 rounded-full">
              <i class="ml-1 -mt-1 text-lg font-bold text-black fa-solid fa-xmark"></i>
            </div>
          </div>
          <form class="mx-auto w-fit" enctype="multipart/form-data">
            <label for="custom-file-input" class="-mt-5 custom-file-upload">
              <input type="file" id="custom-file-input" accept=".jpg, .jpeg, .png" @change="handleFileChange" />
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

      <div class="relative justify-between pt-3 w-100"><!-- flex -->
        <div class="flex justify-between float-right h-16 mb-5 lg:mx-6 sm:mx-2 sm:mr-3 w-60">
          <Link class="my-auto bg-gray-300 buttons buttonsText" type="button" href=" "> Reset Item</Link>
          <button class="my-auto bg-yellow-300 buttons buttonsText" :class="form.attachment ? 'fa-bounce ': 'buttonsText'" type="button" @click="openModal"> Save Changes </button>
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
                      <div class="my-auto">Are you sure you want to apply this changes to your profile?</div>
                    </div>
                    <div class="relative w-100">
                      <!-- <button class="float-right px-4 mr-3 bg-gray-300 buttons buttonsText" @click="closeModal" type="button">cancel</button> -->
                      <button class="float-right px-4 bg-yellow-300 buttons buttonsText" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">confirm</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </form>
  </AuthenticatedLayout>
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
.rightTab{
  margin-left: 106px;
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
/* Add more custom styles as needed */
</style>










 
    <!-- <form @submit.prevent="submit">
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-4 row">
                    <div class="col-6">
                      <label class="form-label">Firstname</label>
                      <input type="text" class="form-control form-control-lg" v-model="form.first_name" placeholder="Enter your firstname..">
                    </div>
                    <div class="col-6">
                      <label class="form-label">Lastname</label>
                      <input type="text" class="form-control form-control-lg" v-model="form.last_name" placeholder="Enter your lastname..">
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="mb-4 row">
                    <div class="col-12">
                      <label class="form-label">Address</label>
                      <input type="text" class="form-control form-control-lg" v-model="form.address" placeholder="Enter your address..">
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="mb-4 row">
                    <div class="col-6">
                      <label class="form-label">City</label>
                      <input type="text" class="form-control form-control-lg" v-model="form.city" placeholder="Enter your city..">
                    </div>
                    <div class="col-6">
                      <label class="form-label">Phone Number</label>
                      <input type="text" class="form-control form-control-lg" v-model="form.phone_number" placeholder="Enter your phone number..">
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="mb-4 row">
                    <div class="col-6">
                      <label class="form-label">Province</label>
                      <select class="form-select" v-model="form.province">
                        <option :value="null">Select A Province</option>
                        <option v-for="province in provinces" v-bind:key="province" :value="province">{{ province }}</option>
                      </select>
                    </div>
                    <div class="col-6">
                      <label class="form-label">TImezone</label>
                      <select class="form-select" v-model="form.timezone">
                        <option :value="null">Select A timezone</option>
                        <option v-for="timezone in timezones" v-bind:key="timezone" :value="timezone.location">{{ timezone.time }}</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-4">
                <button class="btn btn-primary btn-primary-custom" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                  <i class="opacity-50 fa fa-check me-1"></i> {{ button }}
                </button>
              </div>
            </form> -->
    <!-- <div class="block-content">
      <div class="row">
        <div class="col-md-6">
        <div class="">
          <table class="table table-borderless table-striped">
            <thead>
              <tr>
                <th style="width: 200px;"></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><a class="fw-semibold">Name</a></td>
                <td><span class="fw-bold">{{ $page.props.user.name }}</span> </td>
              </tr>
              <tr>
                <td><a class="fw-semibold">Email</a></td>
                <td><span class="fw-bold">{{ $page.props.user.email }}</span> </td>
              </tr>
              <tr>
                <td><a class="fw-semibold">Address</a></td>
                <td><span class="fw-bold">{{ $page.props.user.address }}</span> </td>
              </tr>
              <tr>
                <td><a class="fw-semibold">Phone Number</a></td>
                <td><span class="fw-bold">{{ $page.props.user.phone_number }}</span> </td>
              </tr>
              <tr>
                <td><a class="fw-semibold">City</a></td>
                <td><span class="fw-bold">{{ $page.props.user.city }}</span> </td>
              </tr>
              <tr>
                <td><a class="fw-semibold">Province</a></td>
                <td><span class="fw-bold">{{ $page.props.user.province }}</span> </td>
              </tr>
              <tr>
                <td><a class="fw-semibold">Status</a></td>
                <td><span class="fw-bold">{{ $page.props.user.status }}</span> </td>
              </tr>
              <tr>
                <td><a class="fw-semibold">Timezone</a></td>
                <td><span class="fw-bold">{{ $page.props.user.timezone }}</span> </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-md-6">
        
          </div>
      </div>
    </div> -->
