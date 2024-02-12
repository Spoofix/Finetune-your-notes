<script setup>
import { Head, useForm, useRemember } from '@inertiajs/vue3';
import {Link} from "@inertiajs/vue3"
import { defineComponent, ref } from 'vue';
import { defineProps, onMounted } from 'vue';
import 'intl-tel-input/build/css/intlTelInput.css';

defineComponent({
  components: {
    Link
  },
  props: {
    error: Object
  },
});
// defineProps({
//     provinces: {
//         type: Object,
//     },
//     timezones: {
//         type: Object,
//     }
// });

onMounted(async () => {
  // Load the intlTelInput library dynamically
  const { default: intlTelInput } = await import('intl-tel-input');
  
  const phoneInput = document.querySelector("#phone_number_outlined");
  
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


const form = useForm({
    name: '',
    organization: '',
    phone_number: '',
    email: '',
    domains: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    // var password = form.password; 
    // if (isStrongPassword(password)) {
    //     console.log("Password is strong!");
    //     form.errors.password = "Password is strong!";
    //     return;
    // } else {
    //     console.log("Password is not strong. It should have at least 8 characters, including uppercase, lowercase, and numeric characters.");
    //     form.errors.password = "Password is not strong. It should have at least 8 characters, including uppercase, lowercase, and numeric characters.";

    //     return;
    // } 
    if (form.name && form.name.split(" ").length >= 2) {
      form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
      });
    } else {
      form.errors.name = 'full name required';
    }
}

//will work on it
function isStrongPassword(password) {
    var strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
    var strongPasswordRegex2 = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,}$/;
    var strongPasswordRegex3 = /^(?=(?:.*[a-z]){1,})(?=(?:.*[A-Z]){1,})(?=(?:.*\d){1,})(?=(?:.*[@$!%*?&]){1,})[a-zA-Z\d@$!%*?&]{8,}$/;

    return strongPasswordRegex.test(password) || strongPasswordRegex2.test(password) || strongPasswordRegex3.test(password);
}

//
const passwordInput = document.getElementsByClassName('passwordThing');
const passwordVisible = ref(false);


const passwordDetails = () => {
  passwordVisible.value = !passwordVisible.value;
};

</script>

<template>
        <Head title="Register" />
          <!-- Main Container -->
            <!-- Page Content -->
                    <!-- Header -->
                    <div class="overflow-hidden bg-white rounded lg:flex md:flex" style="width:fit-content; margin: auto; margin-top: 50px;">
                        <!-- <div class="hide" style="max-width: 600px; min-width: 500px; min-height: 100%; align-items: center;">
                        <img
                              src="/assets/media/photos/login.jpg"
                              alt="..."
                              style="height: 100%; align-self: center;"

                        />
                              </div> -->
                    <!-- END Header -->
                    <div style="max-width: 600px; min-width: 500px">
                    <form @submit.prevent="submit">


                        <div class=" block-header">

                        </div>   
                        <div class="py-1 text-center ">
                      <h1 class="mt-1 mb-1 h3 fw-bold">Create account</h1>
                    </div>
                        <div class=" block-content">
                              <div class="relative">
                                <input type="text" id="organization_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.organization" required placeholder=" " />
                                <label for="organization_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Organization</label>
                                <small v-if="form.errors.organization" class="text-red-600">{{ form.errors.organization }}</small>
                            </div>
                            <div class="relative">
                                <input type="text" id="full_name_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.name" required placeholder=" " />
                                <label for="full_name_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Full name</label>
                                <small v-if="form.errors.name" class="text-red-600">{{ form.errors.name }}</small>
                            </div>
                            <div class="relative w-full mb-4">
                                <input type="tel"  id="phone_number_outlined" style="min-width:164%;" class="w-full block px-2.5 pb-2.5 pt-4 text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.phone_number" required  placeholder=" "  ref="phoneInput"/>
                                <!-- <input type="tel" id="phone" class=" ml-14 rounded-3xl" ref="phoneInput" style="min-width: 90%; !width:500%;" v-model="form.phone_number" /> -->

                                <label for="phone_number_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Phone number</label>
                                <small v-if="form.errors.phone_number" class="text-red-600">{{ form.errors.phone_number }}</small>
                            </div>

                            <div class="relative">
                                <input type="email" id="email_outlined"  class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.email" required  placeholder=" " />
                                <label for="email_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Email</label>
                                <small v-if="form.errors.email" class="text-red-600">{{ form.errors.email }}</small>
                            </div>

                            <div class="relative">
                                <input type="text" id="domain_outlined"  class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.domains" required  placeholder=" " />
                                <label for="domain_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Domain /s <small>eg: example.com, example2.com</small></label>
                                <small v-if="form.errors.domains" class="text-red-600">{{ form.errors.domains }}</small>
                            </div>

                            <div class="grid items-end gap-2 md:grid-cols-2">
                              <div class="relative">
                                  <input :type="!passwordVisible ? 'password' : 'text'" id="password_outlined" class="passwordThing block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.password" required  placeholder=" " />
                                  <i v-if="passwordVisible" class="absolute fa-regular fa-eye top-7 right-3"  @click="passwordDetails()"></i>
                                  <i v-else class="absolute top-7 right-3 fa-regular fa-eye-slash"  @click="passwordDetails()"></i>
                                  <label for="password_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">password</label>
                              </div>
                              <div class="relative">
                                  <input :type="!passwordVisible ? 'password' : 'text'" id="confirm_password_outlined" class="passwordThing block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.password_confirmation" required  placeholder=" " />
                                  <i v-if="passwordVisible" class="absolute z-50 w-8 h-6 text-center cursor-pointer fa-regular fa-eye top-7 right-3" @click="passwordDetails()"></i>
                                  <i v-else class="absolute top-7 right-3 fa-regular fa-eye-slash"  @click="passwordDetails()"></i>
                                  <label for="confirm_password_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">confirm password</label>
                              </div>
                              <div>
                              <small v-if="form.errors.password" class="text-red-600">{{ form.errors.password }}</small>
                            </div>
                        </div>
                          <div class="row">
                            <div class="col-sm-6 d-sm-flex align-items-center push">
                              <Link class="mb-1 fs-sm fw-medium link-fx text-muted me-2 d-inline-block" :href="route('login')">
                                <i class="opacity-50 fa fa-arrow-left me-1"></i> Log In
                              </Link>
                            </div>
                            <div class="col-sm-6 text-sm-end push">
                              <button type="submit" :disabled="form.processing" class="bg-yellow-300 btn btn-lg fw-semibold"  :class="{ 'opacity-25': form.processing }">
                                Register
                              </button>
                            </div>
                          </div>
                        </div>

                    </form>
                  </div>
                </div>

</template>
<style>
@media screen and (max-width: 50rem) {
    .hide{
        display: none;
    }
   
}
.round{
        border-radius: 10px;
    }
</style>