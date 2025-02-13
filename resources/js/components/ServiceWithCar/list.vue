<script setup>
import { defineProps, computed } from "vue";

const props = defineProps({
    client: Object,
    selectedCarId: Number
});

const servicesForSelectedCar = computed(() => {
    return props.client.services.filter(service => service.car_id === props.selectedCarId);
});
</script>

<template>
    <table class="table table-hover text-white mb-0 text-center border border-5">
        <thead>
        <tr>
            <th scope="col">Alkalom sorszáma</th>
            <th scope="col">Esemény neve</th>
            <th scope="col">Esemény időpontja</th>
            <th scope="col">Munkalap azonosító</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="service in servicesForSelectedCar" :key="service.id">
            <td class="align-middle">{{ service.lognumber }} </td>
            <td class="align-middle">{{ service.event }}</td>

            <td class="align-middle">
                <span v-if="service.event === 'regisztralt'">
                    {{ client.cars.find(car => car.car_id === service.car_id)?.registered }}
                </span>
                <span v-else>
                    {{ service.eventtime }}
                </span>
            </td>

            <td class="align-middle">{{ service.document_id }}</td>
        </tr>
        </tbody>
    </table>
</template>
