<script setup>
import { getCurrentInstance, onMounted, onUpdated, ref } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/inertia-vue3'
import Builder from './Sidebar/Builder.vue'
import Icon from '@/Components/Icon.vue'
import Swal from 'sweetalert2'
import { Inertia } from '@inertiajs/inertia'

const { open } = defineProps(['open'])
const menus = ref(usePage().props.value.$menus || [])
const { user } = usePage().props.value

const f = async () => {
  try {
    const response = await axios.get(route('user.menu', user.id))

    menus.value = response.data
  } catch (e) {
    const response = await Swal.fire({
      title: 'Do you want to try again?',
      text: `${e}`,
      icon: 'error',
      showCancelButton: true,
      showCloseButton: true,
    })

    response.isConfirmed && fetch()
  }
}
</script>

<style scoped>
.op-enter-active, .op-leave-active {
  transition: all 50ms ease-in-out;
  opacity: 1;
}

.op-enter-from, .op-leave-to {
  opacity: 0;
}
</style>

<template>
  <div class="flex flex-col w-full h-full bg-inherit overflow-y-auto max-h-screen scrollbar" style="margin-top: -5.25rem;">
    <div class="">
      <h1 v-show="open" class=" text-lg font-bold w-full text-white mb-5 pt-5 transition-all ease-in-out duration-150 flex items-center">
        <img class="w-[55px]" src="../../../../public/assets/1.png" alt="">Ticketing
      </h1>
      <div v-show="!open" class="flex items-center w-full text-white mb-5 pt-5 transition-all ease-in-out duration-150">
        <img class="w-[2.5rem]" src="../../../../public/assets/1.png" style="margin-left: 0.5rem;"></div>
    </div>
    <transition name="op" mode="in-out">
      <Builder v-if="menus.length" :menus="menus" :open="open" />
    </transition>
  </div>

  <div class="hidden pl-8"></div>
</template>