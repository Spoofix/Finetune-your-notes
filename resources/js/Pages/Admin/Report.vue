<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import {Link} from "@inertiajs/vue3"
import { defineComponent } from 'vue';
import Editor from '@tinymce/tinymce-vue'
import { MagicString } from 'vue/compiler-sfc';
import { defineProps, onMounted } from 'vue';
import { ref, watch, } from 'vue';


const props = defineProps({
  domain: {
    type: Object,
  },
  details: {
    type: Object,
  },
  spoofData: {
    type: Object,
  },
  userid: {
    type: Number,
  },
  spoofList: {
    type: Object,
  },
  response: {
    type: Object,
  },
  firstSeen:{
    type: String,
  },
  isValidTakedown:{
    type: Boolean,
  },
  initValueHere: {
      type: String,
  }, 
  report_to: {
      type: Object,
  },  
  report_to_country: {
      type: Object,
  },
  userName: {
      type: String,
  }
});

let openModel = false;

// get screenshots urls
const filenames = ref([]);

onMounted(async () => {
  try {
    const domain = props.spoofData.spoofed_domain;
    const response = await fetch(`http://127.0.0.1:8000/latest_screenshots/${domain}`);
    if (!response.ok) {
      throw new Error('Failed to fetch latest screenshots');
    }
    const data = await response.json();
    filenames.value = data.filenames;
  } catch (error) {
    console.error(error.message);
  }
});

function ageContribution(age) {
    const decayFactor = 1.9; //decayValue
    const baseValue = 100;    //Initial contribution value

    const contribution = baseValue * Math.exp(-decayFactor * age);
    return contribution;
}

// age
const calculateTimeDifference = (dateString) => {
  try {
    dateString = JSON.parse(dateString);
  } catch (e) {}

  if (typeof dateString === "string") {
    const firstDate = new Date(dateString);
    const currentDate = new Date();

    if (isNaN(firstDate.getTime())) {
      // Handle invalid date strings
      return 'Invalid Date';
    }

    const diffInMilliseconds = currentDate - firstDate;

    // Check if the difference is negative (in the past)
    if (diffInMilliseconds < 0) {
      return 'Past';
    }

    const units = ['year', 'month', 'day', 'hour'];
    const divisors = [365 * 24 * 60 * 60 * 1000, 30 * 24 * 60 * 60 * 1000, 24 * 60 * 60 * 1000, 60 * 60 * 1000];
    
    for (let i = 0; i < units.length; i++) {
      const unit = units[i];
      const divisor = divisors[i];
      const diff = Math.round(diffInMilliseconds / divisor);

      if (diff > 0) {
        if (diff !== 1) {
          units[i] += 's';
        }
        return ` ${diff} ${units[i]}`;
      }
    }

    return '0 hours';
  } else if (Array.isArray(dateString)) {
    const firstDateString = dateString[0];

    if (typeof firstDateString === "string") {
      return calculateTimeDifference(firstDateString);
    }
  }

  return 'none';
};

function calculateWeightedSum(ratings, weights) {
  // Initialize sum to 0
  let sum = 0;

  // Iterate over each criterion in ratings
  for (let criterion in ratings) {
    if (ratings.hasOwnProperty(criterion)) {
      // Get the rating for the current criterion
      const rating = ratings[criterion];
      // console.log(rating)

      // Normalize the rating (assuming a maximum rating of 5)
      const normalizedRating = rating / 5;
      // console.log(normalizedRating)

      // Get the weight for the current criterion
      const weight = weights[criterion];
      // console.log(normalizedRating)

      // Calculate the weighted value for the current criterion
      
      const weightedValue = normalizedRating * weight;
      // console.log(weightedValue)

      // Add the weighted value to the sum
      if(!isNaN(weightedValue)){
        sum += weightedValue;
      }
    }
  }

  // Return the final sum
  return sum;
}

