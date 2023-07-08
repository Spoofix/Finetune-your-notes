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
    first_name: '',
    last_name: '',
    address: '',
    phone_number: '',
    city: '',
    province: '',
    timezone: '',
    email: '',
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
                            <div class="flex justify-between">
                          <div class="form-floating mb-3 ">
                            <input type="text" class="form-control round" v-model="form.first_name" required placeholder="Enter your first name">
                            <label class="form-label" >First name</label>
                            <small v-if="form.errors.name" class="text-red-600">{{ form.errors.name }}</small>
                          </div>
                          <div class="form-floating mb-3 ">
                            <input type="text" class="form-control round" v-model="form.last_name" required placeholder="Enter your last name">
                            <label class="form-label" >Last name</label>
                            <small v-if="form.errors.name" class="text-red-600">{{ form.errors.name }}</small>
                          </div>
                        </div>
                        
                          <div class="form-floating mb-3 ">
                            <input type="email" class="form-control round"  v-model="form.email" required placeholder="Enter your email">
                            <label class="form-label">Email</label>
                            <small v-if="form.errors.email"  class="text-red-600">{{ form.errors.email }}</small>
                          </div>
                          <div class="flex justify-between">
                          <div class="form-floating mb-3">
                            <input type="text" class="form-control round" v-model="form.phone_number" required placeholder="Enter your last name">
                            <label class="form-label" >Phone Number</label>
                            <small v-if="form.errors.phone_number" class="text-red-600">{{ form.errors.phone_number }}</small>
                          </div>
                       
                          <div class="form-floating mb-3">
                            <input type="text" class="form-control round" v-model="form.city" required placeholder="Enter your last name">
                            <label class="form-label" >City</label>
                            <small v-if="form.errors.city" class="text-red-600">{{ form.errors.city }}</small>
                          </div> 
                        </div>
                          <div class="form-floating mb-3">
                            <input type="text" class="form-control round" v-model="form.address" required placeholder="Enter your last name">
                            <label class="form-label" >Address</label>
                            <small v-if="form.errors.address" class="text-red-600">{{ form.errors.address }}</small>
                          </div>
                          <div class="form-floating mb-3">
                            <select class="form-select round" v-model="form.province">
                                <option :value="null">Select A Province</option>
                                <option v-for="province in provinces" v-bind:key="province" :value="province">{{ province }}</option>
                            </select>
                            <label class="form-label" >Province</label>
                            <small v-if="form.errors.province" class="text-red-600">{{ form.errors.province }}</small>
                          </div>
                          <div class="form-floating mb-3">
                            <select class="form-select round" v-model="form.timezone">
                                <option :value="null">Select A Timezone</option>
                                <option v-for="timezone in timezones" v-bind:key="timezone" :value="timezone.location">{{ timezone.time }}</option>
                            </select>
                            <label class="form-label" >Timezone</label>
                            <small v-if="form.errors.timezone" class="text-red-600">{{ form.errors.timezone }}</small>
                          </div>
                          <div class="flex justify-between">
                          <div class="form-floating mb-3">
                            <input type="password" class="form-control round" v-model="form.password" required placeholder="Enter your password">
                            <label class="form-label " >Password</label>
                            <small v-if="form.errors.password" class="text-red-600">{{ form.errors.password }}</small>
                          </div>
                          <div class="form-floating mb-3">
                            <input type="password" class="form-control round" v-model="form.password_confirmation" required placeholder="Confirm password">
                            <label class="form-label" >Confirm Password</label>
                            <small v-if="form.errors.password_confirmation" class="text-red-600">{{ form.errors.password_confirmation }}</small>
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
        border-radius: 30px;
    }
</style>