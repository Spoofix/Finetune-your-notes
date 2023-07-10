<script setup>
import { Head, useForm, useRemember } from '@inertiajs/vue3';
import {Link} from "@inertiajs/vue3"
import { defineComponent } from 'vue';


defineComponent({
  components: {
    Link
  },
  props: {
    error: Object
  },
});
defineProps({
    provinces: {
        type: Object,
    },
    timezones: {
        type: Object,
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
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
        <Head title="Register" />



          <!-- Main Container -->

            <!-- Page Content -->




                    <!-- Header -->
                    <div class=" rounded overflow-hidden lg:flex md:flex" style="width:fit-content; margin: auto; margin-top: 50px;">
                        <div class="hide" style="max-width: 600px; min-width: 500px; min-height: 100%; align-items: center;">
                <img
                    src="/assets/media/photos/login.jpg"
                    alt="..."
                    style="height: 100%; align-self: center;"

                />
            </div>
                    <!-- END Header -->
                    <div style="max-width: 600px; min-width: 500px">
                    <form @submit.prevent="submit">


                        <div class="block-header bg-gd-dusk">

                        </div>   <div class="py-1 text-center">
                      <h1 class="h3 fw-bold mt-1 mb-1">Create your account</h1>
                    </div>
                        <div class="block-content">
                            <div class="relative">
                                <input type="text" id="full_name_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.name" required placeholder=" " />
                                <label for="full_name_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Full name</label>
                                <small v-if="form.errors.name" class="text-red-600">{{ form.errors.name }}</small>
                            </div>

                            <div class="relative">
                                <input type="text" id="organization_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.organization" required placeholder=" " />
                                <label for="organization_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Organization</label>
                                <small v-if="form.errors.organization" class="text-red-600">{{ form.errors.organization }}</small>
                            </div>

                            <div class="relative">
                                <input type="text" id="phone_number_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.phone_number" required  placeholder=" " />
                                <label for="phone_number_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Phone number</label>
                                <small v-if="form.errors.phone_number" class="text-red-600">{{ form.errors.phone_number }}</small>
                            </div>

                            <div class="relative">
                                <input type="email" id="email_outlined"  class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.email" required  placeholder=" " />
                                <label for="email_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Email</label>
                                <small v-if="form.errors.email" class="text-red-600">{{ form.errors.email }}</small>
                            </div>

                            <div class="relative">
                                <textarea type="text" id="domain_outlined" rows="3" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.domains" required  placeholder=" " />
                                <label for="domain_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Domain <small>use comma  ' , '  for more than one domain</small></label>
                                <small v-if="form.errors.domains" class="text-red-600">{{ form.errors.domains }}</small>
                            </div>

                            <div class="grid items-end gap-2 md:grid-cols-2">
                              <div class="relative">
                                  <input type="password" id="password_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.password" required  placeholder=" " />
                                  <label for="password_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">password</label>
                                  <small v-if="form.errors.password" class="text-red-600">{{ form.errors.password }}</small>
                              </div>
                              <div class="relative">
                                  <input type="password" id="confirm_password_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.password_confirmation" required  placeholder=" " />
                                  <label for="confirm_password_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">confirm password</label>
                                  <small v-if="form.errors.password_confirmation" class="text-red-600">{{ form.errors.password_confirmation}}</small>
                              </div>
                        </div>
                          <div class="row">
                            <div class="col-sm-6 d-sm-flex align-items-center push">
                              <Link class="fs-sm fw-medium link-fx text-muted me-2 mb-1 d-inline-block" :href="route('login')">
                                <i class="fa fa-arrow-left opacity-50 me-1"></i> Log In
                              </Link>
                            </div>
                            <div class="col-sm-6 text-sm-end push">
                              <button type="submit" :disabled="form.processing" class="btn btn-lg btn-alt-primary fw-semibold"  :class="{ 'opacity-25': form.processing }">
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