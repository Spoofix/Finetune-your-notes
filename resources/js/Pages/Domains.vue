<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Link} from "@inertiajs/vue3"
// import { defineComponent, onMounted } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { defineComponent } from 'vue';
import { ref, computed } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

function ageContribution(age) {
    const decayFactor = 1.9; //decayValue
    const baseValue = 100;    //Initial contribution value

    const contribution = baseValue * Math.exp(-decayFactor * age);
    return contribution;
}

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
// let me = props.spoofList[299];
// const [webflowRating, domainRating, interfaceRating, overallRating] = ratingsValues(me.csscolor, me.phashes, me.htmls, me.domainsimilarityrate, 2, true, me.domain_rating);
// const [webflowRating, domainRating, interfaceRating, overallRating] = ratingsValues(100, 100, 100, 100, 100, 100, 90,80);
// console.log(webflowRating, domainRating, interfaceRating, overallRating);

// console.log(me.csscolor, me.phashes, me.htmls,me.domain_rating, me.ssl_certificate_details+ "n",me.registrationDate ,me.domainsimilarityrate)
// const ha = calculateTimeDifference(me.registrationDate);
// console.log('its :')
// console.log(ha)

// Calculate overall rating
// const overallRating = (webflowRating + domainRating + interfaceRating) / 3;

// console.log('Webflow Rating:', webflowRating);
// console.log('Domain Rating:', domainRating);
// console.log('Interface Rating:', interfaceRating);
// console.log('Overall Rating:', overallRating);



defineComponent({
   name: 'App',
  components: {
    Link
  },
  
});

const props = defineProps({
     testList: {
        type: Object,
    },
    domainList: {
        type:Object,
    },
      spoofList: {
        type: Object,
    }
});

const methods = {
    spoofStatus(spoof) {
      const status = spoof.progress_status;
      let firstSeen = spoof.first_seen;
      let firstSeenDate = new Date(firstSeen);
      let currentDate = new Date();
      let timeDifference = currentDate - firstSeenDate;
      let oneWeekInMillis = 7 * 24 * 60 * 60 * 1000;
      if(status == 'monitoring'){
        return "monitoring";
      } 
      if(status == 'norisk'){
        return "Ignored";
      } 
      if (timeDifference > oneWeekInMillis) {
           return "Pending Authorization";
      } else {
           return "New";
      }
    },
    webflowRating(spoof) {
    const [webflowRating, domainRating, interfaceRating, overallRating]  = ratingsValues(spoof.csscolor, spoof.phashes, spoof.htmls, spoof.domainsimilarityrate, calculateTimeDifference(spoof.registrationDate), true, spoof.domain_rating);
    return webflowRating; 
    },

    domainRating(spoof) {
    const [webflowRating, domainRating, interfaceRating, overallRating]  = ratingsValues(spoof.csscolor, spoof.phashes, spoof.htmls, spoof.domainsimilarityrate, calculateTimeDifference(spoof.registrationDate), true, spoof.domain_rating);

      return domainRating; 
    },
    interfaceRating(spoof) {
      // Calculate interface rating for the spoof
    const [webflowRating, domainRating, interfaceRating, overallRating]  = ratingsValues(spoof.csscolor, spoof.phashes, spoof.htmls, spoof.domainsimilarityrate, calculateTimeDifference(spoof.registrationDate), true, spoof.domain_rating);

      return interfaceRating; 
    },
    overallRating(spoof) {
    const [webflowRating, domainRating, interfaceRating, overallRating]  = ratingsValues(spoof.csscolor, spoof.phashes, spoof.htmls, spoof.domainsimilarityrate, calculateTimeDifference(spoof.registrationDate), true, spoof.domain_rating);
      return overallRating; 
    },
    webflowRatingLabel(rating, spoof) {
      const age = calculateTimeDifference(spoof.registrationDate);
      if(age > 3 && age < 5){
        spoof['webflowRatingLabel'] = "Low";
        return "Low";
      }
      if(age > 5){
        spoof['webflowRatingLabel'] = "No Risk";
        return "No Risk";
      }
      spoof['webflowRatingLabel'] =  rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
      return rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
    },
    domainRatingLabel(rating, spoof) {
      spoof['domainRatingLabel'] =  rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
      return rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
    },
    interfaceRatingLabel(rating, spoof) {
      const age = calculateTimeDifference(spoof.registrationDate);
      if(age > 3 && age < 5){
        spoof['interfaceRatingLabel'] = "Low";
        return "Low";
      }
      if(age > 5){
        spoof['interfaceRatingLabel'] = "No Risk";
        return "No Risk";
      }
      spoof['interfaceRatingLabel'] = rating > 5 ? 'High' : rating > 4 ? 'medium' : 'Low';
      return rating > 5 ? 'High' : rating > 4 ? 'medium' : 'low';
    },
    overallRatingLabel(rating, spoof) {
      const age = calculateTimeDifference(spoof.registrationDate);
      if(age > 3 && age < 5){
        spoof['overallRatingLabel'] = "Low";
        return "Low";
      }
      if(age > 5){
        spoof['overallRatingLabel'] = "No Risk";
        return "No Risk";
      }
      spoof['overallRatingLabel'] = rating > 4 ? 'High' : rating > 3 ? 'medium' : 'Low';
      return rating > 4 ? 'High' : rating > 3 ? 'medium' : 'Low';
    },
  };
  const calculateTimeDifference = (dateString) => {
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
      return calculateTimeDifference(firstDateString);
    }
  }
  return 'none';
};
// console.log(calculateTimeDifference(["2021-07-25 16:32:55","2021-07-25 11:32:55"]));

