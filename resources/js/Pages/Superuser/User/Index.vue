<script setup>
import { getCurrentInstance, nextTick, onMounted, onUnmounted, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import Builder from '@/Components/DataTable/Builder.vue'
import Th from '@/Components/DataTable/Th.vue'
import Swal from 'sweetalert2'
import Select from '@vueform/multiselect'
import Modal from '@/Components/Modal.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import ButtonRed from '@/Components/Button/Red.vue'
import Close from '@/Components/Button/Close.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'

const self = getCurrentInstance()
const render = ref(true)
const { permissions, roles, positions, divisions } = defineProps({
  positions: Array,
  divisions: Array,
  permissions: Array,
  roles: Array,
})

const form = useForm({
  id: null,
  name: '',
  username: '',
  position_id: '',
  division_id: '',
  positions: [],
  divisions: [],
  email: '',
  password: '',
  password_confirmation: '',
  permissions: [],
  roles: [],
})

const table = ref(null)
const open = ref(false)

const show = () => open.value = true

const close = () => {
  open.value = false
  form.reset()
  render.value = false
  nextTick(() => render.value = true)
}

const store = () => {
  return form.post(route('superuser.user.store'), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const edit = user => {
  form.id = user.id
  form.name = user.name
  form.username = user.username
  form.position_id = user.position_id
  form.division_id = user.division_id
  form.email = user.email
  form.permissions = user.permissions.map(permission => permission.id)
  form.roles = user.roles.map(role => role.id)

  show()
}

const update = () => {
  return form.patch(route('superuser.user.update', form.id), {
    onSuccess: () => close(),
    onError: () => show(),
  })
}

const destroy = async user => {
  const response = await Swal.fire({
    title: __('are you sure') + '?',
    text: __('you can\'t restore it after deleted'),
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })

  if (response.isConfirmed) {
    Inertia.on('finish', () => close())

    return Inertia.delete(route('superuser.user.destroy', user.id))
  }
}

const submit = () => form.id ? update() : store()

const detachPermission = async (user, permission) => {
  const response = await Swal.fire({
    title: __('are you sure') + '?',
    text: __('you can re adding it on edit page'),
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })

  if (!response.isConfirmed)
    return

  Inertia.on('finish', () => close())
  Inertia.patch(route('superuser.user.permission.detach', { user: user.id, permission: permission.id }))
}

const detachRole = async (user, role) => {
  const response = await Swal.fire({
    title: __('are you sure') + '?',
    text: __('you can re adding it on edit page'),
    icon: 'question',
    showCancelButton: true,
    showCloseButton: true,
  })

  if (!response.isConfirmed)
    return

  Inertia.on('finish', () => close())
  Inertia.patch(route('superuser.user.role.detach', { user: user.id, role: role.id }))
}

const esc = e => e.key === 'Escape' && close()

onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
  <DashboardLayout
    :title="__('user')"
  >
    <main class="p-0 py-0 mb-[1.25rem] ml-[1.25rem] mt-[1.25rem]">
            <h2 class="font-bold text-2xl">User</h2>
           <a type="button" href="/" class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-600">Home</a> 
           <span class="font-semibold text-sm pl-2 pr-2">-</span>
           <span class="text-sm text-gray-500 font-semibold hover:text-sky-600 focus:text-sky-700">Builtin</span> 
            <slot />
        </main>
    <Card class="bg-white pt-[1.875rem] pb-[2.5rem] shadow-lg border border-solid border-slate-200">
      <template #header>
        <div class="flex items-center justify-end space-x-2 p-2 pr-[1.688rem]">
          <ButtonGreen
            v-if="can('create user')"
            @click.prevent="form.id = null; show()"
            class="flex items-center justify-center grid gap-1 w-auto h-11 mr-[1.313rem] rounded-md text-center bg-green-600 hover:bg-green-700 active:bg-green-800 h-[35px] text-center pr-[30px] pl-[30px] w-full flex items-center justify-center space-x-2 ml-[20px] mr-[20px]"
          >
            <!-- <Icon name="plus" /> -->
            <p class="capitalize font-semibold text-[0.938rem]">
              {{ __('create') }}
            </p>
          </ButtonGreen>
        </div>
      </template>

      <template #body>
        <div class="flex flex-col space-y-2">
          <Builder
            v-if="render"
            :url="route('superuser.user.paginate')"
            ref="table"
          >
            <template #thead="table">
              <tr class="bg-gray-200 border-gray-300">
                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center capitalize font-bold"
                >
                  {{ __('no') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="name"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('name') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="username"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('username') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="postion_id"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('postion') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="division_id"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('division') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="email"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('email') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('permissions') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('roles') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="email_verified_at"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('verified at') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="created_at"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('created at') }}
                </Th>

                <Th
                  :table="table"
                  :sort="true"
                  name="updated_at"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('updated at') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('Aksi') }}
                </Th>
              </tr>
            </template>

            <template #tfoot="table">
              <tr class="bg-gray-200 border-gray-300">
                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center capitalize font-bold pl-[2.75rem] pb-[1.5rem]"
                >
                  {{ __('no') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold "
                >
                  {{ __('name') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('username') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('position') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('division') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('email') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('permissions') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('roles') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('verified at') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('created at') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('updated at') }}
                </Th>

                <Th
                  :table="table"
                  :sort="false"
                  class="border px-3 py-2 text-center whitespace-nowrap capitalize font-bold"
                >
                  {{ __('Aksi') }}
                </Th>
              </tr>
            </template>

            <template #tbody="{ data, processing, empty }">
              <TransitionGroup
                enterActiveClass="transition-all duration-200"
                leaveActiveClass="transition-all duration-200"
                enterFromClass="opacity-0 -scale-y-100"
                leaveToClass="opacity-0 -scale-y-100"
              >
                <template v-if="empty">
                  <tr>
                    <td class="text-5xl text-center p-4" colspan="1000">
                      <p class="lowercase first-letter:capitalize font-semibold">
                        {{ __('there are no data available') }}
                      </p>
                    </td>
                  </tr>
                </template>

                <template v-else>
                  <tr
                    v-for="(user, i) in data"
                    :key="i"
                    :class="processing && 'bg-gray-100'"
                    class="transition-all duration-300 h-[4.375rem]"
                  >
                    <td class="px-2 py-1 border-b text-center">
                      {{ i + 1 }}
                    </td>

                    <td class="px-2 py-1 border-b uppercase text-center">
                      {{ user.name }}
                    </td>

                    <td class="px-2 py-1 border-b uppercase text-center">
                      {{ user.username }}
                    </td>

                    <td class="px-2 py-1 border-b uppercase text-center">
                      {{ user.positions ? user.positions.position : '' }}
                      <!-- {{ user}} -->
                    </td>

                    <td class="px-2 py-1 border-b uppercase text-center">
                      {{ user.divisions ? user.divisions.division_name : '' }}
                    </td>

                    <td class="px-2 py-1 border-b uppercase text-center">
                      {{ user.email }}
                    </td>

                    <td class="px-2 py-1 border-b text-center">
                      <div class="flex-wrap">
                        <div
                          v-for="(permission, j) in user.permissions"
                          :key="j"
                          class="inline-block bg-gray-200 rounded-md px-3 py-1 m-[1px] text-sm"
                        >
                          <div class="flex items-center justify-between space-x-1">
                            <p class="uppercase font-semibold whitespace-nowrap">
                              {{ __(permission.name) }}
                            </p>

                            <Icon
                              v-if="can('update user')"
                              @click.prevent="detachPermission(user, permission)"
                              name="times"
                              class="px-2 py-1 rounded-md transition-all hover:bg-red-500 cursor-pointer"
                            />
                          </div>
                        </div>
                      </div>
                    </td>

                    <td class="px-2 py-1 border-b text-center">
                      <div class="flex-wrap">
                        <div
                          v-for="(role, j) in user.roles"
                          :key="j"
                          class="inline-block border rounded-md px-3 py-1 m-[1px] text-sm transition-all"
                        >
                          <div class="flex items-center justify-between space-x-2">
                            <p class="uppercase font-semibold">
                              {{ __(role.name) }}
                            </p>

                            <Icon
                              v-if="can('update user')"
                              @click.prevent="detachRole(user, role)"
                              name="times"
                              class="px-2 py-1 rounded-md transition-all hover:bg-red-500 cursor-pointer"
                            />
                          </div>
                        </div>
                      </div>
                    </td>

                    <td class="px-2 py-1 border-b uppercase text-center">
                      {{ new Date(user.email_verified_at).toLocaleString('id') }}
                    </td>

                    <td class="px-2 py-1 border-b uppercase text-center">
                      {{ new Date(user.created_at).toLocaleString('id') }}
                    </td>

                    <td class="px-2 py-1 border-b uppercase text-center">
                      {{ new Date(user.updated_at).toLocaleString('id') }}
                    </td>

                    <td class="px-2 py-1 border-b ">
                      <div class="flex items-center space-x-2">
                        <ButtonBlue
                          v-if="can('update user')"
                          @click.prevent="edit(user)"
                        >
                          <!-- <Icon name="edit" /> -->
                          <p class="capitalize">
                            {{ __('edit') }}
                          </p>
                        </ButtonBlue>

                        <ButtonRed
                          v-if="can('delete user')"
                          @click.prevent="destroy(user)"
                        >
                          <!-- <Icon name="trash" /> -->
                          <p class="capitalize">
                            {{ __('delete') }}
                          </p>
                        </ButtonRed>
                      </div>
                    </td>
                  </tr>
                </template>
              </TransitionGroup>
            </template>
          </Builder>
        </div>
      </template>
    </Card>

    <Modal :show="open">
      <form
        @submit.prevent="submit"
        class="w-full max-w-xl sm:max-w-5xl h-fit shadow-xl"
      >
        <Card class="bg-gray-50">
          <template #header>
            <div class="flex items-center justify-end bg-gray-200 p-2">
              <Close @click.prevent="close" />
            </div>
          </template>

          <template #body>
            <div class="flex flex-col space-y-4 p-4">
              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="name" class="w-1/3 lowercase first-letter:capitalize">
                    {{ __('name') }}
                  </label>

                  <Input
                    v-model="form.name"
                    :placeholder="__('name')"
                    type="text"
                    name="name"
                    required
                    autofocus
                  />
                </div>

                <InputError :error="form.errors.name" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="username" class="w-1/3 lowercase first-letter:capitalize">
                    {{ __('username') }}
                  </label>
                  
                  <Input
                    v-model="form.username"
                    :placeholder="__('username')"
                    type="text"
                    name="username"
                    required
                  />
                </div>

                <InputError :error="form.errors.username" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="position_id" class="w-1/3 lowercase first-letter:capitalize">
                    {{ __('position') }}
                  </label>
                  
                  <Select
                    v-model="form.position_id"
                    :options="positions.map(position => ({
                    label: `${position.position}`,
                    value: position.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih posisi"
                  />
                </div>

                <InputError :error="form.errors.position_id" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="division_id" class="w-1/3 lowercase first-letter:capitalize">
                    {{ __('division') }}
                  </label>
                  
                  <Select
                    v-model="form.division_id"
                    :options="divisions.map(division => ({
                      label: `${division.division_name}`,
                      value: division.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih divisi"
                  />
                </div>

                <InputError :error="form.errors.division_id" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="email" class="w-1/3 lowercase first-letter:capitalize">
                    {{ __('email') }}
                  </label>

                  <Input
                    v-model="form.email"
                    :placeholder="__('email')"
                    type="email"
                    name="email"
                  />
                </div>

                <InputError :error="form.errors.email" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="password" class="w-1/3 lowercase first-letter:capitalize">
                    {{ __('password') }}
                  </label>

                  <Input
                    v-model="form.password"
                    :placeholder="__('password')"
                    :required="form.id === null"
                    type="password"
                    name="password"
                  />
                </div>

                <InputError :error="form.errors.password" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="password_confirmation" class="w-1/3 lowercase first-letter:capitalize">
                    {{ __('password confirmation') }}
                  </label>

                  <Input
                    v-model="form.password_confirmation"
                    :required="form.id === null"
                    :placeholder="__('password confirmation')"
                    type="password"
                    name="password_confirmation"
                  />
                </div>

                <InputError :error="form.errors.password_confirmation" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="permissions" class="w-1/3 lowercase first-letter:capitalize">
                    {{ __('permissions') }}
                  </label>

                  <Select
                    v-model="form.permissions"
                    :options="permissions.map(p => ({
                      label: __(p.name),
                      value: p.id,
                    }))"
                    :clearOnSelect="false"
                    :closeOnSelect="false"
                    :searchable="true"
                    :placeholder="__('permissions')"
                    class="uppercase"
                    mode="tags"
                  />
                </div>

                <InputError :error="form.errors.permissions" />
              </div>

              <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-2">
                  <label for="roles" class="w-1/3 lowercase first-letter:capitalize">
                    {{ __('roles') }}
                  </label>

                  <Select
                    v-model="form.roles"
                    :options="roles.map(r => ({
                      label: __(r.name),
                      value: r.id,
                    }))"
                    :searchable="true"
                    :clearOnSelect="false"
                    :closeOnSelect="false"
                    :placeholder="__('roles')"
                    class="uppercase"
                    mode="tags"
                  />
                </div>

                <InputError :error="form.errors.roles" />
              </div>
            </div>
          </template>

          <template #footer>
            <div class="flex items-center justify-end space-x-2 bg-gray-200 px-2 py-1">
              <ButtonGreen type="submit">
                <Icon name="check" />
                <p class="uppercase font-semibold">
                  {{ __(form.id ? 'update' : 'create') }}
                </p>
              </ButtonGreen>
            </div>
          </template>
        </Card>
      </form>
    </Modal>
  </DashboardLayout>
</template>