<script setup>
import { onMounted, onUnmounted } from 'vue'
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Swal from 'sweetalert2'

const props = defineProps({
  report: Object,
  machines: Array,
  regions: Array,
  users: Array,
  reportuser: Array,
})

const { user } = usePage().props.value

const form = useForm({
  machine_id: props.report?.machine_id || '',
  region_id: props.report?.region_id || '',
  date: props.report?.date || '',
  has_trouble: props.report?.has_trouble || '',
  status: props.report?.jumlah_msp || '',
})

const submit = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form.post(route('working-reports.store'), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Working Report berhasil disimpan.',
        timer: 1500,
        showConfirmButton: false,
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Terjadi kesalahan saat menyimpan data.',
      })
    },
  })
}

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
  <DashboardLayout :title="__('Working Order')">
    <div class="transition-all duration-300">
      <main class="p-4">
        <h2 class="font-bold text-lg mb-1">Working Order</h2>
        <p class="text-sm text-gray-500 mb-4">Halaman Tambah Data Working Order</p>
        <Card class="bg-white dark:bg-gray-700 dark:text-gray-100 shadow-md">

          <template #header>
             <div class="flex justify-center items-center p-2 bg-gray-200 dark:bg-gray-800">
              <p class="font-semibold text-center">Form Input Working Order</p>
            </div>
          </template>

          <template #body>
            <form @submit.prevent="submit" class=" gap-6 p-4">

              <div>
                <label class="block text-sm font-semibold mb-1">Nama Mesin</label>
                <select
                  v-model="form.machine_id"
                  class="w-full border rounded-md px-3 py-2 bg-white"
                >
                  <option disabled value="">-- Pilih Mesin --</option>
                  <option v-for="machine in machines" :key="machine.id" :value="machine.id">
                    {{ `${machine.name} - ${machine.type}` }}
                  </option>
                </select>
                <div v-if="form.errors.machine_id" class="text-red-500 text-sm">
                  {{ form.errors.machine_id }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Wilayah</label>
                <select
                  v-model="form.region_id"
                  class="w-full border rounded-md px-3 py-2 bg-white"
                >
                  <option disabled value="">-- Pilih Wilayah --</option>
                  <option v-for="region in regions" :key="region.id" :value="region.id">
                    {{ region.name }}
                  </option>
                </select>
                <div v-if="form.errors.region_id" class="text-red-500 text-sm">
                  {{ form.errors.region_id }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-semibold mb-1">Tanggal</label>
                <input
                  v-model="form.date"
                  type="datetime-local"
                  class="w-full border rounded-md px-3 py-2"
                  placeholder="Masukkan tanggal"
                />
              </div>

              <div class="col-span-2 flex justify-end mt-4">
                <button
                  type="submit"
                  class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700"
                >
                  Simpan
                </button>
              </div>
            </form>
          </template>
        </Card>
      </main>
    </div>
  </DashboardLayout>
</template>
