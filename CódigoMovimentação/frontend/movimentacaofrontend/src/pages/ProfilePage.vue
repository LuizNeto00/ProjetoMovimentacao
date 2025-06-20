<template>
    <div>
      <div class="text-h4 q-pa-md">
        Perfil
      </div>
  
      <q-separator inset color="black" />
  
      <div style="width: 500px; height: 210px;">
        <q-card class="q-ma-md" style="width: 100%; height: 100%;">
          <q-card-section>
            <div class="q-mt-sm flex justify-between">
              <q-avatar class="text-white" color="black">
                {{ firstletternome }}
              </q-avatar>

              <div align="right" class="q-mt-md q-mr-lg">
                <span class="text-h4">{{ nomeStorage }}</span>
              </div>
            </div>
  
            <q-separator inset color="black" class="q-mt-md" />
  
            <div class="q-mt-sm">
              <span class="text-h6">Email: {{ emailStorage }}</span>
            </div>

            <div class="q-mt-md">
              <q-btn
              label="Alterar senha"
              color="black"
              @click="popupChangePassword"
              />
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  
    <q-dialog v-model="cardShow" persistent>
      <q-card style="width: 1000px; height: 700px;">
        <q-card-section class="justify-center items-center">
          <div>
            <h2 class="text-bold text-center">
              Ooops...
            </h2>
  
            <h4 class="text-bold text-center">
              Você não fez o Login corretamente!
              Volte para a <a href="http://localhost:9000/#/">página de Login</a>!
            </h4>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>



    <q-dialog v-model="popupPassword">
      <q-card style="width: 80%; max-width: 800px; height: 500px;">
        <q-card-section>
          <div class="flex justify-center">
            <h3>
              Insira sua senha antiga
            </h3>
          </div>
          <q-separator inset color="black"/>
        </q-card-section>


        <q-card-section>
          <div class="flex justify-center">
            <q-input
            label="Digite a senha antiga"
            :type="showPassword ? 'text' : 'password'"
            square outlined
            color="black"
            style="width: 500px;"
            v-model="oldPassword"
            >
            <template v-slot:append>
              <q-icon :name="showPassword ? 'visibility' : 'visibility_off'" class="cursor-pointer"
                @click="showPassword = !showPassword" />
            </template>


            <template v-slot:prepend>
            <q-icon name="lock" />
            </template>
          </q-input>

          </div>

          <div class="flex justify-center q-mt-md">
              <q-btn
          label="Verificar senha"
          color="black"
          :loading="loading"
          @click="confirmPassword"
          size="18px"
          />
            </div>


          <div v-if="passwordVerified" class="q-mt-lg flex justify-center">
            <q-input 
            label="Digite a nova senha"
            :type="showNewPassword ? 'text' : 'password'"
            color="black"
            square outlined
            v-model="newPassword"
            style="width: 500px;"
            >
            <template v-slot:append>
              <q-icon :name="showNewPassword ? 'visibility' : 'visibility_off'" class="cursor-pointer"
                @click="showNewPassword = !showNewPassword" />
            </template>


            <template v-slot:prepend>
            <q-icon name="lock" />
            </template>
            </q-input>
          </div>

          <div class="flex justify-center q-mt-md" v-if="passwordVerified">
            <q-btn
            label="Salvar"
            color="black"
            :loading="loadingNewPassword"
            @click="saveNewPassword"
            size="18px" 
            />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useUserStore } from 'src/stores/userStore';
  import { updatePasswordBackend, verifyPasswordBackend } from 'src/services/userService'
  import { Notify } from 'quasar'
  
  
  const userStore = useUserStore();
const cardShow = ref(false)
const popupPassword = ref(false)
const showPassword = ref(false)
const showNewPassword = ref(false)
const passwordVerified = ref(false)
const loading = ref(false)
const loadingNewPassword = ref(false)
const oldPassword = ref('')
const newPassword = ref('')

const token = userStore.token
const nome = userStore.nome
const email = userStore.email 


const tokenStorage = localStorage.getItem('auth_token') || token
const nomeStorage = localStorage.getItem('Username') || nome
const emailStorage = localStorage.getItem('Email') || email

const firstletternome = nomeStorage ? nomeStorage.charAt(0).toUpperCase() : '?'

onMounted(() => {
  if (!tokenStorage) {
    cardShow.value = true
  }

  if (!localStorage.getItem('auth_token') && token) {
    localStorage.setItem('auth_token', token)
  }

  if (!localStorage.getItem('Username') && nome) {
    localStorage.setItem('Username', nome)
  }

  if (!localStorage.getItem('Email') && email) {
    localStorage.setItem('Email', email)
  }
})

const popupChangePassword = () => {
  popupPassword.value = true
}

const checkOldPassword = () => {
  if (oldPassword.value.trim() === '') {
    return { status: false, message:'Insira a senha antiga!' }
  }
  if(oldPassword.value.length < 8) {
    return { status: false, message:'A senha deve possuir mais de 8 caracteres!' }
  }

  return { status: true }
}

const checkNewPassword = () => {
  if (newPassword.value.trim() === '') {
    return { status: false, message:'Insira a nova senha!' }
  }
  if(newPassword.value.length < 8) {
    return { status: false, message:'A nova senha deve possuir mais de 8 caracteres!' }
  }

  return { status: true }
}


const saveNewPassword = async () => {
  const check = checkNewPassword()

  if(check.status) {
    try {
      loadingNewPassword.value = true

      const response = await updatePasswordBackend(newPassword.value)

      if(response) {
        Notify.create({
          type:'positive',
          message:'Senha atualizada!',
          position:'top'
        })

        popupPassword.value = false
      } else {
        Notify.create({
          type:'negative',
          message:'Erro ao atualizar senha!',
          position:'top'
        })
      }
    } catch (error) {
      console.log('Erro ao alterar senha!', error)
    } finally {
      loadingNewPassword.value = false
    }
  } else {
    Notify.create({
    type:'negative',
    message: check.message,
    position:'top'
  })
  }
}

const confirmPassword = async () => {
  const check = checkOldPassword()

  if(check.status) {
    try {
      loading.value = true

      const response = await verifyPasswordBackend(oldPassword.value)

      if (response) {
        Notify.create({
          type:'positive',
          message:'Senha verificada!',
          position:'top'
        })

        passwordVerified.value = true
      } 
    } catch (error) {
      if(error.status === 401) {
        Notify.create({
          type:'negative',
          message:'Senha incorreta!',
          position:'top'
        })
      }
    } finally {
      loading.value = false
    }
  } else {
  Notify.create({
    type:'negative',
    message: check.message,
    position:'top'
  })
}

} 




  
  </script>
  
  <style scoped>
  a {
    color: black;
  }
  </style>
  