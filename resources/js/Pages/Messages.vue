<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import { Link } from "@inertiajs/vue3"
import { defineComponent } from 'vue';
import { defineProps, onMounted } from 'vue';
import { ref, watch, computed } from 'vue';
import he from 'he';


const isModalVisible = ref(false);

const openModal = () => {
  isModalVisible.value = true;
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

const isModalVisible1 = ref(false);

const openModal1 = () => {
  isModalVisible1.value = true;
};

const closeModal1 = () => {
  isModalVisible1.value = false;
};


watch(isModalVisible1, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
   
      closeModal1();
    }, 300); 
  }
});

const ComposeModalVisible = ref(false);

const openComposeModal = () => {
  ComposeModalVisible.value = true;
};

const closeComposeModal = () => {
  ComposeModalVisible.value = false;
};


watch(ComposeModalVisible, (newValue) => {
  if (!newValue) {
    setTimeout(() => {
   
      closeComposeModal();
    }, 300); 
  }
});
defineComponent({
  components: {
    Link
  },

});


const props = defineProps({
  domains: {
    type: Object,
  },
  info: {
    type: Object,
  },
  messages: {
    type: Object,
  },
});

function date(inputDateString) {
  const options = { month: 'short', day: 'numeric', year: '2-digit' };
  const inputDate = new Date(inputDateString);
  const formattedDate = new Intl.DateTimeFormat('en-US', options).format(inputDate);

  // const yearSubstring = formattedDate.slice(-2);

  return `${formattedDate}`;
}
const messageRef = ref(props.messages[0]);
const messaging = (index, messageId, currentPage) => {
  //  console.log('hello');
    const index1 = (currentPage-1)*8 + index;
    if(props.messages[index1].id === messageId){
      messageRef.value = props.messages[index1] ;
    } 
}


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

// Define the current page and items per page
const currentPage = ref(1);
const itemsPerPage = 8;
// if(props.messages){
  // Calculate the total number of pages
  const totalPages = computed(() => Math.ceil(props.messages.length / itemsPerPage));

  // Slice the messages array based on the current page
  const paginatedMessages = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    return props.messages.slice(startIndex, endIndex);
  });

  // Function to change the current page
  const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
      currentPage.value = page;
    }
  };
// }


const form = useForm({
    from: '',
    date: '',
    subject: '',
});

const submit = () => {
    const formData = new FormData();
    formData.append('from', form.from);
    formData.append('date', form.date);
    formData.append('subject', form.subject);
    console.log(form.subject);
    // if(form.from || form.date || form.subject){
      form.post(route('Messages'));
    // }
}

const activeOne = ref('Inbox');
const menu =(now) => {
  activeOne.value = now;
}
</script>

