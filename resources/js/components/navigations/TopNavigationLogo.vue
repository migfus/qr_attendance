<template>
    <div class="flex gap-1 items-center">
        <div clas="p-4">
            <img :src="`/images/ohrm.png`" alt="OHRM Logo" class="block w-auto lg:hidden mr-4 my-2 size-8" />
            <img :src="`/images/ohrm.png`" alt="OHRM Logo" class="hidden w-auto lg:block mr-4 my-2 size-8" />
        </div>

        <div class="flex flex-col font-semibold mr-2 text-brand-700 dark:text-brand-100">
            <div class="flex text-sm">QR Attendance</div>
            <div class="flex text-sm">OHRM | CMU</div>
        </div>
    </div>
</template>
