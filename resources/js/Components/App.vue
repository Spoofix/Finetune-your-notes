<!-- <template>
    <div id="app" style="width:70%;">
      <h1 style="padding-left:80px;">Vue Js Google bar Charts Example - nicesnippets.com</h1>
      <GChart
        type="BarChart"
        :data="chartData"
        :options="chartOptions"
      />    
    </div>
  </template>
  
  <script>
  import { GChart } from "vue-google-charts";
  export default {
    name: "App",
    components: {
      GChart
    },
    data() {
      return {
        // Array will be automatically processed with visualization.arrayToDataTable function
        chartData: [
          ["Language", "Blog"],
          ["PHP", 180],
          ["Laravel", 200],
          ["Html", 100],
          ["vue.js", 150],
        ],
        chartOptions: {
          chart: {
            title: "Company Performance",
            subtitle: "blog in total: languages"
          }
        }
      };
    }
  };
  </script> -->