<template>

  <Head title="Messages" />

  <AuthenticatedLayout class="overflow-x-hidden overflow-y-scroll fontFamily" style="height:100vh; background: #FFF;"> <!-- v-if=" props.userid === userId" -->
    <div class="flex justify-between mt-6 mr-8 border-b-4 border-black max-w-100 lg;ml-6 md:ml-6 ml-0">

      <div class="relative">
        <button @click="menu('Inbox')" class="absolute h-10 rounded-tr-full lg:h-12 md:h-12 w-36 lg:px-3 lg:w-40 tabsText md:w-40" :class="activeOne === 'Inbox' ? 'bg-dark' : 'bg-gray-300'" style="">Inbox</button>
        <button @click="menu('Sent')" class="h-10 pl-5 rounded-tr-full md:h-12 lg:h-12 lg:pr-4 w-44 lg:w-56 tabsText bg-gray-300 lg:pl-9 md:pl-9 md:w-56 rightTab" style="margin-left: 106px;" :class="activeOne === 'Sent' ? 'bg-dark' : 'bg-gray-300'">Sent</button>
      </div>

    </div>
    <div class="flex items-center justify-between my-2 mt-2 ml-3 cursor-pointer md:mx-3 lg:mx-4 h-14 hover:bg-gray-300 bigDropdownBg" style="border-radius: 6px; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25); " id="myDiv" @click="toggleTable(index, domain.id), scrollToElement(index)">
      <div class="ml-5 text-2xl font-semibold text-blueGray-700">
        <h3 class="orgDomain">
          <Link>Messages</Link>
        </h3>
      </div>
      <div class="mr-5 botton" @click="openModal">
        <i class="fa fa-magnifying-glass" aria-hidden="true"></i>
        <div>Find & Filter</div>
      </div>
    </div>
    <!-- Modal -->
    <div v-if="isModalVisible" class="absolute z-50 float-right p-2 -mt-3 bg-white rounded shadow-lg w-96 model h-96" style="buttom: calc(100% - 10px); right: 32px;">
      <!-- <i @click="closeModal" class="justify-center w-6 h-6 text-yellow-300 align-middle bg-black rounded-full fa fa-x"></i> -->
      <div class="relative w-full">
        <img @click="closeModal" class="absolute flex items-center justify-center float-right mt-2 mr-2 cursor-pointer w-9 h-9 right-1" :src="'/assets/systemImages/Exit.svg'" />
        <div class="h-12 -mt-2 -ml-2 -mr-2 bg-gray-300 rounded-t-lg sm:flex sm:items-start">
          <div class="mt-1 text-center sm:mt-0 sm:ml-4 sm:text-left">
            <h3 class="mt-2 text-lg font-medium text-gray-900 tabsText1" id="modal-headline">
              Find & Filter
            </h3>
          </div>
        </div>
      </div>
      <form action="" class="mt-2" @submit.prevent="submitForm">
        <label for="from" class="block mt-3 mb-2 text-sm font-bold text-gray-700">From:</label>
        <input v-model="form.from" type="text" name="from" class="w-full px-3 py-2 bg-yellow-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-300"><br>

        <label for="date" class="block mt-3 mb-2 text-sm font-bold text-gray-700">Date:</label>
        <input v-model="form.date" type="date" name="date" class="w-full px-3 py-2 bg-yellow-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-300"><br>

        <label for="subject" class="block mt-3 mb-2 text-sm font-bold text-gray-700">Subject:</label>
        <input v-model="form.subject" type="text" name="subject" class="w-full px-3 py-2 mb-3 bg-yellow-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-300">

        <div class="flex float-right mr-3">
          <button class="my-auto mr-2 bg-yellow-100 buttons buttonsText" @click="closeModal">Cancel</button>
          <button class="my-auto bg-yellow-300 buttons buttonsText" @click="submit" type="submit">Apply</button>
        </div>
      </form>

    </div>
    <div class="justify-between mx-4 mt-2 lg:flex lg:flex-row" style="">
      <div style="min-width: 57%; height: 65vh;">
        <button @click="openComposeModal" class="my-2 mb-2 bg-yellow-100 buttons buttonsText h-14"><i class="mx-1 fa fa-plus"></i>Compose Message </button>
        <!-- <table class="w-full mt-3 text-sm" style="max-height: 20px;"> -->
        <div class="max-h-full overflow-auto">
          <table class="w-full mt-3 overflow-x-auto text-sm">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 tablehead">
              <tr class="container justify-evenly">
                <th class="py-3 pl-2">
                  Date
                </th>
                <th class="py-3 text-left" v-if="activeOne === 'Inbox'">
                  From
                </th>
                <th class="py-3 text-left" v-if="activeOne !== 'Inbox'">
                  To
                </th>
                <th class="py-3 text-left">
                  Subject
                </th>
              </tr>
            </thead>
            <tbody v-if="props.messages">
              <!-- <tr class="transition-colors duration-300 bg-yellow-100 cursor-pointer tableRow" -->
              <tr class="duration-300 bg-yellow-100 cursor-pointer tableRow1" :class="{ 'bg-yellow-300': messageRef.id == message.id, 'bg-yellow-100 hover:bg-yellow-50': messageRef.id != message.id }" v-for="(message, index) in paginatedMessages" :key="index" @click="messaging(index, message.id, currentPage)">
                <td class="pl-3 overflow-auto py-auto" style="max-width: 90px;">
                  {{date(message.date)}}
                </td>
                <td class="text-left py-auto" v-if="activeOne === 'Inbox'">
                  <!-- Spoofix -->
                  <!-- {{ message.from_address.substring(0, message.from_address.indexOf('<')) }} -->
                  {{ message.subject && message.subject.length > 16 ? message.from_address.substring(0, message.from_address.indexOf('<')).substring(0, 16) + '...' : message.from_address.substring(0, message.from_address.indexOf('<')) }}
                </td>
                <td class="text-left py-auto" v-if="activeOne !== 'Inbox'">
                  <!-- {{info.name}} {{info.second_name}} -->
                  info.spoofix.com
                </td>
                <td class="overflow-hidden text-left py-auto" style="dispaly:none;">
                  {{ message.subject && message.subject.length > 40 ? message.subject.substring(0, 40) + '...' : message.subject }}
                </td>
              </tr>
            </tbody>
          </table>
          <div class="flex justify-center mt-4" style="">

            <button v-if="currentPage > 1" class="px-3 py-2 mr-2 cursor-pointer round gray paginationButtons" @click="changePage(--currentPage)">
              <i class="fa fa-chevron-left" aria-hidden="true"></i>
            </button>
            <div v-for="page in totalPages" :key="page">
              <button v-if="page > currentPage - 4 && page < currentPage + 4" @click="changePage(page)" :class="{ 'primaryColor paginationButtons ': page === currentPage, 'paginationButtons gray text-gray-700': page !== currentPage }" class="px-3 py-2 mr-2 rounded cursor-pointer">
                {{ page }}
              </button>
            </div>
            <button v-if="currentPage < totalPages" class="px-3 py-2 mr-2 cursor-pointer round gray paginationButtons" @click="changePage(++currentPage)">
              <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="w-full ml-2 box-style" style=" height: 65vh; min-width: 31%; margin-top: 60px; ">
        <div v-if="props.messages != undefined && props.info.role_id == 2 || props.info.role_id == 1 && messageRef && activeOne !== 'Inbox'" class="ml-2 align-middle rounded-t-lg" style="height: 45px;  ">
          <pre style="width: 100%; white-space: pre-wrap"><span class="font-bold">From:</span> info@spoofix.com </pre>
          <pre class="width: 100%; white-space: pre-wrap"><span class="font-bold">To:</span> {{info.email}} </pre>
          <pre style="width: 100%; white-space: pre-wrap"><span class="font-bold">Subject:</span> {{messageRef.subject}} </pre>
          <pre style="width: 100%; white-space: pre-wrap"><span class="font-bold">Reference:</span> MSG0000{{messageRef.id}}</pre>
          <pre style="width: 100%; white-space: pre-wrap"><span class="font-bold">Date:</span> {{date(messageRef.created_at)}}</pre>
          <br>
          Dear <span class="capitalize">{{info.name}} {{info.second_name}}</span>, <br><br>

          I hope this email finds you well. I am writing to inform you about [brief description of the purpose of the email]. We have noted your recent inquiry regarding '{{messageRef.subject}}', and I wanted to provide you with the following information: <br>
          <br>
          {{messageRef.messages}}<br>
          <br>
          If you have any further questions or concerns, please feel free to reach out to us. <br>

          Thank you for your attention and cooperation. <br>
          <br>
          Best regards, <br>
          <br>
          Spoofix Management, <br>
          <!-- [Your position], <br> -->
          Spoofix<br>
          info@spoofix.com <br>
        </div>
        <div v-if="props.messages != undefined && props.info.role_id == 1 && messageRef  && activeOne == 'Inbox'" class="ml-2 align-middle rounded-t-lg" style="height: 60vh; overflow-y:auto; width: 100%;">
          <!-- From: info@spoofix.com <br>
          To: {{info.email}} <br> -->
          <br>
          <pre style="width: 100%; white-space: pre-wrap;"><span class="font-bold">Subject:</span> {{messageRef.subject}} </pre>
          <pre style="width: 100%; white-space: pre-wrap;"><span class="font-bold">Reference:</span> {{messageRef.message_id}}</pre>
          <pre style="width: 100%; white-space: pre-wrap;"><span class="font-bold">From:</span> {{messageRef.from_address}}</pre>
          <pre style="width: 90%; white-space: pre-wrap;"><span class="font-bold">Date:</span> {{date(messageRef.date)}}</pre>
          <br>
          <pre style="width: 90%; white-space: pre-wrap;">{{ messageRef.body }}</pre>
          <!-- <div v-html="he.decode(messageRef.body)" class="w-full"></div> -->
        </div>
      </div>
    </div>
    <div class="relative justify-between pt-3 w-100"><!-- flex -->
      <div class="flex justify-between float-right w-64 pr-14">
      </div>
    </div>

    <!-- Compose Email Modal -->
    <!-- Compose Email Modal -->
    <div class="fixed inset-0 z-40 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-0 sm:items-center sm:justify-end" x-show="isComposeModalOpen" @keydown.escape="closeComposeModal" v-if="ComposeModalVisible">
      <div class="bottom-0 w-full max-w-lg bg-red-300 rounded-lg shadow-lg pointer-events-auto -mb-80">
        <div class="bg-white rounded-lg sm:p-6 sm:pb-1">
          <img @click="closeComposeModal" class="absolute items-center justify-center float-right mr-10 -mt-4 cursor-pointer w-9 h-9 right-1" :src="'/assets/systemImages/Exit.svg'" />
          <div class="h-12 -mt-6 -ml-6 -mr-6 bg-gray-300 rounded-t-lg sm:flex sm:items-start">
            <div class="mt-1 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="mt-2 text-lg font-medium text-gray-900 tabsText1" id="modal-headline">
                Compose Email
              </h3>
            </div>
          </div>
          <form class="mt-3 sm:mt-2" @submit.prevent="submitForm">
            <div class="mb-3">
              <label for="to" class="block mb-2 text-sm font-bold text-gray-700">To:</label>
              <input type="text" id="to" name="to" placeholder="Recipient's email" required class="w-full px-3 py-2 bg-yellow-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-300">
            </div>
            <div class="mb-4">
              <label for="subject" class="block mb-2 text-sm font-bold text-gray-700">Subject:</label>
              <input type="text" id="subject" name="subject" placeholder="Subject" class="w-full px-3 py-2 bg-yellow-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-300">
            </div>
            <div class="mb-4">
              <label for="message" class="block mb-2 text-sm font-bold text-gray-700">Message:</label>
              <textarea id="message" name="message" rows="3" placeholder="Write your message..." class="w-full px-3 py-2 bg-yellow-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 focus:border-indigo-300"></textarea>
            </div>
            <div class="mt-3 mb-3 sm:mt-6 sm:flex sm:flex-row-reverse">
              <div class="flex float-right mr-3">
                <button class="my-auto mr-2 bg-yellow-100 buttons buttonsText" @click="closeComposeModal">Cancel</button>
                <button class="my-auto bg-yellow-300 buttons buttonsText" @click="openModal1" type="submit">Send</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <transition name="modal-fade">
      <div v-if="isModalVisible1" class="backlight" @click="closeModal1">
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
                    <div class="my-auto">Do you want to send email<span class="text-yellow-500">{{form.evidence_urls}}</span>?</div>
                  </div>
                  <div class="relative w-100">
                    <button class="float-right px-4 bg-yellow-300 buttons buttonsText" @click="submit" type="submit">confirm</button>
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
.tabsText1{
color: var(--default-white, #595959);
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
  margin-right: 30px;
  margin-left: 26px;
  right: -300px;
}
.tableRow1{
  height: 45px;
  flex-shrink: 0;
  border: 1px solid var(--dark-neutral-dark-neutral-4, #F0F0F0);
  box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.10) inset;
}
.tableButton{
display: flex;
height: 39px;
justify-content: center;
align-items: center;
border-radius: 30px;
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
/* Add more custom styles as needed */
</style>
