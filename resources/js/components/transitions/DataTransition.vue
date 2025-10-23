<template>
    <TransitionGroup
        enter-active-class="transform transition duration-300 ease-out"
        enter-from-class="opacity-0 scale-95 translate-y-2"
        enter-to-class="opacity-100 scale-100 translate-y-0"
        leave-active-class="transform transition duration-200 ease-in"
        leave-from-class="opacity-100 scale-100 translate-y-0"
        leave-to-class="opacity-0 scale-95 translate-y-2"
        tag="ul"
        appear
    >
        <slot></slot>
    </TransitionGroup>
</template>
