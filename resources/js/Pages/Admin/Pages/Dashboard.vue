<script setup >
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
// import Swal from 'sweetalert2'
import {Link} from "@inertiajs/vue3"
import { defineComponent, onMounted } from 'vue';
import { ref, computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';



// next
const lineChartOptions = ref({
  series: [
    {
      name: 'Takedowns',
      data: [20, 35, 40, 25, 15, 60, 70, 55, 100]
    },
    {
      name: 'In Progress',
      data: [30, 40, 35,60, 49, 60, 70, 91, 15]
    },
    {
      name: 'Reported',
      data: [10, 70, 25, 130, 95, 80, 45, 50, 155]
    }
  ],
  chart: {
    height: 350,
    type: 'area',
    toolbar: {
      show: false
    },
    events: {
      mounted: function(chartContext, config) {
        const w = chartContext.w;

        // Draw mesh-like background
        const { graphics, core } = w.globals;
        const { minY, maxY } = core.getAxisLimitsY();

        for (let i = minY; i <= maxY; i += 10) {
          const y = core.dataPointsToCoordsY(i).y;
          graphics.addRect({
            x: core.seriesAreaWidth,
            y,
            width: core.canvasWidth - core.seriesAreaWidth - core.scales.offsetX,
            height: 1,
            fill: '#CCCCCC'
          });
        }
      }
    }
  },
  
 colors: ['#4B5563', '#E5E7EB', '#FDE68A'],
  xaxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
    axisBorder: {
      show: true,
      color: '#CCCCCC'
    },
    axisTicks: {
      show: true,
      color: '#CCCCCC'
    },
    grid: {
      show: true,
      borderColor: '#CCCCCC',
      strokeDashArray: 3,
      position: 'back',
      xaxis: {
        lines: {
          show: true
        }
      },
      yaxis: {
        lines: {
          show: false
        }
      },
    }
  },
  yaxis: {
    title: {
      text: 'Values'
    },
    axisBorder: {
      show: true,
      color: '#CCCCCC'
    },
    axisTicks: {
      show: true,
      color: '#CCCCCC'
    },
    labels: {
      style: {
        colors: '#333333'
      }
    },
    tooltip: {
      enabled: true
    }
  },
  dataLabels: {
    enabled: false // Disable plot values
  },
  legend: {
    position: 'bottom'
  },
  tooltip: {
    enabled: true // Show values on hover
  }
});


const lineChartRef = ref(null);

onMounted(() => {
  const lineChart = new ApexCharts(lineChartRef.value, lineChartOptions.value);
  lineChart.render();
});

//donat
const radialChartOptions = ref({
  series: [7, 2, 8],
  chart: {
    height: 350,
    type: 'donut',
    toolbar: {
      show: false, // Hide toolbar
    },
  },
  plotOptions: {
    radialBar: {
      hollow: {
        size: '60%',
      },
      track: {
        background: '#f2f2f2', // Change track color
      },
      dataLabels: {
        name: {
          fontSize: '33px', // Increase font size
        },
        value: {
          fontSize: '22px', // Increase font size
        },
        total: {
          show: true,
          label: 'Total',
          formatter: function (w) {
            // Custom formatter for total value
            return w.globals.seriesTotals.reduce((a, b) => {
              return a + b;
            }, 0);
          }
        }
      }
    },
  },
  colors: ['#FDE68A', '#4A5568', '#A3A3A3'], // Custom colors
  labels: ['Reported', 'Takedowns', 'In Progress'], // Custom labels
});

const radialChartRef = ref(null);

onMounted(() => {
  const radialChart = new ApexCharts(radialChartRef.value, radialChartOptions.value);
  radialChart.render();
});


defineComponent({
   name: 'App',
  components: {
    Link
  },
  
});

const bladeViewUrl = ref("");

