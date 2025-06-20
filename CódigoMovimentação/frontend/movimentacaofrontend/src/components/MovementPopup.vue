<template>
    <q-card style="height: 800px; width: 500px;" id="card">
        <q-card-section>
            <div class="q-ma-sm text-h4">
                Crie sua movimentação
            </div>
        </q-card-section>

        <q-separator inset color="black" />

        <q-card-section>
            <div class="q-mt-md">

                <div class="text-h5 text-bold q-mb-sm">
                    Tipo:
                </div>

                <q-radio v-model="movementData.movementType" val="input" label="Entrada" />

                <q-radio v-model="movementData.movementType" val="exit" label="Saída" />
            </div>

            <div class="q-mt-md">
                <div class="text-h5 text-bold q-mb-sm">
                    Valor(R$):
                </div>

                <q-input placeholder="Valor(R$)" square outlined type="number" style="width: 180px;" class="text-h6"
                    v-model="movementData.movementValue" />
            </div>
        </q-card-section>

        <q-card-section>
            <div>
                <div class="text-h5 text-bold q-mb-sm">
                    Categoria:
                </div>

                <div>
                    <q-input square outlined placeholder="Categoria da movimentação. (Ex: Alimentação, Investimento...)"
                        v-model="movementData.movementCategory" />
                </div>


                <div class="text-h5 text-bold q-mt-md">
                    Descrição:
                </div>
                <div>
                    <q-input placeholder="Descrição da movimentação(opcional)" square outlined type="textarea"
                        class="text-h6" v-model="movementData.movementDescription" />
                </div>
            </div>
        </q-card-section>


        <q-card-actions class="flex justify-end">
            <div class="flex q-ma-sm">
                <q-btn label="Cancelar" color="red" outline class="q-mr-md" @click="cancelPopup" size="17px" />

                <q-btn label="Criar" color="black" class="q-pa-md" size="17px" :loading="isLoading"
                    @click="createMovement" />
            </div>
        </q-card-actions>
    </q-card>
</template>


<script setup>
import { ref  } from 'vue'
import { Notify } from 'quasar'
import { createAMovement } from 'src/services/movementService';

const isLoading = ref(false);

const emit = defineEmits(['created-sucess', 'cancel-popup'])


const movementData = ref({
    movementType:'',
    movementValue:'',
    movementCategory:'',
    movementDescription:'',
})


const cancelPopup = () => {
    emit('cancel-popup')
}


const checkMovement = () => {
    if (movementData.value.movementType === '' && movementData.value.movementValue.trim() === '' && movementData.value.movementCategory.trim() === '') {
       return { status: false, message:'Por favor preencha todos os campos!'  }
    }

    if (movementData.value.movementType === '') {
        return { status: false, message:'Escolha o tipo da movimentação!' }
    }

    if (movementData.value.movementValue === ''  ||
    isNaN(movementData.value.movementValue) ) {
        return { status:false, message:'Insira o valor da movimentação!' }
    }

    if (movementData.value.movementCategory.trim() === '') {
        return { status:false, message:'Insira a categoria da sua movimentação!' }
    }
    if (movementData.value.movementValue < 0 || movementData.value.movementValue.includes('e')) {
        return { status:false, message:'Insira um valor válido!' }
    } 

    return { status: true }
}

const createMovement = async () => {
    const check = checkMovement()

    if (check.status) {
        try {
             isLoading.value = true

            const response = await createAMovement({
                type: movementData.value.movementType,
                value: parseFloat(movementData.value.movementValue),
                category: movementData.value.movementCategory,
                description: movementData.value.movementDescription
            });

            if (response.status) {
                Notify.create({
                type:'positive',
                message:'Movimentação criada!',
                 position: 'top'
            });

                movementData.value = {
                    movementType: '',
                    movementValue: '',
                    movementCategory: '',
                    movementDescription: ''
                };

                emit('created-sucess')
            } else {
                Notify.create({
                type:'negative',
                message:'Ocorreu um erro ao criar a movimentação!',
                 position: 'top'
            })
            }
} catch (error) {
            console.log('Erro na movimentação!', error)
        } finally {
            isLoading.value = false
        }
    } else {
        Notify.create({
                type:'negative',
                message: check.message,
                position: 'top',
                size:'80px'
            })
    }
}

</script>

<style scoped>
#card {
    overflow-y: hidden;
    overflow-x: hidden;
    overflow: hidden;
}

</style>