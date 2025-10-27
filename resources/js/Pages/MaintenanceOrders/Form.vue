<script setup>
import { ref, watch, computed } from 'vue'
import { useForm, Link } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Button from '@/Components/Button.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  order: Object,
  machines: Array,
  reports: Array,
})

// ---- Inertia form state
const form = useForm({
  working_report_id: props.order?.working_report_id ?? (props.reports?.[0]?.id ?? null),
  master_machine_id: props.order?.master_machine_id ?? null,
  category: (props.order?.category ?? 'unplanned'),
  title: props.order?.title ?? '',

  // UNPLANNED
  trouble_at: props.order?.trouble_at ?? '',
  location: props.order?.location ?? '',
  problem_note: props.order?.problem_note ?? '',

  // PLANNED
  plan_start_at: props.order?.plan_start_at ?? '',
  action_plan: props.order?.action_plan ?? '',
})

// ---- Keep last typed values per section when switching category
const unplannedStore = ref({
  trouble_at: form.trouble_at,
  location: form.location,
  problem_note: form.problem_note,
})

const plannedStore = ref({
  plan_start_at: form.plan_start_at,
  action_plan: form.action_plan,
})

watch(() => form.category, (val, oldVal) => {
  if (oldVal === 'unplanned') {
    unplannedStore.value = {
      trouble_at: form.trouble_at,
      location: form.location,
      problem_note: form.problem_note,
    }
  } else if (oldVal === 'planned') {
    plannedStore.value = {
      plan_start_at: form.plan_start_at,
      action_plan: form.action_plan,
    }
  }

  if (val === 'unplanned') {
    form.trouble_at   = unplannedStore.value.trouble_at ?? ''
    form.location     = unplannedStore.value.location ?? ''
    form.problem_note = unplannedStore.value.problem_note ?? ''
    form.plan_start_at = ''
    form.action_plan   = ''
  } else {
    form.plan_start_at = plannedStore.value.plan_start_at ?? ''
    form.action_plan   = plannedStore.value.action_plan ?? ''
    form.trouble_at   = ''
    form.location     = ''
    form.problem_note = ''
  }
}, { immediate: true })

const isPlanned = computed(() => form.category === 'planned')

// ---- Category segmented control
function setCategory(cat) {
  form.category = (cat === 'planned') ? 'planned' : 'unplanned'
}

// ---- Submit: only send relevant fields to backend to avoid nullable issues
function submit() {
  const payload = { ...form.data() }

  if (form.category === 'planned') {
    delete payload.trouble_at
    delete payload.location
    delete payload.problem_note
    // Ensure strings for validation type-safety
    payload.action_plan = String(payload.action_plan ?? '')
  } else {
    delete payload.plan_start_at
    delete payload.action_plan
  }

  const onSuccess = () => {
    // Optional toast spot; fallback to redirect
    window.location = route('maintenance-orders.index')
  }

  const onError = (err) => console.error('Error validasi:', err)

  if (props.order) {
    form.transform(() => payload)
        .put(route('maintenance-orders.update', props.order.id), { onSuccess, onError })
  } else {
    form.transform(() => payload)
        .post(route('maintenance-orders.store'), { onSuccess, onError })
  }
}
</script>

<template>
  <DashboardLayout :title="props.order ? __('Edit Maintenance Order') : __('Tambah Maintenance Order')">
    <!-- Header -->
    <header class="px-6 pt-5">
      <nav class="text-sm text-slate-500">
        <a :href="route('maintenance-orders.index')" class="hover:text-sky-600 font-medium">Maintenance Order</a>
        <span class="mx-2">/</span>
        <span class="font-semibold text-slate-700">{{ props.order ? 'Edit' : 'Tambah' }}</span>
      </nav>
      <div class="mt-3 flex items-center justify-between">
        <h2 class="font-bold text-2xl tracking-tight">{{ props.order ? 'Edit' : 'Tambah' }} Maintenance Order</h2>
        <div class="flex gap-2">
          <Button class="border">
            <Link :href="route('maintenance-orders.index')">Batal</Link>
          </Button>
          <ButtonBlue :disabled="form.processing" @click.prevent="submit">
            {{ form.processing ? 'Menyimpan…' : (props.order ? 'Simpan Perubahan' : 'Simpan') }}
          </ButtonBlue>
        </div>
      </div>
    </header>

    <!-- Body -->
    <section class="p-6">
      <Card class="bg-white shadow-lg border border-slate-200 rounded-2xl">
        <template #body>
          <!-- Category Segmented -->
          <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">Kategori</label>
            <div class="inline-flex rounded-xl p-1 bg-slate-100 border border-slate-200 select-none" role="radiogroup" aria-label="Kategori Maintenance">
  <label :class="['px-4 py-2 rounded-lg text-sm font-semibold cursor-pointer transition',
                  form.category==='unplanned' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-600 hover:text-slate-900']">
    <input class="sr-only" type="radio" value="unplanned" v-model="form.category" name="mo-category" />
    Unplanned (Gangguan)
  </label>
  <label :class="['px-4 py-2 rounded-lg text-sm font-semibold cursor-pointer transition',
                  form.category==='planned' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-600 hover:text-slate-900']">
    <input class="sr-only" type="radio" value="planned" v-model="form.category" name="mo-category" />
    Planned (Perawatan)
  </label>