function ratingsValues(cssRating, screenshotSimilarityRating, htmlRating, domainNameSimilarityRating, age, sslValidity, domainrating) {
  const agedecay = ageContribution(age);
    const ratings = {
        cssRating,
        screenshotSimilarityRating,
        htmlRating,
        domainNameSimilarityRating,
        agedecay,
        sslValidity,
        domainrating
    };
      let weights =[];
      if(agedecay > 3 && agedecay < 5){
        weights = {
          webflow: {
            cssRating: 0.1,
            screenshotSimilarityRating: 0.25,
            htmlRating: 0.25,
            agedecay: 0.4,
          },
          domain: {
            domainNameSimilarityRating: 0.1,
            agedecay: 0.05,
            sslValidity: 0.05,
            domainrating: 0.8
          },
          interface: {
            cssRating: 0.25,
            screenshotSimilarityRating: 0.55,
            agedecay: 0.2,
          },
        };
      }
      else if(agedecay > 5){
        weights = {
          webflow: {
            cssRating: 0.1,
            screenshotSimilarityRating: 0.25,
            htmlRating: 0.25,
            agedecay: 0.4,
          },
          domain: {
            domainNameSimilarityRating: 0.1,
            agedecay: 0.05,
            sslValidity: 0.05,
            domainrating: 0.8
          },
          interface: {
            cssRating: 0.2,
            screenshotSimilarityRating: 0.4,
            agedecay: 0.4,
          },
        };
      }else{
        weights = {
          webflow: {
            cssRating: 0.1,
            screenshotSimilarityRating: 0.35,
            htmlRating: 0.35,
            agedecay: 0.1,
          },
          domain: {
            domainNameSimilarityRating: 0.1,
            agedecay: 0.05,
            sslValidity: 0.05,
            domainrating: 0.8
          },
          interface: {
            cssRating: 0.3,
            screenshotSimilarityRating: 0.45,
            agedecay: 0.25,
          },
        };
      }

    const webflowRating = calculateWeightedSum(ratings, weights.webflow);
    const domainRating = calculateWeightedSum(ratings, weights.domain);
    const interfaceRating = calculateWeightedSum(ratings, weights.interface);

    const overallRating = (webflowRating + domainRating + interfaceRating) / 3;

    return [webflowRating, domainRating, interfaceRating, overallRating]
}

const methods = {
  webflowRating(spoof) {
    const [webflowRating, domainRating, interfaceRating, overallRating]  = ratingsValues(spoof.csscolor, spoof.phashes, spoof.htmls, spoof.domainsimilarityrate, calculateTimeDifference2(spoof.registrationDate), true, spoof.domain_rating);
    return webflowRating; 
    },

    domainRating(spoof) {
    const [webflowRating, domainRating, interfaceRating, overallRating]  = ratingsValues(spoof.csscolor, spoof.phashes, spoof.htmls, spoof.domainsimilarityrate, calculateTimeDifference2(spoof.registrationDate), true, spoof.domain_rating);

      return domainRating; 
    },
    interfaceRating(spoof) {
      // Calculate interface rating for the spoof
    const [webflowRating, domainRating, interfaceRating, overallRating]  = ratingsValues(spoof.csscolor, spoof.phashes, spoof.htmls, spoof.domainsimilarityrate, calculateTimeDifference2(spoof.registrationDate), true, spoof.domain_rating);

      return interfaceRating; 
    },
    overallRating(spoof) {
    const [webflowRating, domainRating, interfaceRating, overallRating]  = ratingsValues(spoof.csscolor, spoof.phashes, spoof.htmls, spoof.domainsimilarityrate, calculateTimeDifference2(spoof.registrationDate), true, spoof.domain_rating);
      return overallRating; 
    },
    webflowRatingLabel(rating, spoof) {
      const age = calculateTimeDifference2(props.spoofData.registrationDate);
      if(age > 3 && age < 5){
        props.spoofData['webflowRatingLabel'] = "Low";
        return "Low";
      }
      if(age > 5){
        props.spoofData['webflowRatingLabel'] = "No Risk";
        return "No ";
      }
      props.spoofData['webflowRatingLabel'] =  rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
      return rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
    },
    domainRatingLabel(rating) {
      props.spoofData['domainRatingLabel'] =  rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
      return rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
    },
    interfaceRatingLabel(rating) {
      const age = calculateTimeDifference2(props.spoofData.registrationDate);
      if(age > 3 && age < 5){
        props.spoofData['interfaceRatingLabel'] = "Low";
        return "Low";
      }
      if(age > 5){
        props.spoofData['interfaceRatingLabel'] = "No Risk";
        return "No ";
      }
      props.spoofData['interfaceRatingLabel'] = rating > 5 ? 'High' : rating > 4 ? 'medium' : 'Low';
      return rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
    },
    overallRatingLabel(rating) {
      const age = calculateTimeDifference2(props.spoofData.registrationDate);
      if(age > 3 && age < 5){
        props.spoofData['overallRatingLabel'] = "Low";
        return "Low";
      }
      if(age > 5){
        props.spoofData['overallRatingLabel'] = "No Risk";
        return "No ";
      }
      props.spoofData['overallRatingLabel'] = rating > 4 ? 'High' : rating > 3 ? 'medium' : 'Low';
      return rating > 4 ? 'High' : rating > 3 ? 'medium' : 'Low';
    },

  };
  const calculateTimeDifference2 = (dateString) => {
  try {
    dateString = JSON.parse(dateString);
  } catch (e) {}
  if (typeof dateString === "string") {
    const firstDate = new Date(dateString);
    const currentDate = new Date();

    // Calculate the time difference in milliseconds
    const diffInMilliseconds = currentDate - firstDate;

    // Calculate the time difference in years, months, days, and hours
    const diffInYears = diffInMilliseconds / (365 * 24 * 60 * 60 * 1000);

    return `${diffInYears}`;
  } else if (Array.isArray(dateString)) {
    // If the dateString is an array, get the first date from the array
    const firstDateString = dateString[0];
    if (typeof firstDateString === "string") {
      // Calculate the time difference for the first date in the array
      return calculateTimeDifference2(firstDateString);
    }
  }
  return 'none';
};


