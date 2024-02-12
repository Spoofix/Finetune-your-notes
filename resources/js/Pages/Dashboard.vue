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

const props = defineProps({
    organization: {
        type: Object,
    },
    domainList: {
        type:Object,
    },
    users: {
        type:Object,
    },
    user:{
      type:Object,
    },
    isAdminSwitched: {
        type:Object,
    }
});

const form = useForm({
    name: null,
});

function submit() {
    if (form.name == null) {
        Toast.fire({
            icon: 'error',
            title: 'All fields are required'
        })
        return;
    }

    form.post(route('organization'), {
        onFinish: (response) => {
            Toast.fire({
                icon: 'success',
                title: 'Organization Created Successfully'
            })

            console.log(response);
            //  search()

        },
        onSuccess: () => { },
    });

}

function cancel(id) {
    Swal.fire({
        title: 'Do you want to disable this organization?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: "No"
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            const cancel_form = useForm({
                org_id: id,
            });
            cancel_form.post(route('organization.cancel'), {
                onFinish: (response) => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Domain Updated Successfully'
                    })
                },
                onSuccess: () => { },
            });
        } else if (result.isDenied) {
            // Swal.fire('Changes are not saved', '', 'info')
        }
    })
}

function activate(id) {

    Swal.fire({
        title: 'Do you want to disable this organization?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: "No"
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            const activate_form = useForm({
                org_id: id,
            });
            activate_form.post(route('organization.activate'), {
                onFinish: (response) => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Organization Updated Successfully'
                    })
                },
                onSuccess: () => { },
            });
        } else if (result.isDenied) {
            // Swal.fire('Changes are not saved', '', 'info')
        }
    })

} 

const filteredOrgUsersCurrentValue = ref([]); 


function filteredSpoofList(domainid) {
  const spoofList = props.users;
  const filteredOrguserValue = [];
  for (const spoof of spoofList) {
    if (spoof.org_id === domainid) {
      filteredOrguserValue.push(spoof);
    }
  }
  filteredOrgUsersCurrentValue.value = filteredOrguserValue;
  return filteredOrguserValue;
}
if(props.organization){
  filteredSpoofList(props.organization[0].id);
}

const showTable = ref(true);
const indexStore = ref(0);

