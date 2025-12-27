<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';

import { defineComponent } from 'vue';

defineComponent({
  components: {
    Link
  },
});
defineProps({
    domain: {
        type: Object,
    },
});

const form = useForm({
    domain: "",
});


let data = 'Here';

const search = () => {
    form.post(route('search.domain'), {
        onFinish: ($response) => {
            // console.log($response);
            data = $response
        },
        onSuccess: () => { },
    });
}


</script>

<template>
    <Head title="Welcome" />

    <!-- Navigation-->
    <nav class="p-0 shadow-sm navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
        <div class="px-5 py-2 container-fluid navbar-top">
            <!-- <a class="navbar-brand fw-bold" href="#page-top">Yespoff</a> -->
            <ul class="my-3 navbar-nav ms-auto me-4 my-lg-0">
                <!-- <li class="nav-item"><a class="nav-link me-lg-3" href="#download">Download</a></li> -->
            </ul>
            <a class="px-3 mb-2 mr-5 mb-lg-0">
                <span class="d-flex align-items-center">
                    <span class="text-black large text-uppercase font-bolder">Contact Us</span>
                </span>
            </a>
            <Link class="px-3 mb-2 mr-5 btn btn-secondary mb-lg-0" :href="route('register')" style="background-color: black; ">
                <span class="d-flex align-items-center">
                    <span class="large">Sign Up</span>
                </span>
            </Link>
            <Link class="px-3 mb-2 btn btn-secondary mb-lg-0" :href="route('login')" style="background-color: black; ">
                <span class="d-flex align-items-center">
                    <span class="large">Login</span>
                </span>
            </Link>
        </div>
    </nav>
    <!-- Mashead header-->
    <header class="masthead">
        <div class="px-5 ">
            <div class="row gx-5 align-items-center">
                <div class="col-lg-6">
                    <!-- Mashead text and app badges-->
                    <div class="mb-5 text-center mb-lg-0 text-lg-start">
                        <h1 class="mb-2 text-white display-1 lh-base text-uppercase"
                            style="font-weight: 900; font-size: 110px;">TRACIKG<br> TRAKING</h1>
                        <p class="mb-5 text-white lead fw-normal" style="font-weight: 900; font-size: 30px;">Detect and
                            Report Spoofing Websites.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Masthead device mockup feature-->
                    <!-- class="masthead-device-mockup" -->
                    <div  style="max-width: 600px; margin: auto;">
                        <img class="img-fluid" src="assets/media/photos/tracking.svg" alt="Hero Promo">
                    </div>
                </div>
            </div>
            <div class="my-5 d-flex flex-column flex-lg-row align-items-center">
                <form>
                    <div class="input-group w-[550px] ">
                        <input type="text" class="py-3 text-black rounded form-control" placeholder="Search Domain"
                            v-model="form.domain">
                        <button type="button" class="px-5 btn btn-secondary" style="background-color: black; "
                            @click="search">
                            <div class="text-white spinner-grow spinner-border-sm" role="status"
                                v-if="form.processing">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <div>
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Domain List</h3>
                <div class="block-options">
                </div>
            </div>
            <div class="block-content">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;"></th>
                            <th>Domain</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in domain" :key="index" :style="{ background: index % 2 === 0 ? 'white' : 'rgba(25, 0, 0, 0.1)'}">
                            <th class="text-center" scope="row">{{ ++index }}</th>
                            <td>{{ item }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}

.masthead {
    background-color: #F95124;

    height: max-content;
}
@media screen and (max-width: 70rem) {
  .masthead {
    /* background-color: black; */
    height: max-content;
    padding-top: 50px;
    padding-bottom: 2px;

  }
}
@media screen and (max-width: 50rem) {
  .masthead {
    /* background-color: lightgreen; */
    padding-top: 50px;
    padding-bottom: 2px;
  }
}



.navbar-top {
    background-color: #F95124;
}
</style>