const isModalVisible = ref(false);

const openModal = () => {
  isModalVisible.value = true;
  // console.log('yeah');
  setTimeout(() => {
    closeModal();
  }, 70000);
};

const closeModal = () => {
  isModalVisible.value = false;
};


watch(isModalVisible, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
    
      closeModal();
    }, 70000);
  }
});

// model2
const isModalVisibler = ref(false);

const openModalr = () => {
  isModalVisibler.value = true;
  
  setTimeout(() => {
    closeModalr();
  }, 70000);
};

const closeModalr = () => {
  isModalVisibler.value = false;
};


watch(isModalVisibler, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
    
      closeModalr();
    }, 70000);
  }
});

//model2
const isModalVisiblem = ref(false);

const openModalm = () => {
  isModalVisiblem.value = true;
      setTimeout(() => {
    closeModalm();
  }, 70000);
};

const closeModalm = () => {
  isModalVisiblem.value = false;
};


watch(isModalVisiblem, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
     
      closeModalm();
    }, 70000);
  }
});

//modelt
const isModalVisiblet = ref(false);

const openModalt = () => {
  isModalVisiblet.value = true;
      setTimeout(() => {
    closeModalt();
  }, 70000);
};

const closeModalt = () => {
  isModalVisiblet.value = false;
};


watch(isModalVisiblet, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
     
      closeModalt();
    }, 70000);
  }
});
defineComponent({
  components: {
    Link
  },

});
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
// tonggle well
const heading = ref(props.report_to[0]);
const reportViaSol = ref(typeof heading.value.email === 'string' && heading.value.email.length > 3 ? "email" : "form");

const reportVia = (clicked) => {
    if (clicked === "email") {
        reportViaSol.value = "email";
    } else {
        reportViaSol.value = "form";
    }
}

const changePageContent = (buttonClicked) => {
    heading.value = buttonClicked;
    reportViaSol.value = typeof heading.value.email === 'string' && heading.value.email.length > 3 ? "email" : "form";
}

 
// Data
const bladeViewUrl = ref("");

// Set the URL of your Blade view when the component is mounted
onMounted(() => {
  // bladeViewUrl.value = "http://127.0.0.1:5500/resources/views/map.html"; /emails/reset_password// Replace with the actual relative path
 const spoofID = props.spoofData.id;
 bladeViewUrl.value = `http://127.0.0.1:8000/maps/${spoofID}`;
// bladeViewUrl.value = `https://uat.spoofix.com/maps/${spoofID}`;

});

const userId = props.domain.user_id;


let nextId, nextId2;

if (!props.isValidTakedown) {
    const nextIdIndex = props.spoofList.findIndex(obj => Object.values(obj).includes(props.spoofData.id + 1));
    const nextId2Index = props.spoofList.findIndex(obj => Object.values(obj).includes(props.spoofData.id - 1));

    nextId = ref(nextIdIndex !== -1 ? props.spoofList[nextIdIndex].id : props.spoofData.id);
    nextId2 = ref(nextId2Index !== -1 ? props.spoofList[nextId2Index].id : props.spoofData.id);
} else {
    const nextIdIndex = props.spoofList.findIndex(obj => Object.values(obj).includes(props.spoofData.id + 1));
    const nextId2Index = props.spoofList.findIndex(obj => Object.values(obj).includes(props.spoofData.id - 1));

    nextId = ref(nextIdIndex !== -1 ? props.spoofList[nextIdIndex].id : props.spoofData.id);
    nextId2 = ref(nextId2Index !== -1 ? props.spoofList[nextId2Index].id : props.spoofData.id);
}


