<script setup>
import SvgIcon from "@/Components/Ui/SvgIcon.vue";
const props = defineProps({
    column: {
        type: Object,
        required: true,
    },
    value: {
        // type: Number|Boolean,
        required: true,
    },
});
</script>

<template>
    <div class="flex items-center" v-if="value">
        <span
            class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2"
            v-if="!column.icon_true"
        >
        </span>
        <SvgIcon
            :icon="column.icon_true"
            class="w-4 h-4 text-green-400 mr-2"
            v-else
        />

        {{ __(column.label_true) }}
    </div>
    <div class="flex items-center" v-else>
        <span
            class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"
            v-if="!column.icon_false"
        >
        </span>
        <SvgIcon
            :icon="column.icon_false"
            class="w-4 h-4 text-red-500 mr-2"
            v-else
        />

        {{ __(column.label_false) }}
    </div>
</template>
