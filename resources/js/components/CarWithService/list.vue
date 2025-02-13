<script setup>
import {defineProps, onMounted, ref} from "vue";
import Service from "../ServiceWithCar/list.vue";
import axios from "axios";

const props = defineProps({
    client: Object,
    clientId: Number,
});

let maxLogNumberServices = ref([]);
let isLoading = ref(false);
let selectedCarId = ref(null);

const getMaxLogNumber = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get(`/api/clients/${props.clientId}/cars/max-log-number`);
        maxLogNumberServices.value = response.data;
    } catch (error) {
        console.error("Error fetching max log number services", error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(async () => {
    await getMaxLogNumber();
});

const toggleCarSelection = (carId) => {
    selectedCarId.value = selectedCarId.value === carId ? null : carId;
};
</script>

<template>
    <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 200px;">
        <div class="spinner-border text-dark" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div v-else>
        <table class="table table-hover text-white mb-0 text-center border border-3">
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
                <td class="align-middle">
                    <span @click="toggleCarSelection(car.car_id)" style = "cursor: pointer">{{ car.car_id }}</span>
                </td>
                <td class="align-middle">{{ car.type }}</td>
                <td class="align-middle">{{ car.registered }}</td>
                <td class="align-middle">{{ car.ownbrand === 1 ? 'Igen' : 'Nem' }}</td>
                <td class="align-middle">{{ car.accident }}</td>
                <td class="align-middle" v-if="maxLogNumberServices[car.car_id]">
                    {{ maxLogNumberServices[car.car_id]?.event }}
                </td>
                <td class="align-middle" v-if="maxLogNumberServices[car.car_id]">
                    {{ maxLogNumberServices[car.car_id]?.eventtime }}
                </td>
            </tr>
            </tbody>
        </table>
        <service v-if="selectedCarId !== null" :client="client" :selectedCarId="selectedCarId"></service>
    </div>
</template>

<style scoped lang="scss">
@use "../../../scss/components/loading.scss";
</style>
