<template>
  <q-item
    clickable
    :to="props.to"
  tag="router-link"
  >
    <q-item-section
      v-if="props.icon"
      avatar
    >
      <q-icon :name="props.icon" />
    </q-item-section>

    <q-item-section>
      <q-item-label class="text-h6 text-weight-light">{{ props.title }}</q-item-label>
    </q-item-section>
  </q-item>
</template>

<script setup>

const props = defineProps({
  title: {
    type: String,
    required: true
  },

  icon: {
    type: String,
    default: ''
  }
})
</script>
