<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
  checked: {
    type: [Array, Boolean],
    default: false,
  },
  value: {
    default: null,
  },
});

const proxyChecked = computed({
  get() {
    return props.checked;
  },

  set(val) {
    emit('update:checked', val);
  },
});
</script>

<template>
  <input type="checkbox" :value="value" v-model="proxyChecked"
    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-rose-600 shadow-sm focus:ring-rose-500 dark:focus:ring-rose-600 dark:focus:ring-offset-gray-800" />
</template>
