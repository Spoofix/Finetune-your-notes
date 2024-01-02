<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import {Link} from "@inertiajs/vue3"
import { defineComponent } from 'vue';

defineComponent({
  components: {
    Link
  },
});
defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password" />
    <div id="page-container" class="main-content-boxed">

            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                <div class="bg-body-dark">
                    <div class="mx-0 row justify-content-center">
                        <div class="hero-static col-lg-6 col-xl-4">

                            <div class="overflow-hidden content content-full">
                                <!-- Header -->
                                <div class="py-4 mt-4 mb-2 text-center">
                                    <!-- <h1 class="mt-4 mb-2 h3 fw-bold">Forgot your password</h1> -->
                                </div>
                                <!-- END Header -->
                                <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                                    {{ status }}
                                </div>


                                <form class="" @submit.prevent="submit">
                                    <div class="block block-themed block-rounded block-fx-shadow">
                                        <div class="bg-yellow-200 ">
                                        </div>
                                        <div class="block-content">
                                            <div class="py-2 text-center">
                                                <h1 class=" h3 fw-bold">Forgot your password</h1>
                                            </div>
                                             <div class="relative">
                                <input type="email" id="full_name_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model=" form.email" :class="{ 'is-invalid': form.errors.email }" required autofocus  placeholder=" " />
                                <label for="full_name_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Email</label>
                                             <!-- </div>
                                            <div class="mb-4 form-floating">
                                                <input type="text" class="form-control round"
                                                    :class="{ 'is-invalid': form.errors.email }" v-model="form.email" required
                                                    autofocus placeholder="Enter your email">
                                                <label class="form-label">Email</label> -->
                                                <div v-if="form.errors.email" class="invalid-feedback animated fadeIn" >{{
                                                    form.errors.email }}</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 text-sm-end push">
                                                    <button class="bg-yellow-300 btn btn-lg hover:bg-yellow-200 fw-medium"
                                                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                        Email Password Reset
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="text-center block-content block-content-full bg-body-light d-flex justify-content-between">
                                            <Link class="mb-1 fs-sm fw-medium link-fx text-muted me-2 d-inline-block"
                                                :href="route('register')">
                                                <i class="opacity-50 fa fa-plus me-1"></i> Register
                                            </Link>
                                            <Link class="mb-1 fs-sm fw-medium link-fx text-muted me-2 d-inline-block"
                                                :href="route('login')">
                                                Login
                                            </Link>
                                        </div>
                                    </div>
                                </form>
                                <!-- END Sign In Form -->
                            </div>

                        </div>
                    </div>
                </div>
                <!-- END Page Content -->
            </main>
        <!-- END Main Container -->
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