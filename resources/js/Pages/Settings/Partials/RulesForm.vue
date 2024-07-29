<script setup>
import { useForm } from '@inertiajs/vue3';
// import InputError from '@/Components/Forms/InputError.vue';
import SubmitButton from '@/Components/Forms/SubmitButton.vue';
import { onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
});

onMounted(() => {
    Wysi({
        el: '#rule',
        height: 400,
        onChange: (content) => form.content = content
    });

    // let editor = document.querySelector(".wysi-editor")

    axios.get(route("rules.show.api"))
    .then(({data}) => {
        if (data.content) {
            document.querySelector(".wysi-editor").innerHTML = data.content
        }
    })
});

const form = useForm({
    content: ""
});

const submit = () => {
    form.post(route("rules.update"), {
        preserveScroll: true,
        preserveState: true
    })
}

</script>

<template>
    <section>
        <header>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100 mb-2">Règlement</h2>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6">

            <textarea id="rule"></textarea>

            <div class="flex items-center gap-4">
                <SubmitButton :form="form">
                    Mettre à jour
                </SubmitButton>
            </div>
        </form>        
    </section>
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

    ol>li {
        margin-left: 3rem;
    }

    li {
        counter-increment: outer-counter;
    }

    ol>li::marker {
        content: counter(outer-counter, upper-roman) ". ";
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, Liberation Mono, Courier New, monospace;
        font-size: .875rem;
        line-height: 1.25rem;
        font-weight: 700;
        text-transform: uppercase;
        --tw-text-opacity: 1;
        color: rgb(245 83 83 / var(--tw-text-opacity));
    }

    h2 {
        letter-spacing: .025em;
        text-transform: uppercase;
        font-weight: 500;
        --tw-text-opacity: 1;
        color: rgb(142 153 171 / var(--tw-text-opacity));
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    h3, h4 {
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