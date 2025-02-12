<script setup>
import { defineProps, ref, watch } from "vue";
import axios from "axios";

const props = defineProps({
    client: Object,
    clientId: Number,
});

let maxLogNumberServices = ref([]);

const getMaxLogNumber = async () => {
    try {
        const response = await axios.get(`/api/clients/${props.clientId}/cars/max-log-number`);
        maxLogNumberServices.value = response.data;
    } catch (error) {
        console.error("Error fetching max log number services", error);
    }
};

watch(() => props.client, () => {
    getMaxLogNumber();
}, { immediate: true });

</script>

<template>
    <table class="table table-hover text-white mb-0 text-center">
        <thead>
        <tr>
            <th scope="col">Autó sorszáma</th>
            <th scope="col">Autó típusa</th>
            <th scope="col">Regisztrálás időpontja</th>
            <th scope="col">Saját márkás e</th>
            <th scope="col">Balesetek száma</th>
            <th scope="col">Legutolsó szervíznapló bejegyzés eseményének neve</th>
            <th scope="col">Legutolsó szervíznapló bejegyzés eseményének időpontja</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="car in client.cars" :key="car.id">
            <td class="align-middle">{{ car.id }}</td>
            <td class="align-middle">{{ car.type }}</td>
            <td class="align-middle">{{ car.registered }}</td>
            <td class="align-middle">{{ car.ownbrand === 1 ? 'Igen' : 'Nem' }}</td>
            <td class="align-middle">{{ car.accident }}</td>
            <td class="align-middle" v-if="maxLogNumberServices[car.car_id]">
                {{ maxLogNumberServices[car.car_id].event }}
            </td>
            <td class="align-middle" v-if="maxLogNumberServices[car.car_id]">
                {{ maxLogNumberServices[car.car_id].eventtime }}
            </td>
        </tr>
        </tbody>
    </table>
</template>
