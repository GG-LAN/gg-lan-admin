<script setup>
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Col from "@/Components/Ui/Col.vue";

const page = usePage();

const form = useForm({
    name: page.props.tournament.name,
    description: page.props.tournament.description,
    game_id: page.props.tournament.game_id,
    start_date: page.props.tournament.start_date,
    end_date: page.props.tournament.end_date,
    places: page.props.tournament.places,
    cashprize: page.props.tournament.cashprize,
});

const updateTournament = () => {
    form.put(route("tournaments.update", page.props.tournament.id), {
        preserveScroll: true,
        onSuccess: () => {},
    });
};
</script>

<template>
    <form
        @submit.prevent="updateTournament"
        class="mt-6 grid grid-cols-2 gap-4"
    >
        <!-- Start Date -->
        <Col size="2">
            <InputLabel
                for="update_date-start_date"
                value="Date de début"
                :required="true"
            />
            <TextInput
                id="update_date-start_date"
                class="mt-1 block w-full"
                type="date"
                v-model="form.start_date"
                required
            />
            <InputError class="mt-2" :message="form.errors.start_date" />
        </Col>

        <!-- End Date -->
        <Col size="2">
            <InputLabel
                for="update_date-end_date"
                value="Date de fin"
                :required="true"
            />
            <TextInput
                id="update_date-end_date"
                class="mt-1 block w-full"
                type="date"
                v-model="form.end_date"
                required
            />
            <InputError class="mt-2" :message="form.errors.end_date" />
        </Col>

        <Col size="2" class="flex items-center justify-end">
            <SubmitButton :form="form" color="success">
                {{ __("Update") }}
            </SubmitButton>
        </Col>
    </form>
</template>
