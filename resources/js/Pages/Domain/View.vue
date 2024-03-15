<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { Link } from "@inertiajs/vue3"
import { defineComponent } from 'vue';
import { defineProps, onMounted } from 'vue';
import { ref, watch,  } from 'vue';

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
  }
});



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
  //   const ratings = {
  //       cssRating,
  //       screenshotSimilarityRating,
  //       htmlRating,
  //       domainNameSimilarityRating,
  //       age,
  //       sslValidity,
  //       domainrating
  //   };

  // const weights = {
  //     webflow: {
  //       cssRating: 0.2,
  //       screenshotSimilarityRating: 0.4,
  //       htmlRating: 0.4,
  //     },
  //     domain: {
  //       domainNameSimilarityRating: 0.1,
  //       age: 0.05,
  //       sslValidity: 0.05,
  //       domainrating: 0.8
  //     },
  //     interface: {
  //       cssRating: 0.3,
  //       screenshotSimilarityRating: 0.7,
  //     },
  //   };

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
    // spoofStatus(spoof) {
    //   //
    //   return 'New';
    // },
    // webflowRating(spoof) {
    // const [webflowRating, domainRating, interfaceRating, overallRating]  = ratingsValues(spoof.csscolor, spoof.phashes, spoof.htmls, spoof.domainsimilarityrate, 2, true, spoof.domain_rating);
    // return webflowRating; 
    // },

    // domainRating(spoof) {
    // const [webflowRating, domainRating, interfaceRating, overallRating]  = ratingsValues(spoof.csscolor, spoof.phashes, spoof.htmls, spoof.domainsimilarityrate, 2, true, spoof.domain_rating);

    //   return domainRating; 
    // },
    // interfaceRating(spoof) {
    //   // Calculate interface rating for the spoof
    // const [webflowRating, domainRating, interfaceRating, overallRating]  = ratingsValues(spoof.csscolor, spoof.phashes, spoof.htmls, spoof.domainsimilarityrate, 2, true, spoof.domain_rating);

    //   return interfaceRating; 
    // },
    // overallRating(spoof) {
    // const [webflowRating, domainRating, interfaceRating, overallRating]  = ratingsValues(spoof.csscolor, spoof.phashes, spoof.htmls, spoof.domainsimilarityrate, 2, true, spoof.domain_rating);
    //   return overallRating; 
    // },
    // webflowRatingLabel(rating) {
    //   return rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
    // },
    // domainRatingLabel(rating) {
    //   return rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
    // },
    // interfaceRatingLabel(rating) {
    //   return rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
    // },
    // overallRatingLabel(rating) {
    //   return rating > 4 ? 'High' : rating > 3 ? 'medium' : 'Low';
    // },
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
  }, 7000);
};

const closeModal = () => {
  isModalVisible.value = false;
};


watch(isModalVisible, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
    
      closeModal();
    }, 300);
  }
});

// model2
const isModalVisibler = ref(false);

const openModalr = () => {
  isModalVisibler.value = true;
  
  setTimeout(() => {
    closeModalr();
  }, 7000);
};

const closeModalr = () => {
  isModalVisibler.value = false;
};


watch(isModalVisibler, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
    
      closeModalr();
    }, 300);
  }
});

//model2
const isModalVisiblem = ref(false);

const openModalm = () => {
  isModalVisiblem.value = true;
      setTimeout(() => {
    closeModalm();
  }, 5000);
};

const closeModalm = () => {
  isModalVisiblem.value = false;
};


watch(isModalVisiblem, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
     
      closeModalm();
    }, 300);
  }
});

//modelt
const isModalVisiblet = ref(false);

const openModalt = () => {
  isModalVisiblet.value = true;
      setTimeout(() => {
    closeModalt();
  }, 5000);
};

const closeModalt = () => {
  isModalVisiblet.value = false;
};


watch(isModalVisiblet, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
     
      closeModalt();
    }, 300);
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