// Set the URL of your Blade view when the component is mounted
onMounted(() => {
  // bladeViewUrl.value = "http://127.0.0.1:5500/resources/views/map.html"; /emails/reset_password// Replace with the actual relative path
 const spoofID = 1;
 bladeViewUrl.value = `http://127.0.0.1:8000/maps/${spoofID}`;
// bladeViewUrl.value = `https://uat.spoofix.com/maps/${spoofID}`;

});
</script>


<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout style="min-height: 100vh;" class="overflow-y-scroll">
    <!--user-->
    <!-- <div class="row" style="height: 100vh;">
      <div class="mx-auto ">
        <h1 class="font-extrabold text-center flicker mt-9 h1"><span class="text-yellow-300">spoo</span>fix dashboard</h1>
        <h1 class="text-center h3 seesaw"> coming soon!</h1>
      </div>
    </div> -->
    <div class="mx-2 mt-6 shadow-lg row" style="min-height: 70vh;">
      <div class="grid gap-3 mb-2 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1">
        <!-- card1 -->
        <div class="bg-white shadow-xl rounded-xl w-100">
          <div class="flex justify-between h-10">
            <div class="flex">
              <div class="w-6 h-6 mt-2 font-bold text-center border rounded-full">...</div>
              <h5 class="mt-2 ml-5 h4 "> Your Domains</h5>
            </div>
            <div class="mt-2 mr-4 text-lg text-center bg-yellow-200 rounded-full w-7 h-7">i</div>
          </div>
          <hr>
          <h1 class="ml-4 font-bolder h2">Facebook.com</h1>
          <div class="flex justify-between mx-3 -mt-3 bg-yellow-100 h-7">
            <div class="w-1/3 my-auto font-bold">Registrar:</div>
            <div class="w-2/3 my-auto">publicDomainrgistrar.com</div>
          </div>
          <div class="flex justify-between mx-3 mt-3 bg-yellow-100 h-7">
            <div class="w-1/3 my-auto font-bold">Registered date:</div>
            <div class="w-2/3 my-auto">1st January, 2000</div>
          </div>
          <div class="flex justify-between">
            <div class="flex gap-1 mt-2 ml-4">
              <div class="w-10 h-2 bg-yellow-300 rounded-full"></div>
              <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
              <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
            </div>
            <u class="mt-2 mr-4"><a href="#">View More</a></u>
          </div>
        </div>
        <!-- card2 -->
        <div class="bg-white shadow-xl rounded-xl w-100">
          <div class="flex justify-between h-10">
            <div class="flex">
              <div class="w-6 h-6 mt-2 font-bold text-center border rounded-small"><i class="fa fa-search"></i></div>
              <h5 class="mt-2 ml-5 h4 "> Scans in progress</h5>
            </div>
            <div class="mt-2 mr-4 text-lg text-center bg-yellow-200 rounded-full w-7 h-7">i</div>
          </div>
          <hr>
          <div class="flex justify-between mx-3 mt-3 bg-gray-100 h-9">
            <div class="w-1/2 my-auto ml-1 font-bold">a-facebook.com</div>
            <div class="w-1/2 my-auto mr-4 bg-gray-300 rounded-full">
              <div class="w-10 h-4 bg-yellow-300 rounded-full"></div>
            </div>
          </div>
          <div class="flex justify-between mx-3 mt-3 bg-gray-100 h-9">
            <div class="w-1/2 my-auto ml-1 font-bold">action-facebook.com</div>
            <div class="w-1/2 my-auto mr-4 bg-gray-300 rounded-full">
              <div class="w-10 h-4 bg-yellow-300 rounded-full"></div>
            </div>
          </div>
          <div class="flex justify-between">
            <div class="flex mt-2 ml-4 text-lg font-bold text-gray-300"> 5/14</div>
            <u class="mt-2 mr-4"><a href="#">View More</a></u>
          </div>
        </div>
        <!-- card3 -->
        <div class="bg-white shadow-xl rounded-xl w-100">
          <div class="flex justify-between h-10">
            <div class="flex">
              <div class="w-6 h-6 mt-2 font-bold text-center border rounded-small"><i class="fa fa-hand"></i></div>
              <h5 class="mt-2 ml-5 h4 "> Completed Scans</h5>
            </div>
            <div class="mt-2 mr-4 text-lg text-center bg-yellow-200 rounded-full w-7 h-7">i</div>
          </div>
          <hr>
          <div class="flex justify-between mx-3 mt-3 h-9">
            <div class="w-1/2 my-auto ml-1 font-bold h5">Domain</div>
            <div class="w-1/2 m-auto ml-1 font-bold h5">Risk Status</div>
          </div>
          <div class="flex justify-between mx-3 mt-1 bg-gray-100 h-9">
            <div class="w-1/2 my-auto ml-2 font-bold">facebook.com</div>
            <div class="w-1/2 my-auto mr-4">
              <div class="h-8 mt-1 ml-4 text-center bg-green-500 rounded-full w-28 h6">
                <p class="">Safe</p>
              </div>
            </div>
          </div>
          <div class="flex justify-between mx-3 mt-3 bg-gray-100 h-9">
            <div class="w-1/2 my-auto ml-2 font-bold">fessbook.com</div>
            <div class="w-1/2 my-auto mr-4">
              <div class="h-8 mt-1 ml-4 text-center bg-red-400 rounded-full w-28 h6">
                <p class="">High</p>
              </div>
            </div>
          </div>
          <div class="flex justify-between mx-3 mt-3 bg-gray-100 h-9">
            <div class="w-1/2 my-auto ml-2 font-bold">db.facebook.com</div>
            <div class="w-1/2 my-auto mr-4">
              <div class="h-8 mt-1 ml-4 text-center bg-yellow-300 rounded-full w-28 h6">
                <p class="">Medium</p>
              </div>
            </div>
          </div>
          <div class="flex justify-between mx-3 mt-3 bg-gray-100 h-9">
            <div class="w-1/2 my-auto ml-2 font-bold">fecebook.com</div>
            <div class="w-1/2 my-auto mr-4">
              <div class="h-8 mt-1 ml-4 text-center bg-yellow-100 rounded-full w-28 h6">
                <p class="">Low</p>
              </div>
            </div>
          </div>
          <div class="flex justify-between">
            <div class="flex mt-2 ml-4 text-lg font-bold text-gray-300"> 5/32</div>
            <u class="mt-2 mr-4"><a href="#">View More</a></u>
          </div>
        </div>
        <!-- card4 -->
        <div class="bg-white shadow-xl rounded-xl w-100">
          <div class="flex justify-between h-10">
            <div class="flex">
              <div class="w-6 h-6 mt-2 font-bold text-center border rounded-full"><i class="fa fa-house"></i></div>
              <h5 class="mt-2 ml-5 h4 "> Take downs</h5>
            </div>
            <div class="mt-2 mr-4 text-lg text-center bg-yellow-200 rounded-full w-7 h-7">i</div>
          </div>
          <hr>
          <div class="flex justify-between mx-3 mt-3 bg-yellow-100 h-7">
            <div class="w-1/2 my-auto ml-2 font-bold">db.facebook.com</div>
            <div class="w-1/2 my-auto">3rd June, 2024</div>
          </div>
          <div class="flex justify-between mx-3 mt-3 bg-yellow-100 h-7">
            <div class="w-1/2 my-auto ml-2 font-bold">fassbook.com</div>
            <div class="w-1/2 my-auto">1st January, 2020</div>
          </div>
          <div class="flex justify-between">
            <div class="flex gap-1 mt-2 ml-4">
              <div class="w-10 h-2 bg-yellow-300 rounded-full"></div>
              <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
              <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
            </div>
            <u class="mt-2 mr-4"><a href="#">View More</a></u>
          </div>
        </div>
        <!-- card5 -->
        <div class="bg-white shadow-xl rounded-xl w-100">
          <div class="flex justify-between h-10">
            <div class="flex">
              <div class="w-6 h-6 mt-2 font-bold text-center border rounded-full border-3"><i class=""></i></div>
              <h5 class="mt-2 ml-5 h4 ">Reported vs Take downs</h5>
            </div>
          </div>
          <hr>
          <div ref="radialChartRef" class="chart-container">
            <vue-apex-charts v-if="radialChartOptions" :options="radialChartOptions" ref="radialChartRef" />
          </div>
        </div>
        <!-- card6 -->
        <div class="bg-white shadow-xl rounded-xl w-100">
          <div class="flex justify-between h-10">
            <div class="flex">
              <div class="w-6 h-6 mt-2 font-bold text-center"><i class="fa fa-graph"></i></div>
              <h5 class="mt-2 ml-5 h4 ">Risky domains analysis</h5>
            </div>
            <div class="mt-2 mr-4"><u class="mt-3 mr-4"><a href="#">View More</a></u></div>
          </div>
          <hr>
          <div>
            <div ref="lineChartRef" class="chart-container">
              <vue-apex-charts v-if="lineChartOptions" :options="lineChartOptions" ref="lineChartRef" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- map -->
    <!-- card7 -->
    <div class="mx-4 mt-1 mb-5 mr-3 bg-white shadow-xl rounded-xl" style="min-height: 30vh;">
      <div class="flex justify-between h-10">
        <div class="flex">
          <div class="w-6 h-6 mt-2 font-bold text-center border border-black rounded-full border-3"><i class="fa fa-graph"></i></div>
          <h5 class="mt-2 ml-5 h4 ">Risky domains by location</h5>
        </div>
      </div>
      <hr>
      <div class="flex gap-2 h-100">
        <!-- map -->
        <div class="h-100 lg:w-2/3 md:w-1/2 sm:full">
          <div class="ml-1 overflow-y-auto " style=" max-height: 120%; width: 99%;">
            <div class="w-full overflow-auto">
              <iframe :src="bladeViewUrl" width="100%" height="300px"></iframe>
            </div>
          </div>
        </div>
        <!-- next to map -->
        <div class="bg-blue-100 h-100 lg:w-1/3 md:w-1/2 sm:w-full">
          <div class="bg-white w-100">
            <div class="flex justify-between mx-3 bg-gray-100 h-9">
              <div class="w-1/2 my-auto ml-1 font-bold">a-facebook.com</div>
              <div class="w-1/2 my-auto ml-1 text-gray-500">5th, Ave Canada</div>
            </div>
            <div class="flex justify-between mx-3 mt-3 bg-gray-100 h-9">
              <div class="w-1/2 my-auto ml-1 font-bold">action-facebook.com</div>
              <div class="w-1/2 my-auto ml-1 text-gray-500">3567 Talmit Egypt</div>
            </div>
            <div class="flex justify-between">
              <div class="flex mt-2 ml-4 text-lg font-bold text-gray-300"> 5/14</div>
              <u class="mt-2 mr-4"><a href="#">View More</a></u>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
<style>
.chart-container {
  position: relative;
  width: 100%; /* Ensure the chart container fills its parent's width */
  height: 350px; /* Set the height of the chart container */
}

.chart-container .apexcharts-canvas {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}


@keyframes flickerAnimation {
    0%, 18%, 22%, 25%, 53%, 57%, 100% {
        opacity: 1;
    }

    20%, 24%, 55% {
        opacity: 0.2;
        color: #facd5b;
    }
}

.flicker {
    animation: flickerAnimation 7s infinite;
}
@keyframes seesawAnimation {
    0%, 100% {
        transform: rotate(-15deg);
    }

    50% {
        transform: rotate(15deg);
    }
}

.seesaw {
    transform-origin: top center;
    animation: seesawAnimation 3s infinite;
}


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
  

