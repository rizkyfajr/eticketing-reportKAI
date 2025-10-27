<script setup>
import { onMounted, onUnmounted, nextTick, ref, computed, toRaw } from 'vue'
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
import { result } from 'lodash'
// import moment from 'moment'; 

const props = defineProps({
    report: Object,
    checksheet: Array,
    checksheetday: Array,
    checksheetworkresult: Array,
    warmingup: Array,
    workresult: Array,
    machines: Array,
    regions: Array,
    users: Array,
    warmingup_user: Array,
    workresult_user: Array,
    masters: Array,
    results: Array,
})

const { user } = usePage().props.value

const form = useForm({
    id: props.report?.id || null,
    machine_id: props.report?.machine_id || null,
    region_id: props.report?.region_id || null,
    date: props.report?.date || null,
    has_trouble: props.report?.has_trouble || null,
    status: props.report?.status || null,
});

const form1 = useForm({
    id: props.checksheetday?.id || null,
    working_report_id: props.report?.id || null,
    no_seri: props.checksheetday?.no_seri || null,
    jenis: props.checksheetday?.jenis || null,
    jam_mesin: props.checksheetday?.jam_mesin || null,
    counter_pecok: props.checksheetday?.counter_pecok || null,
    kilometer_mesin: props.checksheetday?.kilometer_mesin || null,
    tanggal: props.checksheetday?.tanggal || null,
    lokasi: props.checksheetday?.lokasi || null,
    wilayah: props.checksheetday?.wilayah || null,
    region_id: props.checksheetday?.region_id || null,
    note: props.checksheetday?.note || null,
    results: props.results ?? [],
});

const form2 = useForm({
  id: props.checksheetworkresult?.id ?? null,
  working_report_id: props.report?.id || null,
  check_sheet_day_id: props.checksheetday?.id ?? null,
  catatan_gangguan: props.checksheetworkresult?.catatan_gangguan ?? '',
  lokasi_dan_jam1: props.checksheetworkresult?.lokasi_dan_jam1 ?? '',
  hu_hi_1: props.checksheetworkresult?.hu_hi_1 ?? '',
  jumlah_1: props.checksheetworkresult?.jumlah_1 ?? '',
  lokasi_dan_jam2: props.checksheetworkresult?.lokasi_dan_jam2 ?? '',
  hu_hi_2: props.checksheetworkresult?.hu_hi_2 ?? '',
  jumlah_2: props.checksheetworkresult?.jumlah_2 ?? '',
  lokasi_dan_jam3: props.checksheetworkresult?.lokasi_dan_jam3 ?? '',
  hu_hi_3: props.checksheetworkresult?.hu_hi_3 ?? '',
  jumlah_3: props.checksheetworkresult?.jumlah_3 ?? '',
  operator_by1: props.checksheetworkresult?.operator_by1 ?? '',
  operator_by2: props.checksheetworkresult?.operator_by2 ?? '',
  operator_by3: props.checksheetworkresult?.operator_by3 ?? '',
  operator_by4: props.checksheetworkresult?.operator_by4 ?? '',
  operator_at1: props.checksheetworkresult?.operator_at1 ?? '',
  operator_at2: props.checksheetworkresult?.operator_at2 ?? '',
  operator_at3: props.checksheetworkresult?.operator_at3 ?? '',
  operator_at4: props.checksheetworkresult?.operator_at4 ?? '',
  validasi1: props.checksheetworkresult?.validasi1 ?? '',
  validasi2: props.checksheetworkresult?.validasi2 ?? '',
  validasi3: props.checksheetworkresult?.validasi3 ?? '',
  validasi4: props.checksheetworkresult?.validasi4 ?? '',
});

// onMounted(() => {
//   console.log("Loaded Results:", props.checksheetworkresult);
// });

const form3 = useForm({
    id: props.warmingup?.id || null,
    working_report_id: props.report?.id || null,
    machine_id: props.warmingup?.machine_id || null,
    waktu_start_engine: props.warmingup?.waktu_start_engine || null,
    jam_kerja: props.warmingup?.jam_kerja || null,
    jam_mesin: props.warmingup?.jam_mesin || null,
    jam_genset: props.warmingup?.jam_genset || null,
    counter_pecok: props.warmingup?.counter_pecok || null,
    oddometer: props.warmingup?.oddometer || null,
    waktu_stop_engine: props.warmingup?.waktu_stop_engine || null,
    penggunaan_hsd: props.warmingup?.penggunaan_hsd || null,
    hsd_tersedia: props.warmingup?.hsd_tersedia || null,
    note: props.warmingup?.note || null,
    user_id: props.warmingup?.warmingup_user.map(warmingup_user => warmingup_user.user_id) || [],
});

// const form4 = useForm({
//     id: props.workresult?.id || null,
//     working_report_id: props.report?.id || null,
//     machine_id: props.workresult?.machine_id || null,
//     region_id: props.workresult?.region_id || null,
//     antara: props.workresult?.antara || null,
//     km_hm: props.workresult?.km_hm || null,
//     jumlah_msp: props.workresult?.jumlah_msp || null,
//     waktu_start_engine: props.workresult?.waktu_start_engine || null,
//     jam_luncuran: props.workresult?.jam_luncuran || null,
//     jam_kerja: props.workresult?.jam_kerja || null,
//     jam_mesin: props.workresult?.jam_mesin || null,
//     jam_genset: props.workresult?.jam_genset || null,
//     counter_pecok: props.workresult?.counter_pecok || null,
//     oddometer: props.workresult?.oddometer || null,
//     penggunaan_hsd: props.workresult?.penggunaan_hsd || null,
//     hsd_tersedia: props.workresult?.hsd_tersedia || null,
//     pengawal_id: props.workresult?.pengawal_id || null,
//     note: props.workresult?.note || null,
//     user_id: props.workresult?.workresult_user.map(workresult_user => workresult_user.user_id) || [],
// });

const form4 = useForm({
    id: props.workresult?.id || null,
    working_report_id: props.report?.id || null,
    machine_id: props.workresult?.machine_id || null,
    region_id: props.workresult?.region_id || null,
    tanggal: props.workresult?.tanggal || null,
    cuaca: props.workresult?.cuaca || null,
    wilayah: props.workresult?.wilayah || null,
    petak_jalan: props.workresult?.petak_jalan || null,
    jalur: props.workresult?.jalur || null,
    kelas_jalan: props.workresult?.kelas_jalan || null,
    kecepatan_lintas: props.workresult?.kecepatan_lintas || null,
    lokasi_awal1: props.workresult?.lokasi_awal1 || null,
    lokasi_akhir1: props.workresult?.lokasi_akhir1 || null,
    jumlah1: props.workresult?.jumlah1 || null,
    lokasi_awal2: props.workresult?.lokasi_awal2 || null,
    lokasi_akhir2: props.workresult?.lokasi_akhir2 || null,
    jumlah2: props.workresult?.jumlah2 || null,
    lokasi_awal3: props.workresult?.lokasi_awal3 || null,
    lokasi_akhir3: props.workresult?.lokasi_akhir3 || null,
    jumlah3: props.workresult?.jumlah3 || null,
    total_distance: props.workresult?.total_distance || null,
    no_wesel1: props.workresult?.no_wesel1 || null,
    km_hm1: props.workresult?.km_hm1 || null,
    jumlah_wesel1: props.workresult?.jumlah_wesel1 || null,
    no_wesel2: props.workresult?.no_wesel2 || null,
    km_hm2: props.workresult?.km_hm2 || null,
    jumlah_wesel2: props.workresult?.jumlah_wesel2 || null,
    no_wesel3: props.workresult?.no_wesel3 || null,
    km_hm3: props.workresult?.km_hm3 || null,
    jumlah_wesel3: props.workresult?.jumlah_wesel3 || null,
    total_wesel: props.workresult?.total_wesel || null,
    waktu_start_engine: props.workresult?.waktu_start_engine || null,
    jam_luncuran: props.workresult?.jam_luncuran || null,
    jam_kerja: props.workresult?.jam_kerja || null,
    jam_mesin: props.workresult?.jam_mesin || null,
    jam_genset: props.workresult?.jam_genset || null,
    waktu_stop_engine: props.workresult?.waktu_stop_engine || null,
    counter_pecok: props.workresult?.counter_pecok || null,
    oddometer: props.workresult?.oddometer || null,
    penggunaan_hsd: props.workresult?.penggunaan_hsd || null,
    penggunaan_hsd1: props.workresult?.penggunaan_hsd1 || null,
    hsd_tersedia: props.workresult?.hsd_tersedia || null,
    oddometer_hsd: props.workresult?.oddometer_hsd || null,
    pengawal_id: props.workresult?.pengawal_id || null,
    note: props.workresult?.note || null,
    operator_by1: props.workresult?.operator_by1 || null,
    operator_at1: props.workresult?.operator_at1 || null,
    operator_by2: props.workresult?.operator_by2 || null,
    operator_at2: props.workresult?.operator_at2 || null,
    operator_by3: props.workresult?.operator_by3 || null,
    operator_at3: props.workresult?.operator_at3 || null,
});

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