const filteredSpoofListCurrentValue = ref([]); 


function filteredSpoofList(domainid) {
  const spoofList = props.spoofList;
  const filteredSpoofListValue = [];
  for (const spoof of spoofList) {
    if (spoof.domain_id === domainid) {
      filteredSpoofListValue.push(spoof);
    }
  }
  filteredSpoofListCurrentValue.value = filteredSpoofListValue;
  return filteredSpoofListValue;
}


const showTable = ref(true);
const indexStore = ref(null);

function delay(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

// const element = document.getElementById("smoothDropDown");

// const toggleTable = async (index, domainid) => {
//   element.style.marginTop = -100 + '%'; 
//   if (index === indexStore.value) {
//     showTable.value = !showTable.value;
//     if (element) {
//       element.classList.add('.smoothDropDown');
//       element.style.marginTop = -100 + '%'; 
//       while (width < 0) {
//             await delay(1000); 
//             width += 10;
//             element.style.marginTop = width + '%'; 
//         }
//       // await delay(1000);
//   };
//   } else {
//     showTable.value = true;
//     console.log("here");
//   }
//   indexStore.value = index;
//   filteredSpoofList(domainid);
// };

// function delay(ms) {
//     return new Promise(resolve => setTimeout(resolve, ms));
// }

// const toggleTable = async (index, domainid) => {
//     // location.reload();
//     // await delay(3000);
//     isSidebarOpen.value = !isSidebarOpen.value;
//     let width = 0;
//     const element = document.getElementById('smoothTextId');
//     if (element) {
//         if (isSidebarOpen.value) {
//             element.classList.add('hiddenClass');
//             await delay(360);
//             element.classList.remove('hiddenClass');
//             element.classList.add('smoothText');
//             while (width < 250) {
//                 await delay(0); // Await the promise to introduce the delay
//                 width += 70;
//                 element.style.maxWidth = width + 'px'; // Update the max-width property
//             }
//         } else if(!isSidebarOpen.value) {
//             element.classList.remove('smoothText');
//         } else {
//             element.classList.remove('smoothText');
//         }
//     }
// };

const toggleTable = async (index, domainid) => {
  changePage(1); 
  // console.log(Object.keys(totalPages).length );
    if (index === indexStore.value) { 
      showTable.value = !showTable.value;
    } else {
      showTable.value = true;
      // console.log("here");
    }
    indexStore.value = index;
    filteredSpoofList(domainid);
};

indexStore.value = null; 
const currentPage = ref(1);
const itemsPerPage = 7;



// const spooflist = filteredSpoofListCurrentValue.value;
// const paginatedSpoofList = computed(() => {
//   // console.log(filteredSpoofListCurrentValue.value);
//   const start = (currentPage.value - 1) * itemsPerPage;
//   const end = start + itemsPerPage;
//   return filteredSpoofListCurrentValue.value.slice(start, end);
// });
// Define a function to sort the list
const asc = ref(true);
const whichSort = ref('overallRatingLabel');

// // overallRatingLabel
// // spoofed_domain
// // webflowRatingLabel
// // interfaceRatingLabel
// // domainRatingLabel

// const sortedList = sortList(filteredSpoofListCurrentValue.value);
function sortList(list) {
    // Step 1: Sort by spoofed_domain
    list.sort((a, b) => {
        const domainA = (a.spoofed_domain || '').toLowerCase();
        const domainB = (b.spoofed_domain || '').toLowerCase();

        if (domainA < domainB) return -1;
        if (domainA > domainB) return 1;
        return 0;
    });

    // Step 2: Sort by webflowRatingLabel
    // list.sort((a, b) => {
    //     const ratingA = (a.overallRatingLabel || '').toLowerCase();
    //     const ratingB = (b.overallRatingLabel || '').toLowerCase();

    //     // Exemption condition: 'm' comes before 'l'
    //     if (ratingA === 'm' && ratingB === 'l') return -1;
    //     if (ratingA === 'l' && ratingB === 'm') return 1;

    //     if (ratingA < ratingB) return -1;
    //     if (ratingA > ratingB) return 1;
    //     return 0;
    // });
    
    // Step 2: Sort by webflowRatingLabel
    list.sort((a, b) => {
        const ratingA = (a[whichSort.value] || '').toLowerCase();
        const ratingB = (b[whichSort.value] || '').toLowerCase();

        // Custom order: high, medium, low, no risk
        const order = { 'high': 0, 'medium': 1, 'low': 2, 'no risk': 3 };

        // Compare ratings based on custom order
        const indexA = order[ratingA] !== undefined ? order[ratingA] : Infinity;
        const indexB = order[ratingB] !== undefined ? order[ratingB] : Infinity;

        // If both ratings are found in the custom order, compare them directly
        if (indexA !== Infinity && indexB !== Infinity) {
            return indexA - indexB;
        }

        return ratingA.localeCompare(ratingB);
    });


    return list;
}

const sortedList = sortList(filteredSpoofListCurrentValue.value);


let paginatedSpoofList = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;

  let sortedList = sortList(filteredSpoofListCurrentValue.value);
  const slicedList = filteredSpoofListCurrentValue.value.slice(start, end);


  // let sortedList = sortList(slicedList);

  // return sortedList;
  return slicedList;
});
console.log(paginatedSpoofList);