const scanDetails = [
  { key: 'Domain Name', value: 'spoofed_domain' },
  { key: 'First Seen', value: calculateTimeDifference(props.firstSeen)},//'created_at' 
  { key: 'Last Seen', value:  calculateTimeDifference(props.spoofData.updated_at) },//'updated_at'
  //{ key: 'Scan Id', value: 'id' },
  { key: 'Country Registered', value: 'country' },
  { key: 'Server Country', value: 'server_country' },
  { key: 'Server Region', value: 'region' },
  { key: 'Server City', value: 'server_city' },
  { key: 'IP Address', value: 'ip_address' },
  { key: 'Organization', value: 'org' },
  // location
    // { key: 'Server Country', value: 'server_country' },
  { key: 'Logitude ', value: 'longitude' },
  { key: 'Latitude', value: 'latitude' },
];
const pageStatistics = [
  { key: 'Internet servise provider', value: 'isp' },
  { key: 'Secure Sockets Layer', value: 'ssl_certificate_details' },
  { key: 'Server Os', value: 'server_os' },
  { key: 'Organization', value: 'org' },
  { key: 'Security Headers', value: 'security_headers' },
  // { key: 'Console Messages', value: 'console_messages' },
  // { key: 'Cookies', value: 'cookies' },
  
];
const domainDetails = [
  { key: 'Whois Server', value: 'whois_server' },
  { key: 'Registration Date', value: 'registrationDate' },
  { key: 'Registrar', value: 'registrar' },
  { key: 'Domain Update Date', value: 'update_date' },
  { key: 'Referal Urls', value: 'referral_url' },
  { key: 'Domain Expiry Date', value: 'expiration_date' },
  { key: 'City', value: 'city' },
  { key: 'Name Servers', value: 'name_servers' },
  { key: 'Domain Name System Security Extensions', value: 'dnssec' },
  { key: 'Address', value: 'address' },
  { key: 'State', value: 'state' },
  { key: 'Registrant Postal Code', value: 'regestrant_postal_code' },
  
];
const location = [
  { key: 'Server Country', value: 'server_country' },
  { key: 'Server Region', value: 'region' },
  { key: 'Server City', value: 'server_city' },
  { key: 'Logitude ', value: 'longitude' },
  { key: 'Latitude', value: 'latitude' },
];
const httpRedirects = [
  { key: 'Http Redirects', value: 'redirect_urls' },
  { key: 'Http Status Code', value: 'http_status_code' },
  // { key: 'Cookies Information', value: '' },
  // { key: 'Console Messages', value: '' },
  
];

const riskRatings = [
  { key: 'Webflow Rating', value:  methods.webflowRatingLabel(methods.webflowRating(props.spoofData))  },
  { key: 'Domian Rating ', value:  methods.domainRatingLabel(methods.domainRating(props.spoofData)) },
  { key: 'Interface Rating', value:  methods.interfaceRatingLabel(methods.interfaceRating(props.spoofData))  },
  { key: 'Age', value: calculateTimeDifference(props.spoofData.registrationDate) },//'registrationDate'
  { key: 'Ip Address', value: 'ip_address' },
  { key: 'Status', value: 'http_status_code' },
  
];

const makeToArray = (theString) => {
  try {
    const data = JSON.parse(theString);
    return Array.isArray(data) ? data : [data]; // Ensure it's always an array
  } catch (error) {
    console.error('Invalid JSON format:', error);
    return theString;
  }
}
const reporte = ref('');
// const reporte = ref('Do you want to report ' + props.spoofData.spoofed_domain + ' for takedown.');

function reported(){
    reporte.value = props.spoofData.spoofed_domain + ' is reported for takedown. Do you want to stop takedown?';
     openModalt();
}

const extraction = (dateString) => {
  const regex = /\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/;
  const match = dateString.match(regex);

  if (match) {
    const firstDateString = match[0];
    return firstDateString;
  } else {
    return 'info not found';
  }
}

// image history
const imageId = ref(0);

function imageLeft() {
  if (imageId.value > 0) {
    imageId.value = imageId.value - 1;
  }
}

function imageRight() {
  if (imageId.value < filenames.length - 1) {
    imageId.value = imageId.value + 1;
  }
}


defineComponent({
  components: {
    Link
  },
  name: 'app',
  components: {
  'editor': Editor
}
});




const form = useForm({
    domains: null,
});

function submit() {
    if (form.report == null) {
        Toast.fire({
            icon: 'error',
            title: 'Add at least one place to report'
        })
        return;
    }

    form.post(route('report'), {
        onFinish: (response) => {
            Toast.fire({
                icon: 'success',
                title: 'Reported'
            })

            // console.log(response);
            //  search()
        },
        onSuccess: () => { },
    });

}

// const reportingTo = ["Google SafeBrowsing", "Microsoft", "Registrar", "Cloudfare", "ISP Provider", "SSL Provider", "Phishing Database", "APWG", "Country Agency", "Global Cyber Alliance"]; report_to
const reportingTo = props.report_to;
// const heading.reportingName = "Cloudfare";

// function copyText() {
//   var copyBtn = document.getElementById("copyBtn");
//   copyBtn.innerHTML = "Copied <i class='fa fa-check'></i>"; // Corrected the quotes around the class attribute
//   var textArea = document.getElementById("report-text");
//   textArea.select();
//   document.execCommand("copy");
//   alert("Text copied to clipboard!");
// }

const copyTex = ref("copy");
const copyText = () => {
  copyTex.value = "copied";
  const textArea = document.getElementById("report-text");
  textArea.select();
  document.execCommand("copy");
  alert("Text copied to clipboard!");
};
const textareaData = "I would like to report the website " + props.spoofData.spoofed_domain + " as a spoofy site. This website is attempting to deceive users by imitating legitimate websites and may pose a threat to their security and privacy. Please investigate and take appropriate action."

