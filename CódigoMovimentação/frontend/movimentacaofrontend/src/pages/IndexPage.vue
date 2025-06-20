<template>
  <main>
    <div class="flex justify-center items-center"  style="padding-top: 50px; background-image: url('/images/backgroundregister.jpg'); background-size: cover; background-position: center; min-height: 100vh;" id="body">
    <q-card style="width: 600px;">
      <q-card-section>
        <div class="text-h4 text-bold q-ml-md">Faça seu registro
        </div>
      </q-card-section>

      <q-separator color="black" inset />

      <q-card-section>
        <div>
          <q-input square outlined dense label="Nome" class="q-ma-md" v-model="userData.username" color="black">
            <template v-slot:prepend>
              <q-icon name="person" />
            </template>
          </q-input>

          <q-input square outlined dense label="Email" class="q-ma-md" v-model="userData.email" color="black">
            <template v-slot:prepend>
              <q-icon name="mail" />
            </template>
          </q-input>

          <q-input :type="showPassword ? 'text' : 'password'" square outlined dense label="Senha" class="q-ma-md"
            v-model="userData.senha" color="black">
            <template v-slot:append>
              <q-icon :name="showPassword ? 'visibility' : 'visibility_off'" class="cursor-pointer"
                @click="showPassword = !showPassword" />
            </template>

            <template v-slot:prepend>
              <q-icon name="lock" />
            </template>
          </q-input>
        </div>
      </q-card-section>

      <q-separator dark />

      <q-card-actions class=" flex justify-center q-mb-md">
        <q-btn color="black" size="17px" style="width: 400px;"  @click="registerUser" :loading="isLoading">REGISTRAR</q-btn>
      </q-card-actions>
    </q-card>
  </div>
  </main>
</template>

<script setup>
import { ref } from 'vue';
import { Notify } from 'quasar';
import { register } from 'src/services/userService';


const userData = ref(
  {
    username:'',
  email:'',
  senha:''
}
)

const showPassword = ref(false)

const isLoading = ref()

const verifyUser = () => {
  if(userData.value.email.trim()==='' && userData.value.senha.trim()==='' && userData.value.username.trim() ==='') {
   return { status: false, message: 'Todos os campos devem ser preenchidos!'}
  }
  if(userData.value.email.trim()==='') {
    return { status:false, message:'O campo de email deve ser preenchido!'}
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(userData.value.email)) {
    return { status: false, message: 'Digite um e-mail válido!' }
  }
  if(userData.value.senha.trim()==='') {
    return { status:false, message:'O campo da senha deve ser preenchido!'}
  }
  if(userData.value.username.trim()==='') {
    return { status:false, message:'O campo de nome deve ser preenchido!'}
  }
  if (userData.value.username.length < 3) {
    return { status: false, message:'Insira um nome válido!'}
  }
  if(userData.value.senha.length < 8 ){
    return { status:false, message:'Sua senha deve conter mais de 8 caracteres!'}
  }

  return { status: true }
}

const registerUser = async () => {
  const check = verifyUser()

  if(check.status){
    try {
      isLoading.value = true
      const response = await register(userData.value)
      Notify.create({
        message: 'Cadastro realizado!',
        type:'positive',
        position:'top',
      })
      console.log('Cadastro feito!', response.data),

      userData.value.username = ''
      userData.value.email = ''
      userData.value.senha = ''
    } catch (error) {
      if (error.status === 422) {
        Notify.create({
        message: 'Este email ja está em uso!',
        type:'negative',
        position:'top',
      })
      }
    } finally {
      isLoading.value = false
    }
    
  } else {
    Notify.create({
      message: check.message,
      type: 'negative',
      position: 'top',
    })
  }
}
</script>

<style lang="css">
body {
  overflow: hidden;
}

a {
  color: black;
}
</style>
