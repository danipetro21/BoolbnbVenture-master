<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <input
    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-rose-500 dark:focus:border-rose-600 focus:ring-rose-500 dark:focus:ring-rose-600 rounded-md shadow-sm"
    :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input" />
</template>
