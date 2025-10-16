<script setup>
import { onMounted, onUnmounted, nextTick, ref, reactive, toRaw } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Select from '@vueform/multiselect'
import Button from '@/Components/Button.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'
import TextArea from '@/Components/TextArea.vue'
import Swal from 'sweetalert2'
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import Icon from '@/Components/Icon.vue'
import axios from 'axios';
import BtnAttachment from '@/Components/Button/Attachment.vue'
// import moment from 'moment'; 

const { report, machine, region, users } = defineProps({
    report: Array,
    machine: Array,
    region: Array,
    users: Array,
})

const { user } = usePage().props.value

const form = useForm({
    id: report?.id || null,
    machine_id: report?.machine_id || null,
    region_id: report?.region_id || null,
    date: report?.date || null,
    has_trouble: report?.has_trouble || null,
    status: report?.status || null,
});


// const form1 = useForm({
//   id: deviation?.id || null,
//   immediate_action: secondSection?.immediate_action || null,
//   justification_of_action: secondSection?.justification_of_action || null,
//   date_of_action: secondSection?.date_of_action || null, 
//   impact_to_others: secondSection?.impact_to_others || null, 
//   initial_level: secondSection?.initial_level || null, 
//   potential_rootcause: secondSection?.potential_rootcause || null, 
//   corrective_action: secondSection?.corrective_action || null, 
//   impacting_other_rules: riskImpactAnalysis?.impacting_other_rules || 0,
//   impacting_to_patient: riskImpactAnalysis?.impacting_to_patient || 0,
//   impacting_cqa: riskImpactAnalysis?.impacting_cqa || 0,
// });

const render = ref(true)
const table = ref(null)
const open = ref(false)
const show = () => open.value = true

const close = () => {
  form4.reset()
  render.value = false
  nextTick(() => {
    nextTick(() => open.value = false)
    render.value = true
  })
}

// const update = async () => {
//   try {
//     // const message = '';
//     const response = await Swal.fire({
//       title: 'Konfirmasi',
//       html: `Apakah anda akan menyimpan isian section 1 untuk penyimpangan ini?`,
//       // html: message,
//       icon: 'question',
//       showCancelButton: true,
//       showCloseButton: true,
//     });

//     if (response.isConfirmed) {
//       Swal.fire({
//         title: 'Menyimpan data...',
//         timer: 2000,
//         timerProgressBar: true,
//         onBeforeOpen: () => {
//           Swal.showLoading();
//         },
//       });

//       if (!form.registration_number) {
//         form.registration_number = await generateCode();
//       }

//       // if (form.status === 'Section 1 - Reported') {
//       //   form.registration_number = await generateCode();
//       // }

//       const result = await form.patch(route('deviation.register', form.id), {
//         onSuccess: () => {
//           close();
//           Swal.showLoading();
//           Swal.fire({
//             title: 'Data berhasil disimpan!',
//             icon: 'success',
//           });
//         },
//         onError: () => {
//           show();
//           Swal.showLoading();
//           Swal.fire({
//             title: 'Terjadi kesalahan!',
//             text: 'Gagal menyimpan data.',
//             icon: 'error',
//           });
//         },
//       });

//       return result;
//     }
//   } catch (error) {
//     console.error(error);
//   }
// };

// const sectionTwoSave = async () => {
//   try {

//     const response = await Swal.fire({
//       title: 'Konfirmasi',
//       html: `Apakah anda akan menyimpan isian section 2 untuk penyimpangan ini? <br>
//             <span style="color: red;">Isian tidak dapat dirubah setelah Asman terkait menyetujui section 2!
//             <span style="color: red;">Pastikan isi informasi dengan benar. </span>`,
//       // html: `Apakah anda akan menyimpan isian section 2 untuk penyimpangan ini?`,
//       icon: 'question',
//       showCancelButton: true,
//       showCloseButton: true,
//     });

//     if (response.isConfirmed) {
//       Swal.fire({
//         title: 'Menyimpan data...',
//         timer: 2000,
//         timerProgressBar: true,
//         onBeforeOpen: () => {
//           Swal.showLoading();
//         },
//       });

//       const result = await form1.post(route('deviation.sectionTwoSave', form1.id), {
//         onSuccess: () => {
//           close();
//           Swal.showLoading();
//           Swal.fire({
//             title: 'Data berhasil disimpan!',
//             icon: 'success',
//           }).then(() => {
//             window.location.reload();
//           });
//         },
//         onError: () => {
//           show();
//           Swal.showLoading();
//           Swal.fire({
//             title: 'Terjadi kesalahan!',
//             text: 'Gagal menyimpan data.',
//             icon: 'error',
//           });
//         },
//       });

//       return result;
//     }
//   } catch (error) {
//     console.error(error);
//   }
// };

const currentSection = ref('workingreport');

const fetch = async (section = 'firstSection', deviation) => {
  Swal.showLoading();

  try {
    const response = await axios.post(route('deviation.fetch', deviation.id), { section: section });
    const data = response.data;
    
    Swal.close();

    switch (section) {
      case 'workingreport':
        form.first_section = data.first_section;
        break;
      case 'secondSection':
        form1.second_section = data.second_section;
        form1.riskImpactAnalysis = data.second_section?.riskImpactAnalysis;
        break;
      case 'thirdSection':
        form2.third_section = data.third_section;
        form2.fiveWhy = data.third_section?.fiveWhy;
        form2.fishbone = data.third_section?.fishbone;
        break;
      case 'fourthSection':
        form3.fourth_section = data.fourth_section;
        break;
      case 'fifthSection':
        form4.fifth_section = data.fifth_section;
        form4.reviews = data.fifth_section?.reviews;
        break;
      case 'sixthSection':
        form5.sixth_section = data.sixth_section;
        break;
    }

    form1.deviation = data.deviation;
    currentSection.value = section;
  } catch (error) {
    console.error(error);
    Swal.close();
  }
};

onMounted(() => {
  form.reported_date = new Date().toISOString().slice(0, 10);
});

