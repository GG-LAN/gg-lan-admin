<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { onMounted, computed, ref } from "vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Forms/SubmitButton.vue";
import PrimaryButton from "@/Components/Forms/PrimaryButton.vue";
import Col from "@/Components/Ui/Col.vue";
import axios from "axios";

const props = defineProps({});

const settingKey = "lan_location";

const page = usePage();

const loading = ref(false);

const search = ref("");
const dropdown = ref();
const locations = ref();

const form = useForm({
    key: settingKey,
    value: "",
});

onMounted(() => {
    dropdown.value = document.getElementById("locations-dropdown");

    let lanLocation = page.props.settings.find(
        (element) => element.key == settingKey
    );

    if (lanLocation.value) {
        let location = JSON.parse(lanLocation.value);

        setAddress(location.address, {
            longitude: location.longitude,
            latitude: location.latitude,
        });
    }
});

const submit = () => {
    form.put(route("settings.update"), {
        preserveScroll: true,
        preserveState: true,
    });
};

const searchLocation = () => {
    loading.value = true;

    form.errors.value = "";

    hideDropdown();

    if (search.value.trim("") == "") {
        form.errors.value = "You can't search an empty address.";

        loading.value = false;

        return;
    }

    axios.get(searchUrl.value).then((data) => {
        locations.value = data.data.features;

        showDropdown();

        loading.value = false;
    });
};

const setAddress = (address, coordinates) => {
    search.value = address;

    let value = JSON.stringify({
        address: address,
        longitude: coordinates.longitude,
        latitude: coordinates.latitude,
    });

    form.value = value;

    hideDropdown();
};

const hideDropdown = (delay = null) => {
    if (delay) {
        setTimeout(() => {
            dropdown.value.classList.add("hidden");
        }, delay);
    } else {
        dropdown.value.classList.add("hidden");
    }
};

const showDropdown = () => {
    dropdown.value.classList.remove("hidden");
};

const searchUrl = computed(
    () =>
        "https://api.mapbox.com/search/geocode/v6/forward?q=" +
        search.value +
        "&access_token=" +
        page.props.mapbox_token
);
</script>

<template>
    <section>
        <header>
            <h2
                class="text-xl font-black text-gray-900 dark:text-gray-100 mb-2"
            >
                Localisation de la LAN
            </h2>
        </header>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <Col size="2">
                <form @submit.prevent="searchLocation">
                    <InputLabel
                        :for="'update_' + settingKey"
                        value="Address"
                        :required="true"
                    />

                    <div class="relative w-full">
                        <TextInput
                            :id="'update_' + settingKey"
                            class="mt-1 block w-full pe-10"
                            type="text"
                            placeholder="123, Av. de la bagarre, 29200 Brest"
                            v-model="search"
                            @focusout="hideDropdown(100)"
                            required
                        />

                        <PrimaryButton
                            type="submit"
                            id="search-location"
                            class="absolute top-0 end-0 h-full"
                            icon="magnifying-glass"
                            :loading="loading"
                        />

                        <div
                            id="locations-dropdown"
                            class="absolute z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-md w-full h-44 dark:bg-gray-700 overflow-y-scroll [&::-webkit-scrollbar-track]:rounded-lg [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded-full dark:[&::-webkit-scrollbar-track]:bg-gray-800 dark:[&::-webkit-scrollbar-thumb]:bg-gray-600 dark:[&::-webkit-scrollbar-thumb]:rounded-full"
                        >
                            <ul
                                class="py-2 text-sm text-gray-700 dark:text-gray-200"
                            >
                                <li v-for="location in locations">
                                    <span
                                        class="block px-4 py-2 flex items-center space-x-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white cursor:pointer overflow-hidden"
                                        @click="
                                            setAddress(
                                                location.properties
                                                    .full_address,
                                                location.properties.coordinates
                                            )
                                        "
                                    >
                                        {{ location.properties.full_address }}
                                        <!--  -->
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <InputError class="mt-2" :message="form.errors.value" />
                </form>
            </Col>

            <Col size="2" class="flex items-center justify-end">
                <form @submit.prevent="submit">
                    <SubmitButton :form="form" color="success">
                        {{ __("Update") }}
                    </SubmitButton>
                </form>
            </Col>
        </div>
    </section>
</template>