</script>


<template>

  <Head title="ReportView" />

  <AuthenticatedLayout v-if=" props.userid === 1" class="overflow-scroll fontFamily" style="height:100vh; background: #FFF;">
    <div class="flex justify-between mt-6 w-100">
      <div class="relative ml-6">
        <button class="absolute w-64 h-12 px-3 rounded-tr-full bg-dark tabsText" style="">{{props.domain.domain_name}}</button>
        <button class="w-56 h-12 pr-4 bg-gray-300 rounded-tr-full pl-9 tabsText" style="margin-left: 200px;">Social Media</button>
      </div>
      <Link class="my-auto buttons buttonsText mr-9 hover:bg-yellow-300" :href="'/reporting/view/' + spoofData.id"><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link>
    </div>

    <!-- bar -->
    <div class="flex flex-row mx-4 mt-3 bg-yellow-100 h-14 rounded-t-xl">
      <h2 class="my-auto text-gray-600 pl-7 h3 riskpush">{{ spoofData.spoofed_domain }}</h2>
      <h2 class="my-auto font-bold text-center capitalize py-auto risk h3" style="min-width:fit-content;" :style="{'color': methods.overallRatingLabel(methods.overallRating(spoofData )) === 'High' ? '#ED0707' : methods.overallRatingLabel(methods.overallRating(spoofData )) === 'medium' ? '#F7610D' : '#3AAC11'}">
        {{ methods.overallRatingLabel(methods.overallRating(spoofData )) }} Risk
      </h2>
    </div>

    <div class="flex flex-row justify-between mx-4 mt-2">
      <div class="overflow-y-auto widthSetting" style="max-height: 72vh; min-height: 50vh; min-width: 27vw;">
        <!-- component -->
        <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent(reportingTos)" :class="{
        'bg-orange-100': heading !== reportingTos && reportingTos.reportingName,
        'bg-gray-200': heading === reportingTos && !reportingTos.reportingName || heading === reportingTos && reportingTos.reportingName,
        'bg-yellow-100': heading !== reportingTos && !reportingTos.reportingName,
    }" v-for="reportingTos in reportingTo" :key="reportingTos">
          <h2 class="my-auto ml-4 detailsNav">{{reportingTos.name}}</h2>
          <button class="px-4 my-auto detailsButton mr-9" :class="{
            'bg-orange-400': reportingTos.reportingName,
            'bg-yellow-300': !reportingTos.reportingName 
          }">
            <span v-if="!reportingTos.reportingName">Report</span>
            <span v-else class="-mr-4">Reported</span>
            <i class="pl-5 text-sm fa " :class="heading === reportingTos ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
        </div>
        <!-- end -->
      </div>
      <!-- <form > -->
      <div class="mt-2 ml-5" style="max-height:100vh; width: 100%;">
        <div class="align-middle rounded-t-lg" :class="{
            'bg-orange-100':heading.form_url && reportViaSol === 'form' && heading.name === heading.reportingName && heading.reported_via === 'form' || heading.email && heading.email.length > 3 && reportViaSol === 'email' && heading.name === heading.reportingName && heading.reported_via2 === 'email',
            'bg-gray-100':heading.form_url && reportViaSol === 'form' && heading.name === heading.reportingName && heading.reported_via !== 'form' || heading.email && heading.email.length > 3 && reportViaSol === 'email' && heading.name === heading.reportingName && heading.reported_via2 !== 'email',
             'bg-gray-100':!heading.reported_via && !heading.reported_via2,
          }" style="height: 45px; width: 100%;">
          <!-- 'bg-gray-100': heading.form_url && reportViaSol === 'form' && heading.name !== heading.reportingName ||  heading.reported_via !== 'form' || heading.email && heading.email.length > 3 && reportViaSol === 'email' &&  heading.name !== heading.reportingName || heading.reported_via2 !== 'email', -->
          <div class="flex py-3 pl-3 chechdetails">
            <span v-if="heading.name != 'Registrar'" class="w-full ml-1">
              <span v-if="heading.name !== heading.reportingName">Report</span>
              <span v-else>Reported</span>
              to {{heading.name}}</span>
            <span v-else class="w-full ml-1">Report to {{props.spoofData.registrar}}</span>
            <div class="float-right w-full -mt-3">
              <button v-if="heading.form_url.length > 3" @click="reportVia('form')" class="float-right h-full my-auto mr-4 buttons buttonsText hover:bg-yellow-400" :class="reportViaSol === 'form' ? 'bg-yellow-300' : 'bg-yellow-100'" title="Report Form">Form</button>
              <button v-if="heading.email.length > 3" @click="reportVia('email')" class="float-right h-full my-auto mr-4 buttons buttonsText hover:bg-yellow-400" :class="reportViaSol === 'email' ? 'bg-yellow-300' : 'bg-yellow-100'" title="Report Email">Email</button>
            </div>
          </div>
        </div>
        <div style="width: 100%;" class="-mr-5 parent">
          <div class="w-full ml-2 overflow-auto" style="height: full; width: 100%;">
            <div class="align-middle rounded-t-lg" style="height: 100%; width: 100%;" v-if="heading.form_url && reportViaSol === 'form'">
              <!-- if reported -->
              <div :class="{
                    'overlay1 z-50 w-100 h-100 absolute top-0 left-0 flex justify-center items-center': heading.name === heading.reportingName,
                    'hidden': heading.name !== heading.reportingName ||  heading.reported_via !== 'form',
                }">
                <div class="overlay">
                  <span class="font-bold icon fas fa-check"></span>
                  <span class="h5 message">Reported {{ heading.reported_via}}</span>
                </div>
              </div>
              <!-- if reported end -->
              <div>
                <div class=""><!--report-guide"-->
                  <h3 class="mt-2 h5">Guide to Report {{ spoofData.spoofed_domain }} to
                    <span v-if="heading != 'Registrar'">{{heading.name}}</span>
                    <span v-else>{{props.spoofData.registrar}}</span>
                  </h3>
                  <p>To <strong>{{spoofData.spoofed_domain }}</strong> to {{ heading.name }}, follow these steps:</p>
                  <ol style="">
                    <li v-if="heading.name !== heading.reportingName">Visit the <a :href="heading.form_url" @click="openModal" target="_blank" class="text-blue-600">{{ heading.name }} Report Form</a>.</li>
                    <li v-else>Visit the <a href="#" class="text-blue-600">{{ heading.name}} Report Form</a>.</li>
                    <li>Fill out the form with the following details:</li>
                    <ul>
                      <li class="ml-5">URL of the spoofy site: <strong>{{spoofData.spoofed_domain }}</strong></li>
                      <li class="ml-5">Description of the issue: Describe why you believe the site is spoofy and harmful.</li>
                    </ul>
                    <li>Submit the form.</li>
                  </ol>
                  <p class="h6">Here's a template for the text to send to {{ heading.name }}:</p>
                  <div>
                    <div class="align-middle -px-20">
                      <div class="relative w-full h-11 rounded-t-md">
                        <button onclick="copyText()" v-if="copyTex == 'copy'" id="copyBtn" class="float-right h-full mr-4 copy-button" style="z-index: 9900%;" title="copy text"><i class="fa fa-clipboard"></i> copy?</button>
                        <button onclick="copyText()" v-if="copyTex == 'copied'" id="copyBtn" class="float-right h-full mr-4 copy-button" style="z-index: 9900%;" title="copy text"><i class="fa fa-check"></i> copied!</button>
                      </div>
                      <textarea id="report-text" v-model="textareaData" class="p-4 overflow-y-auto bg-yellow-100 textarea"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="heading.email && heading.email.length > 3 && reportViaSol === 'email'">
              <!-- if reported -->
              <div :class="{
                    'overlay1 z-50 w-100 h-100 absolute top-0 left-0 flex justify-center items-center': heading.name === heading.reportingName,
                    'hidden': heading.name !== heading.reportingName || heading.reported_via2 !== 'email',
                }">
                <div class="overlay">
                  <span class="font-bold icon fas fa-check"></span>
                  <span class="h5 message">Reported {{ heading.reported_via2}}</span>
                </div>
              </div>
              <!-- if reported end -->
              <div class="relative e_body">
                <div class="container">
                  <div class="logo ">
                    <div style="color: #ffbc12; padding-top: 10px; margin-left: 5px;">Spoo</div>
                    <div style="color: #ffffff; padding-top: 10px;">fix</div>
                  </div>
                  <div class="content">
                    <p>Dear {{heading.name}},</p>
                    <p>I trust this email finds you well.</p>
                    <p>We are writing to formally report a spoofing incident involving {{ spoofData.spoofed_domain }}, which we have detected on spoofix.com. This unauthorized usage poses a significant security risk and undermines the integrity of our online presence.</p>
                    <p>Attached are screenshots documenting the spoofing activity for your immediate review and action. We kindly request your prompt investigation and resolution of this matter to ensure the safety of our online assets and users.</p>
                    <p>Your swift attention to this issue is greatly appreciated.</p>
                    <p>Thank you.</p>
                    <p>Best regards,<br>
                      <span class="capitalize">{{userName}},</span><br>
                      Support,<br>
                      <a href="mailto:support@spoofix.com" class="text-blue-700">support@spoofix.com</a>

                    </p>
                  </div>
                </div>
              </div>
              <div class="right-0 flex justify-between w-40 py-4">
                <!-- <button v-if="isValidTakedown" class="my-auto buttons buttonsText hover:bg-yellow-400" as='button' @click="reported">Mark Safe</button> -->
                <button v-if="isValidTakedown" class="my-auto bg-yellow-300 buttons buttonsText hover:bg-yellow-400" as='button' @click="openModalr">Send Report<i class="fa fa-paper-plane"></i></button>
              </div>
            </div>
            <div v-if="!heading.email && !heading.form_url">
              <div class="relative e_body">
                <div class="container">
                  <h2>Will Automate this with Python</h2>
                  <p>One button click and the reporting to phishing database will take place</p>
                  <div class="right-0 flex justify-between w-40 py-4">
                    <button v-if="isValidTakedown" class="my-auto bg-yellow-300 buttons buttonsText hover:bg-yellow-400" as='button' @click="openModalr">Send Report<i class="fa fa-paper-plane"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- model -->
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
                    <img class="absolute m-2 w-9 h-9" :src="'https://svgsilh.com/svg/1292787.svg'" />
                    <div class="my-auto">Hi, <span class="capitalize">{{ userName }} 👋</span>. Please confirm you have reported <span class="text-yellow-500">{{spoofData.spoofed_domain}}</span> to <span class="text-yellow-500">{{heading.name }}</span> via form.</div>
                  </div>
                  <div class="relative w-100">
                    <Link class="float-right px-4 bg-yellow-300 buttons buttonsText" :href="'/report/formReport/' + spoofData.id +  '/' + heading.name">Reported</Link>
                    <button class="float-right px-4 mr-3 bg-gray-300 buttons buttonsText" @click="closeModal" type="button">Not Reported</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <transition name="modal-fade">
      <div v-if="isModalVisibler" class="backlight" @click="closeModalr">
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
                    <img class="absolute m-2 w-9 h-9" :src="'https://svgsilh.com/svg/1292787.svg'" />
                    <div class="my-auto">This Email will be sent to <span class="text-yellow-500">{{heading.name }}</span> to report <span class="text-yellow-500">{{spoofData.spoofed_domain}}</span> via email . Are you sure you want to proceed?</div>
                  </div>
                  <div class="relative w-100">
                    <!-- <button class="float-right px-4 mr-3 bg-gray-300 buttons buttonsText" @click="closeModal" type="button">cancel</button> -->
                    <Link class="float-right px-4 bg-yellow-300 buttons buttonsText" :href="'/report/sendReport/' + spoofData.id + '/' + heading.email + '/' + heading.name">confirm</Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <div style="margin-top:30px;"></div>
  </AuthenticatedLayout>

