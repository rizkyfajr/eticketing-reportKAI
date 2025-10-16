<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import axios from 'axios'
import Swal from 'sweetalert2'
import { getCurrentInstance, nextTick, onMounted, onUnmounted, onUpdated, ref } from 'vue'
import { cloneDeep } from 'lodash'

const self = getCurrentInstance()
const { url, sticky, request } = defineProps({
  url: String,
  sticky: {
    type: Boolean,
    default: true,
  },
  request: Object,
})

const paginator = ref({
  data: [],
})
const processing = ref(false)
const last = ref(null)
const config = useForm({
  page: 1,
  search: '',
  per_page: 5,
  order: {
    key: '',
    dir: 'asc',
  },
  sticky,
  request,
})

const goTo = link => {
  if (!link.url) {
    return
  }
  
  config.page = Number(link.url.match(/page=([\d]+)/)[1])
  paginator.value.current_page !== config.page && refresh()
}

const changePage = page => {
  console.log(paginator)
  config.page = page
  paginator.value.current_page !== config.page && refresh()
}

let perPage = ref(5)
let showOptions = ref(false)

const setPerPage = per_page => {
  
  config.per_page = per_page
  config.page = 1
  perPage.value = per_page
  showOptions.value = false
  paginator.value.per_page !== config.per_page && refresh()
  
  
}


const refresh = async u => {
  try {
    processing.value = true
    const prev = last.value = u || url
    const { data } = await axios.post(prev || last.value, config.data())
    paginator.value = data
    self.proxy.$forceUpdate()
  } catch (e) {
    const { isConfirmed } = await Swal.fire({
      title: 'error',
      text: `${e}`,
      icon: 'error',
      showCancelButton: true,
      showCloseButton: true,
    })

    if (isConfirmed) {
      return await refresh(last.value)
    }
  } finally {
    processing.value = false
  }

}

onMounted(refresh)

const all = {
  url,
  config,
  refresh,
  paginator: paginator.value,
  data: paginator.value.data,
  processing: !processing.value && !paginator.value?.data?.length,
}

defineExpose(all)
</script>
<!-- per page -->

<template>
  <div class="flex flex-col w-full  ">
    <div class="flex flex-col sm:flex-row items-center sm:justify-between space-y-2 sm:space-y-0 sm:space-x-2 p-2">
      <div class="w-full sm:max-w-xs flex items-center justify-start pl-8 ">
        <!-- page heading belum jalan script nya -->
        <div class="relative inline-block text-left" >
    <button @click.stop="showOptions = !showOptions" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-slate-100  rounded-md shadow-sm focus:bg-slate-200 focus:border-transparent focus:outline-none focus:ring-0 ">
      {{ perPage }}
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
      </svg>
    </button>

    <div v-show="showOptions"  class="origin-top-right absolute right-0 mt-2  w-[75px] rounded-md shadow-lg bg-white border ring-1 ring-black ring-opacity-5 z-50" >
      <div class="py-1 flex flex-col pt-3 items-center " role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
      <button @click.stop="setPerPage(5)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-14" role="menuitem">5</button>
      <button @click.stop="setPerPage(10)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-14" role="menuitem">10</button>
      <button @click.stop="setPerPage(25)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-14" role="menuitem">25</button>
      <button @click.stop="setPerPage(100)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-14" role="menuitem">100</button>
    </div>
    </div>
  </div>
      </div>
        <!--Search Belum jalan script nya  -->
      <div class="w-full sm:max-w-sm flex items-center space-x-2 sm:justify-end pl-[115px] pr-[20px]">
        <!-- <label for="search" class="w-1/4 sm:w-auto lowercase first-letter:capitalize">search</label> -->
        <div class="flex flex-row items-center bg-transparant rounded-md z-10">
          <!-- <img src="../../../../bootstrap/Search.png" alt="" class="w-5 ml-[19px]"> -->
          <input v-model="config.search" @input.prevent="refresh()" type="search" name="search" class="z-20 w-full bg-slate-100 h-[40px] rounded-md px-3 py-1 placeholder:capitalize border-transparent focus:bg-slate-200 focus:border-transparent focus:outline-none focus:ring-0" placeholder="search" autofocus>
        </div>
      </div>
    </div>

    <div class=" pt-[40px]">
      <div class="overflow-auto rounded-md max-h-[800px] thin-scrollbar">
        <table class="w-full border-collapse">
          <thead ref="thead" class=" z-10" :class="config.sticky && 'sticky top-0 left-0'">
            <slot name="thead" :config="config" :paginator="paginator" :data="paginator.data" :refresh="refresh" />
          </thead>

          <tfoot ref="tfoot" class=" z-10" :class="config.sticky && 'sticky bottom-0 left-0'">
            <slot name="tfoot" :config="config" :paginator="paginator" :data="paginator.data" :refresh="refresh" />
          </tfoot>

          <tbody ref="tbody" class="text-[16px] text-slate-600 ">
            <slot name="tbody" :config="config" :paginator="paginator" :data="paginator.data" :processing="processing" :empty="!processing && !paginator?.data?.length" />
          </tbody>
        </table>
      </div> 
      <!-- Pagenation Belum jalan scriptnya -->
  <!-- component -->
        <!-- component -->
        <!-- Pagenation 1 -->
        <div class="pt-5">
        <div class="flex justify-end pr-3">
          <div class=" w-full pl-7">Showing {{ paginator.from }} to {{ paginator.to }} of {{ paginator.total }} entries</div>
            <nav class="flex space-x-2" aria-label="Pagination">
              <a href="#" 
                 v-if="paginator.prev_page_url" 
                 @click.prevent="changePage(paginator.current_page - 1)" 
                 class="relative inline-flex items-center px-4 py-2 text-sm bg-slate-100 rounded-md shadow-sm hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800"
              >
                Previous
              </a>
              <div v-for="(link, i) in paginator.links" :key="i">
                  <a 
                    v-if="link.label != 'pagination.next' && link.label != 'pagination.previous'" 
                    @click.prevent="changePage(link.label)" 
                    :class="{
                      'bg-sky-100': paginator.current_page == link.label,
                      'relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-fuchsia-100 rounded-md shadow-sm hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800': true
                    }"
                  >
                    {{ link.label }}
                  </a>
              </div>
              
              <a href="#" 
                 v-if="paginator.next_page_url" 
                 @click.prevent="changePage(paginator.current_page + 1)" 
                 class="relative inline-flex items-center px-4 py-2 text-sm bg-slate-100 rounded-md shadow-sm hover:bg-blue-100 hover:text-blue-700 hover:border-blue-100 focus:bg-blue-200 focus:text-blue-800"
              >
                Next
              </a>
            </nav>
        </div>
    </div>
 </div>
  </div>
</template>