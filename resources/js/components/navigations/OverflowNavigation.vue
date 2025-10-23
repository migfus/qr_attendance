<template>
    <div>
        <Swiper :slidesPerView="'auto'" :centeredSlides="false" :spaceBetween="10" class="flex rounded-xl bg-brand-50 mb-2" :navigation="true" :observer="true">
            <SwiperSlide v-for="slide in slides" :key="slide.display_name" class="my-2">
                <AppButton :href="slide.link" class="text-nowrap" :color="slide.display_name == 'All Tasks' ? 'brand' : ''">
                    {{ slide.display_name }}
                </AppButton>
            </SwiperSlide>

            <div class="absolute shadow-right bg-red z-10 top-0 right-0 h-full pt-[18px] cursor-pointer">
                <ChevronRightIcon class="h-4 w-4 mr-2 ml-4" />
            </div>
            <div class="absolute shadow-left bg-red z-10 top-0 left-0 h-full pt-[18px] cursor-pointer">
                <ChevronLeftIcon class="h-4 w-4 mr-4 ml-2" />
            </div>
        </Swiper>
    </div>
</template>

<script setup lang="ts">
import { Swiper, SwiperSlide } from "swiper/vue"
import "swiper/css"
import AppButton from "../form/AppButton.vue"
import { ChevronRightIcon, ChevronLeftIcon } from "@heroicons/vue/24/solid"

defineProps<{
    slides: {
        display_name: string
        link: string
    }[]
}>()
</script>

<style>
.swiper-slide {
    flex-shrink: unset !important;
    width: auto !important;
}
</style>
