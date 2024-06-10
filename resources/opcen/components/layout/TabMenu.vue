<template>
  <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400 mb-4">  
    <li v-for="item in tabItems" class="me-2" :key="String(item.name)">
          <a
            :href="item.path"
            class="inline-block p-4 rounded-t-lg"
            :class="[
              route.name === item.name
                ? 'text-blue-600 bg-gray-100 active dark:bg-gray-800 dark:text-blue-500'
                : 'hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300'
            ]"
          >{{ item.label }}</a>
      </li>
  </ul>
</template>

<script setup lang="ts">
import { PropType } from 'vue';
import { useRoute } from 'vue-router';
import { SubMenuItem } from '@/models/Tab';

const props = defineProps({
  tabItems: {
    type: Object as PropType<SubMenuItem[]>,
    required: true
  }
});

const route = useRoute();
</script>

<style scoped>

</style>