function delay(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

const toggleTable = async (index, domainid) => {
    filteredSpoofList(domainid);
    if (index === indexStore.value) { 
      showTable.value = !showTable.value;
    } else {
      showTable.value = true;
    }
    indexStore.value = index;
};
</script>


<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout style="height: 100vh;">

        <div class="w-full overflow-scroll" style=" overflow: scroll; height: 100vh;" v-if="$page.props.auth.user.role_id == 1" >
<!--admin content-->
        <div class="pl-1 mt-6">
                    <!-- testing place -->
          <div class="mr-4 lg:mr-7" v-for="(domain, index) in organization" :key="index">
            <div
              class="flex items-center justify-between w-full my-2 cursor-pointer md:mx-3 lg:mx-4 h-14 hover:bg-gray-300"
                style="border-radius: 6px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);"
                id="myDiv"
                :class="showTable && index == indexStore ? 'bigDropdownBgActive' : 'bigDropdownBg'"     
              @click="toggleTable(index, domain.id), scrollToElement(index)"
            >
              <div class="ml-5 text-2xl font-semibold text-blueGray-700">
                  <h3 class="orgDomain">
                   <!-- <Link :href="'rescan-domain/' + domain.id"></Link>  -->
                   {{ domain.name }}
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
            <!-- v-if="showTable && index === indexStore" -->
             <div
                v-if="showTable && index === indexStore"
                class="items-center overflow-auto bg-transparent border-collapse lg:ml-4 md:ml-4 md:-mr-3 smooth "
                :class="{ 'smoothDropDown': showTable }"
                id="smoothDropDown"
                  > 
                    <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                      <tr>
                        <th class="text-center"></th>
                        <th>Name</th>
                        <th class="d-none d-sm-table-cell">Email</th>
                        <th class="d-none d-sm-table-cell" style="width: 15%;">Org User Role</th>
                        <th class="text-center" style="width: 15%;">Status</th>
                        <th class="text-center" style="width: 10%;">Action</th>
                        <th class="text-center" style="width: 15%;">login as:</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(user, index) in filteredOrgUsersCurrentValue" v-bind:key="user">
                        <td class="text-center">{{ ++index }}</td>
                        <td class="capitalize fw-semibold">{{ user.name }}  {{ user.second_name}}</td>
                        <td class="d-none d-sm-table-cell">{{ user.email }}</td>
                        <td class="d-none d-sm-table-cell" v-if="user.org_role_id === 1">
                         Admin
                        </td>
                        <td class="d-none d-sm-table-cell" v-else>
                         User
                        </td>
                        <th class="text-center" style="width: 15%;">
                            <span class="badge bg-success" v-if="user.status == 'ACTIVE'">{{ user.status }}</span>
                            <span class="badge bg-warning" v-if="user.status == 'PENDING'">{{ user.status }}</span>
                            <span class="badge bg-danger" v-if="user.status == 'LOCKED'">{{ user.status }}</span>
                        </th>
                        <td class="text-center">
                          <Link @click="approve(user.id)"  class="mr-1 btn btn-sm btn-success" data-bs-toggle="tooltip" title="Approve User"  v-if="user.status != 'ACTIVE'">
                            <i class="fa fa-check"></i>
                          </Link>
                          <Link  @click="lock(user.id)"  class="mr-1 btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Lock User"   v-if="user.status == 'ACTIVE'">
                            <i class="fa fa-times"></i>
                          </Link>
                          <!-- <Link :href="route('user.view', user.id)"  class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="View User">
                            <i class="fa fa-eye"></i>
                          </Link> -->
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <Link 
                                :href="'dashboard/' + user.id" 
                                as="button"
                                class="py-1 mx-auto overflow-hidden transition-all duration-150 ease-linear w-52 tableButton focus:outline-none"
                                style="background-color: #f9ff90;"
                                >
                                 {{ domain.name }}'s {{ user.name }}
                           </Link>
                        </td>
                      </tr>

                    </tbody>
                  </table>

             </div>
             </div>
        </div>
        </div> 
<!--user-->
    <div class="row" v-if="$page.props.auth.user.role_id == 2 || props.isAdminSwitched" style="height: 100vh;">
      <div class="mx-auto ">
          <h1 class="font-extrabold text-center flicker mt-9 h1"><span class="text-yellow-300">spoo</span>fix dashboard</h1>
          <h1 class="text-center h3 seesaw"> coming soon!</h1>
      </div>
      <!-- test -->
      <!-- <div class="grid grid-cols-1 gap-4 mt-6 ml-4 mr-7 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-8 min-h-10"> -->
          <!-- Card 1 -->
          <!-- <div class="max-h-full p-4 bg-white rounded-md shadow-md"> -->
              <!-- Card content goes here -->
              <!-- Card 1 -->
          <!-- </div> -->
          <!-- Card 2 -->
          <!-- <div class="p-4 bg-white rounded-md shadow-md"> -->
              <!-- Card content goes here -->
              <!-- Card 2 -->
          <!-- </div> -->
          <!-- Card 3 -->
          <!-- <div class="p-4 bg-white rounded-md shadow-md"> -->
              <!-- Card content goes here -->
              <!-- Card 3 -->
          <!-- </div> -->
          <!-- Card 4 -->
          <!-- <div class="p-4 bg-white rounded-md shadow-md"> -->
              <!-- Card content goes here -->
              <!-- Card 4 -->
          <!-- </div> -->
          <!-- Card 5 -->
          <!-- <div class="p-4 bg-white rounded-md shadow-md"> -->
              <!-- Card content goes here -->
              <!-- Card 5 -->
          <!-- </div> -->
          <!-- Card 6 -->
          <!-- <div class="p-4 bg-white rounded-md shadow-md"> -->
              <!-- Card content goes here -->
              <!-- Card 6 -->
          <!-- </div> -->
          <!-- Card 7 -->
          <!-- <div class="p-4 bg-white rounded-md shadow-md"> -->
              <!-- Card content goes here -->
              <!-- Card 7 -->
          <!-- </div> -->
          <!-- Card 8 -->
          <!-- <div class="p-4 bg-white rounded-md shadow-md"> -->
              <!-- Card content goes here -->
              <!-- Card 8 -->
          <!-- </div>
      </div>
      <div class="flex flex-wrap items-center justify-center h-full">
          <div class="w-full p-4 md:w-1/2 lg:w-1/3">
              <div class="bg-white rounded-md shadow-md">
                <div ref="chartRef" class="">
                  <vue-apex-charts v-if="chartOptions" :options="chartOptions" ref="chartRef" />
                </div>
              </div>
          </div>
          <div class="w-full p-4 md:w-1/2 lg:w-1/3">
              <div class="bg-white rounded-md shadow-md">
                <div ref="barChartRef" >>
                  <vue-apex-charts v-if="barChartOptions" :options="barChartOptions" ref="barChartRef" />
                </div>
              </div>
          </div>
          <div class="w-full p-4 md:w-1/2 lg:w-1/3">
              <div class="bg-white rounded-md shadow-md">
                <div ref="radialChartRef" class="">
                  <vue-apex-charts v-if="radialChartOptions" :options="radialChartOptions" ref="radialChartRef" />
                </div>
              </div>
          </div>
      </div> -->
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