</div>
<InputError :message="form.errors.category" class="mt-1" />
          </div>

          <!-- Top meta -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-semibold mb-1">Working Report <span class="text-red-600">*</span></label>
              <select v-model="form.working_report_id" class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-sky-500">
                <option :value="null" disabled>Pilih Working Report</option>
                <option v-for="r in props.reports" :key="r.id" :value="r.id">WR #{{ r.id }}</option>
              </select>
              <p class="text-[11px] text-slate-500 mt-1">Hubungkan MO ke laporan kerja terkait.</p>
              <InputError :message="form.errors.working_report_id" class="mt-1" />
            </div>

            <div>
              <label class="block text-sm font-semibold mb-1">Mesin <span class="text-red-600">*</span></label>
              <select v-model="form.master_machine_id" class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-sky-500">
                <option :value="null" disabled>Pilih Mesin</option>
                <option v-for="m in props.machines" :key="m.id" :value="m.id">{{ m.name }} — {{ m.nomor }} ({{ m.no_sarana }})</option>
              </select>
              <InputError :message="form.errors.master_machine_id" class="mt-1" />
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-semibold mb-1">Judul <span class="text-red-600">*</span></label>
              <Input v-model="form.title" placeholder="Contoh: Gangguan motor kanan / Perawatan berkala pompa" />
              <InputError :message="form.errors.title" class="mt-1" />
            </div>
          </div>

          <!-- Divider -->
          <div class="h-px bg-slate-200 my-6" />

          <!-- UNPLANNED SECTION -->
          <div :key="`sec-${form.category}`" v-if="!isPlanned" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="block text-sm font-semibold mb-1">Waktu Gangguan <span class="text-red-600">*</span></label>
                <input type="datetime-local" v-model="form.trouble_at" class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                <InputError :message="form.errors.trouble_at" class="mt-1" />
              </div>
              <div>
                <label class="block text-sm font-semibold mb-1">Lokasi</label>
                <Input v-model="form.location" placeholder="Contoh: Binjai KM 12" />
                <InputError :message="form.errors.location" class="mt-1" />
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-1">Catatan Gangguan</label>
              <textarea v-model="form.problem_note" rows="4" class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-sky-500" placeholder="Gejala, indikator, kondisi awal, dsb."></textarea>
              <InputError :message="form.errors.problem_note" class="mt-1" />
            </div>
          </div>

          <!-- PLANNED SECTION -->
          <div v-else class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="block text-sm font-semibold mb-1">Rencana Mulai <span class="text-red-600">*</span></label>
                <input type="datetime-local" v-model="form.plan_start_at" class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                <InputError :message="form.errors.plan_start_at" class="mt-1" />
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-1">Rencana Tindak Lanjut / Deskripsi Perawatan</label>
              <textarea v-model="form.action_plan" rows="4" class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-sky-500" placeholder="Checklist pekerjaan, interval, parts disiapkan, dsb."></textarea>
              <InputError :message="form.errors.action_plan" class="mt-1" />
            </div>
          </div>

          <!-- Footer actions (mobile) -->
          <div class="mt-8 flex gap-2 md:hidden">
            <Button class="border w-full">
              <Link :href="route('maintenance-orders.index')">Batal</Link>
            </Button>
            <ButtonBlue class="w-full" :disabled="form.processing" @click.prevent="submit">
              {{ form.processing ? 'Menyimpan…' : 'Simpan' }}
            </ButtonBlue>
          </div>
        </template>
      </Card>
    </section>

    <!-- Sticky bottom bar (desktop) -->
    <div class="hidden md:block sticky bottom-0 inset-x-0 bg-white/80 backdrop-blur border-t border-slate-200">
      <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-end gap-2">
        <Button class="border">
          <Link :href="route('maintenance-orders.index')">Batal</Link>
        </Button>
        <ButtonBlue :disabled="form.processing" @click.prevent="submit">
          {{ form.processing ? 'Menyimpan…' : (props.order ? 'Simpan Perubahan' : 'Simpan') }}
        </ButtonBlue>
        <ButtonRed v-if="props.order" @click.prevent="$inertia.delete(route('maintenance-orders.destroy', props.order.id))">
          Hapus
        </ButtonRed>
      </div>
    </div>
  </DashboardLayout>
</template>

<style scoped>
/* Small polish without imposing a full design system */
:deep(select), :deep(textarea), :deep(input[type="datetime-local"]) {
  @apply bg-white/90;
}
</style>