const totalPages = computed(() => Math.ceil(filteredSpoofListCurrentValue.value.length / itemsPerPage));

const changePage = (page) => {
  if(page === 0 || page < 0) {
    console.log(page);
    return;
  }else{
    currentPage.value = page;
  //  console.log(page);
  }
};
// console.log(Object.keys(totalPages).length - 1);
const active = toggleTable(0 , props.domainList[0].id);

 function scrollToElement(index) {
    const element = document.getElementById('myDiv');
    const margin = getComputedStyle(element).marginTop;

    // Calculate the desired scroll position
    const scrollPosition = element.offsetTop - parseInt(margin);

    // Scroll to the element
    window.scrollTo({
        top: scrollPosition,
        // behavior: 'smooth'
    });
}

function sortbyvalue(update){
  whichSort.value = update;
} 
</script>

<template>
    <Head title="Scanned" />

    <AuthenticatedLayout class="overflow-scroll " style="height: 100vh;">
        <section class="py-1 pt-6 bg-blueGray-50">
          
        <!--  place -->
          <div class="mr-4 lg:mr-7" v-for="(domain, index) in domainList" :key="index">
            <div
              class="flex items-center justify-between w-full my-2 cursor-pointer md:mx-3 lg:mx-4 h-14 hover:bg-gray-300"
                style="border-radius: 6px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);"
                id="myDiv"
                :class="showTable && index == indexStore ? 'bigDropdownBgActive' : 'bigDropdownBg'"     
              @click="toggleTable(index, domain.id), scrollToElement(index)"
            >
              <div class="ml-5 text-2xl font-semibold text-blueGray-700">
                  <h3 class="orgDomain">
                   <Link :href="'rescan-domain/' + domain.id">{{ domain.domain_name }}</Link> 
                  </h3>
              </div>
              <div class="mr-5 botton ">
                <i
                  class="fa"
                  :class="showTable && index == indexStore ? 'fa-chevron-down' : 'fa-chevron-left'"
                  aria-hidden="true"
                ></i>
                  <div>
                  Spoofing sites 
                </div>
              </div>
            </div>
             <div
                v-if="showTable && index === indexStore"
                class="items-center overflow-auto bg-transparent border-collapse lg:ml-4 md:ml-4 md:-mr-3 smooth "
                :class="{ 'smoothDropDown': showTable }"
                id="smoothDropDown"
                  > 
              <div :class="{ 'table-container.open': showTable, 'table-container' : !showTable}">
                <!-- <div> -->
                <table class="w-full text-sm " style="min-width: 600px;" v-if="paginatedSpoofList.length !== 0">
                  <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                      <tr class="container justify-evenly">
                          <th  class="py-3 pl-2 cursor-pointer min-w-max" @click="sortbyvalue('spoofed_domain')">
                              Domain 
                              <i class="ml-1 fa fa-sort-up" v-if="whichSort && whichSort != 'spoofed_domain'"></i> <!-- v-if="sortOrder === 'asc'"-->
                              <i class="ml-1 fa fa-sort-down" v-else></i> <!--v-if="sortOrder === 'desc'" -->
                          </th>
                          <th  class="py-3 text-left cursor-pointer" @click="sortbyvalue('status')">
                              <span class="lg:mr-9">Status</span>
                            <!--  <i class="ml-1 fa fa-sort-up" v-if="whichSort && whichSort == 'status'"></i>--> <!-- v-if="sortOrder === 'asc'"-->
                              <!--<i class="ml-1 fa fa-sort-down" v-else></i>--> <!--v-if="sortOrder === 'desc'" -->
                          </th>
                          <th  class="py-3 text-left cursor-pointer" @click="sortbyvalue('webflowRatingLabel')">
                              Webflow Rating
                              <i class="ml-1 fa fa-sort-up" v-if="whichSort && whichSort != 'webflowRatingLabel'"></i> <!-- v-if="sortOrder === 'asc'"-->
                              <i class="ml-1 fa fa-sort-down" v-else></i> <!--v-if="sortOrder === 'desc'" -->
                          </th>
                          <th  class="py-3 text-left cursor-pointer" @click="sortbyvalue('domainRatingLabel')">
                              Domain Rating
                              <i class="ml-1 fa fa-sort-up" v-if="whichSort && whichSort != 'domainRatingLabel'"></i> <!-- v-if="sortOrder === 'asc'"-->
                              <i class="ml-1 fa fa-sort-down" v-else></i> <!--v-if="sortOrder === 'desc'" -->
                          </th>
                          <th  class="py-3 text-left cursor-pointer" @click="sortbyvalue('interfaceRatingLabel')">
                              Interface Rating
                              <i class="ml-1 fa fa-sort-up" v-if="whichSort && whichSort != 'interfaceRatingLabel'"></i> <!-- v-if="sortOrder === 'asc'"-->
                              <i class="ml-1 fa fa-sort-down" v-else></i> <!--v-if="sortOrder === 'desc'" -->
                          </th>
                            <th class="py-3 text-left cursor-pointer" @click="sortbyvalue('overallRatingLabel')">
                              Overall
                              <i class="ml-1 fa fa-sort-up" v-if="whichSort && whichSort != 'overallRatingLabel'"></i> <!-- v-if="sortOrder === 'asc'"-->
                              <i class="ml-1 fa fa-sort-down" v-else></i> <!--v-if="sortOrder === 'desc'" -->
                            </th>
                          <!-- <th  class="py-3 text-center">
                              Overall
                          </th> -->
                          <th  class="py-3 text-center">
                              Action
                          </th>
                      </tr>
                  </thead>
                  <tbody>
                      <!-- <tr class=" tableRow
                      transition-colors duration-300 hover:!bg-blue-50 border-solid border-white "
                      v-for="(spoof, index) in paginatedSpoofList" :key="index"
                          :style="{ background: index % 2 !== 1 ? '#FDE6E6' : '#F7E6E4' }"
                          
                          > -->
                <tr class=" tableRow
                      transition-colors duration-300 hover:!bg-yellow-200 border-solid border-white text-black"
                      v-for="(spoof, index) in paginatedSpoofList" :key="index"
                 :class="{ 'hidden': spoof.spoofed_domain == domain.domain_name }"
                  :style="{'background-color': methods.overallRatingLabel(methods.overallRating(spoof), spoof) === 'High' && spoof.spoofed_domain == domain.domain_name ? '#EBF7E7' : methods.overallRatingLabel(methods.overallRating(spoof), spoof) === 'High' ? '#FDE6E6' : methods.overallRatingLabel(methods.overallRating(spoof), spoof) === 'medium' ? '#FEF1E9' : '#EBF7E7'}"
                  >
                  <!--  :style="{ background: index % 2 !== 1 ? '#FDE6E6' : '#F7E6E4' }" -->
                <td class="pl-3 overflow-auto py-auto" style="max-width: 190px;">
                  {{ spoof.spoofed_domain }}
                </td>
                <td class="text-left py-auto" v-if="spoof.current_scan_status === 'not_scanned' && spoof.phashes === 'processing'">
                  <h1 class="border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
                </td>
                <td class="text-left capitalize py-auto" v-else>
                  {{ methods.spoofStatus(spoof) }}
                </td>
                <td class="text-left py-auto" v-if="spoof.current_scan_status === 'not_scanned' && spoof.phashes === 'processing'">
                  <h1 class="border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
                </td>
                <td class="text-left capitalize py-auto" v-else>
                  {{ methods.webflowRatingLabel(methods.webflowRating(spoof), spoof) }}
                </td>
                <td class="text-left py-auto" v-if="spoof.current_scan_status === 'not_scanned' && spoof.phashes === 'processing'">
                  <h1 class="border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
                </td>
                <td class="text-left capitalize py-auto" v-else>
                  {{ methods.domainRatingLabel(methods.domainRating(spoof), spoof) }}
                </td>
                <td class="text-left py-auto" v-if="spoof.current_scan_status === 'not_scanned' && spoof.phashes === 'processing'">
                  <h1 class="border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
                </td>
                <td class="text-left capitalize py-auto" v-else>
                  {{ methods.interfaceRatingLabel(methods.interfaceRating(spoof), spoof) }}
                </td>
                <td class="text-left py-auto" v-if="spoof.current_scan_status === 'not_scanned' && spoof.phashes === 'processing'">
                  <h1 class="border-l-white border-y-yellow-300 spinner-border border-r-yellow-100 "></h1>
                </td>
                <td class="font-bold text-left py-auto"
                v-else
                :style="{'color': methods.overallRatingLabel(methods.overallRating(spoof), spoof) === 'High' ? '#ED0707' : methods.overallRatingLabel(methods.overallRating(spoof), spoof) === 'medium' ? '#F7610D' : '#3AAC11'}">
                  {{ methods.overallRatingLabel(methods.overallRating(spoof), spoof) }}
                  <!-- {{spoof.overallRatingLabel}} -->
                </td>
                <td class="py-1">
                  <Link :href="'/spoof/view/' + spoof.id"
                        class="w-16 py-1 mx-auto transition-all duration-150 ease-linear tableButton focus:outline-none"
                        :style="{'background-color': methods.overallRatingLabel(methods.overallRating(spoof), spoof) === 'High' ? '#F89999' : methods.overallRatingLabel(methods.overallRating(spoof), spoof) === 'medium' ? '#FCBE9C' : '#AEDD9D'}"

                        >
                    View
                  </Link>
              </td>
            </tr>
                  </tbody>
              </table>
              <div class="bg-white" style="width: 100%; height: 330px" v-if="paginatedSpoofList.length === 0">
                <div class="mx-auto ">
                  <h1 class="pt-6 text-center max-w-60 h6"
                    :class="{
                      'text-red-500': domain.status === 'NOT_SCANNED',
                      'text-yellow-400': domain.status === 'PROCESSING'
                    }">
                      {{ domain.status }}
                  </h1>
                  <h1 class="pt-1 text-center max-w-60 h6">Thank you for your submission! Our team is diligently working on scanning your domain "{{domain.domain_name }}" to ensure a thorough analysis. <br>We appreciate your patience and understanding. If you have any questions or need further assistance, feel free to reach out.<br> Your satisfaction is our priority.</h1>
                  <div style="display: flex; justify-content: center; align-items: center; height: 50px;">
                    <h1 class="border-l-white border-y-yellow-300 spinner-border border-r-yellow-100"></h1>
                  </div>
                </div>
               </div>
              </div>

               <div class="flex justify-center mt-4" style="">

                  <button v-if="currentPage > 1" class="px-3 py-2 mr-2 cursor-pointer round gray paginationButtons"  @click="changePage(--currentPage)">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                  </button>
                  <div v-for="page in totalPages"
                    :key="page">
                    <button
                    v-if="page > currentPage - 4 && page < currentPage + 4"
                    @click="changePage(page)"
                    :class="{ 'primaryColor paginationButtons ': page === currentPage, 'paginationButtons gray text-gray-700': page !== currentPage }"
                    class="px-3 py-2 mr-2 rounded cursor-pointer"
                  >
                    {{ page }}
                  </button >
                  </div>
                  <button v-if="currentPage < totalPages" class="px-3 py-2 mr-2 cursor-pointer round gray paginationButtons"  @click="changePage(++currentPage)">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                  </button>
                </div>
            </div>
                       
          </div>
        </section>
    </AuthenticatedLayout>
