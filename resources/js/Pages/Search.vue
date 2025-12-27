<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { defineComponent , onMounted} from 'vue';
import AOS from 'aos';
import 'aos/dist/aos.css'; // Import AOS styles

onMounted(() => {
  AOS.init();
});

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

// const form = useForm({
//     domain: "",
// });


// let data = 'Here';

// const search = () => {
//     form.post(route('search.domain'), {
//         onFinish: ($response) => {
//             // console.log($response);
//             data = $response
//         },
//         onSuccess: () => { },
//     });
// }


</script>

<template style="background-color: white;">

  <Head title="Welcome" />

  <!-- Navigation-->
  <nav class="flex flex-row justify-between h-16 p-0 text-white shadow-sm navbar navbar-expand-lg fixed-top" style="background-color:#263238; font-family: 'Proxima Nova', sans-serif;">
    <!-- Logo -->
    <Link class="py-1 my-auto ml-9 logo-div " :href="route('dashboard')">
    <span class="logo">Spoo</span>
    <span class="logo-fix">fix</span>
    </Link>
    <div class="flex flex-row mr-9 min-w-fit">
      <h1 class="px-3 my-auto cursor-pointer hover:text-yellow-400 hover:border-b-2 hover:border-b-yellow-400" style="font-size:20px; transition-duration: 300ms;"><a class="hover:text-yellow-400" href="#part3">Why Us</a></h1>
      <Link class="px-3 my-auto hover:text-yellow-400 hover:border-b-2 hover:border-b-yellow-400" style="font-size:20px; transition-duration: 300ms;" :href="route('login')">Log In</Link>
      <Link :href="route('register')"><button class="px-2 mt-4 text-black bg-yellow-400 rounded-full h-11 w-36 text-bolder h4 hover:bg-yellow-200" style="font-size:20px;">Sign Up</button></Link>
    </div>
  </nav>
  <!-- part one -->
  <div class="w-full mt-5 text-white lg:flex md:flex" style="height:700px; background-image:url('assets/systemImages/Frame 1707478460 (1).png');"><!--#263238-->
    <!-- image -->
    <div class="mt-5 lg:w-1/2 md:w-1/2 aos-init" data-aos="slide-up" data-aos-duration="100">
      <img class="mx-auto aos-init" data-aos="zoom-out" data-aos-duration="800" src="assets/systemImages/Hacker-amico%201.svg" alt="" style="height:100%; width:66vw;">
    </div>
    <!-- text -->
    <div class="mt-8 align-center lg:w-1/2 md:w-1/2 aos-init" data-aos="slide-right" data-aos-duration="1000">
      <div class="w-5/6 text-left scale-2">
        <h1 class="h1" style="font-size:60px;">Report and combat spoofing sites today!</h1>
        <p style="font-size:30px;" class="text-bold">Identify different <span class="text-yellow-400"><u>spoofing, phishing, malicious</u></span> sites and report them to ensure the safety of your organization’s website. </p>
        <Link :href="route('register')"><button class="left-0 px-2 mt-4 text-black bg-yellow-400 rounded-full h-14 w-60 text-bolder h3 hover:bg-yellow-200 aos-init" data-aos="zoom-in-up" data-aos-duration="2000">Create an account</button></Link>
      </div>
    </div>
  </div>
  <!-- part two -->
  <div class="w-full text-black lg:flex md:flex" style="min-height:500px; background-color:#fff;">
    <!-- text -->
    <div class="mt-6 ml-40 align-center lg:w-1/2 md:w-1/2 aos-init" data-aos="zoom-in-right" data-aos-duration="1000">
      <div class="w-5/6 text-left scale-2">
        <h1 class="h1" style="font-size:46px;">Discover <span class="text-yellow-400">Spoofix’s</span> takedown capabilities</h1>
        <p style="font-size:24px;" class="text-bold">We shut down scammers and hackers fast, reporting their fake websites, and take them offline. This stops them from selling our products without permission or passing off fake stuff as real.</p>
        <a href="#part5" class="text-decoration-none"><button class="left-0 h-12 px-2 mt-4 text-black bg-yellow-400 rounded-full w-60 text-bold hover:bg-yellow-200 h5" style="font-size:22px;">Request a demo <i class="fa fa-angle-right"></i></button></a>
      </div>
    </div>
    <!-- image -->
    <div class="mt-3 mr-20 lg:w-1/2 md:w-1/2 aos-init" data-aos="slide-up" data-aos-duration="1000">
      <img class="mx-auto" src="assets/systemImages/In sync-cuate 1.svg" alt="" style="height:100%; max-height:500px; width:150%;">
    </div>
  </div>
  <!-- part 3 -->
  <div class="w-full text-black" style="min-height:600px; background-color:#FFFAE6;" id="part3">
    <h1 class="w-full pt-3 text-center h1 aos-init" data-aos="zoom-out" data-aos-duration="1000" style="font-size:46px;">Here’s how we do it:</h1>
    <p style="font-size:24px;" class="-mt-3 text-center text-bold aos-init" data-aos="zoom-out" data-aos-duration="1000">Optional subtitle goes here as an introduction to this section</p>
    <div class="flex justify-between mx-auto mt-3" style="width: 90vw;">
      <button class="w-10 h-10 my-auto text-2xl bg-white rounded-full shadow-sm hover:shadow-lg">&lt;</button>
      <div class="grid justify-between mb-6 lg:grid-cols-3 md:grid-cols-3 lg:gap-8 md:gap-3 sm:grid-cols-1" style="width: 89%;">
        <div class="bg-white shadow-lg rounded-xl w-100 min-h-96 wrap aos-init" data-aos="zoom-out-down" data-aos-duration="1000">
          <img src="assets/systemImages/Reset password-cuate 1.png" alt="" style="width:100%; height:auto;" class="rounded-t-lg">
          <h1 class="mx-4 text-bold h4">Title of process goes here</h1>
          <p class="mx-4 mb-4">Description of process and how spoofix bridges this gap</p>
        </div>
        <div class="bg-white shadow-lg rounded-xl w-100 min-h-96 wrap aos-init" data-aos="zoom-in-up" data-aos-duration="1000">
          <img src="assets/systemImages/Enter OTP-cuate 1.png" alt="" style="width:100%; height:auto;" class="rounded-t-lg">
          <h1 class="mx-4 text-bold h4">Title of process goes here</h1>
          <p class="mx-4 mb-4">Description of process and how spoofix bridges this gap</p>
        </div>
        <div class="bg-white shadow-lg rounded-xl w-100 min-h-96 wrap aos-init" data-aos="zoom-in-down" data-aos-duration="1000">
          <img src="assets/systemImages/Static assets-cuate 1.png" alt="" style="width:100%; height:auto;" class="rounded-t-lg">
          <h1 class="mx-4 text-bold h4">Title of process goes here</h1>
          <p class="mx-4 mb-4">Description of process and how spoofix bridges this gap</p>
        </div>
      </div>
      <button class="w-10 h-10 my-auto text-2xl bg-white rounded-full shadow-sm hover:shadow-lg">&gt;</button>
    </div>
  </div>
  <!-- part4 -->
  <div class="w-full text-black" style="min-height:600px; background-color:#fff;" id="part4">
    <h1 class="w-full pt-3 text-center h1 aos-init" data-aos="zoom-out" data-aos-duration="1000" style="font-size:46px;">Simple & flexible pricing built for everyone</h1>
    <div class="flex justify-between mx-auto w-fit">
      <p style="font-size:20px;" class="text-center text-bold aos-init" data-aos="zoom-out" data-aos-duration="1000">Monthlty</p>
      <div class="w-16 mx-3 bg-yellow-400 rounded-full h-7">
        <div class="py-1 ml-1 bg-white rounded-full h-7 w-7"></div>
      </div>
      <p style="font-size:20px;" class="text-center text-bold aos-init" data-aos="zoom-out" data-aos-duration="1000">Yearly</p>
    </div>
    <div class="mx-auto mt-3" style="width: 90vw;">
      <div class="grid justify-between mx-auto lg:grid-cols-3 md:grid-cols-3 lg:gap-8 md:gap-3 sm:grid-cols-1" style="width: 89%;">
        <div class="mb-3 shadow-md rounded-xl w-100 min-h-96 wrap aos-init" style="background-color:#F5F5F5;" data-aos="zoom-out-down" data-aos-duration="1000">
          <div class="mx-auto" style="width:70%;">
            <h1 class="mt-4 text-bolder h1">Starter</h1>
            <p style="font-size:24px;" class="text-bolder">What You’ll Get</p>
            <div class="flex mt-3">
              <i class="px-1 text-center text-white bg-yellow-400 border border-black rounded-full fa fa-check"></i>
              <p class="ml-3 text-bolder">Plan offering 1</p>
            </div>
            <div class="flex mt-3">
              <i class="px-1 text-center text-white bg-yellow-400 border border-black rounded-full fa fa-check"></i>
              <p class="ml-3 text-bolder">Plan offering 1</p>
            </div>
            <h1 class="mt-40 text-center text-bolder h1">Free</h1>
            <button class="h-16 mx-auto mb-3 bg-yellow-400 rounded-lg shadow-md text-bolder h4" style="width:100%;">Choose</button>
          </div>
        </div>
        <div class="mb-3 text-white shadow-md rounded-xl w-100 min-h-96 wrap aos-init" style="background-color:#0C0C0C;" data-aos="zoom-in-up" data-aos-duration="1000">
          <div class="mx-auto" style="width:70%;">
            <h1 class="mt-4 text-bolder h1">Standard</h1>
            <p style="font-size:24px;" class="text-bolder">What You’ll Get</p>
            <div class="flex mt-3">
              <i class="px-1 text-center text-black bg-white border border-black rounded-full fa fa-check"></i>
              <p class="ml-3 text-bolder">Plan offering 1</p>
            </div>
            <div class="flex mt-3">
              <i class="px-1 text-center text-black bg-white border border-black rounded-full fa fa-check"></i>
              <p class="ml-3 text-bolder">Plan offering 1</p>
            </div>
            <div class="flex mt-3">
              <i class="px-1 text-center text-black bg-white border border-black rounded-full fa fa-check"></i>
              <p class="ml-3 text-bolder">Advanced analytics</p>
            </div>
            <div class="flex mt-3">
              <i class="px-1 text-center text-black bg-white border border-black rounded-full fa fa-check"></i>
              <p class="ml-3 text-bolder">24/7 support</p>
            </div>
            <h1 class="mt-6 text-center text-bolder h1">$12/month</h1>
            <button class="h-16 mx-auto mb-3 bg-yellow-400 rounded-lg shadow-md text-bolder h4" style="width:100%;">Choose</button>
          </div>
        </div>
        <div class="mb-3 shadow-md rounded-xl w-100 min-h-96 wrap aos-init" style="background-color:#F5F5F5;" data-aos="zoom-up-down" data-aos-duration="1000">
          <div class="mx-auto" style="width:70%;">
            <h1 class="mt-4 text-bolder h1">Enterprise</h1>
            <p style="font-size:24px;" class="text-bolder">What You’ll Get</p>
            <div class="flex mt-3">
              <i class="px-1 text-center text-white bg-yellow-400 border border-black rounded-full fa fa-check"></i>
              <p class="ml-3 text-bolder">Plan offering 1</p>
            </div>
            <div class="flex mt-3">
              <i class="px-1 text-center text-white bg-yellow-400 border border-black rounded-full fa fa-check"></i>
              <p class="ml-3 text-bolder">Plan offering 2</p>
            </div>
            <div class="flex mt-3">
              <i class="px-1 text-center text-white bg-yellow-400 border border-black rounded-full fa fa-check"></i>
              <p class="ml-3 text-bolder">Plan offering 3</p>
            </div>
            <div class="flex mt-3">
              <i class="px-1 text-center text-white bg-yellow-400 border border-black rounded-full fa fa-check"></i>
              <p class="ml-3 text-bolder">Advanced analytics</p>
            </div>
            <div class="flex mt-3">
              <i class="px-1 text-center text-white bg-yellow-400 border border-black rounded-full fa fa-check"></i>
              <p class="ml-3 text-bolder">24/7 support</p>
            </div>
            <h1 class="mt-5 text-center text-bolder h1">$48/month</h1>
            <button class="h-16 mx-auto mb-3 bg-yellow-400 rounded-lg shadow-md text-bolder h4" style="width:100%;">Choose</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- part5 -->
  <div class="w-full text-black lg:flex md:flex" style="min-height:600px; background-color:#FFFAE6;" id="part5">
    <!-- image -->
    <div class="mt-5 lg:w-1/2 md:w-1/2 aos-init" data-aos="slide-up" data-aos-duration="100">
      <img class="mx-auto aos-init" data-aos="zoom-out" data-aos-duration="800" src="assets/systemImages/Frame 1707478732.png" alt="" style="height:100%; width:85%;">
    </div>
    <!-- text -->
    <div class="mt-6 align-center lg:w-1/2 md:w-1/2 aos-init" data-aos="slide-right" data-aos-duration="1000">
      <div class="w-5/6 text-left scale-2">
        <h1 class="h1" style="font-size:40px;">Request a demo</h1>
        <p style="font-size:29px;" class="text-bold">Optional subtitle to this section goes here</p>
        <form action="" class="grid grid-cols-2 gap-3 mt-4">
          <div>
            <label for="">Company name*</label>
            <input type="text" placeholder="Enter company name" class="border-gray-200 rounded-md h-11 bg-gray-50 w-100 focus:border-yellow-300 focus:outline-none">
          </div>
          <div>
            <label for="">Name and surname*</label>
            <input type="text" placeholder="Enter name and surname" class="border-gray-200 rounded-md h-11 bg-gray-50 w-100 focus:border-yellow-300 focus:outline-none">
          </div>
          <div>
            <label for="">Email Address*</label>
            <input type="text" placeholder="Enter email address" class="border-gray-200 rounded-md h-11 bg-gray-50 w-100 focus:border-yellow-300 focus:outline-none">
          </div>
          <div>
            <label for="">Phone number</label>
            <input type="number" placeholder="720 000000" class="border-gray-200 rounded-md h-11 bg-gray-50 w-100 focus:border-yellow-300 focus:outline-none">
          </div>
          <p class="my-auto mt-3">*Mandatory fields</p>
          <div class="relative w-100">
            <button class="absolute right-0 h-12 px-2 mt-1 text-black bg-yellow-400 rounded-full w-36 text-bolder h4 hover:bg-yellow-200 aos-init" data-aos="zoom-in-up" data-aos-duration="2000">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- part6 -->
  <div class="w-full text-black" style="min-height:600px; background-color:#fff;" id="part6">
    <h1 class="w-full pt-3 text-center h1 aos-init" data-aos="zoom-out" data-aos-duration="1000" style="font-size:46px;">Frequently Asked Questions</h1>
    <div class="grid grid-cols-2 ">
      <!-- text -->
      <div class="mt-1 ml-40 align-center w-100 aos-init" data-aos="zoom-in-right" data-aos-duration="1000">
        <div class="w-9/12">
          <div class="bg-gray-100 rounded-lg shadow-md">
            <div class="py-4 mx-5">
              <div class="flex justify-between">
                <h1 class="mt-3 h3">FAQ Title 1 Goes here</h1>
                <div class="w-10 h-10 mt-2 text-center text-black bg-yellow-400 rounded-md shadow-md"><i class="mt-3 fa fa-angle-up"></i></div>
              </div>
              <p class="my-3">Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat aute irure dolor</p>
            </div>
          </div>
          <div class="mt-2 bg-gray-100 rounded-lg shadow-md">
            <div class="py-4 mx-5">
              <div class="flex justify-between">
                <h1 class="mt-3 h3">FAQ Title 2 Goes here</h1>
                <div class="w-10 h-10 mt-2 text-center text-black bg-yellow-100 rounded-md shadow-md"><i class="mt-3 fa fa-angle-down"></i></div>
              </div>
            </div>
          </div>
          <div class="mt-2 bg-gray-100 rounded-lg shadow-md">
            <div class="py-4 mx-5">
              <div class="flex justify-between">
                <h1 class="mt-3 h3">FAQ Title 3 Goes here</h1>
                <div class="w-10 h-10 mt-2 text-center text-black bg-yellow-100 rounded-md shadow-md"><i class="mt-3 fa fa-angle-down"></i></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- image -->
      <div class="mt-3 mr-20 lg:w-100 aos-init" data-aos="slide-up" data-aos-duration="1000">
        <img class="mx-auto" src="assets/systemImages/FAQs-pana 1.png" alt="" style="height:100%; width:80%;">
      </div>
    </div>
  </div>
  <!-- footer -->
  <footer class="bottom-0 w-full text-center text-black" style="height:40px; background-color:#ffcc00;" id="footer">
    <p class="pt-2">Copyright Spoofix {{ new Date().getFullYear() }} All Rights Reserved</p>
  </footer>
</template>

<style>
.logo{
  color: #FC0;

/* Bold/Heading 2/Bold */
font-family: Poppins;
font-size: 45px;
font-style: normal;
font-weight: 700;
line-height: 120%; /* 54px */
}
.logo-div{
  width: 229px;
height: 74px;
flex-shrink: 0;
}
.logo-fix{
color: #FFF;
/* Bold/Heading 2/Bold */
font-family: Poppins;
font-size: 45px;
font-style: normal;
font-weight: 700;
line-height: 120%;
}
</style>
