<template>
    <div class="q-pa-md text-h4">
        Movimentações
    </div>

    <q-separator inset color="black" />

    <div class="q-ma-md flex justify-end">
        <q-btn label="Criar movimentação" @click="showMovementPopup" />
    </div>

    <q-dialog v-model="mPopup">
        <q-card>
            <MovementPopup @created-sucess="closePopup"  @cancel-popup="closeCreateMovement"/>
        </q-card>
    </q-dialog>

    <div class="q-pa-md">
        <q-table title="Suas Movimentações" card-class="bg-grey-11" table-class="bg-grey-1" title-class="text-h4" :rows="rows" :columns="columns" row-key="id"
            no-data-label="Você ainda não criou nenhuma movimentação" :loading="loadingTable" color="blue-grey-3">
            <template v-slot:body-cell-type="props">
            <q-td :props="props" :class="props.row.type === 'exit' ? 'text-negative' : 'text-positive'">
                {{ props.row.type === 'exit' ? 'Saída' : 'Entrada' }}
             </q-td>
             </template>

  
            <template v-slot:body-cell-value="props">
            <q-td :props="props" :class="props.row.type === 'exit' ? 'text-negative' : 'text-positive'">
             {{ formatCurrency(props.row.value) }}
            </q-td>
             </template>

             <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                    <q-btn flat icon="edit" color="black" size="sm" class="q-mr-sm" @click="openEditMovement(props.row)"/>
                    <q-btn flat icon="delete" color="red" size="sm" :loading="deleteLoadingId === props.row.id" @click="deleteMovement(props.row.id)"/>
                </q-td>
             </template>
        </q-table>
    </div>

    <q-dialog v-model="editPopup">
        <q-card>
            <EditMovement :movement="selectedMovement" @updated="closeEditPopup" @cancel-edit-popup="cancelEditPopup" />
        </q-card>
    </q-dialog>

</template>

<script setup>
import MovementPopup from 'src/components/MovementPopup.vue';
import EditMovement from 'src/components/EditMovement.vue';
import { ref, onMounted } from 'vue';
import { getMovementData } from 'src/services/movementService';
import { Notify } from 'quasar'
import { excludeMovement } from 'src/services/movementService';

const mPopup = ref(false)

const editPopup = ref(false)

const loadingTable = ref(false)

const selectedMovement = ref(null)

const closeCreateMovement = () => {
    mPopup.value = false
}

const cancelEditPopup = () => {
    editPopup.value = false
}

const showMovementPopup = () => {
    mPopup.value = true

}

const deleteLoadingId = ref(false)

const closePopup = async () => {
    mPopup.value = false

    try {
       const response = await getMovementData();

       if (response.data) {
        rows.value = response.data.movements
       }
    } catch (error) {
        console.log('Erro ao atualizar a lista após criação!',error)

        Notify.create({
      type: 'negative',
      message: 'Erro ao atualizar lista após criação!'
    });
    }
}

const closeEditPopup = async () => {
    editPopup.value = false

    try {
       const response = await getMovementData();

       if (response.data) {
        rows.value = response.data.movements
       }
    } catch (error) {
        console.log('Erro ao atualizar a lista após edição!',error)

        Notify.create({
      type: 'negative',
      message: 'Erro ao atualizar lista após edição!'
    });
    }
}

const openEditMovement = (movement) => {
    selectedMovement.value = movement

    editPopup.value = true
}

const rows = ref([]);

onMounted ( async () => {
    loadingTable.value = true
    try {
        const response = await getMovementData()

        if (response.data) {
            rows.value = response.data.movements;
            localStorage.setItem('movements', JSON.stringify(response.data))

        }
    } catch (error) {
        console.log(error)

        Notify.create({
            type:'negative',
            message:'Erro ao fazer a busca das movimentações!'
        })
    } finally {
        loadingTable.value = false
    }
})

const formatCurrency = (val) => {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  }).format(val)
}

const deleteMovement =  async (id) => {
    
        const response = await excludeMovement(id)

        deleteLoadingId.value = id

    if (response.status) {
        try {
            const responseget = await getMovementData()

        if (responseget.data) {
          rows.value = responseget.data.movements
        }

        Notify.create({
            type: 'positive',
            message:'Movimentação deletada!',
            position: 'top'
        })
        } catch (error) {
            console.log('Erro ao deletar a moviumentação!', error)
        } finally {
            deleteLoadingId.value = null
        }
    } else {
        Notify.create({
            type: 'negative',
            message:'Erro ao deletar a movimentação!',
            position: 'top'
        })
    }
}


const columns = [
    {
        name: 'category',
        required: true,
        label: 'Categoria da Movimentação',
        align: 'left',
        field: 'category',
    },

    {
    name: 'type',
    label: 'Tipo',
    align: 'center',
    field: 'type',
  },
  {
    name: 'value',
    label: 'Valor',
    align: 'center',
    field: 'value',
  },
    {
        name: 'description',
        label: 'Descrição',
        align: 'center',
        field: 'description',
        format: val => val ? val : 'Nenhuma descrição adicionada'
    },
    {
        name: 'created_at',
        label: 'Criada em',
        align: 'center',
        field: 'created_at',
        format: val => {
            const date = new Date(val)
            return date.toLocaleDateString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute:'2-digit'
            })
        }
    },

    {
        name: 'updated_at',
        label: 'Atualizada em',
        align: 'center',
        field: 'updated_at',
        format: val => {
            const date = new Date(val)
            return date.toLocaleDateString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute:'2-digit'
            })
        }
    },
    {
        name: 'actions',
        label: 'Ações',
        field: 'actions',
        align: 'center'
    }

]


</script>