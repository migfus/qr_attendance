<template>


                        <RadioGroupOption as="template" v-for="item in select_version" :key="item.name" :value="item.name" v-slot="{ active, checked }">
                            <div
                                :class="[
                                    active ? '' : 'bg-dark-001',
                                    'relative flex cursor-pointer rounded-lg bg-light-002 dark:bg-dark-001 p-4 object-shadow focus:outline-none '
                                ]"
                            >
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <RadioGroupLabel as="span" class="block text-sm font-medium text-light-002">{{ item.name }}</RadioGroupLabel>
                                        <RadioGroupDescription as="span" class="mt-1 flex items-center text-sm text-brand-300">
                                            {{ item.description }}
                                        </RadioGroupDescription>
                                        <!-- <RadioGroupDescription as="span" class="mt-6 text-sm font-medium text-gray-900">{{ mailingList.users }}</RadioGroupDescription> -->
                                    </span>
                                </span>
                                <CheckCircleIcon :class="[!checked ? 'invisible' : '', 'h-5 w-5 text-brand-400']" aria-hidden="true" />
                                <span
                                    :class="[
                                        active ? 'border' : 'border-2',
                                        checked ? 'border-brand-700' : 'border-transparent',
                                        'pointer-events-none absolute -inset-px rounded-lg'
                                    ]"
                                    aria-hidden="true"
                                />
                            </div>
                        </RadioGroupOption>




</template>

<script setup lang="ts">

</script>

<style scoped>

</style>
