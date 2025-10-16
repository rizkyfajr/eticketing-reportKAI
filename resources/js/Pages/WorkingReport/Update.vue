<script setup>
import { onMounted, onUnmounted } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
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
  status: props.report?.status || '',
})

const submit = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form.patch(route('working-reports.update', props.report.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Working Report berhasil diubah.',
        timer: 1500,
        showConfirmButton: false,
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Terjadi kesalahan saat ubah data.',
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
  <DashboardLayout :title="__('Working Report')">
    <div class="transition-all duration-300">
      <main class="p-4">
        <h2 class="font-bold text-lg mb-1">Working Report</h2>
        <p class="text-sm text-gray-500 mb-4">Halaman Ubah Data Working Report</p>
        <Card class="bg-white dark:bg-gray-700 dark:text-gray-100 shadow-md">

          <template #header>
             <div class="flex justify-center items-center p-2 bg-gray-200 dark:bg-gray-800">
              <p class="font-semibold text-center">Form Ubah Working Report</p>
            </div>
          </template>

          <template #body>
            <form @submit.prevent="submit" class="gap-6 p-4">

              <div>
                <label class="block text-sm font-semibold mb-1">Nama Mesin</label>
                <select
                  v-model="form.machine_id"
                  class="w-full border rounded-md px-3 py-2 bg-white"
                >
                  <option disabled value="">-- Pilih Mesin --</option>
                  <option v-for="machine in machines" :key="machine.id" :value="machine.id">
                    {{ machine.name }}
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
                />
              </div>

              <div class="col-span-2 flex justify-end mt-4 gap-2">

                <button class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 mt-10">
                    <Link :href="route('working-reports.index')">
                        <Icon name="edit" />
                        <p>{{ __('Kembali') }}</p>
                    </Link>
                </button>

                <button
                  type="submit"
                  class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 mt-10"
                >
                  Ubah
                </button>

              </div>
            </form>
          </template>
        </Card>
      </main>
    </div>
  </DashboardLayout>
</template>