</template>
<style scoped>
/*text area*/
.textarea {
  width: 100%;
  height: fit-content;
  border: none;
  font-size: 16px;
  color: #333; 
  box-shadow: none;
}

.textarea:focus {
  outline: none; 
  box-shadow:none; 
}
/*text area end*/
.copy-button {
    float: right;
    height: 70%;
    z-index: 9900;
    background-color: white;
    margin-top: 7px;
    padding: 4px 6px;
    border-radius: 6px;
    color: #000;
            border: #0e0e0f;
}
.copy-button:hover{
    background-color: rgb(243, 255, 110);
    transition-duration: 0.5s;
}
/*if reported*/
.parent {
    position: relative;
    /* Add any styles for the parent div */
}
.overlay1{
  opacity: -100%;
}
.overlay1:hover {
    opacity: 100%;
    transition-duration: 0.5s;
  }
.overlay {
    background-color: rgba(255, 76, 5, 0.762);
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    z-index: 3000%;
}
.icon {
    font-size: 24px;
    color: rgb(2, 2, 2); 
}
.message {
    font-size: 18px;
    margin-top: 10px;
    color: rgb(2, 2, 2); 
}
/*end if reported*/
/*this email temp*/
        .e_body {
            font-family: 'Arial', sans-serif;
            margin: -20px;
            padding: 0;
            background-color: #e5e2e2;
            width: 100%;
            min-height: 100%;
            /* Light background color */
        }
        .container {
            min-width: 500px;
            max-height: 500px;
            overflow: hidden;
            margin: 2px auto;
            background-color: #ffffff;
            /* White container background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #0e0e0f;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                'Segoe UI Emoji', 'Segoe UI Symbol';
            font-size: 16px;
            /* Light shadow */
        }
        .container:hover{
            overflow: auto;
        }
        .logo {
            /* Yellow-300 */
            font-size: 38px;
            font-weight: 900;
            display: flex;
            width: 100%;
            height: 60px;
            border-radius: 6px;
            background: var(--default-black, linear-gradient(0deg, rgba(0, 0, 0, 0.20) 0%, rgba(0, 0, 0, 0.20) 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.20) 0%, rgba(0, 0, 0, 0.20) 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.20) 0%, rgba(0, 0, 0, 0.20) 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.20) 0%, rgba(0, 0, 0, 0.20) 100%), #0C0C0C);
            flex-shrink: 0;
            line-height: 120%; /* 72px */
        }
        .heading{
            color: #262525;
            font-size: 19px;
            font-weight: 600;
        }

      .content {
             padding: 20px; 
            /* color: #333; */
            /* Dark text color*/
        } 

        .button {
            padding: 14px 20px;
            background-color: #FFD633;
            /* Yellow-300 */
            color: #000000;
            /* White text color */
            text-decoration: none;
            font-weight: bold;
            font-size: 20px;
            border-radius: 4px;
            height: 34px;
            text-align: center;
            border: #0e0e0f;
        }

        .button:hover {
            /* Yellow-300 */
            transition-duration: 400ms;
            transition-delay: 20ms;
            box-shadow: #000000;
            background-color: #ffcc00;
            color: #343333;
            
        }

        .footer {
            text-align: center;
            color: #000000;
            width: 100%;
            font-size: 9px;
            
            /* Gray text color */
        }
            /* end email temp */
