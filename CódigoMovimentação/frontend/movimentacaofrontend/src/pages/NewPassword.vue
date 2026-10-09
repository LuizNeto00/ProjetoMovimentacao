<template>
    <div class="q-ma-md flex justify-center items-center">
        <q-card style="width: 600px;height: 520px;margin-top: 40px;">
            <q-card-section>
                <div><h4 class="text-weight-light text-center">Insira seu email</h4></div>

                <q-separator color="black" inset/>

                <div><h5 class="text-weight-light text-center" color="blue">Insira o email que você se registrou para alterar a senha</h5></div>

                <q-input square outlined label="Email" class="q-ma-md" v-model="userData.email" color="black" :loading="isLoading">

                    
            <template v-slot:prepend>
              <q-icon name="mail" />
            </template>
          </q-input>

          <q-input :type="showPassword ? 'text' : 'password'" square outlined label="Nova Senha" class="q-ma-md"
            v-model="userData.nova_senha" color="black" >
            <template v-slot:append>
              <q-icon :name="showPassword ? 'visibility' : 'visibility_off'" class="cursor-pointer"
                @click="showPassword = !showPassword" />
            </template>

            <template v-slot:prepend>
              <q-icon name="lock" />
            </template>
          </q-input>
            </q-card-section>

            <q-card-actions class="flex justify-center">
                <q-btn color="black" @click="changePassword" style="width: 300px;">salvar</q-btn>
            </q-card-actions>
        </q-card>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Notify } from 'quasar';
import { updatePasswordBackend } from 'src/services/userService';
import { useRouter } from 'vue-router';

const userData = ref({
    email:'',
    nova_senha:''
})

const router = useRouter()

const showPassword = ref(false)

const isLoading = ref()


const checkCredentials = () => {
    if(userData.value.email.trim() === '' && userData.value.nova_senha.trim()===''){
        return { status: false, message:'Todos os campos devem ser preenchidos!' }
    }
    if (userData.value.email.trim() === ''){
       return { status: false, message: 'Preencha o campo de email!'}
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(userData.value.email)) {
    return { status: false, message: 'Digite um e-mail válido!' }
  }
  if(userData.value.nova_senha.trim()===''){
        return { status: false, message:'Insira a nova senha!'}
    }
    if(userData.value.nova_senha.length < 8 ){
        return { status: false, message:'Sua nova senha deve conter mais de 8 caracteres!'}
    }
  return { status: true }
}

const changePassword = async () => {
    const check = checkCredentials()

    if(check.status){
        isLoading.value = true
        try {
            const response = await newpassword(userData.value)

            if(response.status){
                router.push({ path: '/login' })
            }
        } catch (error) {
            Notify.create({
                message: 'Erro ao mudar a senha!',
      type: 'negative',
      position: 'top',
            })

            if (error.status === 404) {
                Notify.create({
                message: 'Email não encontrado!',
      type: 'negative',
      position: 'top',
            })
            }

            console.log(error)
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

</style>
