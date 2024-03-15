<script setup >
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import {Link} from "@inertiajs/vue3"
import { defineComponent, onMounted } from 'vue';
import { ref, computed } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import VueApexCharts from 'vue3-apexcharts';





/*const chartOptions = ref({
  series: [{
    data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
  }],
  chart: {
    type: 'bar',
    height: 350
  },
  plotOptions: {
    bar: {
      borderRadius: 4,
      horizontal: true,
    }
  },
  dataLabels: {
    enabled: false
  },
  xaxis: {
    categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy', 'France', 'Japan',
      'United States', 'China', 'Germany'
    ],
  }
});

const chartRef = ref(null);

onMounted(() => {
  const chart = new ApexCharts(chartRef.value, chartOptions.value);
  chart.render();
});

// next
const barChartOptions = ref({
  series: [{
    name: 'Marine Sprite',
    data: [44, 55, 41, 37, 22, 43, 21]
  }, {
    name: 'Striking Calf',
    data: [53, 32, 33, 52, 13, 43, 32]
  }, {
    name: 'Tank Picture',
    data: [12, 17, 11, 9, 15, 11, 20]
  }, {
    name: 'Bucket Slope',
    data: [9, 7, 5, 8, 6, 9, 4]
  }, {
    name: 'Reborn Kid',
    data: [25, 12, 19, 32, 25, 24, 10]
  }],
  chart: {
    type: 'bar',
    height: 350,
    stacked: true,
  },
  plotOptions: {
    bar: {
      horizontal: true,
      dataLabels: {
        total: {
          enabled: true,
          offsetX: 0,
          style: {
            fontSize: '13px',
            fontWeight: 900
          }
        }
      }
    },
  },
  stroke: {
    width: 1,
    colors: ['#fff']
  },
  title: {
    text: 'Fiction Books Sales'
  },
  xaxis: {
    categories: [2008, 2009, 2010, 2011, 2012, 2013, 2014],
    labels: {
      formatter: function (val) {
        return val + "K"
      }
    }
  },
  yaxis: {
    title: {
      text: undefined
    },
  },
  tooltip: {
    y: {
      formatter: function (val) {
        return val + "K"
      }
    }
  },
  fill: {
    opacity: 1
  },
  legend: {
    position: 'top',
    horizontalAlign: 'left',
    offsetX: 40
  }
});

const radialChartOptions = ref({
  series: [70],
  chart: {
    height: 350,
    type: 'radialBar',
  },
  plotOptions: {
    radialBar: {
      hollow: {
        size: '70%',
      }
    },
  },
  labels: ['Cricket'],
});

const barChartRef = ref(null);
const radialChartRef = ref(null);

onMounted(() => {
  const barChart = new ApexCharts(barChartRef.value, barChartOptions.value);
  barChart.render();

  const radialChart = new ApexCharts(radialChartRef.value, radialChartOptions.value);
  radialChart.render();
});*/

// next


// Run the function every second (1000 milliseconds)
// var intervalId = setInterval(updatePageTitle, 1000);

// function updatePageTitle() 
// onMounted(() => {
//   // Load Google Charts library
//   if (typeof google === 'undefined' || typeof google.charts === 'undefined') {
//     // If Google Charts library is not loaded, load it dynamically
//     const script = document.createElement('script');
//     script.type = 'text/javascript';
//     script.src = 'https://www.gstatic.com/charts/loader.js';
//     script.onload = () => {
//       google.charts.load('current', {'packages': ['corechart']});
//       google.charts.setOnLoadCallback(drawChart);
//     };
//     document.head.appendChild(script);
//   } else {
//     // If Google Charts library is already loaded, proceed to draw the chart
//     drawChart();
//   }

//   function drawChart() {
//     var data = google.visualization.arrayToDataTable([
//       ['Day', 'new domains', 'High Risk'],
//       ['monday',  50,      10],
//       ['tuesday',  100,      20],
//       ['wednesday',  100,       20],
//       ['thursday',  80,      10],
//       ['friday', 40, 10],
//       ['saturday', 30, 5],
//       ['sunday', 70, 30]
//     ]);

//     var options = {
//       title: 'Domains In Days',
//       legend: { position: 'bottom' }
//     };

//     var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
//     chart.draw(data, options);
//   }
// });


defineComponent({
   name: 'App',
  components: {
    Link
  },
  
});

</script>


<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout style="height: 100vh;">
    <!--user-->
    <div class="row" style="height: 100vh;">
      <div class="mx-auto ">
        <h1 class="font-extrabold text-center flicker mt-9 h1"><span class="text-yellow-300">spoo</span>fix dashboard</h1>
        <h1 class="text-center h3 seesaw"> coming soon!</h1>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
<style>
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
    <!-- </div>
    </AuthenticatedLayout>
</template> -->
