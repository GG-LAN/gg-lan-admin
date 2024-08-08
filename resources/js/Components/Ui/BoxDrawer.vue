<script setup>
import { onMounted } from 'vue';
import SvgIcon from "@/Components/Ui/SvgIcon.vue";

onMounted(() => {
});

const props = defineProps({
    uid: {
        type: String,
        required: true
    },
    title: {
        type: String,
        required: true
    },
    drawer: {
        type: Object
    }
});

const close = () => {
    props.drawer.hide()
}

</script>

<template>
    <div 
        :id="props.uid"
        class="fixed top-0 right-0 z-40 w-full h-screen max-w-xs p-4 overflow-y-auto transition-transform translate-x-full bg-white dark:bg-gray-800"
        tabindex="-1"
        aria-labelledby="drawer-label"
        aria-hidden="true"
        data-drawer-placement="right"
        :data-drawer-target="props.uid"
    >
        <h5 id="drawer-label" class="inline-flex items-center mt-16 mb-4 text-sm font-semibold text-gray-500 uppercase dark:text-gray-400">
            {{ props.title }}
        </h5>
        <button type="button" @click="close" :aria-controls="props.uid" class="
            text-gray-400
            bg-transparent
            hover:bg-gray-200
            hover:text-gray-900
            rounded-lg
            text-sm
            p-2.5
            absolute
            top-2.5
            right-2.5
            inline-flex
            items-center
            dark:hover:bg-gray-600
            dark:hover:text-white"
        >
            <SvgIcon icon="xmark" class="w-5 h-5"/>
            <span class="sr-only">Close menu</span>
        </button>

        <slot/>
    </div>
</template>