const heading = ref('Scan Details');
const changePageContent = (buttonCLicked) =>{
    heading.value = buttonCLicked;
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

const userId = props.domain[0].user_id;

// const nextId = props.spoofList[1].id;
// let isNumberInSpoofList, isNumberInSpoofListPlus;
// if(!props.isValidTakedown){
//     isNumberInSpoofList = props.spoofList.some(obj => Object.values(obj).includes(props.spoofData.id - 1));
//     isNumberInSpoofListPlus = props.spoofList.some(obj => Object.values(obj).includes(props.spoofData.id + 1));
// }else{
//    isNumberInSpoofList = props.spoofList.some(obj => Object.values(obj).includes(props.spoofData.id - 1));
//    isNumberInSpoofListPlus = props.spoofList.some(obj => Object.values(obj).includes(props.spoofData.id + 1));
// }

// const nextId = ref(isNumberInSpoofListPlus ? props.spoofData.id + 1 : props.spoofData.id);
// const nextId2 = ref(isNumberInSpoofList ? props.spoofData.id - 1: props.spoofData.id);

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
// let nextId, nextId2;

// if (!props.isValidTakedown) {
//     const nextIdIndex = Array.isArray(props.spoofList) ? props.spoofList.findIndex(obj => Object.values(obj).includes(props.spoofData.id + 1)) : -1;
//     const nextId2Index = Array.isArray(props.spoofList) ? props.spoofList.findIndex(obj => Object.values(obj).includes(props.spoofData.id - 1)) : -1;

//     nextId = ref(nextIdIndex !== -1 ? props.spoofList[nextIdIndex].id : props.spoofData.id);
//     nextId2 = ref(nextId2Index !== -1 ? props.spoofList[nextId2Index].id : props.spoofData.id);
// } else {
//     const nextIdIndex = Array.isArray(props.spoofList) ? props.spoofList.findIndex(obj => Object.values(obj).includes(props.spoofData.id + 1)) : -1;
//     const nextId2Index = Array.isArray(props.spoofList) ? props.spoofList.findIndex(obj => Object.values(obj).includes(props.spoofData.id - 1)) : -1;

//     nextId = ref(nextIdIndex !== -1 ? props.spoofList[nextIdIndex].id : props.spoofData.id);
//     nextId2 = ref(nextId2Index !== -1 ? props.spoofList[nextId2Index].id : props.spoofData.id);
// }



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
  { key: 'Organization', value: 'organization' },
  // location
    // { key: 'Server Country', value: 'server_country' },
  { key: 'Logitude ', value: 'longitude' },
  { key: 'Latitude', value: 'latitude' },
];
const pageStatistics = [
  { key: 'Internet servise provider', value: 'isp' },
  { key: 'Secure Sockets Layer', value: 'ssl_certificate_details' },
  { key: 'Server Os', value: 'server_os' },
  { key: 'Organization', value: 'organization' },
  { key: 'Security Headers', value: 'security_headers' },
  // { key: 'Console Messages', value: 'console_messages' },
  // { key: 'Cookies', value: 'cookies' },
  
];
const domainDetails = [
  { key: 'Whois Server', value: 'whois_server' },
  { key: 'Registration Date', value: 'registrationDate' },
  { key: 'Registrar', value: 'registrar' },
  { key: 'Domain Update Date', value: 'update_date' },
  // { key: 'Referal Urls', value: 'referral_url' },
  { key: 'Domain Expiry Date', value: 'expiration_date' },
  { key: 'City', value: 'city' },
  { key: 'Name Servers', value: 'name_servers' },
  { key: 'Domain Name System Security Extensions', value: 'dnssec' },
  { key: 'Address', value: 'address' },
  { key: 'State', value: 'state' },
  { key: 'Registrant Postal Code', value: 'registrant_postal_code' },
  
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
// methods.webflowRatingLabel(methods.webflowRating(spoofData))
const riskRatings = [
  { key: 'Webflow Rating', value:  methods.webflowRatingLabel(methods.webflowRating(props.spoofData))  },
  { key: 'Domian Rating ', value:  methods.domainRatingLabel(methods.domainRating(props.spoofData)) },
  { key: 'Interface Rating', value:  methods.interfaceRatingLabel(methods.interfaceRating(props.spoofData))  },
  { key: 'Age', value: calculateTimeDifference(props.spoofData.registrationDate) },//'registrationDate'
  { key: 'Ip Address', value: 'ip_address' },
  { key: 'Status', value: 'http_status_code' },
  
];



// function checkImageExists(imageUrlL) {
//   let imageUrl = `http://127.0.0.1:8000/assets/screenshots/${imageUrlL}.png`;
//   fetch(imageUrl, { method: 'HEAD' })
//     .then((response) => {
//      if(response.status === 200){

//      }
// }
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
    //  Toast.fire({
    //         icon: 'error',
    //         title: 'This spoofing site is already reported for take down.',
    //     });
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

// const submit = () => {

// const formData = new FormData();
// formData.append('abuse_type', form.abuse_type);

// const response = form.post(route('ReportSpoofySite'));
//   // const response = router.post('/ReportSpoofySite', form)
// if(response.errors == {}){
//     Toast.fire({
//       icon: 'success',
//       title: 'Stopped Takedown Successfully',
//     });
// }
// }

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
</script>

<template>

  <Head v-if="spoofData.spoof_status_new == 'completed'" title="ScannedCompleted" />

  <Head v-else-if="spoofData.spoof_status_new == 'inprogress' || isValidTakedown" title="ScannedInProgress" />

  <Head v-else title="Domain" />

  <!-- ScannedCompleted-->
  <!-- testing  -->
  <!-- <div v-if="1==1" style="z-index: 1000; background-color: white;">
    <h1>Latest Screenshots</h1>
    <ul>
      <li v-for="filename in filenames" :key="filename">{{ filename }}</li>
      <li>{{filenames[0]}}</li>
    </ul>
  </div> -->
  <AuthenticatedLayout v-if=" props.userid === userId" class="overflow-scroll fontFamily" style="height:100vh; background: #FFF;">

    <div class="flex justify-between mt-6 w-100">
      <div class="relative ml-6">
        <button class="absolute w-40 h-12 px-3 rounded-tr-full bg-dark tabsText" style="">{{props.domain[0].domain_name}}</button>
        <button class="w-56 h-12 pr-4 bg-gray-300 rounded-tr-full tabsText pl-9" style="margin-left: 106px;">Social Media</button>
      </div>
      <Link v-if="isValidTakedown" class="my-auto buttons buttonsText mr-9 hover:bg-yellow-300" :href="'/InProgress'"><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link>
      <Link v-else class="my-auto buttons buttonsText mr-9 hover:bg-yellow-300" :href="'/domains'"><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link>
    </div>
    <div class="flex flex-row mx-4 mt-3 bg-yellow-100 h-14 rounded-t-xl">
      <h2 class="my-auto text-gray-600 pl-7 h3 riskpush">{{ spoofData.spoofed_domain }}</h2>
      <div class="flex my-auto overflow-x-visible py-auto min-w-60" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
        <h1 class="border-l-white border-y-yellow-300 spinner-border border-r-yellow-100 "></h1>
        <h1 class="my-auto ml-6 text-gray-300 py-auto h6">Scanning . . . This may take a while. . . </h1>
      </div>
      <h2 class="my-auto font-bold text-center capitalize py-auto risk h3" v-else style="min-width:fit-content;" :style="{'color': methods.overallRatingLabel(methods.overallRating(spoofData )) === 'High' ? '#ED0707' : methods.overallRatingLabel(methods.overallRating(spoofData )) === 'medium' ? '#F7610D' : '#3AAC11'}">
        {{ methods.overallRatingLabel(methods.overallRating(spoofData )) }} Risk
      </h2>
    </div>
    <div class="flex flex-row justify-between mx-4 mt-2">
      <div class="widthSetting" style=" min-height: 65vh;">
        <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('Scan Details')" :class="{'bg-gray-200': heading === 'Scan Details', 'bg-yellow-100': heading !== 'Scan Details'}">
          <h2 class="my-auto ml-4 detailsNav">Scan Details</h2>
          <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa " :class="heading === 'Scan Details' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
        </div>
        <!-- scan details on small -->
        <div class="ml-1 overflow-y-auto box-style widthSetting largeHidden" style=" height: 350px; width: 99%;" v-if=" heading ==  'Scan Details'">
          <!-- scan Details -->
          <div class="w-full overflow-auto " v-if=" heading ==  'Scan Details'">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <div class="flex mt-2 hover:bg-yellow-300 DetailsTableRow" v-else v-for="ScanDetail in scanDetails" :key="ScanDetail.value">
              <div class="mt-3 ml-3 w-50 DetailsTableRowText">{{ScanDetail.key}}</div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-if="Array.isArray(makeToArray(spoofData[ScanDetail.value]))">
                <div class="mt-2" v-for="(data, index) in  makeToArray(spoofData[ScanDetail.value])" :key="index">
                  <div v-if="spoofData[ScanDetail.value] === '' || typeof spoofData[ScanDetail.value] === 'undefined' || !spoofData[ScanDetail.value]" class="text-gray-400">info not available</div>
                  <div v-else>{{ spoofData[ScanDetail.value] }}</div>
                  <!-- {{spoofData[ScanDetail.value]}} -->
                </div>
              </div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-else>
                <!-- {{ makeToArray(spoofData[ScanDetail.value]) }} -->
                <div v-if="spoofData[ScanDetail.value] === '' || typeof spoofData[ScanDetail.value] === 'undefined' || !spoofData[ScanDetail.value]" class="text-gray-400">info not available</div>
                <div v-else>{{ makeToArray(spoofData[ScanDetail.value])  }}</div>
              </div>
            </div>
          </div>
          <!-- <div class="w-full overflow-auto">
              <img class="w-full" :src="'/assets/screenshots/' + spoofData.spoofed_domain + '.png'" alt="...">/spoof//{spoofId}
          </div> -->
        </div>
        <!-- scan detais on small end -->
        <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('Page Statistics')" :class="{'bg-gray-200': heading === 'Page Statistics', 'bg-yellow-100': heading !== 'Page Statistics'}">
          <h2 class="my-auto ml-4 detailsNav">Page Statistics</h2>
          <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa " :class="heading === 'Page Statistics' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
        </div>
        <!-- ...on small -->
        <div class="ml-1 overflow-y-auto box-style widthSetting largeHidden" style=" height: 350px; width: 99%;" v-if=" heading ==  'Page Statistics'">
          <!-- page statistics -->
          <div class="w-full overflow-auto" v-if=" heading ==  'Page Statistics'">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <div class="flex mt-2 hover:bg-yellow-300 DetailsTableRow" v-else v-for="pageStatistic in pageStatistics" :key="pageStatistic.value">
              <div class="mt-3 ml-3 w-50 DetailsTableRowText">{{pageStatistic.key}}</div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-if="Array.isArray(makeToArray(spoofData[pageStatistic.value]))">
                <div class="mt-2" v-for="(data, index) in  makeToArray(spoofData[pageStatistic.value])" :key="index">
                  <!-- {{spoofData[pageStatistic.value]}} -->
                  <div v-if="spoofData[pageStatistic.value] === '' || typeof spoofData[pageStatistic.value] === 'undefined' || !spoofData[pageStatistic.value]" class="text-gray-400">info not available</div>
                  <div v-else>{{ spoofData[pageStatistic.value]  }}</div>
                </div>
              </div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-else>
                <!-- {{ makeToArray(spoofData[pageStatistic.value]) }} -->
                <div v-if="spoofData[pageStatistic.value] === '' || typeof spoofData[pageStatistic.value] === 'undefined' || !spoofData[pageStatistic.value]" class="text-gray-400">info not available</div>
                <div v-else>{{ spoofData[pageStatistic.value] }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('Domain Details')" :class="{'bg-gray-200': heading === 'Domain Details', 'bg-yellow-100': heading !== 'Domain Details'}">
          <h2 class="my-auto ml-4 detailsNav">Domain Details</h2>
          <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa " :class="heading === 'Domain Details' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
        </div>
        <!-- .on small. -->

        <div class="ml-1 box-style largeHidden" style=" height: 350px; width: 99%; overflow: scroll;" v-if=" heading ==  'Domain Details'">
          <!-- domain details -->
          <div class="w-full overflow-auto" v-if=" heading ==  'Domain Details'">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <div class="flex mt-2 hover:bg-yellow-300 DetailsTableRow" v-else v-for="domainDetail in domainDetails" :key="domainDetail.value">
              <div class="mt-3 ml-3 w-50 DetailsTableRowText">{{domainDetail.key}}</div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-if="Array.isArray(makeToArray(spoofData[domainDetail.value]))">
                <!-- <div class="mt-2" v-for="(data, index) in  spoofData[domainDetail.value]" :key="index"> -->
                <div v-if="spoofData[domainDetail.value] === '' || typeof spoofData[domainDetail.value] === 'undefined' || !spoofData[domainDetail.value]" class="text-gray-400">info not available</div>
                <div v-else>{{extraction(spoofData[domainDetail.value])}}</div>
                <!-- </div> -->
              </div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-else>
                <!-- {{ makeToArray(spoofData[domainDetail.value]) }} -->
                <div v-if="spoofData[domainDetail.value] === '' || typeof spoofData[domainDetail.value] === 'undefined' || !spoofData[domainDetail.value]" class="text-gray-400">info not available</div>
                <div v-else>{{ spoofData[domainDetail.value]  }}</div>
              </div>
            </div>
          </div>
        </div>
        <!-- location -->
        <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('Locations')" :class="{'bg-gray-200': heading === 'Locations', 'bg-yellow-100': heading !== 'Locations'}">
          <h2 class="my-auto ml-4 detailsNav">Locations</h2>
          <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa " :class="heading === 'Locations' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
        </div>
        <!-- location -->
        <div class="ml-1 overflow-y-auto box-style largeHidden" style=" height: 350px; width: 99%;" v-if=" heading ==  'Locations'">
          <div class="w-full overflow-auto">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <iframe v-else :src="bladeViewUrl" width="100%" height="500"></iframe>
            <!-- <div v-html="emailTemplate" width="100%" height="500"></div> -->
            <!-- <iframe src="../../../views/map.html" width="100%" height="500px" frameborder="0"></iframe> -->
          </div>
        </div>
        <!-- screenshots -->
        <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('Screenshots')" :class="{'bg-gray-200': heading === 'Screenshots', 'bg-yellow-100': heading !== 'Screenshots'}">
          <h2 class="my-auto ml-4 detailsNav">Screenshots</h2>
          <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa" :class="heading === 'Screenshots' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
        </div>
        <!-- screenshots -->
        <div class="ml-1 overflow-y-scroll box-style largeHidden" style=" max-height: 350px; width: 99%;" v-if=" heading ==  'Screenshots'">
          <!-- will do js here -->
          <div class="w-full" @click="openModal">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <div v-else>
              <img v-if="filenames[0]" class="w-full" :src="'/assets/screenshots/' + spoofData.spoofed_domain + '/' + filenames[0]" alt="SORRY ( : ,IMAGE NOT FOUND" @click="openModal">

              <img v-else class="w-full" :src="'/assets/systemImages/screenshotplaceholder.png'" alt="..." />
            </div>

          </div>
        </div>
        <!-- .. -->
        <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('HTTP Redirects')" :class="{'bg-gray-200': heading === 'HTTP Redirects', 'bg-yellow-100': heading !== 'HTTP Redirects'}">
          <h2 class="my-auto ml-4 detailsNav">HTTP Redirects</h2>
          <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa " :class="heading === 'HTTP Redirects' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
        </div>
        <!-- ...on small -->
        <div class="ml-1 overflow-y-auto box-style widthSetting largeHidden" style=" height: 350px; width: 99%;" v-if=" heading ==  'HTTP Redirects'">
          <!-- http redirects -->
          <div class="w-full overflow-auto" v-if=" heading ==  'HTTP Redirects'">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <div class="flex mt-2 hover:bg-yellow-300 DetailsTableRow" v-else v-for="httpRedirect in httpRedirects" :key="httpRedirect.value">
              <div class="mt-3 ml-3 w-50 DetailsTableRowText">{{httpRedirect.key}}</div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-if="Array.isArray(makeToArray(spoofData[httpRedirect.value]))">
                <div class="mt-2" v-for="(data, index) in  makeToArray(spoofData[httpRedirect.value])" :key="index">
                  <!-- {{spoofData[httpRedirect.value]}} -->
                  <div v-if="spoofData[httpRedirect.value] == '' || spoofData[httpRedirect.value] == null" class="text-gray-400"> info not available</div>
                  <div v-else> {{ spoofData[httpRedirect.value]}}</div>
                </div>
              </div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-else>
                <div v-if="spoofData[httpRedirect.value] === '' || typeof spoofData[httpRedirect.value] === 'undefined' || !spoofData[ScanDetail.value]" class="text-gray-400">info not available</div>
                <div v-else>{{ spoofData[httpRedirect.value] }}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex justify-between mt-2 shadow- rounded-xl" style="height: 60px;" @click="changePageContent('Risk Rating')" :class="{'bg-gray-200': heading === 'Risk Rating', 'bg-yellow-100': heading !== 'Risk Rating'}">
          <h2 class="my-auto ml-4 detailsNav">Risk Rating</h2>
          <button class="px-4 my-auto detailsButton mr-9">Details<i class="pl-5 text-sm fa " :class="heading === 'Risk Rating' ? 'fa-chevron-down' : 'fa-chevron-right'" aria-hidden="true"></i></button>
        </div>
        <!-- ...on small -->
        <div class="ml-1 overflow-y-auto box-style widthSetting largeHidden" style=" height: 350px; width: 99%;" v-if=" heading ==  'Risk Rating'">
          <!-- risk ratings -->
          <div class="w-full overflow-auto" v-if=" heading ==  'Risk Rating'">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <div class="flex mt-2 hover:bg-yellow-300 DetailsTableRow" v-else v-for="riskRating in riskRatings" :key="riskRating.value">
              <div class="mt-3 ml-3 w-50 DetailsTableRowText">{{riskRating.key}}</div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-if="Array.isArray(makeToArray(spoofData[riskRating.value]))">
                <div class="mt-2" v-for="(data, index) in  makeToArray(spoofData[riskRating.value])" :key="index">
                  <div v-if="spoofData[riskRating.value] == '' || spoofData[riskRating.value] == null" class="text-gray-400"> info not available</div>
                  <div v-else> {{spoofData[riskRating.value]}}</div>
                </div>
              </div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-else>
                <div v-if="spoofData[riskRating.value]  === '' || typeof spoofData[riskRating.value] === 'undefined' && riskRating.value !== 'High' && riskRating.value !== 'low' && riskRating.value !== 'medium' && riskRating.key !== 'Age'" class="text-gray-400">info not available</div>
                <div v-else>{{ spoofData[riskRating.value]  }}</div>
                <div v-if="riskRating.value === 'High' || riskRating.value === 'low' || riskRating.value === 'medium' || riskRating.key === 'Age'"> {{riskRating.value}}</div>

              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="ml-1 overflow-y-auto box-style smallHidden widthSetting" style=" height: 65vh; width: 64%;">
        <div class="align-middle bg-gray-100 rounded-t-lg" style="height: 45px; width: 100%;">
          <div class="py-3 pl-3 chechdetails" v-if="heading == 'Domain Details'">
            <span class="ml-1">Area</span>
            <span class="pl-16 ml-56 ">Details</span>
          </div>
          <div class="py-3 pl-3 chechdetails" v-else>{{ heading }}</div>
          <!-- will do js here -->
          <div class="relative w-full overflow-auto" v-if="heading == 'Screenshots'">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px; " v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <div v-else>
              <img v-if="filenames[0]" class="w-full" :src="'/assets/screenshots/' + spoofData.spoofed_domain + '/' + filenames[0]" alt="SORRY ( : ,IMAGE NOT FOUND" @click="openModal">
              <img v-else class="w-full" :src="'/assets/systemImages/screenshotplaceholder.png'" alt="..." />
            </div>
            <Link class="absolute z-50 m-4 my-auto bg-yellow-100 right-3 bottom-6 hover:bg-yellow-300 -mt-9 buttons buttonsText w-fit" :href="'/screenshot/'+ spoofData.id" v-if="spoofData.current_scan_status !== 'not_scanned'">>Live Screenshot</Link>
            <!-- <img class="w-full" :src="'/assets/systemImages/screenshotplaceholder.png'" alt="..." v-if="spoofData.screenshot === null"> -->
          </div>

          <!-- location -->
          <div class="w-full overflow-auto" v-if=" heading ==  'Locations'">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <iframe :src="bladeViewUrl" width="100%" height="500" v-else></iframe>
            <!-- <div v-html="emailTemplate" width="100%" height="500"></div> -->
            <!-- <iframe src="../../../views/map.html" width="100%" height="500px" frameborder="0"></iframe> -->
          </div>
          <!-- domain details -->
          <div class="w-full overflow-auto" v-if=" heading ==  'Domain Details'">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <div class="flex mt-2 hover:bg-yellow-300 DetailsTableRow" v-else v-for="domainDetail in domainDetails" :key="domainDetail.value">
              <div class="mt-3 ml-3 w-50 DetailsTableRowText">{{domainDetail.key}}</div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-if="Array.isArray(makeToArray(spoofData[domainDetail.value]))">
                <!-- <div class="mt-2" v-for="(data, index) in  spoofData[domainDetail.value]" :key="index"> -->
                <div v-if="spoofData[domainDetail.value] === '' || typeof spoofData[domainDetail.value] === 'undefined' || !spoofData[domainDetail.value]" class="text-gray-400">info not available</div>
                <div v-else>{{extraction(spoofData[domainDetail.value])}}</div>
                <!-- </div> -->
              </div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-else>
                <!-- {{ makeToArray(spoofData[domainDetail.value]) }} -->
                <div v-if="spoofData[domainDetail.value] === '' || typeof spoofData[domainDetail.value] === 'undefined' || !spoofData[domainDetail.value]" class="text-gray-400">info not available</div>
                <div v-else>{{ spoofData[domainDetail.value]  }}</div>
              </div>
            </div>
          </div>
          <!-- scan Details -->
          <div class="w-full overflow-auto " v-if=" heading ==  'Scan Details'">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <div class="flex mt-2 hover:bg-yellow-300 DetailsTableRow" v-else v-for="ScanDetail in scanDetails" :key="ScanDetail.value">
              <div class="mt-3 ml-3 w-50 DetailsTableRowText">{{ScanDetail.key}}</div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-if="Array.isArray(makeToArray(spoofData[ScanDetail.value]))">
                <!-- <div class="mt-2" v-for="(data, index) in  makeToArray(spoofData[ScanDetail.value])" :key="index"> -->
                <div v-if="spoofData[ScanDetail.value] === '' || typeof spoofData[ScanDetail.value] === 'undefined' || !spoofData[ScanDetail.value]" class="text-gray-400">info not available</div>
                <div v-else>
                  <div v-if="ScanDetail.key == 'Scan Id'">{{ spoofData[ScanDetail.value] }}</div>
                  <div v-else>{{ spoofData[ScanDetail.value] }}</div>
                </div>

                <!-- </div> -->
              </div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-else>
                <!-- {{ makeToArray(spoofData[ScanDetail.value]) }} -->
                <div v-if="ScanDetail.key === 'First Seen' || ScanDetail.key === 'Last Seen'">{{ScanDetail.value}} ago</div>
                <div v-else-if="spoofData[ScanDetail.value] === '' || typeof spoofData[ScanDetail.value] === 'undefined' || !spoofData[ScanDetail.value]" class="text-gray-400">info not available</div>
                <div v-else>{{ makeToArray(spoofData[ScanDetail.value])  }}</div>

              </div>
            </div>
          </div>
          <!-- page statistics -->
          <div class="w-full overflow-auto" v-if=" heading ==  'Page Statistics'">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <div class="flex mt-2 hover:bg-yellow-300 DetailsTableRow" v-else v-for="pageStatistic in pageStatistics" :key="pageStatistic.value">
              <div class="mt-3 ml-3 w-50 DetailsTableRowText">{{pageStatistic.key}}</div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-if="Array.isArray(makeToArray(spoofData[pageStatistic.value]))">
                <div class="mt-2" v-for="(data, index) in  makeToArray(spoofData[pageStatistic.value])" :key="index">
                  <!-- {{spoofData[pageStatistic.value]}} -->
                  <div v-if="spoofData[pageStatistic.value] === '' || typeof spoofData[pageStatistic.value] === 'undefined' || !spoofData[pageStatistic.value]" class="text-gray-400">info not available</div>
                  <div v-else>{{ spoofData[pageStatistic.value]  }}</div>
                </div>
              </div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-else>
                <!-- {{ makeToArray(spoofData[pageStatistic.value]) }} -->
                <div v-if="spoofData[pageStatistic.value] === '' || typeof spoofData[pageStatistic.value] === 'undefined' || !spoofData[pageStatistic.value]" class="text-gray-400">info not available</div>
                <div v-else>{{ spoofData[pageStatistic.value] }}</div>
              </div>
            </div>
          </div>
          <!-- http redirects -->
          <div class="w-full overflow-auto" v-if=" heading ==  'HTTP Redirects'">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <div class="flex mt-2 hover:bg-yellow-300 DetailsTableRow" v-else v-for="httpRedirect in httpRedirects" :key="httpRedirect.value">
              <div class="mt-3 ml-3 w-50 DetailsTableRowText">{{httpRedirect.key}}</div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-if="Array.isArray(makeToArray(spoofData[httpRedirect.value]))">
                <!-- <div class="mt-2" v-for="(data, index) in  makeToArray(spoofData[httpRedirect.value])" :key="index"> -->
                <!-- {{spoofData[httpRedirect.value]}} -->
                <div v-if="spoofData[httpRedirect.value] == '' || spoofData[httpRedirect.value] == null" class="text-gray-400"> info not available</div>
                <div v-else> {{spoofData[httpRedirect.value].replace(/[\[\]\\\"]/g, '')}}</div>
                <!-- </div> -->
              </div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-else>
                <div v-if="spoofData[httpRedirect.value] === '' || typeof spoofData[httpRedirect.value] === 'undefined' || !spoofData[ScanDetail.value]" class="text-gray-400">info not available</div>
                <div v-else>{{ spoofData[httpRedirect.value] }}</div>
              </div>
            </div>
          </div>
          <!-- risk Rating -->
          <div class="w-full overflow-auto" v-if=" heading ==  'Risk Rating'">
            <!-- loader -->
            <div class="flex mx-auto my-auto overflow-x-visible bg-gray-200 rounded-lg py-auto min-w-60 mt-7" style="height: 200px; width:200px;" v-if="spoofData.current_scan_status === 'not_scanned' && spoofData.phashes === 'processing'">
              <h1 class="my-auto ml-3 border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
              <h1 class="my-auto ml-4 text-black py-auto h6">Scanning . . . </h1>
            </div>
            <div class="flex mt-2 hover:bg-yellow-300 DetailsTableRow" v-else v-for="riskRating in riskRatings" :key="riskRating.value">
              <div class="mt-3 ml-3 w-50 DetailsTableRowText">{{riskRating.key}}</div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-if="Array.isArray(makeToArray(spoofData[riskRating.value]))">
                <div class="mt-2" v-for="(data, index) in  makeToArray(spoofData[riskRating.value])" :key="index">
                  <div v-if="spoofData[riskRating.value] == '' || spoofData[riskRating.value] == null" class="text-gray-400"> info not available</div>
                  <div v-else-if="riskRating.key === 'Status'">
                    <div v-if="spoofData[riskRating.value] === '200' || spoofData[riskRating.value] === 200" class="p-1 -mt-3 bg-yellow-300 rounded-lg shadow-xl w-fit bottom-2">active</div>
                    <div v-else-if="spoofData[riskRating.value] === null">...</div>
                    <div v-else class="p-1 -mt-3 rounded-lg shadow-xl w-fit bottom-2" style="background-color: #aedd9d2a;">not active</div>
                  </div>
                  <div v-else> {{spoofData[riskRating.value]}}</div>
                </div>
              </div>
              <div class="mt-3 ml-50 w-100 DetailsTableRowText" v-else>
                <div v-if="spoofData[riskRating.value]  === '' || typeof spoofData[riskRating.value] === 'undefined' && riskRating.value !== 'High' && riskRating.value !== 'low' && riskRating.value !== 'Low' && riskRating.value !== 'No ' && riskRating.value !== 'medium' && riskRating.key !== 'Age'" class="text-gray-400">info not available</div>
                <!-- <div v-else-if="spoofData[riskRating.key] === 'Status'">
                  <div v-if="spoofData[riskRating.value] === 200">active</div>
                  <div v-else-if="spoofData[riskRating.value] === null">...</div>
                  <div v-else>not active</div>
                </div> -->
                <div v-else>{{ spoofData[riskRating.value]  }} </div>
                <div v-if="riskRating.value === 'High' || riskRating.value === 'low' || riskRating.value === 'Low' || riskRating.value === 'medium' || riskRating.key === 'Age'"> {{riskRating.value}}</div>
                <div v-if="riskRating.value === 'No '"> {{riskRating.value}}Risk</div>
              </div>
            </div>
          </div>
          <!-- <div class="w-full overflow-auto">
              <img class="w-full" :src="'/assets/screenshots/' + spoofData.spoofed_domain + '.png'" alt="...">/spoof//{spoofId}
          </div> -->
        </div>
      </div>
    </div>
    <div class="flex justify-between pt-3 mb-6">
      <div class="flex justify-between w-96 pl-7">
        <button class="my-2 buttons buttonsText hover:bg-yellow-300 sm:hidden lg:flex md:flex hidding" title="Mark Safe" @click="openModalr">Mark Safe</button>
        <!-- <button class="my-auto bg-yellow-100 buttons buttonsText hover:bg-yellow-300 sm:hidden lg:flex md:flex hidding" @click="openModalm">Monitor</button> -->
        <button v-if="props.spoofData.progress_status == 'monitoring'" class="my-auto bg-yellow-100 buttons buttonsText hover:bg-yellow-300 sm:hidden lg:flex md:flex hidding" @click="openModalm">Stop Monitoring</button>
        <button v-else class="my-auto bg-yellow-100 buttons buttonsText hover:bg-yellow-300 sm:hidden lg:flex md:flex hidding" @click="openModalm">Monitor</button>
        <button v-if="isValidTakedown" class="my-auto bg-yellow-300 buttons buttonsText hover:bg-yellow-400" as='button' @click="reported">Stop TakeDown</button> <!-- || spoofData.spoof_status_new == 'inprogress'|| response  || spoofData.spoof_status_new == 'completed' || spoofData.spoof_status_new == 'inprogress' <i class="text-xl fa-solid fa-ban"></i>-->
        <button v-else class="my-auto bg-yellow-300 buttons buttonsText hover:bg-yellow-300" @click="openModalt">Take Down</button>

      </div>
      <div class="flex justify-between mr-6 w-80">
        <Link class="my-auto buttons buttonsText hover:bg-yellow-300" @click="changeID(spoofData.id)" :href="'/spoof/view/' + nextId2"><i class="pr-2 fa fa-chevron-left" aria-hidden="true"></i> Previous Item</Link>
        <Link class="my-auto buttons buttonsText hover:bg-yellow-300" @click="changeIDPlus(spoofData.id)" :href="'/spoof/view/' + nextId ">Next Item<i class="pr-2 fa fa-chevron-right" aria-hidden="true"></i> </Link>
      </div>
    </div>
    <!-- Modal -->
    <!-- <transition name="modal-fade" >
      <div v-if="isModalVisible && spoofData.screenshot !== null" class="backlight" @click="closeModal">
        <div
          role="dialog"
          aria-modal="true"
          class="fade image-modal dark modal show backlight lgPadding "
          tabindex="-1"
          style=" display: block;"
        >
          <div class="modal-dialog modal-xl modal-dialog-centered full_sm" >
            <div class="modal-content ">
              <div class="image-modal-content full_sm">
                <img
                  class="card-img full-screen-image add-white-background full_sm mg_top"
                  :src="'/assets/screenshots/' + spoofData.spoofed_domain + '.png'"
                  alt="scan result screenshot"
                  style="max-height: 78vh; "
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition> -->
    <!--image Modal -->
    <transition name="modal-fade">
      <div v-if="isModalVisible " class="backlight" @click="closeModal">
        <!-- && spoofData.screenshot !== null -->
        <div role="dialog" aria-modal="true" class="fade image-modal dark modal show backlight lgPadding " tabindex="-1" style=" display: block;">
          <div class="modal-dialog modal-xl modal-dialog-centered full_sm">
            <!-- <div class="modal-content">
              <div class="image-modal-content full_sm">
                <img class="card-img full-screen-image add-white-background full_sm mg_top" :src="'/assets/screenshots/' + spoofData.spoofed_domain+'/'+filenames[0]" alt="scan result screenshot" style="max-height: 78vh; " />
                <button><i></i></button>
                <button><i></i></button>
              </div>
            </div> -->
            <div class="modal-content">
              <div class="relative image-modal-content full_sm">
                <img class="card-img full-screen-image add-white-background full_sm mg_top" :src="'/assets/screenshots/' + spoofData.spoofed_domain + '/' + filenames[imageId]" alt="scan result screenshot" style="max-height: 78vh;" />
                <button @click="imageLeft" class="arrow-button left"><i class="fas fa-chevron-left"></i></button>
                <button @click="imageRight" class="arrow-button right"><i class="fas fa-chevron-right"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <!--moniter Modal -->
    <transition name="modal-fade">
      <div v-if="isModalVisiblem" class="backlight" @click="closeModalm">
        <div role="dialog" aria-modal="true" class="fade image-modal dark modal show backlight" tabindex="-1" style="padding-left: 14px; display: block;">
          <div class="modal-dialog modal-xl modal-dialog-centered" style="">
            <div class="modal-content">
              <div class="">
                <div class="mx-auto bg-white modelStyle" style="">
                  <div class="relative w-100">
                    <img class="float-right" :src="'/assets/systemImages/Exit.svg'" />
                  </div>
                  <div class="relative flex my-2 modelText">
                    <img class="pr-3 mb-3" :src="'/assets/systemImages/Promo.svg'" />
                    <img class="absolute m-2" :src="'/assets/systemImages/bookmark.svg'" />
                    <div class="my-auto" v-if="props.spoofData.progress_status != 'monitoring'">
                      By setting the status to 'monitoring', you are indicating that
                      <span class="text-yellow-500">{{spoofData.spoofed_domain}} will be under constant surveillance</span>.
                      Are you sure you want to proceed?
                    </div>
                    <!-- stop monitoring -->
                    <div class="my-auto" v-else>
                      By setting the status to 'stop-monitoring', you are indicating that
                      <span class="text-yellow-500">{{spoofData.spoofed_domain}} will stop being under constant surveillance</span>.
                      Are you sure you want to proceed?
                    </div>
                  </div>
                  <div class="relative -mt-4 w-100">
                    <Link v-if="props.spoofData.progress_status != 'monitoring'" class="float-right px-4 bg-yellow-300 buttons buttonsText" :href="'/spoof/Monitor/' + spoofData.id">confirm</Link>
                    <Link v-else class="float-right px-4 bg-yellow-300 buttons buttonsText" :href="'/spoof/stop_monitoring/' + spoofData.id">confirm</Link>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </transition>

    <!--norisk Modal -->
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
                    <img class="absolute m-2" :src="'/assets/systemImages/bookmark.svg'" />
                    <div class="my-auto">By clicking 'Mark as Norisk', you are indicating that <span class="text-yellow-500">{{spoofData.spoofed_domain}} will be ignored</span> . Are you sure you want to proceed?</div>
                  </div>
                  <div class="relative w-100">
                    <!-- <button class="float-right px-4 mr-3 bg-gray-300 buttons buttonsText" @click="closeModal" type="button">cancel</button> -->
                    <Link class="float-right px-4 bg-yellow-300 buttons buttonsText" :href="'/spoof/markAsNoRisk/' + spoofData.id">confirm</Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <!--norisk Modal -->
    <transition name="modal-fade">
      <div v-if="isModalVisiblet" class="backlight" @click="closeModalt">
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
                    <div class="my-auto"><span v-if="reporte == ''">Do you want to report <span class="text-yellow-500">{{spoofData.spoofed_domain}}</span> for takedown?</span><span v-if="reporte != ''">{{reporte}} </span></div>
                  </div>
                  <div class="relative w-100">
                    <!-- <button class="float-right px-4 mr-3 bg-gray-300 buttons buttonsText" @click="closeModal" type="button">cancel</button> -->
                    <Link v-if="reporte == ''" class="float-right px-4 bg-yellow-300 buttons buttonsText" :href="'/spoof/requestAuthorization/' + spoofData.id">confirm</Link>
                    <!-- <Link @click="closeModalt" v-if="reporte != ''" class="float-right px-4 bg-yellow-300 buttons buttonsText">cancel</Link> -->
                    <button v-if="reporte != ''" class="float-right px-4 bg-yellow-300 buttons buttonsText" @click="submit()"> stop Takedown </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </AuthenticatedLayout>
</template>

<style scoped>
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
.widthSetting{
  width: 35%;
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










 