</template>
<style>
.table-container {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.5s ease-out;
}

.table-container.open {
    max-height: 500px;
    /* Set a suitable max-height */
    transition: max-height 0.5s ease-in;
    overflow-x:auto ;
}

/* */

.height{
  margin-top: -100%;
}
.height2{
  margin-top: 0%;
}
.smoothDropDown{
  transition-duration: 1s;
  transition-delay: 20ms;
}
.bigDropdownBgActive{
  background: var(--dark-neutral-dark-neutral-5, #D9D9D9);
}
.bigDropdownBg{
  background: #FFEFB0;
}
.tableRow{
  height: 45px;
  flex-shrink: 0;
  border: 1px solid var(--dark-neutral-dark-neutral-4, #F0F0F0);
  box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.10) inset;
  background: #dc8f8f;
  border-top-width: 6px;
}
.tableButton{
display: flex;
height: 39px;
justify-content: center;
align-items: center;
border-radius: 30px;
background: var(--error-e-75, #F89999);
}
.smooth{
   transition-duration: 1s;
 transition-delay: 20ms;
}
.gray{
  background-color: #BFBFBF;
}
.round{
  border-radius:24px ;
  
}
.paginationButtons{
  color: var(--dark-neutral-dark-neutral-9, #454545);
font-family: Poppins;
font-size: 12px;
font-style: normal;
font-weight: 600;
line-height: 120%; /* 14.4px */
}
.primaryColor{
  background-color: #FFE88A;
}
.botton{
  display: flex;
width: 159px;
height: 34px;
justify-content: center;
align-items: center;
gap: 16px;
flex-shrink: 0;
border-radius: 30px;
background: var(--yellow-yellow-400, #FFD633);
}
.orgDomain{
  color: var(--dark-neutral-dark-neutral-8, #595959);

/* Semibold/Heading 5/Semibold */
font-family: Poppins;
font-size: 20px;
font-style: normal;
font-weight: 600;
line-height: 120%; /* 24px */
}
  .bigDropdown{
  height: 68px;
  border-radius: 6px;
background: var(--yellow-yellow-50, #FFFAE6);
}
</style>