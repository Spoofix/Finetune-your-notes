<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { Link } from "@inertiajs/vue3";
import { defineComponent } from "vue";

defineComponent({
    components: {
        Link,
    },
});
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 7000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

const $props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    token: {
        type: String,
    },
    user: {
        type: Object,
    },
});

const form = useForm({
    email: $props.user ? $props.user.email : "",
    password: $props.user ? $props.user.password : "",
    otp: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};

const submitToken = () => {
    form.post(route("login", { user_token: $props.token }), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template >
    <Head title="Log in" />
    <div class=" rounded overflow-hidden lg:flex md:flex" style="width:fit-content; margin: auto; margin-top: 50px; " v-if="!$props.token">

            <div class="hide" style="max-width: 600px; min-width: 500px; min-height: 100%;">
                <img
                    src="/assets/media/photos/login.jpg"
                    alt="..."
                    style="height: 100%; align-self: center;"

                />
            </div>
         <div style="min-width: 500px; ">
            <form

@submit.prevent="submit"

>
            <div class="block block-themed block-fx-shadow">
                <div class="block-header bg-gd-dusk"></div>
                <div class="block-content " >
                <div class="py-4 text-center" >
                    <h1 class="h3 fw-bold mt-4 mb-2">Login to your account</h1>
                </div>
                    <div class="form-floating mb-4">
                        <div class="relative">
                            <input type="text" id="login-username_outline" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" :class="{ 'is-invalid': form.errors.email}"
                                   v-model="form.email"
                                    required placeholder=" " />
                            <label for="login-username_outline" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Email</label>
                            <small v-if="form.errors.email"
                                   class="invalid-feedback animated fadeIn"
                            >
                                {{ form.errors.email }}</small>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="password" id="login-password_outlined"  v-model="form.password" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" :class="{ 'is-invalid': form.errors.password }" required  placeholder=" " />
                        <label for="login-password_outlined" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1" >Password</label>
                        <small  v-if="form.errors.password" class="text-red-600 invalid-feedback animated fadeIn" >{{ form.errors.password }}</small>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 d-sm-flex align-items-center push">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="login-remember-me"
                                    v-model="form.remember"
                                />
                                <label
                                    class="form-check-label"
                                    for="login-remember-me"
                                    >Remember Me</label
                                >
                            </div>
                        </div>
                        <div class="col-sm-6 text-sm-end push">
                            <button
                                class="btn btn-lg btn-alt-primary fw-medium"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Sign In
                            </button>

                        </div>
                    </div>
                </div>
                <div
                    class="block-content block-content-full bg-body-light text-center d-flex justify-content-between"
                >
                    <Link class="fs-sm fw-medium link-fx text-muted me-2 mb-1 d-inline-block" :href="route('register')">
                                    <i class="fa fa-plus opacity-50 me-1"></i> Create Account
                    </Link>
                    <Link
                        class="fs-sm fw-medium link-fx text-muted me-2 mb-1 d-inline-block"
                        :href="route('password.request')"
                    >
                        Forgot Password
                    </Link>
                </div>
            </div>
          </form>
        </div>
        </div>
        <!-- END Sign In Form -->


    <div class="content content-full overflow-hidden" v-else>
        <!-- Header -->
        <div class="py-4 text-center">
            <h1 class="h3 fw-bold mt-4 mb-2">OTP Token</h1>
        </div>
        <!-- END Header -->

        <form class="" @submit.prevent="submitToken">
            <div class="block block-themed block-rounded block-fx-shadow" style="max-width: 600px;">
                <div class="block-header bg-gd-dusk"></div>
                <div class="block-content">
                    <div class="relative">
                        <input type="text" id="login-username" :class="{ 'is-invalid': form.errors.otp }" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="form.otp" required  placeholder=" " />
                        <label for="login-username" class="absolute round text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Otp</label>
                        <small v-if="form.errors.otp" class="text-red-600 invalid-feedback animated fadeIn">{ {{ form.errors.otp }}</small>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 text-sm-end push">
                            <button
                                class="btn btn-lg btn-alt-primary fw-medium"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Verify
                            </button>
                        </div>
                    </div>
                </div>
                <div
                    class="block-content block-content-full bg-body-light text-center d-flex justify-content-between"
                >
                    <Link
                        class="fs-sm fw-medium link-fx text-muted me-2 mb-1 d-inline-block"
                        :href="route('register')"
                    >
                        <i class="fa fa-plus opacity-50 me-1"></i> Create
                        Account
                    </Link>
                    <Link
                        class="fs-sm fw-medium link-fx text-muted me-2 mb-1 d-inline-block"
                        :href="route('password.request')"
                    >
                        Forgot Password
                    </Link>
                </div>
            </div>
        </form>
        <!-- END Sign In Form -->
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
