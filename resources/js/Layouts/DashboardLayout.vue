<script setup>
import { getCurrentInstance, onMounted, onUnmounted, ref, watch } from 'vue'
import { Head, usePage } from '@inertiajs/inertia-vue3'
import Toggler from '@/Components/DashboardLayout/SidebarToggler.vue'
import TopbarDropdown from '@/Components/DashboardLayout/TopbarDropdown.vue'
import Sidebar from '@/Components/DashboardLayout/Sidebar.vue';


const { title } = defineProps({
  title: String,
})

const { $config } = usePage().props.value
const open = ref(Boolean(
  Number(localStorage.getItem('sidebar_open'))
))

watch(open, () => {
  localStorage.setItem('sidebar_open', Number(open.value))
})

const q = e => {
  if (e.key === 'q' && !(e.target instanceof HTMLInputElement)) {
    open.value = !open.value
  }
}

onMounted(() => document.addEventListener('keyup', q))
onUnmounted(() => document.removeEventListener('keyup', q))
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: all 300ms ease-in-out;
  opacity: 1;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.max-h-sidebar {
  max-height: calc(100vh - 3.5rem);
}

@media screen and (max-width: 768px) {
  .w-14 {
    width: 150px !important;
  }
}
</style>



<script>
export default {
  data() {
    return {
      isScrolled: false,
    };
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll);
  },
  beforeDestroy() {
    window.removeEventListener('scroll', this.handleScroll);
  },
  methods: {
    handleScroll() {
      this.isScrolled = window.scrollY > 0;
    },
  },
};
</script>

<template>
  <div class="font-sans w-full h-full min-h-screen" style="background: #fcfcfc;">
<!-- Navbar Layout -->
  <div class="z-20 sticky top-0 left-0 flex-none flex justify-between w-full h-14 transition-all ease-in-out duration-150 bg-white shadow">
      <div class="mx-auto p-4 flex justify-between items-center relative  w-full">
        <div class="transition-all duration-300" style="margin-top: 30px;margin-left: 22px;">
          <Toggler @toggle="open = !open" class="xl:hidden md:hidden" />
        </div>

      <div class="flex-row flex items-center justify-center text-[#434343]">
        <!-- <img class="w-[50px]" src="../../../../ticketing/Logo-black.png" alt=""> -->
        <!-- <img class="w-[50px]" src="../../../public/assets/1.png" alt="">
        <h1 class="text-2xl font-bold">
          TICKETING-KPJR
        </h1> -->
      </div>
        <!-- User right profile -->
        <div><TopbarDropdown /></div>
      </div>
    </div>

    <div
      class="transition-all duration-300"
      :class="{
        'pl-14 md:pl-64': open,
        'pl-14': !open,
      }"
    >
      <main class="p-6 ml-[-3.5rem] sm:ml-[-3.5rem] lg:ml-[0rem] md:ml-[0rem] ">
        <slot />
      </main>
    </div>
<!-- Side Bar Layout -->
    
<div class="sidebar bg-black fixed top-0 left-0 transition-all duration-300 h-full flex flex-col z-50"
      :class="{
        'w-64': open,
        'w-14': !open,
        '-left-full md:left-0': !open,
      }"
    >
    <!-- Toogle Di Sidebar-->
    <div class="transition-all duration-300 flex justify-center pr-5 sm:justify-end  lg:justify-center ml-[13.75rem] sm:ml-[16.75rem] md:ml-[11.438rem] lg:ml-[11.438rem] xl:ml-[11.438rem]" 
         :class="{
           'w-2/5 translate-x-10': open,
           'w-full -translate-x-20 ': !open,
         }"
         style="padding-bottom: 0.25rem;margin-bottom: 0px;margin-top: 2.188rem; "
    >
      <Toggler @toggle="open = !open" :class="open ? 'ml-0' : 'ml-[-6.125rem]'" />
    </div>
      <!--End Toogle -->

      <Sidebar :open="open" />
    </div>

  </div>``

  <div class="hidden bg-gray-300 font-sans w-full h-full">

    <Head :title="title" />

    <div ref="sidebar"
      class="fixed sm:static top-0 left-0 flex-none flex flex-col h-full min-h-screen transition-all ease-in-out duration-300 z-20 "
      :class="`${themes().get('sidebar', ' hover:bg-gray-800 hover:text-gray-100 transition-all ease-in-out duration-100').replace(/hover:(bg|text)-(.*?)-(\d+)/)} ${open ? 'w-full sm:w-60' : 'w-0'}`">
      <div v-if="open" class="flex-none flex items-center justify-between w-full h-14 px-2"
        :class="themes().get('topbar', 'bg-cyan-500 text-gray-700 hover:bg-cyan-600 hover:text-gray-800 transition-all ease-in-out duration-150').replace(/hover:(bg|text)-(.*?)-(\d+)/, '')">
        <Toggler @toggle="open = !open" class="sm:hidden" />
        <div class="flex-none sm:hidden">
          <TopbarDropdown />
        </div>
      </div>

      <Sidebar v-if="open" />
    </div>

    <div class="relative w-full h-full min-h-screen max-h-screen overflow-auto">
      <div class="sticky top-0 left-0 z-20 flex-none flex justify-between w-full h-14 px-2"
        :class="themes().get('topbar', 'bg-cyan-500 text-gray-700 hover:bg-cyan-600 hover:text-gray-800 transition-all ease-in-out duration-150').replace(/hover:(bg|text)-(.*?)-(\d+)/, '')">
        <Toggler @toggle="open = !open" />

        <TopbarDropdown />
      </div>

      <main class="p-6">
        <slot />
      </main>
    </div>
  </div>
</template>