<template>
  <div class=" flex justify-center items-center"
    style="padding-top: 50px;background-image: url('/images/backgroundlogin.avif'); background-size: cover; background-position: center; min-height: 100vh;">
    <q-card style="width: 600px;">
      <q-card-section>
        <div class="text-h4 text-bold q-ml-md">Faça seu login
        </div>
      </q-card-section>

      <q-separator color="black" inset />

      <q-card-section>
        <div>
          <q-input square outlined label="Email" class="q-ma-md" v-model="userData.email" color="black">
            <template v-slot:prepend>
              <q-icon name="mail" />
            </template>
          </q-input>

          <q-input :type="showPassword ? 'text' : 'password'" square outlined label="Senha" class="q-ma-md"
            v-model="userData.senha" color="black">
            <template v-slot:append>
              <q-icon :name="showPassword ? 'visibility' : 'visibility_off'" class="cursor-pointer"
                @click="showPassword = !showPassword" />
            </template>

            <template v-slot:prepend>
              <q-icon name="lock" />
            </template>
          </q-input>
          <div class="q-ml-md flex justify-between">
            <a href="#">
              <p>Esqueceu sua senha?</p>
            </a>

            <a href="http://localhost:9000/register" class="q-mr-md">
              <p>Ainda não tem uma conta?</p>
            </a>
          </div>
        </div>
      </q-card-section>

      <q-separator dark />

      <q-card-actions class=" flex justify-center q-mb-md">
        <q-btn color="black" size="17px" style="width: 400px;" @click="loginUser" :loading="isLoading">LOGIN</q-btn>
      </q-card-actions>
    </q-card>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Notify } from 'quasar';
import { login, getAuthenticatedUser } from 'src/services/userService';
import { useRouter } from 'vue-router';
import { useUserStore } from 'src/stores/userStore';

const isLoading = ref(false);
const showPassword = ref(false);
const router = useRouter();
const userStore = useUserStore();

const userData = ref({
  email: '',
  senha: ''
});

const verifyUser = () => {
  if (userData.value.email.trim() === '' && userData.value.senha.trim() === '') {
    return { status: false, message: 'Todos os campos devem ser preenchidos!' };
  }
  if (userData.value.email.trim() === '') {
    return { status: false, message: 'O campo de email deve ser preenchido!' };
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(userData.value.email)) {
    return { status: false, message: 'Digite um e-mail válido!' };
  }
  if (userData.value.senha.trim() === '') {
    return { status: false, message: 'O campo da senha deve ser preenchido!' };
  }
  if (userData.value.senha.length < 8) {
    return { status: false, message: 'Sua senha deve conter mais de 8 caracteres!' };
  }

  return { status: true };
};

const loginUser = async () => {
  const check = verifyUser();

  if (!check.status) {
    Notify.create({
      message: check.message,
      type: 'negative',
      position: 'top',
    });
    return;
  }

  try {
    isLoading.value = true;

    const response = await login(userData.value);

 
    const token = response.data.token;
    localStorage.setItem('auth_token', token); 


    const authResponse = await getAuthenticatedUser(token);
    const { username, email } = authResponse.data;

   
    userStore.setUserData({
      nome: username,
      email,
      token
    });

    
    router.push({ path: '/app/profile' });

  } catch (error) {
    Notify.create({
      message: error?.response?.data?.message || 'Erro ao fazer login!',
      type: 'negative',
      position: 'top'
    });
  } finally {
    isLoading.value = false;
  }
};
</script>


<style lang="css">
body {
  overflow: hidden;
}

a {
  color: black;
}
</style>