const submitchecksheetday = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form1.post(route('check-sheet-day.store', props.report.id), {
    onSuccess: () => {
      close();
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        title: 'Data berhasil disimpan!',
        timer: 1500,
        showConfirmButton: false,
      }).then(() => {
        window.location.reload()
      })
    },
    onError: () => {
      show();
      Swal.hideLoading();
      Swal.fire({
        title: 'Terjadi kesalahan!',
        text: 'Gagal menyimpan data.',
        icon: 'error',
      });
    },
  })
}

const updatechecksheetday = async () => {
  try {
    const response = await Swal.fire({
      title: 'Konfirmasi',
      html: `Apakah anda yakin akaan ubah data ini?`,
      icon: 'question',
      showCancelButton: true,
      showCloseButton: true,
    });

    if (response.isConfirmed) {
      Swal.fire({
        title: 'Sedang menyimpan data...',
        didOpen: () => {
          Swal.showLoading();
        },
        allowOutsideClick: false,
      });

      const result = await form1.patch(route('check-sheet-day.update', form1.id), {
        onSuccess: () => {
          close();
          Swal.showLoading();
          Swal.fire({
            title: 'Data berhasil diupdate!',
            icon: 'success',
          });
        },
        onError: () => {
          Swal.showLoading();
          Swal.fire({
            title: 'Terjadi kesalahan!',
            text: 'Gagal menyimpan data.',
            icon: 'error',
          });
        },
      });

      return result;
    }
  } catch (error) {
    console.error(error);
  }
};

const toggleResult = async (item, field) => {
  const previousValue = item[field];
  item[field] = item[field] == 1 ? 0 : 1;

  try {
    const response = await axios.post(route("checksheetday-results.autosave"), {
      check_sheet_day_id: form1.id,
      check_sheet_master_day_id: item.check_sheet_master_day_id,
      cek: item.cek ?? 0,
      tambahan: item.tambahan ?? 0,
      ganti: item.ganti ?? 0,
      kiri_depan: item.kiri_depan ?? '',
      kanan_depan: item.kanan_depan ?? '',
      keterangan: item.keterangan ?? '',
    });

    // console.log(`Autosaved ${field}: ${item[field]}`);

    Swal.fire({
      icon: "success",
      title: previousValue == null ? "Berhasil Disimpan" : "Berhasil Diperbarui",
      timer: 1000,
      showConfirmButton: false,
    });
  } catch (error) {
    console.error("Autosave failed:", error);
    Swal.fire("Error", "Gagal menyimpan data!", "error");
  }
};

const saveTextField = async (item) => {
  try {
    await axios.post(route("checksheetday-results.autosave"), {
      check_sheet_day_id: form1.id,
      check_sheet_master_day_id: item.check_sheet_master_day_id,
      cek: item.cek ?? 0,
      tambahan: item.tambahan ?? 0,
      ganti: item.ganti ?? 0,
      kiri_depan: item.kiri_depan ?? '',
      kanan_depan: item.kanan_depan ?? '',
      keterangan: item.keterangan ?? '',
    });

    Swal.fire({
      icon: "success",
      title: "Berhasil disimpan",
      timer: 1000,
      showConfirmButton: false,
    });
  } catch (error) {
    console.error("Autosave failed:", error);
    Swal.fire("Error", "Gagal menyimpan data!", "error");
  }
};

// const saveWorkResult = async () => {
//   try {
//     await axios.post(route("checksheet-workresult.autosave"), {
//       id: form2.id,
//       working_report_id: form2.working_report_id,
//       check_sheet_day_id: form2.check_sheet_day_id,
//       catatan_gangguan: form2.catatan_gangguan,
//       lokasi_dan_jam1: form2.lokasi_dan_jam1,
//       hu_hi_1: form2.hu_hi_1,
//       jumlah_1: form2.jumlah_1,
//       lokasi_dan_jam2: form2.lokasi_dan_jam2,
//       hu_hi_2: form2.hu_hi_2,
//       jumlah_2: form2.jumlah_2,
//       lokasi_dan_jam3: form2.lokasi_dan_jam3,
//       hu_hi_3: form2.hu_hi_3,
//       jumlah_3: form2.jumlah_3,
//       // operator_by1: form2.operator_by1,
//       // operator_by2: form2.operator_by2,
//       // operator_by3: form2.operator_by3,
//       // operator_by4: form2.operator_by4,
//       operator_by1: form2.operator_by1?.value ?? form2.operator_by1 ?? null,
//       operator_by2: form2.operator_by2?.value ?? form2.operator_by2 ?? null,
//       operator_by3: form2.operator_by3?.value ?? form2.operator_by3 ?? null,
//       operator_by4: form2.operator_by4?.value ?? form2.operator_by4 ?? null,
//       validasi1: form2.validasi1,
//       validasi2: form2.validasi2,
//       validasi3: form2.validasi3,
//       validasi4: form2.validasi4,
//     })

//     Swal.fire({
//       icon: "success",
//       title: "Berhasil disimpan otomatis",
//       timer: 1000,
//       showConfirmButton: false,
//     })
//   } catch (error) {
//     console.error("Autosave gagal:", error)
//     Swal.fire("Error", "Gagal menyimpan data!", "error")
//   }
// }

const submitchecksheetworkresult = async () => {
  try {
    await axios.post(route("checksheet-workresult.store"), form2)
    Swal.fire({
      icon: "success",
      title: "Data berhasil disimpan!",
      timer: 1500,
      showConfirmButton: false,
    })
  } catch (error) {
    console.error(error)
    Swal.fire("Gagal", "Terjadi kesalahan saat menyimpan data", "error")
  }
}

const updatechecksheetworkresult = async () => {
  try {
    const response = await Swal.fire({
      title: 'Konfirmasi',
      html: `Apakah anda yakin akan ubah data ini?`,
      icon: 'question',
      showCancelButton: true,
      showCloseButton: true,
      confirmButtonText: 'Ya, ubah',
      cancelButtonText: 'Batal',
    });

    if (!response.isConfirmed) return;

    Swal.fire({
      title: 'Sedang menyimpan data...',
      didOpen: () => {
        Swal.showLoading();
      },
      allowOutsideClick: false,
    });

    await form2.patch(route('checksheet-workresult.update', form2.id), {
      onSuccess: () => {
        Swal.fire({
          icon: 'success',
          title: 'Data berhasil diperbarui!',
          timer: 1500,
          showConfirmButton: false,
        });
      },
      onError: () => {
        Swal.fire({
          icon: 'error',
          title: 'Terjadi kesalahan!',
          text: 'Gagal menyimpan data.',
        });
      },
    });
  } catch (error) {
    console.error(error);
    Swal.fire({
      icon: 'error',
      title: 'Kesalahan!',
      text: 'Terjadi kesalahan tak terduga.',
    });
  }
};

const canApprove = computed(() => {
  const result = props.checksheetday?.checksheetworkresult
  if (!result || !user?.id) return false

  return (
    (result.operator_by1 === user.id && !result.operator_at1) ||
    (result.operator_by2 === user.id && !result.operator_at2) ||
    (result.operator_by3 === user.id && !result.operator_at3) ||
    (result.operator_by4 === user.id && !result.operator_at4)
  )
})

const approvechecksheetworkresult = async () => {
  const result = props.checksheetday?.checksheetworkresult
  if (!result) return

  const confirm = await Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda akan menyetujui data ini.",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, setujui",
    cancelButtonText: "Batal",
  })

  if (!confirm.isConfirmed) return 

  try {
    const res = await axios.post(route("checksheet-workresult.approve"), {
      id: result.id,
    })

    const now = new Date().toISOString()
    if (result.operator_by1 === user.id) result.operator_at1 = now
    if (result.operator_by2 === user.id) result.operator_at2 = now
    if (result.operator_by3 === user.id) result.operator_at3 = now
    if (result.operator_by4 === user.id) result.operator_at4 = now

    Swal.fire({
      icon: "success",
      title: res.data.message || "Berhasil disetujui!",
      timer: 1500,
      showConfirmButton: false,
    })
  } catch (error) {
    console.error(error)
    Swal.fire({
      icon: "error",
      title: "Gagal approve!",
      text: error.response?.data?.message || error.message || "Terjadi kesalahan saat menyetujui.",
    })
  }
}

