<template>
    <div class="w-full flex justify-center py-5 text-white">
        <font-awesome-icon class="animate-spin h-10 w-10" :icon="['fas', 'spinner']" />
    </div>
</template>

<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core';
import { faSpinner } from '@fortawesome/free-solid-svg-icons';
library.add(faSpinner);
</script>

<style scoped>

</style>