/* Define the counter */
ol {
  counter-reset: roman-counter;
  list-style-type: none; 
}

/* Set the ::before pseudo-element to display the Roman numeral */
ol li::before {
  content: counter(roman-counter, upper-roman); 
  counter-increment: roman-counter; 
  margin-right: 0.5em; 
}

ul {
  list-style-type: none; 
  padding-left: 0; 
}

ul li {
  position: relative;
  padding-left: 1.5em; 
  margin-bottom: 0.5em; 
}

ul li::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0.5em;
  width: 0.75em; 
  height: 0.75em; 
  border-radius: 50%; 
  background-color: #000; 
}

.report-guide {
    background-color: #f9f9f9;
    padding: 20px;
    border: none;
    box-shadow: #000 ;
    margin-bottom: 20px;
}

.report-guide h3 {
    margin-top: 0;
}

.report-guide p {
    margin-bottom: 10px;
}

.report-guide textarea {
    width: 100%;
    height: 100px;
    margin-bottom: 10px;
}

.report-guide button {
    background-color: #fdff85;
    border: none;
    padding: 10px 20px;
    border-radius: 20px;
    cursor: pointer;
}

.report-guide button:hover {
    background-color: #fffa63;
}

/*image history*/
.arrow-button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgb(255, 255, 0);
    border: none;
    cursor: pointer;
    padding: 10px;
    font-size: 18px;
    color: rgb(0, 0, 0);
    border-radius: 50%;
    width: 50px;
    z-index: 1000%;
}
.arrow:hover {
    background-color: rgb(115, 115, 2);
}