const submit = () => form.id ? update() : store()
const submit2 = () => form1.id ? sectionTwoSave() : update()

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
  
  <DashboardLayout :title="__('Working Report')">
    <div
      class="transition-all duration-300"
      :class="{
        'pl-1 md:pl-64': open,
      }"
    >
      <main class="p-0 py-0">
        <h2 class="font-bold">Working Report</h2>
        <p class="font-semibold">Halaman Detail Working Report </p>
        <slot />
      </main>
    </div>
    <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
      <template #header>
        <div class="flex items-center justify-between p-2 bg-gray-200 dark:bg-gray-800">
          <div class="font-semibold">
            <!-- {{ report.name }} -->
          </div>

          <div class="rounded-md gap-2 w-full md:w-auto">
            <ButtonBlue class="font-semibold">
              <!-- {{ report.status }} -->
            </ButtonBlue>
          </div>
        </div>
      </template>

      <template #body>
      <div class="flex flex-col space-y-4 p-4">
        <!-- <template v-if="hasRole (['superuser', 'supervisor penyimpangan', 'pelaksana penyimpangan', 'user', 'asman', 'spv', 'ampr', 'mpm', 'risk assessment', 'Manager'])"> -->
          
        <div class="flex flex-col space-y-4 p-4">
          <div class="text-center">
            <label for="name" class="first-letter:capitalize font-bold">
              {{ __('Formulir Laporan Pekerjaan') }}<br>
              {{ __('Tanggal') }}
              <!-- <span v-if="deviation.registration_number">{{ deviation.registration_number }}</span>
              <span v-else>Belum diberi nomor</span> -->
            </label>
          </div>

          <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-0 md:space-x-0">
            <div class="border border-gray-300 p-1 w-full" :class="{ 'bg-blue-600': currentSection === 'workingreport' }">
              <!-- <a 
                href="#list-section1" id="list-section1-list" data-toggle="list" role="tab" aria-controls="section1" class="list-group-item list-group-item-action active" 
                @click.prevent="fetch('firstSection', deviation)" 
              > -->
                <p :class="{ 'text-white': currentSection === 'firstSection' }">Working Report</p>
              <!-- </a> -->
            </div>

            <div class="border border-gray-300 p-1 w-full" :class="{ 'bg-blue-600': currentSection === 'checksheet' }">
              <!-- <a 
                href="#list-section2" id="list-section2-list" data-toggle="list" role="tab" aria-controls="section2" class="list-group-item list-group-item-action"
                :class="{ 'disabled': !deviation.first_section?.ampr_responded_at, 'active': currentSection === 'secondSection' }" 
                @click.prevent="!deviation.first_section?.ampr_responded_at ? null : fetch('secondSection', deviation)" 
              > -->
                <p :class="{ 'text-white': currentSection === 'secondSection' }">Check Sheet
                  <!-- <i v-if="!deviation.first_section?.ampr_responded_at" class="fas fa-info-circle" 
                    data-toggle="tooltip" title="Penyimpangan yang belum di registrasi dan di disposisi oleh AMPR tidak dapat mengakses Section 2.">
                  </i> -->
                </p>
              <!-- </a> -->
            </div>

            <div class="border border-gray-300 p-1 w-full" :class="{ 'bg-blue-600': currentSection === 'warmingup' }">
              <!-- <a 
                href="#list-section2" id="list-section2-list" data-toggle="list" role="tab" aria-controls="section2" class="list-group-item list-group-item-action"
                :class="{ 'disabled': !deviation.first_section?.ampr_responded_at, 'active': currentSection === 'secondSection' }" 
                @click.prevent="!deviation.first_section?.ampr_responded_at ? null : fetch('secondSection', deviation)" 
              > -->
                <p :class="{ 'text-white': currentSection === 'secondSection' }">Warming Up
                  <!-- <i v-if="!deviation.first_section?.ampr_responded_at" class="fas fa-info-circle" 
                    data-toggle="tooltip" title="Penyimpangan yang belum di registrasi dan di disposisi oleh AMPR tidak dapat mengakses Section 2.">
                  </i> -->
                </p>
              <!-- </a> -->
            </div>

            

            <div class="border border-gray-300 p-1 w-full" :class="{ 'bg-blue-600': currentSection === 'uploadfoto' }">
              <!-- <a 
                href="#list-section2" id="list-section2-list" data-toggle="list" role="tab" aria-controls="section2" class="list-group-item list-group-item-action"
                :class="{ 'disabled': !deviation.first_section?.ampr_responded_at, 'active': currentSection === 'secondSection' }" 
                @click.prevent="!deviation.first_section?.ampr_responded_at ? null : fetch('secondSection', deviation)" 
              > -->
                <p :class="{ 'text-white': currentSection === 'secondSection' }">Upload Foto
                  <!-- <i v-if="!deviation.first_section?.ampr_responded_at" class="fas fa-info-circle" 
                    data-toggle="tooltip" title="Penyimpangan yang belum di registrasi dan di disposisi oleh AMPR tidak dapat mengakses Section 2.">
                  </i> -->
                </p>
              <!-- </a> -->
            </div>

            <div class="border border-gray-300 p-1 w-full" :class="{ 'bg-blue-600': currentSection === 'workresult' }">
              <!-- <a 
                href="#list-section2" id="list-section2-list" data-toggle="list" role="tab" aria-controls="section2" class="list-group-item list-group-item-action"
                :class="{ 'disabled': !deviation.first_section?.ampr_responded_at, 'active': currentSection === 'secondSection' }" 
                @click.prevent="!deviation.first_section?.ampr_responded_at ? null : fetch('secondSection', deviation)" 
              > -->
                <p :class="{ 'text-white': currentSection === 'secondSection' }">Laporan Pekerjaan
                  <!-- <i v-if="!deviation.first_section?.ampr_responded_at" class="fas fa-info-circle" 
                    data-toggle="tooltip" title="Penyimpangan yang belum di registrasi dan di disposisi oleh AMPR tidak dapat mengakses Section 2.">
                  </i> -->
                </p>
              <!-- </a> -->
            </div>
            <!-- END -->

          </div>
        </div>

        <div class="flex flex-col md:flex-row mt-2 md:space-y-0 md:space-x-0 md:space-x-0">
          <div class="border border-black p-2 w-full" id="list-section1" role="tabpanel" aria-labelledby="list-section1-list">
          <div class="flex flex-col space-y-2"> 
            
            <!-- section 1 -->             
					  <div v-if="currentSection === 'workingreport'" class="tab-pane fade show active p-2" id="list-section1" role="tabpanel" aria-labelledby="list-section1-list">
            <!-- <div v-if="! deviation.first_section.ampr_responded_at" class="tab-pane fade show active p-2" id="list-section1" role="tabpanel" aria-labelledby="list-section1-list"> -->
            <!-- <div class="tab-pane fade show active p-2" id="list-section1" role="tabpanel" aria-labelledby="list-section1-list"> -->
              <div class="flex flex-col space-y-4 p-4">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 first-letter:capitalize font-semibold">
                    {{ __('Nama Mesin') }}
                  </label>
                  <div>{{ __(report.machine?.name ?? '-') }}</div>
                </div>

                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 first-letter:capitalize font-semibold">
                    {{ __('Wilayah') }}
                  </label>
                  <div>{{ __(report.region?.name ?? '-') }}</div>
                </div>
                                
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 first-letter:capitalize font-semibold">
                    {{ __('Tanggal') }}
                  </label>
                  <div>{{ new Date(report?.date).toOnlyIndonesianDate() }}</div>
                </div>
                
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 first-letter:capitalize font-semibold">
                    {{ __('Trouble') }}
                  </label>
                  <div>{{ __(report.has_trouble)?? '-' }}</div>
                </div>
                
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 first-letter:capitalize font-semibold">
                    {{ __('Status') }}
                  </label>
                  <div>{{ report.status }}</div>
                </div>

                <!-- <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label class="w-1/3 first-letter:capitalize font-semibold">
                      {{ __('Bukti Tambahan') }}
                    </label>

                    <BtnAttachment
                      :model="firstSection"
                      type="First_Section"
                      :redaction="`Lampiran`"
                      :closed="false"
                    />

                  </div>
                </div>
                <div>
                  <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2 my-2">
                      <p style="color:white; font-size: 0.7rem;  margin-top: -2rem;" class="w-1/3">Note</p>
                      
                      <p style="color: black; font-size: 0.7rem;  margin-top: -2rem; text-align: center;">
                        Format file yang didukung : <lable style="color: black; font-size: 0.8rem;  margin-top: -1rem; text-align: center;" class="font-bold">PNG, JPG, PDF, DOC, DOCX, XLS, XLSX, RAR, ZIP (Max : 5 MB / file)</lable>
                      </p>
                    </div>
                  </div>
                </div> -->
                <hr>
              </div>

              <!-- <form @submit.prevent="submit" class="w-full max-w-x8 h-fit shadow-xl">
                <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
                  <template #body>
                    <div class="flex flex-col space-y-4 p-4">
                      <template v-if="isComplianceMember && ! deviation.first_section?.ampr_responded_at">

                        <div class="flex flex-col space-y-2">
                          <div class="flex items-center space-x-2">
                            <label for="registration_number" class="w-1/2 lowercase first-letter:capitalize">
                              {{ __('Nomor Registrasi') }}
                            </label>
                            <Input
                              v-model="form.registration_number"
                              :placeholder="__('Nomor Registrasi')"
                              type="text"
                              required
                            />
                          </div>
                          <InputError :error="form.errors.registration_number" />
                        </div>

                        <div class="flex flex-col space-y-2">
                          <div class="flex items-center space-x-2">
                            <label for="reported_date" class="w-1/2 capitalize">
                              {{ __('Tanggal dilaporkan') }}
                            </label>
                            <Input
                              v-model="form.reported_date"
                              :placeholder="__('Tanggal Dilaporkan')"
                              type="date"
                              required
                              readonly
                            />
                          </div>
                          <InputError :error="form.errors.reported_date" />
                        </div>

                        <div class="flex flex-col space-y-2">
                          <div class="flex items-center space-x-2">
                            <label for="reporting_delay" class="w-1/2 capitalize">
                              {{ __('Keterlambatan Laporan') }}
                            </label>
                            <Select
                              v-model="form.reporting_delay"
                              :options="[
                                { label: 'Ada', value: '1' },
                                { label: 'Tidak Ada', value: '0' }
                              ]"
                              :searchable="true"
                              :placeholder="__('Keterlambatan Laporan')"
                              required
                            />
                          </div>
                          <InputError :error="form.errors.reporting_delay" />
                        </div>

                        <div v-if="form.reporting_delay === '1'" class="flex flex-col space-y-2">
                          <div class="flex items-center space-x-2">
                            <label for="delay_impact" class="w-1/2 capitalize">
                              {{ __('Dampak Keterlambatan Respon') }}
                            </label>
                            <Select
                              v-model="form.delay_impact"
                              :placeholder="__('Dampak Keterlambatan Respon')"
                              :options="[
                                { label: 'Kegagalan Proses', value: 'Kegagalan Proses' },
                                { label: 'Terhambatnya Proses', value: 'Terhambatnya Proses' },
                                { label: 'Timbul Penyimpangan Lainnya', value: 'Timbul Penyimpangan Lainnya' },
                                { label: 'Tidak ada dampak yang signifikan secara mutu', value: 'Tidak ada dampak yang signifikan secara mutu' }
                              ]"
                            />
                          </div>
                          <InputError :error="form.errors.delay_impact" />
                        </div>
                        
                        <div class="flex flex-col space-y-2">
                          <div class="flex items-center space-x-2">
                            <label for="responsibles_id" class="w-1/2">
                              {{ __('Pihak Terkait untuk Diinformasikan') }}
                            </label>
                            <Select
                              v-model="form.responsibles_id"
                              :options="users.map(user => ({
                                label: user.name,
                                value: user.id,
                              }))"
                              :searchable="true"
                              mode="tags"
                              placeholder="Pilih Penanggung Jawab"
                              required
                            />
                          </div>
                          <InputError :error="form.errors.responsibles_id" />
                        </div>

                        <hr>

                        <div class="flex flex-col space-y-2">
                          <div class="flex items-center space-x-2">
                            <label for="name" class="w-1/2 capitalize">
                              {{ __('Disposisi oleh AMPR') }}
                            </label>
                            <TextArea
                              v-model="form.ampr_note"
                              :placeholder="__('Disposisi oleh AMPR')"
                              type="text"
                            />
                          </div>
                          <InputError :error="form.errors.ampr_note" />
                        </div>

                      </template>
                      <template v-else>
                        <div class="flex flex-col space-y-2">
                          <div v-if="deviation.first_section?.reviewed_by" class="flex items-center space-x-2">
                            <label for="reviewed_by" class="w-1/3 first-letter:capitalize font-semibold">
                              {{ __('Review Oleh') }}
                            </label>
                            <div>
                              {{ __(deviation.first_section.reviewer.name) }} ({{ deviation.first_section.reviewer.jabatan }})
                            </div>
                          </div>
                        </div>
                        
                        <div class="flex flex-col space-y-2">
                          <div v-if="deviation.first_section?.reported_date" class="flex items-center space-x-2">
                            <label for="reported_date" class="w-1/3 first-letter:capitalize font-semibold">
                              {{ __('Tanggal Dilaporkan') }}
                            </label>
                            <div>{{ new Date(deviation.first_section.reported_date).toOnlyIndonesianDate('id') }}</div>
                          </div>
                        </div>
                        
                        <div class="flex flex-col space-y-2">
                          <div v-if="deviation.first_section?.delay_impact" class="flex items-center space-x-2">
                            <label for="delay_impact" class="w-1/3 first-letter:capitalize font-semibold">
                              {{ __('Keterlambatan Laporan') }}
                            </label>
                            <div>
                              {{ __(deviation.first_section.delay_impact) }}
                            </div>
                          </div>
                        </div>
                        
                        <div class="flex flex-col space-y-2">
                          <div v-if="deviation.responsibles?.length > 0" class="flex items-center space-x-2">
                            <label for="responsibles" class="w-1/3 first-letter:capitalize font-semibold">
                              {{ __('Pihak Terkait untuk Diinformasikan') }}
                            </label>
                            <div class="flex flex-col">
                              <ul>
                                <li v-for="(responsible, index) in deviation.responsibles">{{ index + 1 }}. {{ responsible.name }} ({{ responsible.jabatan }})</li>
                              </ul>
                            </div>
                          </div>
                        </div>

                        <div class="flex flex-col space-y-2">
                          <div v-if="deviation.first_section?.ampr_responded_at" class="flex items-center space-x-2">
                            <label for="ampr_note" class="w-1/3 first-letter:capitalize font-semibold">
                              {{ __('Disposisi oleh AMPR') }}
                            </label>
                            <div>
                              {{ __(deviation.first_section.ampr_note) }}
                            </div>
                          </div>
                        </div>

                        <div class="flex flex-col space-y-2">
                          <div v-if="deviation.first_section?.spkp_responded_at" class="flex items-center space-x-2">
                            <label for="spkp_responded_at" class="w-1/3 first-letter:capitalize font-semibold">
                              {{ __('Review SPKP pada') }}
                            </label>
                            <div>{{ new Date(deviation.first_section.spkp_responded_at).toOnlyIndonesianDate('id') }}</div>
                          </div>
                        </div>

                        <div class="flex flex-col space-y-2">
                          <div v-if="deviation.first_section?.ampr_responded_at" class="flex items-center space-x-2">
                            <label for="ampr_responded_at" class="w-1/3 first-letter:capitalize font-semibold">
                              {{ __('Reveiew AMPR pada') }}
                            </label>
                            <div>{{ new Date(deviation.first_section.ampr_responded_at).toOnlyIndonesianDate('id') }}</div>
                          </div>
                        </div>
                      </template>
                    </div>
                    
                    <div class="flex items-center space-x-2 p-2 justify-end">
                      <ButtonBlue type="submit" v-if="isSpkp && !deviation.first_section?.spkp_responded_at">
                        <p class="uppercase font-semibold">
                          {{ __(form.id ? 'Paraf SPV Peyimpangan' : 'laporkan') }}
                        </p>
                      </ButtonBlue>
                      
                      <ButtonBlue type="submit" v-if="isAmpr && deviation.first_section?.spkp_responded_at && !deviation.first_section.ampr_responded_at">
                        <p class="uppercase font-semibold">
                          {{ __(form.id ? 'Paraf Asman Pemenuhan Regulasi' : 'laporkan') }}
                        </p>
                      </ButtonBlue>

                      <ButtonGreen type="submit" v-if="isComplianceMember && ! isAmpr && ! isSpkp && ! deviation.first_section?.ampr_responded_at">
                        <p class="uppercase font-semibold">
                          {{ __(form.id ? 'Register' : 'laporkan') }}
                        </p>
                      </ButtonGreen>
                    </div>
                      
                  </template>
                </Card>
              </form> -->
            </div>
            <!-- section 1 -->

            <!-- section 2 --> 
						<!-- <div v-if="currentSection === 'secondSection'" class="tab-pane fade p-2" id="list-section2" role="tabpanel" aria-labelledby="list-section2-list">
              <form 
                @submit.prevent="submit2" 
                class="w-full max-w-x8 h-fit shadow-xl">
                <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
                  <template #body>
                    <div class="flex flex-col space-y-4 p-4">

                        <div class="flex flex-col space-y-2">
                          <div class="flex items-center space-x-2">
                            <label for="immediate_action" class="w-1/3 lowercase first-letter:capitalize">
                              {{ __('Tindakan Segera yang diambil') }}
                            </label>
                            
                            <template v-if="(isResponsible || isComplianceMember && ! deviation.second_section?.reviewed_at)">
                              <div class="w-2/3">
                                <TextArea
                                  v-model="form1.immediate_action"
                                  :placeholder="__('Tindakan Segera yang diambil')"
                                  type="text"
                                />
                              </div>
                            </template>
                            <template v-else>
                              <div class="w-2/3">
                                <span>{{ deviation.second_section?.immediate_action }}</span>
                              </div>
                            </template>

                          </div>
                          <InputError :error="form1.errors.immediate_action" />
                        </div>

                        <div class="flex flex-col space-y-2">
                          <div class="flex items-center space-x-2">
                            <label for="justification_of_action" class="w-1/3 lowercase first-letter:capitalize">
                              {{ __('Alasan Pengambilan Tindakan Segera') }}
                            </label>
                            
                            <template v-if="(isResponsible || isComplianceMember && ! deviation.second_section?.reviewed_at)">
                              <div class="w-2/3">
                                <TextArea
                                  v-model="form1.justification_of_action"
                                  :placeholder="__('Alasan Pengambilan Tindakan Segera')"
                                  type="text"
                                />
                              </div>
                            </template>
                            <template v-else>
                              <div class="w-2/3">
                                <span>{{ deviation.second_section?.justification_of_action }}</span>
                              </div>
                            </template>

                          </div>
                          <InputError :error="form1.errors.justification_of_action" />
                        </div>  

                        <div class="flex flex-col space-y-2">
                          <div class="flex items-center space-x-2">
                            <label for="date_of_action" class="w-1/3 lowercase first-letter:capitalize">
                              {{ __('Tanggal Pelaksanaan Tindakan') }}
                            </label>
                            
                            <template v-if="(isResponsible || isComplianceMember && ! deviation.second_section?.reviewed_at)">
                              <div class="w-2/3">
                                <Input
                                  v-model="form1.date_of_action"
                                  type="date"
                                />
                              </div>
                            </template>
                            <template v-else>
                              <div class="w-2/3">
                                <span>{{ new Date (deviation.second_section?.date_of_action).toOnlyIndonesianDate() }}</span>
                              </div>
                            </template>

                          </div>
                          <InputError :error="form1.errors.date_of_action" />
                        </div>
                        
                        <table class="table-auto w-full">
                          <tr style="background-color: #e1f5fe;">
                            <th colspan="3" class="uppercase center">Analisis Resiko Dampak</th>
                          </tr>
                          <tr>
                            <td style="text-align: left;">Apakah penyimpangan memiliki impact terhadap Protap / CPOB / Spesifikasi / Aturan lainnya ?</td>
                            <td style="width:10%">
                              <template v-if="(isResponsible || isComplianceMember)">
                                <label class="mr-2">
                                  <input v-model="form1.impacting_other_rules" @change="defineLevel()" value="1" type="radio" class="form-check-input">
                                  <span>Iya</span>
                                </label>
                                <label>
                                  <input v-model="form1.impacting_other_rules" @change="defineLevel()" value="0" type="radio" class="form-check-input">
                                  <span>Tidak</span>
                                </label>
                              </template>
                              <template v-else>
                                <span class="font-weight-semibold justify-end text-right"> {{ form1?.impacting_other_rules == 1 ? 'Iya': 'Tidak' }} </span>
                              </template>
                            </td>
                          </tr>
                          <tr v-if="form1?.impacting_other_rules == 1">
                            <td style="text-align: left;">Apakah penyimpangan memiliki impact langsung signifikan terhadap Keamanan Pasien? </td>
                            <td>
                              <template v-if="(isResponsible || isComplianceMember)">
                                <label class="mr-2">
                                  <input v-model="form1.impacting_to_patient" @change="defineLevel()" value="1" type="radio" class="form-check-input">
                                  <span>Iya</span>
                                </label>
                                <label>
                                  <input v-model="form1.impacting_to_patient" @change="defineLevel()" value="0" type="radio" class="form-check-input">
                                  <span>Tidak</span>
                                </label>
                              </template> 
                              <template v-else>
                                <span class="font-weight-semibold"> {{ form1?.impacting_to_patient == 1 ? 'Iya': 'Tidak' }} </span>
                              </template>
                            </td>
                          </tr>
                          <tr v-if="form1?.impacting_other_rules == 1 && form1?.impacting_to_patient == 0">
                            <td style="text-align: left;">Apakah penyimpangan memiliki impact langsung signifikan terhadap Efikasi dan Critical Quality Attributes (produk dan material) ? </td>
                            <td>
                              <template v-if="(isResponsible || isComplianceMember)">
                                <label class="mr-2">
                                  <input v-model="form1.impacting_cqa" @change="defineLevel()" value="1" type="radio" class="form-check-input">
                                  <span>Iya</span>
                                </label>
                                <label>
                                  <input v-model="form1.impacting_cqa" @change="defineLevel()" value="0" type="radio" class="form-check-input">
                                  <span>Tidak</span>
                                </label>
                              </template>
                              <template v-else>
                                <span class="font-weight-semibold"> {{ form1?.impacting_cqa == 1 ? 'Iya': 'Tidak' }} </span>
                              </template>
                            </td>
                          </tr>
                        </table>

                        <div class="flex flex-col space-y-3">
                          <div class="flex items-center space-x-3">
                            <label for="impact_to_others" class="w-1/3 lowercase first-letter:capitalize">
                              {{ __('Dampak terhadap batch / produk / peralatan / fasilitas lainnya') }}
                            </label>
                            
                            <template v-if="(isResponsible || isComplianceMember) && ! deviation.second_section?.reviewed_at">
                              <div class="w-2/3">
                                <TextArea
                                  v-model="form1.impact_to_others"
                                  :placeholder="__('Dampak terhadap batch / produk / peralatan / fasilitas lainnya')"
                                  type="text"
                                />
                              </div>
                            </template>
                            <template v-else>
                              <div class="w-2/3">
                                <span>{{ deviation.second_section?.impact_to_others }}</span>
                              </div>
                            </template>                            

                          </div>
                          <InputError :error="form1.errors.impact_to_others" />
                        </div>

                        <div class="flex flex-col space-y-2">
                          <div class="flex items-center space-x-2">
                              <label for="initial_level" class="w-1/3">
                                  {{ __('Kesimpulan Klasifikasi Awal Level Penyimpangan') }}
                              </label>

                              <template v-if="(isResponsible || isComplianceMember) && ! deviation.second_section?.reviewed_at">
                                <div class="form-check flex flex-col">
                                  <div class="flex items-start">
                                    <input class="form-check-input mt-1" type="radio" value="RC" id="initial_level1" name="initial_level" v-model="form1.initial_level">
                                    <label class="form-check-label ml-2" for="initial_level1"> RC(Required Correction) </label>
                                  </div>
                                  <div class="flex items-start">
                                    <input class="form-check-input mt-1" type="radio" value="Minor" id="initial_level2" name="initial_level" v-model="form1.initial_level">
                                    <label class="form-check-label ml-2" for="initial_level2"> Minor </label>
                                  </div>
                                  <div class="flex items-start">
                                    <input class="form-check-input mt-1" type="radio" value="Mayor" id="initial_level3" name="initial_level" v-model="form1.initial_level">
                                    <label class="form-check-label ml-2" for="initial_level3"> Mayor </label>
                                  </div>
                                  <div class="flex items-start">
                                    <input class="form-check-input mt-1" type="radio" value="Critical" id="initial_level4" name="initial_level" v-model="form1.initial_level">
                                    <label class="form-check-label ml-2" for="initial_level4"> Critical </label>
                                  </div>
                                </div>
                              </template>
                              <template v-else>
                                <span v-if="deviation.second_section?.initial_level === 'RC'" class="p-1 bg-green-500 rounded"> RC (Required Correction) </span>
                                <span v-else-if="deviation.second_section?.initial_level === 'Minor'" class="p-1 bg-blue-500 rounded"> Minor </span>
                                <span v-else-if="deviation.second_section?.initial_level === 'Mayor'" class="p-1 bg-yellow-500 rounded"> Mayor </span>
                                <span v-else-if="deviation.second_section?.initial_level === 'Critical'" class="p-1 bg-red-500 rounded"> Critical </span>
                              </template>

                          </div>
                          <InputError :error="form1.errors['initial_level']" />
                      </div>

                      <div v-if="form1.initial_level === 'RC'" class="flex flex-col space-y-3">
                        <div class="flex items-center space-x-3">
                          <label for="potential_rootcause" class="w-1/3 lowercase first-letter:capitalize">
                            {{ __('Dugaan Penyebab') }}
                          </label>
                          
                          <template v-if="(isResponsible || isComplianceMember) && ! deviation.second_section?.reviewed_at">
                            <div class="w-2/3">
                              <TextArea
                                v-model="form1.potential_rootcause"
                                :placeholder="__('Dugaan Penyebab')"
                                type="text"
                              />
                            </div>
                          </template>
                          <template v-else>
                            <div class="w-2/3">
                              <span class="flex-grow">{{ deviation.second_section?.potential_rootcause }}</span>
                            </div>
                          </template>
                        </div>
                        <InputError :error="form1.errors.potential_rootcause" />
                      </div>

                      <div v-if="form1.initial_level === 'RC'" class="flex flex-col space-y-3">
                        <div class="flex items-center space-x-3">
                          <label for="corrective_action" class="w-1/3 lowercase first-letter:capitalize">
                            {{ __('Corrective Action') }}
                          </label>
                          
                          <template v-if="(isResponsible || isComplianceMember) && ! deviation.second_section?.reviewed_at">
                            <div class="w-2/3">
                              <TextArea
                                v-model="form1.corrective_action"
                                :placeholder="__('Corrective Action')"
                                type="text"
                              />
                            </div>
                          </template>
                          <template v-else>
                            <div class="w-2/3">
                              <span class="w-full">{{ deviation.second_section?.corrective_action }}</span>
                            </div>
                          </template>
                        </div>
                        <InputError :error="form1.errors.corrective_action" />
                      </div>

                      <div class="flex flex-col space-y-2">
                        <div class="flex items-center space-x-2">
                          <label for="corrective_action" class="w-1/3 lowercase first-letter:capitalize">
                            {{ __('Lampiran / Bukti (jika diperlukan)') }}
                          </label>
                          
                            <BtnAttachment
                              :model="secondSection"
                              type="Second_Section"
                              :redaction="`Lampirkan`"
                              :closed="false"
                            />

                        </div>
                      </div>

                      <div>
                        <div class="flex flex-col space-y-2" style="margin-top: 0rem;">
                          <div class="flex items-center space-x-2 my-2" style="margin-top: 0rem;">
                            <p style="color:white; font-size: 0.8rem;  margin-top: -2rem;" class="w-1/3">Note</p>
                            
                            <p style="color: red; font-size: 0.8rem;  margin-top: -1rem; text-align: center;">
                              *Sebelum upload dokumen, mohon simpan data terlebih dahulu.
                            </p>
                          </div>
                        </div>
                      </div>

                      <div>
                        <div class="flex flex-col space-y-2" style="margin-top: -0rem;">
                          <div class="flex items-center space-x-2 my-2" style="margin-top: -0rem;">
                            <p style="color:white; font-size: 0.8rem;  margin-top: -2rem;" class="w-1/3">Note</p>
                            
                            <p style="color: black; font-size: 0.8rem;  margin-top: -2rem; text-align: center;">
                              Format file yang didukung : <lable style="color: black; font-size: 0.8rem;  margin-top: -1rem; text-align: center;" class="font-bold">PNG, JPG, PDF, DOC, DOCX, XLS, XLSX, RAR, ZIP (Max : 5 MB / file)</lable>
                            </p>
                          </div>
                        </div>
                      </div>

                      <div v-if="deviation.second_section?.recommended_by" class="flex flex-col space-y-2" style="margin-top:0rem;">
                        <div class="flex items-center space-x-2">
                          <label for="recommended_by" class="w-1/3 first-letter:capitalize font-semibold">
                            {{ __('Direkomendasikan oleh') }}
                          </label>
                          
                          <div class="flex flex-col">
                            {{ deviation.second_section.recommender.name }} ({{ deviation.second_section.recommender.jabatan }})
                          </div>
                        </div>
                      </div>
                      
                      <div v-if="deviation.second_section?.recommended_at" class="flex flex-col space-y-2">
                        <div class="flex items-center space-x-2">
                          <label for="recommended_at" class="w-1/3 first-letter:capitalize font-semibold">
                            {{ __('Direkomendasikan pada') }}
                          </label>
                          <div>{{ new Date(deviation.second_section.recommended_at).toShortIndonesianDate() }}</div>
                        </div>
                      </div>

                      <div v-if="deviation.second_section?.reviewed_by" class="flex flex-col space-y-2">
                        <div class="flex items-center space-x-2">
                          <label for="reviewed_by" class="w-1/3 first-letter:capitalize font-semibold">
                            {{ __('Direview oleh') }}
                          </label>
                          
                          <div class="flex flex-col">
                            {{ deviation.second_section.reviewer.name }} ({{ deviation.second_section.reviewer.jabatan }})
                          </div>
                        </div>
                      </div>
                      
                      <div v-if="deviation.second_section?.reviewed_at" class="flex flex-col space-y-2">
                        <div class="flex items-center space-x-2">
                          <label for="reviewed_at" class="w-1/3 first-letter:capitalize font-semibold">
                            {{ __('Direview pada') }}
                          </label>
                          <div>{{ new Date(deviation.second_section.reviewed_at).toShortIndonesianDate() }}</div>
                        </div>
                      </div>

                      <div class="flex items-center justify-end space-x-2">
                        <template v-if="(isResponsible || isComplianceMember) && ! deviation.second_section?.reviewed_at">
                          <ButtonGreen type="submit2">
                            <p class="uppercase font-semibold">
                              {{ __(form1.id ? 'Simpan' : 'laporkan') }}
                            </p>
                          </ButtonGreen>
                        </template>
                        <template v-if="deviation.second_section?.reviewed_by == user.id && ! deviation.second_section?.reviewed_at">
                          <ButtonGreen type="submit2">
                            <p class="uppercase font-semibold">
                              {{ __(form1.id ? 'Selesai Review' : 'laporkan') }}
                            </p>
                          </ButtonGreen>
                        </template> 
                      </div>
                    </div>
                  </template>
                </Card>
              </form>
            </div> -->
            <!-- section2 -->

            <!-- section 6 -->
            <!-- <div v-if="currentSection === 'sixthSection'" class="tab-pane fade p-2" id="list-section6" role="tabpanel" aria-labelledby="list-section6-list">
							<div class="row my-2">
                <div class="flex items-center space-x-2">
                  <label for="method" class="col-4 w-1/3">Metode Peninjauan Efektivitas CA / PA</label>
                  <div class="col w-2/3">
                    <div v-if="isComplianceMember && ! deviation.sixth_section?.spkp_signed_at">
                      <Select
                        v-model="form6.method"
                        :options="sixthSectionMethodOptions.concat(['Lainnya'])"
                        :searchable="true"
                        :disabled="deviation.sixth_section?.method && ! sixthSectionMethodOptions.includes(deviation.sixth_section.method)"
                        placeholder="Pilih metode"
                        class="capitalize"
                        required>
                      </Select>
                      <div v-if="deviation.sixth_section?.method === 'Lainnya'" class="input-group mt-2">
                        <input v-model="form6.other_method" type="text" class="form-control" :disabled="deviation.sixth_section.method !== 'Lainnya'" placeholder="Sebutkan metode lain">
                        <div class="input-group-append">
                          <button class="btn btn-danger" type="button" @click.prevent="deviation.sixth_section.method = null; deviation.sixth_section.other_method = null">Pilih Metode</button>
                        </div>
                      </div>
                    </div>
                    <span v-else>{{ deviation.sixth_section?.other_method ?? deviation.sixth_section?.method }}</span>
                  </div>
                </div>
							</div>
							<div class="row my-2">
                <div class="flex items-center space-x-2">
                  <label for="remarks" class="col-4 w-1/3">Keterangan</label>
                  <div class="col w-2/3">
                    <TextArea v-if="isComplianceMember && ! deviation.sixth_section?.spkp_signed_at" v-model="form6.remarks" name="remarks" class="form-control" placeholder="Keterangan"></TextArea>
                    <span v-else>{{ deviation.sixth_section?.remarks }}</span>
                  </div>
                </div>
							</div>
							<div class="row my-2">
                <div class="flex items-center space-x-2">
                  <label for="conclusion" class="col-4 w-1/3">Kesimpulan</label>
                  <div class="col w-2/3">
                    <div v-if="isComplianceMember && ! deviation.sixth_section?.spkp_signed_at">
                      <div class="form-check">
                        <input v-model="form6.conclusion" class="form-check-input" type="radio" id="CAPAEffectiveYes" value="1">
                        <label class="form-check-label" for="CAPAEffectiveYes"> CAPA Efektif </label>
                      </div>
                      <div class="form-check">
                        <input v-model="form6.conclusion" class="form-check-input" type="radio" id="CAPAEffectiveNo" value="0">
                        <label class="form-check-label" for="CAPAEffectiveNo"> CAPA Tidak Efektif </label>
                      </div>
                    </div>
                    <span v-else>{{ deviation.sixth_section?.conclusion === 1 ? 'CAPA Efektif' : 'CAPA Tidak Efektif' }}</span>
                  </div>
                </div>
							</div>
							<div class="row my-2">
                <div class="flex items-center space-x-2">
                  <label for="description" class="col-4 w-1/3">Penjelasan</label>
                  <div class="col w-2/3">
                    <TextArea v-if="isComplianceMember && ! deviation.sixth_section?.spkp_signed_at" v-model="form6.description" name="description" class="form-control" placeholder="Penjelasan"></TextArea>
                    <span v-else>{{ deviation.sixth_section?.description }}</span>
                  </div>
                </div>
							</div>
							<div class="row my-2">
                <div class="flex items-center space-x-2">
                  <label for="capa_closed_date" class="col-4 w-1/3">Tanggal Penutupan CAPA</label>
                  <div class="col w-2/3">
                    <Input v-if="isComplianceMember && ! deviation.sixth_section?.spkp_signed_at" v-model="form6.capa_closed_date" name="capa_closed_date" class="form-control" type="date" max="<?= date('Y-m-d') ?>" />
                    <span v-else>{{ new Date(deviation.sixth_section?.capa_closed_date)?.toOnlyIndonesianDate() }}</span>
                  </div>
                </div>
							</div>
							<div v-if="deviation.sixth_section?.spkp_id" class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2 my-2">
                  <label for="recommended_by" class="w-1/3 first-letter:capitalize font-semibold">Paraf SPKP Oleh</label>
                  <div class="col d-flex align-items-center">
                    {{ deviation.sixth_section.spkp?.name }} ({{ deviation.sixth_section.spkp?.jabatan }})
                  </div>
                </div>
							</div>
							<div v-if="deviation.sixth_section?.spkp_signed_at" class="row my-2">
                <div class="flex items-center space-x-2 my-2">
                  <label for="recommended_by" class="w-1/3 first-letter:capitalize font-semibold">Paraf SPKP Pada</label>
                  <div class="col d-flex align-items-center">
                    {{ new Date(deviation.sixth_section?.spkp_signed_at).toShortIndonesianDate() }}
                  </div>
                </div>
							</div>
							<div v-if="deviation.sixth_section?.ampr_id" class="row my-2">
                <div class="flex items-center space-x-2 my-2">
                  <label for="recommended_by" class="w-1/3 first-letter:capitalize font-semibold">Paraf AMPR Oleh</label>
                  <div class="col d-flex align-items-center">
                    {{ deviation.sixth_section.ampr?.name }} ({{ deviation.sixth_section.ampr?.jabatan }})
                  </div>
                </div>
							</div>
							<div v-if="deviation.sixth_section?.ampr_signed_at" class="row my-2">
                <div class="flex items-center space-x-2 my-2">
                  <label for="recommended_by" class="w-1/3 first-letter:capitalize font-semibold">Paraf AMPR Pada</label>
                  <div class="col d-flex align-items-center">
                    {{ new Date(deviation.sixth_section?.ampr_signed_at).toShortIndonesianDate() }}
                  </div>
                </div>
							</div>
							<div v-if="deviation.sixth_section?.mpm_id" class="row my-2">
                <div class="flex items-center space-x-2 my-2">
                  <label for="recommended_by" class="w-1/3 first-letter:capitalize font-semibold">Paraf MPM Oleh</label>
                  <div class="col d-flex align-items-center">
                    {{ deviation.sixth_section.mpm?.name }} ({{ deviation.sixth_section.mpm?.jabatan }})
                  </div>
                </div>
							</div>
							<div v-if="deviation.sixth_section?.mpm_signed_at" class="row my-2">
                <div class="flex items-center space-x-2 my-2">
                  <label for="recommended_by" class="w-1/3 first-letter:capitalize font-semibold">Paraf MPM Pada</label>
                  <div class="col d-flex align-items-center">
                    {{ new Date(deviation.sixth_section?.mpm_signed_at).toShortIndonesianDate() }}
                  </div>
                </div>
							</div>
							<hr>
							<div v-if="isComplianceMember || isMpm" class="d-flex justify-content-end mt-3">
								<Button v-if="isSpkp && ! deviation.sixth_section?.spkp_signed_at" @click.prevent="approveSixthSection('spkp')" class="bg-blue-700 hover:bg-blue-900 rounded-md float-right">Paraf SPV Penyimpangan</Button>
								<Button v-if="isAmpr && ! deviation.sixth_section?.ampr_signed_at && deviation.sixth_section?.spkp_signed_at" @click.prevent="approveSixthSection('ampr')" class="bg-blue-700 hover:bg-blue-900 rounded-md float-right">Paraf Asman Pemenuhan Regulasi</Button>
								<Button v-if="isMpm && ! deviation.sixth_section?.mpm_signed_at && deviation.sixth_section?.ampr_signed_at" @click.prevent="approveSixthSection('mpm')" class="bg-blue-700 hover:bg-blue-900 rounded-md float-right">Paraf Manager Pemastian Mutu</Button>
								<Button v-if="! isAmpr && ! isMpm && ! deviation.sixth_section?.spkp_signed_at" @click.prevent="submitSixthSection()" class="bg-green-700 hober:bg-green-900 float-right mr-2">Simpan Verifikasi</Button>
							</div>
						</div> -->
            <!-- section 6 -->

          </div>
          </div>
        </div>
        <Link :href="route('working-reports.index')">
          <Button class="bg-gray-700 hover:bg-gray-900 rounded-md float-right">
            <Icon name="caret-left" />
            <p class="uppercase font-semibold">
              {{ __('kembali') }}
            </p>
          </Button>
        </Link> 
        </div>
      </template>
    </Card>
    
    <!-- <Modal :show="open">
      <form
        @submit.prevent="submit4"
        class="w-full max-w-5xl h-fit shadow-xl" 
        enctype="multipart/form-data"
      >
        <Card class="bg-gray-50 dark:bg-gray-700 dark:text-gray-100">
          <template #header>
            <div class="flex items-center justify-between bg-gray-200 dark:bg-gray-800 p-2">
              <div class="flex items-center bg-gray-200 dark:bg-gray-800 p-2 font-semibold">
                <p>Tambah CA/PA</p>
              </div>
              <Close @click.prevent="close" />
            </div>
          </template>

          <template #body>
            <div class="flex flex-col space-y-4 p-4">       
						<h4 class="text-center font-semibold">Rencana CAPA</h4>
              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="description" class="w-1/3">
                    {{ __('Tindakan Perbaikan / Pencegahan') }}
                  </label>

                  <Input
                    v-model="form4.description"
                    :placeholder="__('Tindakan Perbaikan / Pencegahan')"
                    type="text"
                  />
                </div>

                <InputError
                  :error="form4.errors.description"
                />
              </div>         
              
              <div class="flex flex-col space-y-2">
              <div class="flex items-center space-x-2">
                <label for="type" class="w-1/3">
                  {{ __('Tipe Tindakan') }}
                </label>
                
                <Select
                  v-model="form4.type"
                  :options="[
                    {label: 'Corrective Action (CA)', value: 'corrective'}, 
                    {label: 'Preventive Action (PA)', value: 'preventive'}
                  ]"
                  :searchable="false"
                  :placeholder="__('Tipe Tindakan')"
                  required
                />
              </div>

              <InputError
                :error="form4.errors.type"
              />
            </div>
            
            <div class="flex flex-col space-y-2">
              <div class="flex items-center space-x-2">
                <label for="due_date" class="w-1/3">
                  {{ __('Batas Waktu Penyelesaian') }}
                </label>

                <Input
                  v-model="form4.due_date"
                  :placeholder="__('Batas Waktu Penyelesaian')"
                  type="date"
                />
              </div>

              <InputError
                :error="form4.errors.due_date"
              />
            </div>  

            <div class="flex flex-col space-y-2">
              <div class="flex items-center space-x-2">
                <label for="pic_id" class="w-1/3">
                  {{ __('Penanggung Jawab') }}
                </label>
                
                <Select
                  v-model="form4.pic_id"
                  :options="users.map(user => ({
                    label: user.name,
                    value: user.id,
                  }))"
                  :searchable="true"
                  placeholder="Pilih Penanggung Jawab"
                  required
                />
              </div>

              <InputError
                :error="form4.errors.pic_id"
              />
            </div>  
            
            <div class="flex flex-col space-y-2">
              <div class="flex items-center space-x-2">
                <label for="others_pic" class="w-1/3">
                  {{ __('Penanggung Jawab Lain') }} <br>
                  <span class="text-sm text-gray-600">*opsional / jika tidak ada dalam list / PIC lebih dari satu</span>
                </label>

                <Input
                  v-model="form4.others_pic"
                  :placeholder="__('Penanggung Jawab Lain')"
                  type="text"
                />
              </div>

              <InputError
                :error="form4.errors.others_pic"
              />
            </div>
            <hr> 
            
						<h4 class="text-center font-semibold">Implementasi CAPA</h4>
						<h6 v-if="! form4.id" class="text-center text-gray-600 my-2">Implementasi dapat langsung diisi jika CA/PA sudah terlaksana.</h6>
            <div class="flex flex-col space-y-2">
              <div class="flex items-center space-x-2">
                <label for="implementation" class="w-1/3">
                  {{ __('Uraian Implementasi Tindakan') }} 
                </label>

                <Input
                  v-model="form4.implementation"
                  :placeholder="__('Uraian Implementasi Tindakan')"
                  type="text"
                />
              </div>

              <InputError
                :error="form4.errors.implementation"
              />
            </div> 

            <div class="flex flex-col space-y-2">
              <div class="flex items-center space-x-2">
                <label for="verified_by" class="w-1/3">
                  {{ __('Verifikasi Oleh') }}
                </label>
                
                <Select
                  v-model="form4.verified_by"
                  :options="users.filter(user => user.username == 's35' || user.username_atasan == 's35').map(user => ({
                    label: `${user.name} - ${user.jabatan}`,
                    value: user.id,
                  }))"
                  :searchable="true"
                  placeholder="Pilih Penanggung Jawab"
                />
              </div>

              <InputError
                :error="form4.errors.verified_by"
              />
            </div> 
        
          </div>
          </template>

          <template #footer>
            <div class="flex items-center justify-end space-x-2 bg-gray-200 dark:bg-gray-800 px-2 py-1">
                <Button @click.prevent="close" class="bg-gray-600">
                  <p class="uppercase font-semibold">
                    {{ __('Tutup') }}
                  </p>
                </Button>
                <ButtonGreen type="submit4">
                  <p class="uppercase font-semibold">
                  {{ __(form4.id ? 'ubah' : 'simpan') }}
                  </p>
                </ButtonGreen>
            </div>
          </template>
        </Card>
      </form>
    </Modal> -->

  </DashboardLayout>
</template>