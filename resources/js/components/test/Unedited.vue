<template>
    <div class="bg-yellow-500 text-yellow-900 font-bold rounded-xl p-4 flex justify-between" v-if="opened">
        <div>🕛Unedited Page</div>
        <button @click="opened = !opened" class="cursor-pointer"><XMarkIcon class="size-4" /></button>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const opened = ref(true)
</script>
