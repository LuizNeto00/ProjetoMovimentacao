<template>
     <q-card style="height: 800px; width: 500px;" id="card">
        <q-card-section>
            <div class="q-ma-sm text-h4">
                Edite sua movimentação
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

                <q-btn label="Editar" color="black" class="q-pa-md" size="17px" :loading="isLoading"
                    @click="editMovement" />
            </div>
        </q-card-actions>
    </q-card>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Notify } from 'quasar'
import { updateMovement } from 'src/services/movementService'

const props = defineProps({
    movement: Object
})

const isLoading = ref(false)

const emit = defineEmits(['updated', 'cancel-edit-popup'])

const movementData = ref({
    movementType: '',
    movementValue: '',
    movementCategory: '',
    movementDescription: '',
})

const cancelPopup = () => {
    emit('cancel-edit-popup')
}

watch(
    () => props.movement,
    (val) => {
        if (val) {
            movementData.value = {
                movementType: val.type,
                movementValue: val.value,
                movementCategory: val.category,
                movementDescription: val.description
            }
        }
    }, { immediate: true })

    const checkUpdateMovement = () => {
        if (movementData.value.movementValue.trim() === '') {
            return { status: false, message:'Insira um valor para a movimentação!' }
        }
        if (movementData.value.movementCategory.trim() === '') {
            return { status: false, message:'Insira uma categoria para a movimentação!' }
        }
        if (movementData.value.movementValue < 0 || movementData.value.movementValue.includes('e')) {
            return { status: false, message:'Insira um valor válido para o valor da movimentação!' }
        }

        return { status: true }
    }

    const editMovement = async () => {
        const check = checkUpdateMovement()

        if (check.status) {
            try {
                isLoading.value = true

                const response = await updateMovement(props.movement.id, {
                    type: movementData.value.movementType,
                    value: movementData.value.movementValue,
                    category: movementData.value.movementCategory,
                    description: movementData.value.movementDescription
                })

                if (response.status) {
                    Notify.create({
                        type:'positive',
                        message:'Movimentação atualizada!',
                        position: 'top'
                    })

                    emit('updated')
                } else {
                    Notify.create({
                    type: 'negative',
                    message: 'Erro ao atualizar a movimentação!',
                     position: 'top'
                })
                }
            } catch (error) {
                console.log('erro ao atualizar os dados!', error)
            } finally {
                isLoading.value = false
            }
        } else {
            Notify.create({
                    type: 'negative',
                    message: check.message,
                     position: 'top'
                })
        }
    }

</script>