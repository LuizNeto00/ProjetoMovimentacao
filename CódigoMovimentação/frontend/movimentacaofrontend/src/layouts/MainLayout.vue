<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar class="bg-black">
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          Projeto Movimentação
        </q-toolbar-title>

        <div>{{ nomeStorage }}<q-avatar class="bg-white text-black q-ml-sm" size="48px">{{ nomefirstletter }}</q-avatar></div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
    >
      <q-list>
        <q-item-label
          header
        >
          Essential Links
        </q-item-label>

        <EssentialLink
          v-for="link in linksList"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'
import EssentialLink from 'components/EssentialLink.vue';
import { useUserStore } from 'src/stores/userStore';

const useStore = useUserStore()

const nome = useStore.nome || ''
const nomeStorage = localStorage.getItem('Username') || nome
const nomefirstletter = nomeStorage ? nomeStorage.charAt(0).toUpperCase() : '?'


if (!localStorage.getItem('Username') && nome) {
  localStorage.setItem('Username', nome)
}

const linksList = [
  {
    title: 'Perfil',
    icon: 'person',
    to: '/app/profile'
  },
  {
    title: 'Movimentações',
    icon: 'local_atm',
    to: '/app/movements'
  },
]

const leftDrawerOpen = ref(false)

function toggleLeftDrawer () {
  leftDrawerOpen.value = !leftDrawerOpen.value
}
</script>
