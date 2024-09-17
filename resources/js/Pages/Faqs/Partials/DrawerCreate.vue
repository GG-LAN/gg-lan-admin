<script setup>
import { onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import BoxDrawer from "@/Components/Ui/BoxDrawer.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TextareaInput from "@/Components/Forms/TextareaInput.vue";

onMounted(() => {
    // Wysi({
    //     el: '#create-response',
    //     onChange: (response) => form.response = response
    // });
});

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    drawer: {
        type: Object,
    },
    uid: {
        type: Number,
    },
});

let form = useForm({
    question: "",
    response: "",
});

const submit = () => {
    form.post(route("faqs.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            props.drawer.hide();
        },
    });
};

const close = () => {
    props.drawer.hide();
};
</script>

<template>
    <BoxDrawer
        :uid="'drawer-create-' + props.uid"
        :title="props.title"
        :drawer="props.drawer"
    >
        <form @submit.prevent="submit">
            <div class="space-y-4">
                <div>
                    <InputLabel
                        for="create-question"
                        value="Question"
                        :required="true"
                    />
                    <TextInput
                        id="create-question"
                        type="text"
                        v-model="form.question"
                        placeholder="Pourquoi ?"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.question" />
                </div>

                <div>
                    <InputLabel
                        for="create-response"
                        value="Réponse"
                        :required="true"
                    />
                    <TextareaInput
                        id="create-response"
                        v-model="form.response"
                        placeholder="Parce que..."
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.response" />
                </div>

                <div
                    class="bottom-0 left-0 flex justify-center w-full pb-4 space-x-4 md:px-4 md:absolute"
                >
                    <button
                        :disabled="form.processing"
                        type="submit"
                        class="text-white w-full justify-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                    >
                        Ajouter
                    </button>
                    <button
                        :disabled="form.processing"
                        @click="close"
                        type="button"
                        :data-drawer-dismiss="'drawer-create-' + props.uid"
                        :aria-controls="'drawer-create-' + props.uid"
                        class="inline-flex w-full justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                    >
                        <svg
                            aria-hidden="true"
                            class="w-5 h-5 -ml-1 sm:mr-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </BoxDrawer>
</template>

<style>
.wysi-editor {
    a {
        color: rgb(250 142 142 / var(--tw-text-opacity));
        text-decoration: underline;
    }

    ul {
        list-style: disc;
        margin-left: 1.5rem;
        color: rgb(255 255 255 / var(--tw-text-opacity));
    }

    ol {
        list-style: none;
        margin: 0;
        padding: 0;
        counter-reset: outer-counter;
    }

    ol > li {
        margin-left: 3rem;
    }

    li {
        counter-increment: outer-counter;
    }

    ol > li::marker {
        content: counter(outer-counter, upper-roman) ". ";
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,
            Liberation Mono, Courier New, monospace;
        font-size: 0.875rem;
        line-height: 1.25rem;
        font-weight: 700;
        text-transform: uppercase;
        --tw-text-opacity: 1;
        color: rgb(245 83 83 / var(--tw-text-opacity));
    }

    h2 {
        letter-spacing: 0.025em;
        text-transform: uppercase;
        font-weight: 500;
        --tw-text-opacity: 1;
        color: rgb(142 153 171 / var(--tw-text-opacity));
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    h3,
    h4 {
        font-weight: 700;
    }

    p {
        display: block;
        margin-block-start: 1em;
        margin-block-end: 1em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        unicode-bidi: isolate;
        color: rgb(255 255 255 / var(--tw-text-opacity));
    }
}
</style>
