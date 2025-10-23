<template>
    <div>
        <label :for="name" class="text-brand-700 dark:text-neutral-200 font-semibold text-sm">{{ name }}</label>

        <Menu as="div" class="relative text-left w-full flex">
            <div class="flex-auto">
                <input
                    v-model="$model"
                    :name="name"
                    class="rounded-l-xl bg-wite dark:bg-dark-001 h-10 flex-auto border border-brand-200 dark:border-neutral-600 text-sm w-full px-3"
                    @focus="isFocused = true"
                    @blur="isFocused = false"
                />
            </div>

            <MenuButton class="">
                <button
                    @click="
                        () => {
                            isFocused = true
                            open_menu = !open_menu
                        }
                    "
                    class="rounded-r-lg px-2 py-2 bg-brand-600 dark:bg-brand-700 border border-brand-600 dark:border-brand-600 cursor-pointer"
                    type="button"
                >
                    <ChevronDownIcon v-if="filtered_suggestions.length > 0" class="h-5 w-5 text-brand-100 dark:text-neutral-400" />
                    <CheckIcon v-else class="h-5 w-5 text-brand-100 dark:text-neutral-400" />
                </button>
            </MenuButton>

            <div class="flex flex-col z-10 absolute mt-10 w-full">
                <MenuItems
                    ref="menuItems"
                    static
                    v-show="open_menu"
                    class="max-h-72 text-left w-full bg-white dark:bg-dark-001 rounded-lg shadow-lg border border-brand-200 dark:border-neutral-600 flex flex-col overflow-y-auto divide-y-2 divide-brand-100 dark:divide-neutral-600"
                >
                    <MenuItem
                        as="div"
                        v-for="item in filtered_suggestions"
                        :key="item.id"
                        @mousedown.prevent="selected(item.name)"
                        :class="[
                            $model == item.name ? 'bg-brand-600 dark:bg-brand-800 text-white' : 'hover:bg-brand-100 dark:hover:bg-dark-003',
                            'flex justify-between items-center px-4 py-2 w-full cursor-pointer'
                        ]"
                    >
                        <div class="flex items-center gap-2">
                            <img v-if="item.image_url" :src="item.image_url" class="h-4 w-4" />
                            <div class="line-clamp-2">{{ `${item.name}` }}</div>
                        </div>

                        <CheckCircleIcon v-if="$model == item.name" class="h-5 w-5 text-brand-200" />
                    </MenuItem>
                </MenuItems>
            </div>
        </Menu>

        <label v-if="errors" class="block text-sm font-medium text-red-600">
            {{ errors }}
        </label>
    </div>
</template>

<script setup lang="ts">
import { CheckCircleIcon, ChevronDownIcon } from '@heroicons/vue/24/solid'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { computed, ref, watch } from 'vue'
import { CheckIcon } from '@heroicons/vue/24/outline'
import { Select } from '@/globalTypes'

const isFocused = ref(false)

const { suggestions } = defineProps<{
    name: string
    suggestions: Select[]
    errors?: string[]
}>()
const $model = defineModel<string>({ default: '' })

const open_menu = ref(false)
const menuItems = ref<HTMLElement | null>(null)
const selected_ = ref(false)
const $emit = defineEmits(['search'])

const filtered_suggestions = computed(() => {
    if (!$model.value) return suggestions
    return suggestions.filter((d) => {
        const q = $model.value.toLowerCase()
        if (d.short_name) {
            return d.name.toLowerCase().includes(q) || d.short_name.toLowerCase().includes(q)
        } else {
            return d.name.toLowerCase().includes(q)
        }
    })
})

watch([filtered_suggestions, isFocused], ([suggestions, focused]) => {
    if (selected_.value) {
        open_menu.value = false
        selected_.value = false
    } else if (suggestions.length > 0 && focused) {
        open_menu.value = true
    } else {
        open_menu.value = false
    }
})

function selected(name: string) {
    $model.value = name
    open_menu.value = false
    selected_.value = true
}
</script>