const submitwarmingup = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form3.post(route('warming-up.store', props.report.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data berhasil disimpan.',
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

const updatewarmingup = async () => {
  try {
    const response = await Swal.fire({
      title: 'Konfirmasi',
      html: `Apakah anda yakin akaan ubah data ini?`,
      icon: 'question',
      showCancelButton: true,
      showCloseButton: true,
    });

    if (response.isConfirmed) {
      Swal.fire({
        title: 'Sedang menyimpan data...',
        didOpen: () => {
          Swal.showLoading();
        },
        allowOutsideClick: false,
      });

      const result = await form3.patch(route('warming-up.update', form3.id), {
        onSuccess: () => {
          close();
          Swal.showLoading();
          Swal.fire({
            title: 'Data berhasil diupdate!',
            icon: 'success',
          });
        },
        onError: () => {
          Swal.showLoading();
          Swal.fire({
            title: 'Terjadi kesalahan!',
            text: 'Gagal menyimpan data.',
            icon: 'error',
          });
        },
      });

      return result;
    }
  } catch (error) {
    console.error(error);
  }
};

const submitworkresult = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form4.post(route('work-results.store', props.report.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Laporan kerja berhasil disimpan.',
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

const updateworkresult = async () => {
  try {
    const response = await Swal.fire({
      title: 'Konfirmasi',
      html: `Apakah anda yakin akaan ubah data ini?`,
      icon: 'question',
      showCancelButton: true,
      showCloseButton: true,
    });

    if (response.isConfirmed) {
      Swal.fire({
        title: 'Sedang menyimpan data...',
        didOpen: () => {
          Swal.showLoading();
        },
        allowOutsideClick: false,
      });

      const result = await form4.patch(route('work-results.update', form4.id), {
        onSuccess: () => {
          close();
          Swal.showLoading();
          Swal.fire({
            title: 'Data berhasil diupdate!',
            icon: 'success',
          });
        },
        onError: () => {
          Swal.showLoading();
          Swal.fire({
            title: 'Terjadi kesalahan!',
            text: 'Gagal menyimpan data.',
            icon: 'error',
          });
        },
      });

      return result;
    }
  } catch (error) {
    console.error(error);
  }
};

const canApproveWorkResult = computed(() => {
  const workresult = props.report?.workresult
  if (!workresult || !user?.id) return false

  return (
    (workresult.operator_by1 === user.id && !workresult.operator_at1) ||
    (workresult.operator_by2 === user.id && !workresult.operator_at2) ||
    (workresult.operator_by3 === user.id && !workresult.operator_at3) 
  )
})

const approveworkresult = async () => {
  const workresult = props.report?.workresult
  if (!workresult) return

  const confirm = await Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda akan menyetujui data ini.",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, setujui",
    cancelButtonText: "Batal",
  })

  if (!confirm.isConfirmed) return 

  try {
    const res = await axios.post(route("workresult.approve"), {
      id: workresult.id,
    })

    const now = new Date().toISOString()
    if (workresult.operator_by1 === user.id) workresult.operator_at1 = now
    if (workresult.operator_by2 === user.id) workresult.operator_at2 = now
    if (workresult.operator_by3 === user.id) workresult.operator_at3 = now

    Swal.fire({
      icon: "success",
      title: res.data.message || "Berhasil disetujui!",
      timer: 1500,
      showConfirmButton: false,
    })
  } catch (error) {
    console.error(error)
    Swal.fire({
      icon: "error",
      title: "Gagal approve!",
      text: error.response?.data?.message || error.message || "Terjadi kesalahan saat menyetujui.",
    })
  }
}

const currentSection = ref('report');

const fetch = async (section = 'report', report) => {
  const reportId = typeof report === 'object' ? report.id : report;

  if (!reportId) {
    console.error('Report tidak ditemukan');
    return;
  }

  Swal.showLoading();

  try {
    const response = await axios.post(
      route('working-reports.fetch', reportId),
      { section }
    );
    const data = response.data;
    Swal.close();

    switch (section) {
      case 'report':
        form.first_section = data.first_section;
        break;
      case 'checksheetday':
        form1.checksheetday = data.checksheetday;
        break;
      case 'warmingup':
        form3.warmingup = data.warmingup;
        break;
      case 'uploadfoto':
        form2.uploadfoto = data.uploadfoto;
        break;
      case 'workresult':
        form4.workresult = data.workresult;
        break;
    }

    form.report = data.report;
    currentSection.value = section;
  } catch (error) {
    console.error(error);
    Swal.close();
  }
};

const shouldShowGroup = (item, index) => {
  if (index === 0) return true;
  return form1.results[index - 1].group_name !== item.group_name;
};

const getGroupCount = (groupName) => {
  return form1.results.filter(i => i.group_name === groupName).length;
};

const isLastInGroup = (index) => {
  const current = form1.results[index];
  const next = form1.results[index + 1];

  return !next || next.group_name !== current.group_name;
};

onMounted(() => {
  form.reported_date = new Date().toISOString().slice(0, 10);
});

const formatDate = (date) => {
  if (!date) return '-'
  return new Date(date).toOnlyIndonesianDate()
}

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
  
  <DashboardLayout :title="__('Working Order')">
    <div
      class="transition-all duration-300"
      :class="{
        'pl-1 md:pl-64': open,
      }"
    >
      <main class="p-0 py-0">
        <h2 class="font-bold">Working Order</h2>
        <p class="font-semibold">Halaman Detail Working Order </p>
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
            <Button class="bg-red-700 text-white-800 px-4 py-2 rounded-md hover:bg-red-500">
              <Icon name="warning" class="w-6 h-4"/> |
              <!-- {{ report.status }} -->Lapor Gangguan
            </Button>
          </div>
        </div>
      </template>

      <template #body>
      <div class="flex flex-col space-y-4 p-4">
          
        <div class="flex flex-col space-y-4 p-4">
          <!-- <div class="text-center">
            <label for="name" class="first-letter:capitalize font-bold">
              {{ __('Formulir Laporan Pekerjaan') }}<br>
              {{ __('Tanggal') }}
            </label>
          </div> -->

          <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-0">
            <div class="border border-gray-300 p-1 w-full" :class="{ 'bg-blue-600': currentSection === 'report' }">
              <a 
                href="#list-report" id="list-report-list" data-toggle="list" role="tab" aria-controls="report" class="list-group-item list-group-item-action active" 
                @click.prevent="fetch('report', report)" 
              >
                <p :class="{ 'text-white': currentSection === 'report' }">Working Order</p>
              </a>
            </div>

            <div class="border border-gray-300 p-1 w-full" :class="{ 'bg-blue-600': currentSection === 'checksheetday' }">
              <a 
                href="#list-checksheetday" id="list-checksheetday-list" data-toggle="list" role="tab" aria-controls="checksheetday"  class="list-group-item list-group-item-action d-flex justify-content-between" 
                :class="! report.sectionFiveOpen ? 'disabled' : ''"
                @click.prevent="fetch('checksheetday', report)" 
              >
                <p :class="{ 'text-white': currentSection === 'checksheetday' }">Check Sheet
                  <!-- <i v-if="!deviation.first_section?.ampr_responded_at" class="fas fa-info-circle" 
                    data-toggle="tooltip" title="Penyimpangan yang belum di registrasi dan di disposisi oleh AMPR tidak dapat mengakses Section 2.">
                  </i> -->
                </p>
              </a>
            </div>

            <div class="border border-gray-300 p-1 w-full" :class="{ 'bg-blue-600': currentSection === 'warmingup' }">
              <a 
                href="#list-warmingup" id="list-warmingup-list" data-toggle="list" role="tab" aria-controls="warmingup"  class="list-group-item list-group-item-action d-flex justify-content-between" 
                :class="! report.sectionFiveOpen ? 'disabled' : ''"
                @click.prevent="fetch('warmingup', report)" 
              >
                <p :class="{ 'text-white': currentSection === 'warmingup' }">Warming Up
                  <!-- <i v-if="!deviation.first_section?.ampr_responded_at" class="fas fa-info-circle" 
                    data-toggle="tooltip" title="Penyimpangan yang belum di registrasi dan di disposisi oleh AMPR tidak dapat mengakses Section 2.">
                  </i> -->
                </p>
              </a>
            </div>            

            <div class="border border-gray-300 p-1 w-full" :class="{ 'bg-blue-600': currentSection === 'uploadfoto' }">
              <!-- <a 
                href="#list-section2" id="list-section2-list" data-toggle="list" role="tab" aria-controls="section2" class="list-group-item list-group-item-action"
                :class="{ 'disabled': !deviation.first_section?.ampr_responded_at, 'active': currentSection === 'secondSection' }" 
                @click.prevent="!deviation.first_section?.ampr_responded_at ? null : fetch('secondSection', deviation)" 
              > -->
                <p :class="{ 'text-white': currentSection === 'uploadfoto' }">Upload Foto
                  <!-- <i v-if="!deviation.first_section?.ampr_responded_at" class="fas fa-info-circle" 
                    data-toggle="tooltip" title="Penyimpangan yang belum di registrasi dan di disposisi oleh AMPR tidak dapat mengakses Section 2.">
                  </i> -->
                </p>
              <!-- </a> -->
            </div>

            <div class="border border-gray-300 p-1 w-full" :class="{ 'bg-blue-600': currentSection === 'workresult' }">
              <a 
                href="#list-workresult" id="list-workresult-list" data-toggle="list" role="tab" aria-controls="workresult"  class="list-group-item list-group-item-action d-flex justify-content-between" 
                :class="! report.sectionFiveOpen ? 'disabled' : ''"
                @click.prevent="fetch('workresult', report)" 
              >
                <p>Work Result
                  <!-- <i v-if="! deviation.fourth_section?.plan_approved_at" class="fas fa-info-circle" data-toggle="tooltip" 
                    :data-original-title="deviation.sectionFiveNote">
                  </i> -->
                </p>
              </a>
            </div>
            <!-- END -->

          </div>
        </div>

        <div class="flex flex-col md:flex-row mt-2 md:space-y-0 md:space-x-0">
          <div class="border border-black p-2 w-full" id="list-section1" role="tabpanel" aria-labelledby="list-section1-list">
          <div class="flex flex-col space-y-2"> 
            
            <!-- section working report -->             
					  <div v-if="currentSection === 'report'" class="tab-pane fade show active p-2" id="list-report" role="tabpanel" aria-labelledby="list-report-list">
            <!-- <div v-if="! deviation.first_section.ampr_responded_at" class="tab-pane fade show active p-2" id="list-section1" role="tabpanel" aria-labelledby="list-section1-list"> -->
            <!-- <div class="tab-pane fade show active p-2" id="list-section1" role="tabpanel" aria-labelledby="list-section1-list"> -->
              <div class="flex flex-col space-y-4 p-4">
                
                <div class="row my-2">
                  <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                      <label for="machine_id" class="w-1/3">
                        {{ __('Nama Mesin') }}
                      </label>
                      
                        <div class="w-2/3">
                          <Select
                            v-model="form.machine_id"
                            :options="machines.map(machine => ({
                              label: `${machine.name} — ${machine.type}`,
                              value: machine.id,
                            }))"
                            :searchable="true"
                            placeholder="Pilih Mesin"
                            required
                          />
                        </div>
                    </div>

                    <InputError
                      :error="form.errors.machine_id"
                    />
                  </div> 
                </div>


                <div class="row my-2">
                  <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                      <label for="region_id" class="w-1/3">
                        {{ __('Nama Wilayah') }}
                      </label>
                      
                        <div class="w-2/3">
                          <Select
                            v-model="form.region_id"
                            :options="regions.map(region => ({
                              label: region.name,
                              value: region.id,
                            }))"
                            :searchable="true"
                            placeholder="Pilih Wilayah"
                            required
                          />
                        </div>
                    </div>

                    <InputError
                      :error="form.errors.region_id"
                    />
                  </div> 
                </div>

                <div class="row my-2">
                  <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                      <label for="date" class="w-1/3 lowercase first-letter:capitalize">
                        {{ __('Tanggal') }}
                      </label>
                      
                        <div class="w-2/3">
                          <Input
                            v-model="form.date"
                            :placeholder="__('Tanggal')"
                            type="datetime-local"
                          />
                        </div>
                        
                      <template>
                        <div class="w-2/3">
                          <div>{{ new Date(report?.date).toOnlyIndonesianDate() }}</div>
                        </div>
                      </template>

                    </div>
                    <InputError :error="form.errors.date" />
                  </div>
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

                <hr>
              </div>
            </div>
            <!-- section working report -->

            <!-- section checksheetday -->
            <div v-if="currentSection === 'checksheetday'" class="tab-pane fade p-2" id="list-checksheetday" role="tabpanel" aria-labelledby="list-checksheetday-list">

							<!-- Bagian Informasi Mesin -->
              <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="flex items-center">
                  <label class="w-1/3 font-semibold">Jenis / Tipe KPJR</label>
                  <!-- <input v-model="form1.upt_resor" type="text" placeholder="Jam Genset" class="w-2/3 border border-black px-1 py-0.5" /> -->
                  <Input v-model="form1.jenis" :placeholder="__('Jenis / Tipe KPJR')" required type="text" class="w-2/3" />
                  <InputError :error="form1.errors.jenis" />
                </div>

                <div class="flex items-center">
                  <label class="w-1/3 font-semibold">Hari / Tanggal</label>
                  <Input v-model="form1.tanggal" required type="date" class="w-2/3"/>
                  <InputError :error="form1.errors.tanggal" />
                </div>
                
                <div class="flex items-center">
                  <label class="w-1/3 font-semibold">Jam Mesin</label>
                  <Input v-model="form1.jam_mesin" :placeholder="__('Jam Mesin')" required type="time" step="1" class="w-2/3" />
                  <InputError :error="form1.errors.jam_mesin" />
                </div>

                <div class="flex items-center">
                  <label class="w-1/3 font-semibold">Lokasi Pelaksanaan</label>
                  <Input v-model="form1.lokasi" required type="text" :placeholder="__('Lokasi Pelaksanaan')" class="w-2/3"/>
                  <InputError :error="form1.errors.lokasi" />
                </div>

                <div class="flex items-center">
                  <label class="w-1/3 font-semibold">Counter Pecok</label>
                  <Input v-model="form1.counter_pecok" required type="number" :placeholder="__('Counter Pecok')" class="w-2/3"/>
                  <InputError :error="form1.errors.counter_pecok" />
                </div>

                <div class="flex items-center">
                  <label class="w-1/3 font-semibold">Wilayah Resort</label>
                  <Input v-model="form1.wilayah" required type="text" :placeholder="__('Wilayah Resort')" class="w-2/3"/>
                  <InputError :error="form1.errors.wilayah" />
                </div>

                <div class="flex items-center">
                  <label class="w-1/3 font-semibold">Kilometer Mesin</label>
                  <Input v-model="form1.kilometer_mesin" required type="number" :placeholder="__('Kilometer Mesin')" class="w-2/3"/>
                  <InputError :error="form1.errors.kilometer_mesin" />
                </div>

                <div class="flex items-center">
                  <label class="w-1/3 font-semibold">Daop / Divre</label>
                  
                  <div class="w-2/3">
                  <Select
                    v-model="form1.region_id"
                    :options="regions.map(region => ({
                      label: region.name,
                      value: region.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Daop / Divre"
                    required
                  />
                  <InputError :error="form1.errors.region_id" />
                  </div>
                </div>
                
                <div class="flex items-center">
                  <label class="w-1/3 font-semibold">No Seri</label>
                  <Input v-model="form1.no_seri" required type="number" :placeholder="__('No Seri')" class="w-2/3"/>
                  <InputError :error="form1.errors.no_seri" />
                </div>

              </div>
              
							<div class="d-flex justify-content-end mt-3">
							<!-- <div v-if="isComplianceMember || isMpm" class="d-flex justify-content-end mt-3"> -->
								<!-- <Button v-if="isSpkp && ! deviation.sixth_section?.spkp_signed_at" @click.prevent="approveSixthSection('spkp')" class="bg-blue-700 hover:bg-blue-900 rounded-md float-right">Paraf SPV Penyimpangan</Button>
								<Button v-if="isAmpr && ! deviation.sixth_section?.ampr_signed_at && deviation.sixth_section?.spkp_signed_at" @click.prevent="approveSixthSection('ampr')" class="bg-blue-700 hover:bg-blue-900 rounded-md float-right">Paraf Asman Pemenuhan Regulasi</Button>
								<Button v-if="isMpm && ! deviation.sixth_section?.mpm_signed_at && deviation.sixth_section?.ampr_signed_at" @click.prevent="approveSixthSection('mpm')" class="bg-blue-700 hover:bg-blue-900 rounded-md float-right">Paraf Manager Pemastian Mutu</Button> -->
								<Button v-if="!report.checksheetday?.id" class="bg-green-700 hover:bg-green-900 float-right mr-2" @click.prevent="submitchecksheetday()">Simpan</Button>
                <Button v-else class="bg-blue-700 hover:bg-blue-900 float-right mr-2" @click.prevent="updatechecksheetday()">Ubah</Button>
							</div>
              <br>
              <br>
              <hr>

              <!-- Tabel Unit Komponen -->
              <div class="overflow-x-auto">
                <table class="table-auto border-collapse border border-black w-full text-xs">
                  <thead class="bg-gray-300 text-black">
                    <tr>
                      <th colspan="4" rowspan="2" class="border border-black px-2 py-1 text-center bg-gray-200 font-bold">
                        Perawatan Harian
                      </th>
                      <th rowspan="2" class="border border-black px-2 py-1 text-center align-middle bg-gray-200 font-bold w-[60px] h-[10px] ">
                        <div class="rotate-[-90deg] origin-bottom whitespace-nowrap flex justify-center items-center h-full">Cek</div>
                      </th>
                      <th rowspan="2" class="border border-black px-2 py-1 text-center align-middle bg-gray-200 font-bold w-[45px] h-[10px] ">
                        <div class="rotate-[-90deg] origin-bottom whitespace-nowrap flex justify-center items-center h-full">Tambah</div>
                      </th>
                      <th rowspan="2" class="border border-black px-2 py-1 text-center align-middle bg-gray-200 font-bold w-[45px] h-[10px] ">
                        <div class="rotate-[-90deg] origin-bottom whitespace-nowrap flex justify-center items-center h-full">Ganti</div>
                      </th>
                      <th rowspan="2" class="border border-black px-2 py-1 text-center bg-gray-200 font-bold">
                        Nilai Rujukan
                      </th>
                      <th colspan="2" class="border border-black px-2 py-1 text-center bg-gray-200 font-bold">
                        Hasil Pemeriksaan
                      </th>
                      <th rowspan="2" class="border border-black px-2 py-1 text-center bg-gray-200 font-bold">
                        Sat.
                      </th>
                      <th rowspan="2" class="border border-black px-2 py-1 text-center bg-gray-200 font-bold">
                        App.
                      </th>
                    </tr>
                    <tr>
                      <!-- <th colspan="3" class="border border-black px-2 py-1 text-center bg-gray-200 font-bold w-12"></th> -->
                      <th class="border border-black px-2 py-1 text-center bg-gray-200 font-bold w-10">Kr/Dpn</th>
                      <th class="border border-black px-2 py-1 text-center bg-gray-200 font-bold w-10">Kn/Dpn</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr
                      v-for="(item, index) in form1.results"
                      :key="index"
                      :class="{'border-b-4 border-black': isLastInGroup(index)}"
                    >
                      <td
                        v-if="shouldShowGroup(item, index)"
                        :rowspan="getGroupCount(item.group_name)"
                        class="border border-black px-2 py-1 font-bold align-center bg-gray-200 w-[200px]"
                      >                          
                        <div class="rotate-[-90deg] origin-bottom whitespace-nowrap flex justify-center items-center h-full">{{ item.group_name }}</div>
                      </td>

                      <td class="border border-black px-2 py-1 text-center w-10">
                        {{ item.urutan }}
                      </td>

                      <td class="border border-black px-2 py-1 text-left w-64">
                        {{ item.komponen }}
                      </td>

                      <td class="border border-black px-2 py-1 w-36 text-center">
                        {{ item.rujukan }}
                      </td>

                      <td class="border border-black px-2 py-1 text-center">
                        <input
                          type="checkbox"
                          :checked="item.cek == 1"
                          @change="toggleResult(item, 'cek')"
                        />
                      </td>

                      <td class="border border-black px-2 py-1 text-center">
                        <input
                          type="checkbox"
                          :checked="item.tambahan == 1"
                          @change="toggleResult(item, 'tambahan')"
                        />
                      </td>

                      <td class="border border-black px-2 py-1 text-center">
                        <input
                          type="checkbox"
                          :checked="item.ganti == 1"
                          @change="toggleResult(item, 'ganti')"
                        />
                      </td>

                      <td class="border border-black px-2 py-1 text-center italic w-24">
                        {{ item.nilai_rujukan }}
                      </td>

                      <td class="border border-black px-2 py-1 text-center w-24">
                        <input
                          v-model="item.kiri_depan"
                          type="text"
                          placeholder="...."
                          class="w-full focus:ring-0 text-center"
                          @change="saveTextField(item)"
                        />
                      </td>

                      <td class="border border-black px-2 py-1 text-center w-24">
                        <input
                          v-model="item.kanan_depan"
                          type="text"
                          placeholder="...."
                          class="w-full focus:ring-0 text-center"
                          @change="saveTextField(item)"
                        />
                      </td>

                      <td class="border border-black px-2 py-1 text-center w-24">
                        {{ item.satuan }}
                      </td>

                      <td class="border border-black px-2 py-1 text-center w-24">
                        <input
                          v-model="item.keterangan"
                          type="text"
                          placeholder="...."
                          @change="saveTextField(item)"
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>
                <br><br>
                
                <!-- Tabel Catatan -->
                <tfoot>
                  <tr>
                    <td colspan="9" class="border border-black align-top px-2 py-1 font-semibold text-sm w-[40%]">
                      Catatan riwayat gangguan :
                      <textarea
                        v-model="form2.catatan_gangguan"
                        class="w-full border-none focus:ring-0 text-sm resize-none mt-1"
                        rows="9"
                        placeholder="Tuliskan catatan riwayat gangguan di sini..."
                        @change="saveWorkResult"
                      ></textarea>
                    </td>

                    <td colspan="4" class="border border-black align-top px-0 py-0 w-[30%]">
                      <table class="w-full border-collapse">
                        <thead>
                          <tr>
                            <th colspan="6" class="border border-black bg-gray-200 text-center font-semibold py-1">
                              Hasil Kerja:
                            </th>
                          </tr>
                          <tr class="bg-gray-100 text-center font-semibold text-sm">
                            <th colspan="3" class="border border-black px-2 py-1 w-[70%]">Lokasi dan Jam beroperasi</th>
                            <th class="border border-black px-2 py-1 w-[15%]">hu / hi</th>
                            <th class="border border-black px-2 py-1 w-[15%]">Jumlah</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="3" class="border border-black px-2 py-1 text-left">
                              <input
                                v-model="form2.lokasi_dan_jam1"
                                type="text"
                                class="w-full border-none focus:ring-0 text-sm"
                                placeholder="Isi lokasi dan jam beroperasi"
                                @change="saveWorkResult"
                              />
                            </td>
                            <td class="border border-black px-2 py-1 text-center">
                              <input
                                v-model="form2.hu_hi_1"
                                type="text"
                                placeholder="...."
                                class="w-full border-none focus:ring-0 text-center text-sm"
                                @change="saveWorkResult"
                              />
                            </td>
                            <td class="border border-black px-2 py-1 text-center">
                              <input
                                v-model="form2.jumlah_1"
                                type="text"
                                placeholder="...."
                                class="w-full border-none focus:ring-0 text-center text-sm"
                                @change="saveWorkResult"
                              />
                            </td>
                          </tr>
                          <tr>
                            <td colspan="3" class="border border-black px-2 py-1 text-left">
                              <input
                                v-model="form2.lokasi_dan_jam2"
                                type="text"
                                class="w-full border-none focus:ring-0 text-sm"
                                placeholder="Isi lokasi dan jam beroperasi"
                                @change="saveWorkResult"
                              />
                            </td>
                            <td class="border border-black px-2 py-1 text-center">
                              <input
                                v-model="form2.hu_hi_2"
                                type="text"
                                placeholder="...."
                                class="w-full border-none focus:ring-0 text-center text-sm"
                                @change="saveWorkResult"
                              />
                            </td>
                            <td class="border border-black px-2 py-1 text-center">
                              <input
                                v-model="form2.jumlah_2"
                                type="text"
                                placeholder="...."
                                class="w-full border-none focus:ring-0 text-center text-sm"
                                @change="saveWorkResult"
                              />
                            </td>
                          </tr>
                          <tr>
                            <td colspan="3" class="border border-black px-2 py-1 text-left">
                              <input
                                v-model="form2.lokasi_dan_jam3"
                                type="text"
                                class="w-full border-none focus:ring-0 text-sm"
                                placeholder="Isi lokasi dan jam beroperasi"
                                @change="saveWorkResult"
                              />
                            </td>
                            <td class="border border-black px-2 py-1 text-center">
                              <input
                                v-model="form2.hu_hi_3"
                                type="text"
                                placeholder="...."
                                class="w-full border-none focus:ring-0 text-center text-sm"
                                @change="saveWorkResult"
                              />
                            </td>
                            <td class="border border-black px-2 py-1 text-center">
                              <input
                                v-model="form2.jumlah_2"
                                type="text"
                                placeholder="...."
                                class="w-full border-none focus:ring-0 text-center text-sm"
                                @change="saveWorkResult"
                              />
                            </td>
                          </tr>

                          <tr class="bg-gray-200 text-center font-semibold text-sm">
                            <td colspan="2" class="border border-black py-1">Operator</td>
                            <td class="border border-black py-1">Paraf.</td>
                            <td colspan="3" class="border border-black py-1">Validasi:</td>
                          </tr>

                          <tr class="bg-gray-200 text-center font-semibold text-sm">
                            <td colspan="2" class="border border-black text-left w-64">
                              <Select
                                v-model="form2.operator_by1"
                                :options="users.filter(user => user.id !== 1).map(user => ({
                                  label: user.name,
                                  value: user.id,
                                }))"
                                :searchable="true"
                                placeholder="Pilih"
                                class="w-full border-none focus:ring-0 text-center"
                                @change="saveWorkResult"
                              />
                            </td>

                            <td class="border border-black text-center">
                              Disetujui Pada : <br>
                              {{ formatDate(report.checksheetday?.checksheetworkresult?.operator_at1) }}
                            </td>

                            <td colspan="3" class="border border-black text-center">
                              <input
                                v-model="form2.validasi1"
                                type="text"
                                class="w-full border-none focus:ring-0 text-center"
                                placeholder="Validasi"
                                @change="saveWorkResult"
                              />
                            </td>
                          </tr>

                          <tr class="bg-gray-200 text-center font-semibold text-sm">
                            <td colspan="2" class="border border-black text-left w-64">
                              <Select
                                v-model="form2.operator_by2"
                                :options="users.filter(user => user.id !== 1).map(user => ({
                                  label: user.name,
                                  value: user.id,
                                }))"
                                :searchable="true"
                                placeholder="Pilih"
                                class="w-full border-none focus:ring-0 text-center"
                                @change="saveWorkResult"
                              />
                            </td>

                            <td class="border border-black text-center">
                              Disetujui Pada : <br>
                              {{ formatDate(report.checksheetday?.checksheetworkresult?.operator_at2) }}
                            </td>

                            <td colspan="3" class="border border-black text-center">
                              <input
                                v-model="form2.validasi2"
                                type="text"
                                class="w-full border-none focus:ring-0 text-center"
                                placeholder="Validasi"
                                @change="saveWorkResult"
                              />
                            </td>
                          </tr>

                          <tr class="bg-gray-200 text-center font-semibold text-sm">
                            <td colspan="2" class="border border-black text-left w-64">
                              <Select
                                v-model="form2.operator_by3"
                                :options="users.filter(user => user.id !== 1).map(user => ({
                                  label: user.name,
                                  value: user.id,
                                }))"
                                :searchable="true"
                                placeholder="Pilih"
                                class="w-full border-none focus:ring-0 text-center"
                                @change="saveWorkResult"
                              />
                            </td>

                            <td class="border border-black text-center">
                              Disetujui Pada : <br>
                              {{ formatDate(report.checksheetday?.checksheetworkresult?.operator_at3) }}
                            </td>

                            <td colspan="3" class="border border-black text-center">
                              <input
                                v-model="form2.validasi3"
                                type="text"
                                class="w-full border-none focus:ring-0 text-center"
                                placeholder="Validasi"
                                @change="saveWorkResult"
                              />
                            </td>
                          </tr>

                          <tr class="bg-gray-200 text-center font-semibold text-sm">
                            <td colspan="2" class="border border-black text-left w-64">
                              <Select
                                v-model="form2.operator_by4"
                                :options="users.filter(user => user.id !== 1).map(user => ({
                                  label: user.name,
                                  value: user.id,
                                }))"
                                :searchable="true"
                                placeholder="Pilih"
                                class="w-full border-none focus:ring-0 text-center"
                                @change="saveWorkResult"
                              />
                            </td>

                            <td class="border border-black text-center">
                              Disetujui Pada : <br>
                              {{ formatDate(report.checksheetday?.checksheetworkresult?.operator_at4) }}
                            </td>

                            <td colspan="3" class="border border-black text-center">
                              <input
                                v-model="form2.validasi4"
                                type="text"
                                class="w-full border-none focus:ring-0 text-center"
                                placeholder="Validasi"
                                @change="saveWorkResult"
                              />
                            </td>
                          </tr>

                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tfoot>
                
                <div class="d-flex justify-content-end mt-3">
                  <Button v-if="!report.checksheetday?.checksheetworkresult?.id" class="bg-green-700 hover:bg-green-900 float-right mr-2" @click.prevent="submitchecksheetworkresult()">Simpan</Button>
                  <Button v-if="report.checksheetday?.checksheetworkresult?.id" class="bg-blue-700 hover:bg-blue-900 float-right mr-2" @click.prevent="updatechecksheetworkresult()">Ubah</Button>
                  <Button v-if="canApprove" class="bg-blue-700 hover:bg-blue-900 float-right mr-2" @click.prevent="approvechecksheetworkresult()">Approve</Button>
                </div>

              </div>
						</div>
            <!-- section checksheetday -->

            <!-- section warmingup -->
            <div v-if="currentSection === 'warmingup'" class="tab-pane fade p-2" id="list-warmingup" role="tabpanel" aria-labelledby="list-warmingup-list">

							<div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="machine_id" class="w-1/3">
                      {{ __('Nama Mesin') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Select
                          v-model="form3.machine_id"
                          :options="machines.map(machine => ({
                            label: `${machine.name} — ${machine.type}`,
                            value: machine.id,
                          }))"
                          :searchable="true"
                          placeholder="Pilih Mesin"
                          required
                        />
                      </div>
                  </div>

                  <InputError
                    :error="form3.errors.machine_id"
                  />
                </div> 
              </div>

							<div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="waktu_start_engine" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Waktu Start Engine') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form3.waktu_start_engine"
                          :placeholder="__('Waktu Start Engine')"
                          type="datetime-local"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.warmingup?.waktu_start_engine }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form3.errors.waktu_start_engine" />
                </div>
              </div>

							<div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="jam_kerja" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Jam Kerja') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form3.jam_kerja"
                          :placeholder="__('Jam Kerja')"
                          type="time"
                          step="1"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.warmingup?.jam_kerja }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form3.errors.jam_kerja" />
                </div>
              </div>

							<div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="jam_mesin" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Jam Mesin') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form3.jam_mesin"
                          :placeholder="__('Jam Mesin')"
                          type="time"
                          step="1"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.warmingup?.jam_mesin }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form3.errors.jam_mesin" />
                </div>
              </div>

							<div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="jam_genset" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Jam Genset') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form3.jam_genset"
                          :placeholder="__('Jam Genset')"
                          type="time"
                          step="1"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.warmingup?.jam_genset }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form3.errors.jam_genset" />
                </div>
              </div>

							<div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="counter_pecok" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Counter Pecok') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form3.counter_pecok"
                          :placeholder="__('Counter Pecok')"
                          type="number"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.warmingup?.counter_pecok }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form3.errors.counter_pecok" />
                </div>
              </div>

							<div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="oddometer" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Oddo Meter') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form3.oddometer"
                          :placeholder="__('Oddo Meter')"
                          type="number"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.warmingup?.oddometer }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form3.errors.oddometer" />
                </div>
              </div>

							<div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="waktu_stop_engine" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Waktu Stop Engine') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form3.waktu_stop_engine"
                          :placeholder="__('Waktu Stop Engine')"
                          type="datetime-local"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.warmingup?.waktu_stop_engine }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form3.errors.waktu_stop_engine" />
                </div>
              </div>

							<div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="penggunaan_hsd" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Penggunaan HSD') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form3.penggunaan_hsd"
                          :placeholder="__('Penggunaan HSD')"
                          type="number"
                          step="0.01"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.warmingup?.penggunaan_hsd }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form3.errors.penggunaan_hsd" />
                </div>
              </div>

							<div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="hsd_tersedia" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('HSD Tersedia') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form3.hsd_tersedia"
                          :placeholder="__('HSD Tersedia')"
                          type="number"
                          step="0.01"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.warmingup?.hsd_tersedia }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form3.errors.hsd_tersedia" />
                </div>
              </div>

              <div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="user_id" class="w-1/3">
                      {{ __('Crew') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Select
                          v-model="form3.user_id"
                          :options="users.filter(user => user.id !== 1).map(user => ({
                            label: user.name,
                            value: user.id,
                          }))"
                          :searchable="true"
                          mode="tags"
                          placeholder="Pilih Crew"
                          required
                        />
                      </div>
                  </div>

                  <InputError
                    :error="form3.errors.user_id"
                  />
                </div> 
              </div>

							<div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="note" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Keterangan') }}
                    </label>
                    
                      <div class="w-2/3">
                        <TextArea
                          v-model="form3.note"
                          :placeholder="__('Keterangan')"
                          type="text"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.warmingup?.note }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form3.errors.note" />
                </div>
              </div>
							
							<div class="d-flex justify-content-end mt-3">
								<Button v-if="!report.warmingup?.id" class="bg-green-700 hover:bg-green-900 float-right mr-2" @click.prevent="submitwarmingup()">Simpan</Button>
                <Button v-else class="bg-blue-700 hover:bg-blue-900 float-right mr-2" @click.prevent="updatewarmingup()">Ubah</Button>

							</div>
						</div>
            <!-- section warmingup -->

            <!-- section workresult -->
            <div v-if="currentSection === 'workresult'" class="tab-pane fade p-2" id="list-workresult" role="tabpanel" aria-labelledby="list-warmingup-list">

              <div class="grid grid-cols-2 gap-1 mb-1 rounded-md text-sm">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="machine_id" class="w-1/3">
                      {{ __('Nama Mesin') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Select
                          v-model="form4.machine_id"
                          :options="machines.map(machine => ({
                            label: `${machine.name} — ${machine.type} — ${machine.no_sarana}`,
                            value: machine.id,
                          }))"
                          :searchable="true"
                          placeholder="Pilih Mesin"
                          required
                        />
                      </div>
                  </div>

                  <InputError
                    :error="form4.errors.machine_id"
                  />
                </div> 

                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="tanggal" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Hari/Tanggal') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.tanggal"
                          :placeholder="__('Hari/Tanggal')"
                          type="datetime-local"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.tanggal }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.tanggal" />
                </div>

                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="cuaca" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('cuaca') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.cuaca"
                          :placeholder="__('cuaca')"
                          type="text"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.cuaca }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.cuaca" />
                </div>
              </div>
              <br>

              <div class="font-bold rounded-md text-sm">A. DATA WILAYAH</div>
              <div class="grid grid-cols-2 gap-1 mb-1 rounded-md text-sm">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="wilayah" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('wilayah resor') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.wilayah"
                          :placeholder="__('wilayah resor')"
                          type="text"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.wilayah }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.wilayah" />
                </div>
              </div>

              <div class="grid grid-cols-2 gap-1 mb-1 rounded-md text-sm">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="petak_jalan" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('petak jalan') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.petak_jalan"
                          :placeholder="__('petak jalan')"
                          type="text"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.petak_jalan }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.petak_jalan" />
                </div>
              </div>

              <div class="grid grid-cols-2 gap-1 mb-1 rounded-md text-sm">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="jalur" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('jalur') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.jalur"
                          :placeholder="__('jalur')"
                          type="text"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.jalur }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.jalur" />
                </div>
              </div>

              <div class="grid grid-cols-2 gap-1 mb-1 rounded-md text-sm">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="kelas_jalan" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('kelas jalan') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.kelas_jalan"
                          :placeholder="__('kelas jalan')"
                          type="text"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.kelas_jalan }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.kelas_jalan" />
                </div>
              </div>

              <div class="grid grid-cols-2 gap-1 mb-1 rounded-md text-sm">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="kecepatan_lintas" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('kecepatan lintas') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.kecepatan_lintas"
                          :placeholder="__('kecepatan lintas')"
                          type="text"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.kecepatan_lintas }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.kecepatan_lintas" />
                </div>
              </div>
              <br>

              <!-- <div class="row my-2">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="region_id" class="w-1/3">
                      {{ __('Nama Wilayah') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Select
                          v-model="form4.region_id"
                          :options="regions.map(region => ({
                            label: region.name,
                            value: region.id,
                          }))"
                          :searchable="true"
                          placeholder="Pilih Wilayah"
                          required
                        />
                      </div>
                  </div>

                  <InputError
                    :error="form4.errors.region_id"
                  />
                </div> 
              </div> -->

              <div class="rounded-md text-sm">
                <div class="font-bold mb-2">B. DATA PEKERJAAN</div>

                <div class="space-y-2">
                  <!-- Baris 1 -->
                  <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                    <label class="md:col-span-2">Lokasi (Km/Hm)</label>

                    <div class="md:col-span-6 flex flex-wrap items-center gap-1">
                      <Input v-model="form4.lokasi_awal1" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[100px]" />
                      <span class="text-center">s/d</span>
                      <Input v-model="form4.lokasi_akhir1" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[100px]" />
                    </div>

                    <div class="md:col-span-4 flex justify-between md:justify-end items-center gap-1">
                      <span class="whitespace-nowrap">Jumlah (Km/Hm)</span>
                      <span>:</span>
                      <Input v-model="form4.jumlah1" :placeholder="__('Jumlah Km/Hm')" type="text" class="dot-input flex-1 min-w-[150px] text-right" />
                      <span>M'sp</span>
                    </div>
                  </div>

                  <!-- Baris 2 -->
                  <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                    <label class="md:col-span-2 invisible">Lokasi (Km/Hm)</label>

                    <div class="md:col-span-6 flex flex-wrap items-center gap-1">
                      <Input v-model="form4.lokasi_awal2" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[100px]" />
                      <span class="text-center">s/d</span>
                      <Input v-model="form4.lokasi_akhir2" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[100px]" />
                    </div>

                    <div class="md:col-span-4 flex justify-between md:justify-end items-center gap-1">
                      <span class="whitespace-nowrap">Jumlah (Km/Hm)</span>
                      <span>:</span>
                      <Input v-model="form4.jumlah2" :placeholder="__('Jumlah Km/Hm')" type="text" class="dot-input flex-1 min-w-[150px] text-right" />
                      <span>M'sp</span>
                    </div>
                  </div>

                  <!-- Baris 3 -->
                  <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                    <label class="md:col-span-2 invisible">Lokasi (Km/Hm)</label>

                    <div class="md:col-span-6 flex flex-wrap items-center gap-2">
                      <Input v-model="form4.lokasi_awal3" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[100px]" />
                      <span class="text-center">s/d</span>
                      <Input v-model="form4.lokasi_akhir3" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[100px]" />
                    </div>

                    <div class="md:col-span-4 flex justify-between md:justify-end items-center gap-1">
                      <span class="whitespace-nowrap">Jumlah (Km/Hm)</span>
                      <span>:</span>
                      <Input v-model="form4.jumlah3" :placeholder="__('Jumlah Km/Hm')" type="text" class="dot-input flex-1 min-w-[150px] text-right" />
                      <span>M'sp</span>
                    </div>
                  </div>

                  <!-- Baris Total -->
                  <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                    <label class="md:col-span-2 invisible">Lokasi (Km/Hm)</label>
                    <div class="md:col-span-6"></div>

                    <div class="md:col-span-4 flex justify-between md:justify-end items-center gap-1 font-semibold">
                      <span class="whitespace-nowrap">Total (Km/Hm)</span>
                      <span>:</span>
                      <Input v-model="form4.total_distance" :placeholder="__('Total Km/Hm')" type="text" class="dot-input flex-1 min-w-[150px] text-right font-semibold" />
                      <span>M'sp</span>
                    </div>
                  </div>
                </div>
              </div>
              <br><br>

              <div class="rounded-md text-sm">
                <div class="space-y-2">
                  <!-- Baris 1 -->
                  <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                    <label class="md:col-span-2">No Wesel</label>

                    <div class="md:col-span-6 flex flex-wrap items-center gap-2">
                      <Input v-model="form4.no_wesel1" :placeholder="__('No Wesel')" type="text" class="dot-input flex-1 min-w-[100px]" />
                      <span class="text-center">Km/Hm : </span>
                      <Input v-model="form4.km_hm1" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[100px]" />
                    </div>

                    <div class="md:col-span-4 flex justify-between md:justify-end items-center gap-1">
                      <span class="whitespace-nowrap">Jumlah Wesel</span>
                      <span>:</span>
                      <Input v-model="form4.jumlah_wesel1" :placeholder="__('Jumlah Wesel')" type="text" class="dot-input flex-1 min-w-[150px] text-right" />
                      <span>M'sp</span>
                    </div>
                  </div>

                  <!-- Baris 2 -->
                  <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                    <label class="md:col-span-2 invisible">No Wesel</label>

                    <div class="md:col-span-6 flex flex-wrap items-center gap-2">
                      <Input v-model="form4.no_wesel2" :placeholder="__('No Wesel')" type="text" class="dot-input flex-1 min-w-[100px]" />
                      <span class="text-center">Km/Hm : </span>
                      <Input v-model="form4.km_hm2" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[100px]" />
                    </div>

                    <div class="md:col-span-4 flex justify-between md:justify-end items-center gap-1">
                      <span class="whitespace-nowrap">Jumlah Wesel</span>
                      <span>:</span>
                      <Input v-model="form4.jumlah_wesel2" :placeholder="__('Jumlah Wesel')" type="text" class="dot-input flex-1 min-w-[150px] text-right" />
                      <span>M'sp</span>
                    </div>
                  </div>

                  <!-- Baris 3 -->
                  <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                    <label class="md:col-span-2 invisible">No Wesel</label>

                    <div class="md:col-span-6 flex flex-wrap items-center gap-2">
                      <Input v-model="form4.no_wesel3" :placeholder="__('No Wesel')" type="text" class="dot-input flex-1 min-w-[100px]" />
                      <span class="text-center">Km/Hm : </span>
                      <Input v-model="form4.km_hm3" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[100px]" />
                    </div>

                    <div class="md:col-span-4 flex justify-between md:justify-end items-center gap-1">
                      <span class="whitespace-nowrap">Jumlah Wesel</span>
                      <span>:</span>
                      <Input v-model="form4.jumlah_wesel3" :placeholder="__('Jumlah Wesel')" type="text" class="dot-input flex-1 min-w-[150px] text-right" />
                      <span>M'sp</span>
                    </div>
                  </div>

                  <!-- Baris Total -->
                  <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                    <label class="md:col-span-2 invisible">No Wesel</label>
                    <div class="md:col-span-6"></div>

                    <div class="md:col-span-4 flex justify-between md:justify-end items-center gap-1 font-semibold">
                      <span class="whitespace-nowrap">Total Wesel</span>
                      <span>:</span>
                      <Input v-model="form4.total_wesel" :placeholder="__('Total Wesel')" type="text" class="dot-input flex-1 min-w-[150px] text-right font-semibold" />
                      <span>M'sp</span>
                    </div>
                  </div>
                </div>
              </div>
              <br>

              <div class="grid grid-cols-2 gap-1 mb-4 text-sm">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="waktu_start_engine" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('waktu Start Engine') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.waktu_start_engine"
                          :placeholder="__('waktu Start Engine')"
                          type="datetime-local"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.waktu_start_engine }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.waktu_start_engine" />
                </div>
                
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="waktu_stop_engine" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Waktu Stop Engine') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.waktu_stop_engine"
                          :placeholder="__('Waktu Stop Engine')"
                          type="datetime-local"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.waktu_stop_engine }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.waktu_stop_engine" />
                </div>
                
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="jam_luncuran" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Jam Luncuran') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.jam_luncuran"
                          :placeholder="__('Jam Luncuran')"
                          type="time"
                          step="1"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.jam_luncuran }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.jam_luncuran" />
                </div>
                
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="counter_pecok" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Counter Pecok') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.counter_pecok"
                          :placeholder="__('Counter Pecok')"
                          type="number"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.counter_pecok }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.counter_pecok" />
                </div>
                
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="jam_kerja" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Jam Kerja') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.jam_kerja"
                          :placeholder="__('Jam Kerja')"
                          type="time"
                          step="1"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.jam_kerja }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.jam_kerja" />
                </div>
                
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="oddometer" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Oddo Meter') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.oddometer"
                          :placeholder="__('Oddo Meter')"
                          type="number"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.oddometer }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.oddometer" />
                </div>
                
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="jam_mesin" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Jam Mesin') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.jam_mesin"
                          :placeholder="__('Jam Mesin')"
                          type="time"
                          step="1"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.jam_mesin }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.jam_mesin" />
                </div>
              </div>
                
              <div class="grid grid-cols-2 gap-1 mb-4 text-sm">
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center space-x-2">
                    <label for="jam_genset" class="w-1/3 lowercase first-letter:capitalize">
                      {{ __('Jam Generator') }}
                    </label>
                    
                      <div class="w-2/3">
                        <Input
                          v-model="form4.jam_genset"
                          :placeholder="__('Jam Generator')"
                          type="time"
                          step="1"
                        />
                      </div>
                      
                    <template>
                      <div class="w-2/3">
                        <span>{{ report.workingresult?.jam_genset }}</span>
                      </div>
                    </template>

                  </div>
                  <InputError :error="form4.errors.jam_genset" />
                </div>

              </div>
              <br>

              <div class="rounded-md text-sm">
              <div class="font-bold mb-2">C. BAHAN BAKAR</div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                  <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                      <label for="penggunaan_hsd" class="w-1/3 lowercase first-letter:capitalize">
                        {{ __('Pemakaian HSD') }}
                      </label>
                      
                        <div class="w-2/3">
                          <Input
                            v-model="form4.penggunaan_hsd"
                            :placeholder="__('Pemakaian HSD')"
                            type="number"
                            step="0.01"
                          />
                        </div>
                        
                      <template>
                        <div class="w-2/3">
                          <span>{{ report.workingresult?.penggunaan_hsd }}</span>
                        </div>
                      </template>

                    </div>
                    <InputError :error="form4.errors.penggunaan_hsd" />
                  </div>

                  <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                      <label for="hsd_tersedia" class="w-1/3 lowercase first-letter:capitalize">
                        {{ __('HSD Tersedia') }}
                      </label>
                      
                        <div class="w-2/3">
                          <Input
                            v-model="form4.hsd_tersedia"
                            :placeholder="__('HSD Tersedia')"
                            type="number"
                            step="0.01"
                          />
                        </div>
                        
                      <template>
                        <div class="w-2/3">
                          <span>{{ report.workingresult?.hsd_tersedia }}</span>
                        </div>
                      </template>

                    </div>
                    <InputError :error="form4.errors.hsd_tersedia" />
                  </div>

                  <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                      <label for="penggunaan_hsd1" class="w-1/3 lowercase first-letter:capitalize">
                        <!-- {{ __('HSD Tersedia') }} -->
                      </label>
                      
                        <div class="w-2/3">
                          <Input
                            v-model="form4.penggunaan_hsd1"
                            :placeholder="__('HSD Tersedia')"
                            type="number"
                            step="0.01"
                          />
                        </div>
                        
                      <template>
                        <div class="w-2/3">
                          <span>{{ report.workingresult?.penggunaan_hsd1 }}</span>
                        </div>
                      </template>

                    </div>
                    <InputError :error="form4.errors.penggunaan_hsd1" />
                  </div>

                  <div class="flex flex-col space-y-2">
                    <div class="flex items-center space-x-2">
                      <label for="oddometer_hsd" class="w-1/3 lowercase first-letter:capitalize">
                        {{ __('Oddomeeter hsd') }}
                      </label>
                      
                        <div class="w-2/3">
                          <Input
                            v-model="form4.oddometer_hsd"
                            :placeholder="__('Oddomeeter hsd')"
                            type="number"
                            step="0.01"
                          />
                        </div>
                        
                      <template>
                        <div class="w-2/3">
                          <span>{{ report.workingresult?.oddometer_hsd }}</span>
                        </div>
                      </template>

                    </div>
                    <InputError :error="form4.errors.oddometer_hsd" />
                  </div>
                </div>
                <br>

                <div class="rounded-md text-sm">
                <div class="font-bold mb-2">D. DATA PERSONEL</div>
                  <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="flex flex-col space-y-2">
                      <div class="flex items-center space-x-2">
                        <label for="operator_by1" class="w-1/3">
                          {{ __('Operator 1') }}
                        </label>
                        
                          <div class="w-2/3">
                            <Select
                              v-model="form4.operator_by1"
                              :options="users.filter(user => user.id !== 1).map(user => ({
                                label: user.name,
                                value: user.id,
                              }))"
                              :searchable="true"
                              placeholder="Pilih Operator"
                              required
                            />
                          </div>
                      </div>

                      <InputError
                        :error="form4.errors.operator_by1"
                      />
                    </div> 
                    
                    <div class="flex flex-col space-y-2">
                      <div class="flex items-center space-x-2">
                        <label for="pengawal_id" class="w-1/3">
                          {{ __('Pengawal') }}
                        </label>
                        
                          <div class="w-2/3">
                            <Select
                              v-model="form4.pengawal_id"
                              :options="users.filter(user => user.id !== 1).map(user => ({
                                label: user.name,
                                value: user.id,
                              }))"
                              :searchable="true"
                              placeholder="Pilih Pengawal"
                              required
                            />
                          </div>
                      </div>

                      <InputError
                        :error="form4.errors.pengawal_id"
                      />
                    </div> 
                  </div>
                  
                  <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="flex flex-col space-y-2">
                      <div class="flex items-center space-x-2">
                        <label for="operator_by2" class="w-1/3">
                          {{ __('Operator 2') }}
                        </label>
                        
                          <div class="w-2/3">
                            <Select
                              v-model="form4.operator_by2"
                              :options="users.filter(user => user.id !== 1).map(user => ({
                                label: user.name,
                                value: user.id,
                              }))"
                              :searchable="true"
                              placeholder="Pilih Operator"
                              required
                            />
                          </div>
                      </div>

                      <InputError
                        :error="form4.errors.operator_by2"
                      />
                    </div> 
                  </div> 
                  
                  <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="flex flex-col space-y-2">
                      <div class="flex items-center space-x-2">
                        <label for="operator_by3" class="w-1/3">
                          {{ __('Operator 3') }}
                        </label>
                        
                          <div class="w-2/3">
                            <Select
                              v-model="form4.operator_by3"
                              :options="users.filter(user => user.id !== 1).map(user => ({
                                label: user.name,
                                value: user.id,
                              }))"
                              :searchable="true"
                              placeholder="Pilih Operator"
                              required
                            />
                          </div>
                      </div>

                      <InputError
                        :error="form4.errors.operator_by3"
                      />
                    </div> 
                  </div> 

                  <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="flex flex-col space-y-2">
                      <div class="flex items-center space-x-2">
                        <label for="note" class="w-1/3 lowercase first-letter:capitalize">
                          {{ __('Keterangan') }}
                        </label>
                        
                          <div class="w-2/3">
                            <TextArea
                              v-model="form4.note"
                              :placeholder="__('Keterangan')"
                              type="text"
                            />
                          </div>
                          
                        <template>
                          <div class="w-2/3">
                            <span>{{ report.workingresult?.note }}</span>
                          </div>
                        </template>

                      </div>
                      <InputError :error="form4.errors.note" />
                    </div>
                  </div>
                </div>
              </div>
              <br>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-center">
              <!-- Operator 1 -->
              <div class="flex flex-col items-center border border-gray-300 rounded-xl p-4 shadow-sm">
                <h3 class="font-semibold text-gray-800">Operator 1</h3>
                <p class="mt-2 text-gray-700">
                  {{ report.workresult?.operator1?.name || '-' }}
                </p>
                <p class="mt-2 text-sm text-gray-500">
                  Disetujui pada: 
                  <span class="font-medium text-gray-700">
                    {{ report.workresult?.operator_at1
                      ? formatDate (report.workresult.operator_at1)
                      : '-' }}
                  </span>
                </p>
              </div>

              <!-- Operator 2 -->
              <div class="flex flex-col items-center border border-gray-300 rounded-xl p-4 shadow-sm">
                <h3 class="font-semibold text-gray-800">Operator 2</h3>
                <p class="mt-2 text-gray-700">
                  {{ report.workresult?.operator2?.name || '-' }}
                </p>
                <p class="mt-2 text-sm text-gray-500">
                  Disetujui pada: 
                  <span class="font-medium text-gray-700">
                    {{ report.workresult?.operator_at2
                      ? formatDate(report.workresult.operator_at2)
                      : '-' }}
                  </span>
                </p>
              </div>

              <!-- Operator 3 -->
              <div class="flex flex-col items-center border border-gray-300 rounded-xl p-4 shadow-sm">
                <h3 class="font-semibold text-gray-800">Operator 3</h3>
                <p class="mt-2 text-gray-700">
                  {{ report.workresult?.operator3?.name || '-' }}
                </p>
                <p class="mt-2 text-sm text-gray-500">
                  Disetujui pada: 
                  <span class="font-medium text-gray-700">
                    {{ report.workresult?.operator3_approved_at
                      ? formatDate(report.workresult.operator3_approved_at)
                      : '-' }}
                  </span>
                </p>
              </div>
            </div>

              <br>
							
							<div class="d-flex justify-content-end mt-3">
								<Button v-if="!report.workresult?.id" class="bg-green-700 hover:bg-green-900 float-right mr-2" @click.prevent="submitworkresult()">Simpan</Button>
                <Button v-if="report.workresult?.id" class="bg-blue-700 hover:bg-blue-900 float-right mr-2" @click.prevent="updateworkresult()">Ubah</Button>
                <Button v-if="canApproveWorkResult" class="bg-blue-700 hover:bg-blue-900 float-right mr-2" @click.prevent="approveworkresult()">Approve</Button>
							</div>
						</div>
            <!-- section workresult -->

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

  </DashboardLayout>
</template>