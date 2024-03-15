<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { Link } from "@inertiajs/vue3"
import { defineComponent } from 'vue';
import { defineProps, onMounted } from 'vue';
import { ref, watch, computed} from 'vue';


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
    type: Object,
  },
  spoofList: {
    type: Object,
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


const isModalVisible = ref(false);

const openModal = () => {
  isModalVisible.value = true;
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

//openr
const isModalVisibler = ref(false);

const openModalr = () => {
  isModalVisibler.value = true;
};

const closeModalr = () => {
  isModalVisibler.value = false;
};

// Add a watcher to close the modal after a delay
watch(isModalVisibler, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
      // Close the modal after the fade-out transition
      closeModalr();
    }, 300); // Adjust the delay to match the transition duration
  }
});

defineComponent({
  components: {
    Link
  },
  props: {
    error: Object
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

    return 'Past';
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

// methods.webflowRatingLabel(methods.webflowRating(spoofData))
const riskRatings = [
  { key: 'Webflow Rating', value:  methods.webflowRatingLabel(methods.webflowRating(props.spoofData))  },
  { key: 'Domian Rating ', value:  methods.domainRatingLabel(methods.domainRating(props.spoofData)) },
  { key: 'Interface Rating', value: methods.interfaceRatingLabel(methods.interfaceRating(props.spoofData)) },
   { key: 'Overall Rating', value:  methods.overallRatingLabel(methods.overallRating(props.spoofData))  },
  // { key: 'Age', value: calculateTimeDifference(props.spoofData.registrationDate) },//'registrationDate'
  // { key: 'Ip Address', value: 'ip_address' },
  // { key: 'Status', value: 'http_status_code' },
  
];

//image getting test
const imageUrl = ref(null);
const basePath = '/assets/screenshots/google.com/';

const spoofedDomain = "google.com";
// const spoofedDomain = props.spoofData.spoofed_domain;
async function getLatestImageFile() {
    try {
        // Fetch the list of files in the directory
        const response = await fetch(basePath);
        const files = await response.json(); // Assuming the server returns a JSON array of file names

        // Filter out image files that match the spoofed domain
        const imageFiles = files.filter(file => file.startsWith(spoofedDomain) && file.endsWith('.png'));

        // Sort the image files by modification date
        imageFiles.sort((a, b) => {
            return new Date(b.lastModified) - new Date(a.lastModified);
        });

        // Return the name of the latest modified image file
        return imageFiles.length > 0 ? imageFiles[0] : null;
    } catch (error) {
        console.error('Error fetching image files:', error);
        return null;
    }
}
// Usage example
getLatestImageFile().then(latestImageFile => {
    if (latestImageFile) {
        imageUrl.value = basePath + latestImageFile;
        console.log('Latest image URL:', imageUrl.value);
    } else {
        console.log('No image files found for the spoofed domain.');
    }
});
//end of testing


const heading = ref('Screenshots');
const changePageContent = (buttonCLicked) =>{
    heading.value = buttonCLicked;
}
 
// Data
const bladeViewUrl = ref("");

// Set the URL of your Blade view when the component is mounted
onMounted(() => {
  bladeViewUrl.value = "http://127.0.0.1:5500/resources/views/map.html"; // Replace with the actual relative path
});

const userId = props.domain[0].user_id;

// const nextId = props.spoofList[1].id;
const nextId = ref(props.spoofData.id + 1);
const nextId2 = ref(props.spoofData.id - 1);


const form = useForm({
    abuse_type: '',
    evidence_urls: props.spoofData.spoofed_domain,
    additional_notes: '',
    observed_date: props.spoofData.created_at,
    attachment: null,
    carbon_copy_request_to: '',
    reported_via: 'takedown',
    targetDomain: props.spoofData.domain_id,
    id: props.spoofData.id,
    Confirm: true,
});
const reporte =ref('');

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
const submit = () => {

    const formData = new FormData();
    formData.append('abuse_type', form.abuse_type);
    formData.append('evidence_urls', form.evidence_urls);
    formData.append('additional_notes', form.additional_notes);
    formData.append('targetDomain', form.targetDomain);
    formData.append('Confirm', form.Confirm);
    formData.append('id', form.id);
    // Append the file to the FormData object if it's not an empty string
    if (form.attachment !== null) {
        formData.append('attachment', form.attachment);
        console.log('it is not null')
    }else{
      console.log('it is null')
    }

    formData.append('carbon_copy_request_to', form.carbon_copy_request_to);
    const response = form.post(route('ReportSpoofySite'));
      // const response = router.post('/ReportSpoofySite', form)
    if(response.errors == {}){
        Toast.fire({
          icon: 'success',
          title: 'Reported Successfully',
        });
    }

}


</script>

<template>

  <Head v-if="spoofData.spoof_status_new == 'completed'" title="ScannedCompleted" />

  <Head v-else-if="spoofData.spoof_status_new == 'inprogress'" title="ScannedInProgress" />

  <Head v-else title="RequestAuthorization" />
  <!-- <Head title="RequestAuthorization" /> -->

  <AuthenticatedLayout v-if=" props.userid === userId" class="overflow-scroll fontFamily" style="height:100vh; background: #FFF;">
    <div class="flex justify-between mt-6 w-100">
      <div class="relative ml-6">
        <button class="absolute w-40 h-12 px-3 rounded-tr-full bg-dark tabsText" style="">{{props.domain[0].domain_name}}</button>
        <button class="w-56 h-12 pr-4 bg-gray-300 rounded-tr-full tabsText pl-9" style="margin-left: 106px;">Social Media</button>
      </div>
      <Link class="my-auto buttons buttonsText mr-9" :href="'/spoof/view/' + spoofData.id"><i class="pr-2 fa fa-chevron-left" aria-hidden="true" preserve-scroll></i> Back</Link>
    </div>
    <div class="flex flex-row mx-4 mt-3 bg-yellow-100 h-14 rounded-t-xl">
      <h2 class="my-auto text-gray-600 pl-7 h3 riskpush">{{ spoofData.spoofed_domain }}</h2>
      <!-- <h2 class="my-auto risk h3">High Risk</h2> -->
      <h2 class="my-auto font-bold text-center capitalize py-auto risk h3" style="min-width:fit-content;" :style="{'color': methods.overallRatingLabel(methods.overallRating(spoofData )) === 'High' ? '#ED0707' : methods.overallRatingLabel(methods.overallRating(spoofData )) === 'medium' ? '#F7610D' : '#3AAC11'}">
        {{ methods.overallRatingLabel(methods.overallRating(spoofData )) }} Risk
      </h2>
    </div>
    <div class="justify-between mx-4 mt-2 lg:flex-row lg:flex">
      <form class="overflow-y-auto" style="lg:width: 67%; md:width:67%; min-width:50%; height: 79vh; max-height:500px;" @submit.prevent="submitForm" enctype="multipart/form-data">
        <div class="flex pt-2 w-100 justify-evenly">
          <label for="abuseType" class="p-3 w-50 reportFormText">Abuse Type<span class="text-red-500 h5">*</span></label>
          <div class="relative w-50">
            <select id="abuseType" name="abuseType" class="w-full h-10 pl-3 pr-10 text-gray-300 bg-yellow-100 border border-gray-300 rounded-md reportFormText" v-model="form.abuse_type">
              <option value="" disabled selected class="text-black ">...Choose Abuse Type...</option>
              <option value="Spoofing" class="text-black">Spoofing</option>
              <option value="Phishing" class="text-black">Phishing</option>
            </select>
            <small v-if="form.errors.abuse_type" class="text-red-700">{{form.errors.abuse_type}}</small>

            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
              <!-- <i class="fa fa-chevron-down"></i> -->
            </div>
          </div>
        </div>
        <div class="flex pt-2 w-100 justify-evenly">
          <label for="Other URLs" class="p-3 w-50 reportFormText">
            Other URLs<span class="text-red-500 h5">*</span>
          </label>
          <div class="mr-1 w-50">
            <textarea type="url" id="fname" name="fname" class="text-gray-700 bg-yellow-100 border-none w-100 reportFormText" placeholder="provide domain, eg example.com" v-model="form.evidence_urls"></textarea>
            <small v-if="form.errors.evidence_urls" class="text-red-700">{{form.errors.evidence_urls}}</small>
          </div>
        </div>
        <div class="flex pt-2 w-100 justify-evenly">
          <label for="Other URLs" class="p-3 w-50 reportFormText">
            Additional Notes
          </label>
          <div class="w-50">
            <div class="justify-around mr-1 bg-yellow-100 ">
              <textarea type="url" id="fname" name="Additional Notes" class="text-gray-700 bg-yellow-100 border-none h-100 w-100 reportFormText" rows="7" placeholder="Please provide details of abuse" v-model="form.additional_notes"></textarea>
            </div>
            <small v-if="form.errors.additional_notes" class="text-red-700">{{form.errors.additional_notes}}</small>
          </div>
        </div>
        <div class="flex pt-2 w-100 justify-evenly">
          <label class="p-3 w-50 reportFormText">
            Attachments
          </label>
          <div class=" w-50">
            <div class="flex mr-1 bg-yellow-100">
              <i class="mx-3 mt-2 text-xl fa fa-paperclip"></i>
              <input type="file" class="pt-2 h-100 w-100" placeholder="Attachment.dox" @change="handleFileChange" tooltip="Attachment.dox" />
            </div>
            <small v-if="form.errors.attachment" class="text-red-700">{{form.errors.attachment}}</small>
          </div>
        </div>
        <div class="flex pt-2 w-100 justify-evenly">
          <label class="p-3 w-50 reportFormText">
            Carbon Copy (CC) the Request
          </label>
          <div class="w-50">
            <div class="flex justify-around mr-1 bg-yellow-100">
              <input type="email" id="fname" name="Carbon Copy (CC) the Request" class="placeholder-gray-700 bg-yellow-100 border-none h-100 w-100 reportFormText" v-model="form.carbon_copy_request_to" placeholder="james.name@example.com" />
            </div>
            <small v-if="form.errors.carbon_copy_request_to" class="text-red-700">{{form.errors.carbon_copy_request_to}}</small>
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
                        <div class="my-auto">Do you want to Report <span class="text-yellow-500">{{form.evidence_urls}}</span>?</div>
                      </div>
                      <div class="relative w-100">
                        <button class="float-right px-4 bg-yellow-300 buttons buttonsText" type="submit" @click="submit()">confirm</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </transition>
      </form>
      <div class="ml-1 overflow-y-auto box-style sm_hidden sm:w-full" style="min-width: 50%; height: 75vh; max-height:500px;">
        <div class="align-middle bg-gray-100 rounded-t-lg" style="height: 45px; width: 100%;">
          <div class="py-3 pl-3 chechdetails">Details</div>
          <!-- will do js here -->
          <div v-if="form.abuse_type == 'Spoofing'">
            <h1 class="text-lg font-bold">Spoofing:</h1>
            <p>Spoofing refers to the act of deceiving or tricking someone or something by falsifying information or pretending to
              be someone or something else. In the context of computer security, spoofing commonly involves manipulating data,
              IP addresses, or electronic communications to appear as though they originate from a trustworthy source.</p>
          </div>
          <div v-if="form.abuse_type == 'Phishing'">
            <h1 class="text-lg font-bold">Phishing:</h1>
            <p> Phishing is a type of cyber attack in which attackers attempt to trick individuals into revealing sensitive information, such as
              login credentials or personal details, by posing as a trustworthy entity.
              This is often done through deceptive emails, messages, or websites that
              mimic legitimate sources to deceive the target into providing confidential information.</p>
          </div>
          <div v-if="form.evidence_urls "><!-- && form.abuse_type -->
            <br>
            <p>Reporting <span class="text-lg text-bold h3">{{form.evidence_urls}}</span> <span v-if="form.targetDomain">for {{form.abuse_type}} <span class="text-lg text-bold h3">{{props.domain[0].domain_name}}</span></span></p>
          </div>
          <div v-if="form.attachments">
            <img v-if="imageUrl" :src="imageUrl" alt="Please Upload Image!" style="height: 300px; width: auto;" />
          </div>
          <!-- our screenshot -->
          <img v-if="filenames[0]" class="w-full" :src="'/assets/screenshots/' + spoofData.spoofed_domain + '/' + filenames[0]" alt="SORRY ( : ,IMAGE NOT FOUND" @click="openModal">
          <!-- risk Rating -->
          <div class="flex mt-2 hover:bg-yellow-300 DetailsTableRow" v-for="riskRating in riskRatings" :key="riskRating.value">
            <div class="mt-3 ml-3 w-50 DetailsTableRowText">{{riskRating.key}}</div>
            <div class="mt-3 ml-50 w-100 DetailsTableRowText">
              <div v-if="spoofData[riskRating.value]  === '' || typeof spoofData[riskRating.value] === 'undefined' && riskRating.value !== 'High' && riskRating.value !== 'low' && riskRating.value !== 'No ' && riskRating.value !== 'medium' && riskRating.key !== 'Overall Rating'" class="text-gray-400">info not available</div>
              <div v-else>{{ spoofData[riskRating.value]  }} </div>
              <div v-if="riskRating.value === 'High' || riskRating.value === 'low' || riskRating.value === 'medium' || riskRating.key === 'Overall Rating'"> {{riskRating.value}}</div>
              <div v-if="riskRating.value === 'No '"> {{riskRating.value}}Risk</div>
            </div>
          </div>

          <!-- end rating -->
        </div>
      </div>
    </div>
    <div class="relative justify-between pt-3 w-100"><!-- flex -->
      <div class="flex justify-between float-right w-64 pr-14">
        <Link class="my-2 buttons buttonsText">cancel</Link>
        <button class="my-auto bg-yellow-300 buttons buttonsText" @click="openModal">submit</button>
      </div>
      <!-- <div class="flex justify-between mr-6 w-80"> -->
      <!-- <Link class="my-auto buttons buttonsText" @click="changeID(spoofData.id)" :href="'/spoof/view/' + nextId2"><i class="pr-2 fa fa-chevron-left" aria-hidden="true"></i> Previous Item</Link> -->
      <!-- <Link class="my-auto buttons buttonsText" @click="changeIDPlus(spoofData.id)" :href="'/spoof/view/' + nextId ">Next Item<i class="pr-2 fa fa-chevron-right" aria-hidden="true"></i> </Link> -->
      <!-- </div> -->
    </div>

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
                    <div class="my-auto" v-if="reporte == 'success'">"You have successfully reported <span class="text-yellow-500">{{spoofData.spoofed_domain}}</span>. Would you like to <span class="text-red-600">submit a takedown report</span> for the next domain, {{domain}}?</div>
                    <div class="my-auto" v-if="reporte == 'error'">Please complete all required fields.</div>
                  </div>
                  <div class="relative w-100">
                    <!-- <button class="float-right px-4 mr-3 bg-gray-300 buttons buttonsText" @click="closeModal" type="button">cancel</button> -->
                    <Link class="float-right px-4 bg-yellow-300 buttons buttonsText" :href="'/spoof/requestAuthorization/' + spoofData.id">confirm</Link>
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
width: 624px;
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
.riskpush{
width: 36%;
}
.shadow-{
box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);

}
.modelText{
width: 474px;
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








 