.arrow-button.left {
    left: 10px;
}

.arrow-button.right {
    right: 10px;
}
/*image history end*/
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

/*animation*/
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

/*  */
@media (max-width: 500px) {
  .hidding {
    display: none;
  }
}


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
.modelText{
  max-width: 474px;
  color: #2A2A2A;
  
  /* Regular/Heading 5/Regular */
  font-family: Poppins;
  font-size: 16px;
  font-style: normal;
  font-weight: 400;
  line-height: 120%; /* 24px */
  background: #FFF;
  
  
  }
.image-container {
  max-width: 390px;
  max-height: 239px;
  margin: auto;
}
.DetailsTableRow{
  width: 99%;
  margin-top: 3px;
  margin: auto;
min-height: 45px;
flex-shrink: 0;
border-radius: 6px;
background: var(--yellow-yellow-50, #FFFAE6);
box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
}
.DetailsTableRowText{
  width: 105px;
  color: var(--dark-neutral-dark-neutral-9, #454545);

/* Regular/Base/Regular */
font-family: Poppins;
font-size: 14px;
font-style: normal;
font-weight: 400;
line-height: 120%; /* 16.8px */
}
.riskpush{
  width: 36%;
}
.shadow-{
box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);

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
.widthSetting{
  width: 36%;
}
@media (min-width: 955px) {
  .largeHidden{
  display: none;
  }
    .full_sm{
    width: 100%;
  }
}

  .full_sm{
    width: 70vw;
  }
  .lgPadding{
    padding-left: 0px;
  }
  .mg_top{
    margin-top: 100px;
  }
/* Add more custom styles as needed */
@media (max-width: 955px) {
  .smallHidden{
    display: none;
  }

  .widthSetting{
    width: 100%;
  }
  .full_sm{
    width: 100%;
  }
   .lgPadding{
    padding-left: 0px;
  }
    .mg_top{
    margin-top: 10px;
  }
}
</style>

            <!-- <editor api-key="9oj3gwaxetv26w50l19uiuwh3yxa6a6t8efa7zupst18nj5i" :init="{
          height: 650,
          menubar: false,
          plugins: [
            'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
            'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
            'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
          ],
          toolbar:
            'undo redo | casechange blocks | bold italic backcolor | \
            alignleft aligncenter alignright alignjustify | \
            bullist numlst checklist outdent indent | removeformat | a11ycheck code table help'
        }" :initial-value="initValueHere" /> -->