<script setup>
import { onMounted, ref, computed } from "vue";
import listCarWithService from "../CarWithService/list.vue";
import search from "../Search/search.vue";

let clients = ref([]);
let searchClients = ref([]);
let isLoading = ref(false);
let expandedClientId = ref(null);

const currentPage = ref(1);
const perPage = ref(10);

const getClients = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get("/api/clients");
        clients.value = response.data.data;
    } catch (error) {
        console.error("Error fetching projects:", error);
    } finally {
        isLoading.value = false;
    }
};

const totalPages = computed(() => {
    return Math.ceil(clients.value.length / perPage.value);
});

const currentClients = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    const end = start + perPage.value;
    return clients.value.slice(start, end);
});

const changePage = (page) => {
    if (page < 1 || page > totalPages.value) {
        return;
    }
    currentPage.value = page;
};

const toggleClientDetails = (clientId) => {
    if (expandedClientId.value === clientId) {
        expandedClientId.value = null;
    } else {
        expandedClientId.value = clientId;
    }
};

const searchClientsData = (newClients) => {
    searchClients.value = newClients;
};

onMounted(async () => {
    await getClients();
});
</script>

<template>
    <section class="min-vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-12 col-xl-10">
                    <div class="card mask-custom">
                        <div class="card-body p-4 text-white">
                            <div class="text-center pt-3 pb-2">
                                <h1 class="my-4">Kliensek</h1>
                            </div>
                            <div v-if="clients.length === 0 && !isLoading">
                                <h2 class="text-center">Nincsenek adatok!</h2>
                            </div>
                            <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 200px;">
                                <div class="spinner-border text-light" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div v-if="!isLoading && clients.length > 0">
                                <search @updateClients="searchClientsData"></search>
                                <table class="table table-hover text-white mb-0 text-center">
                                    <thead>
                                    <tr>
                                        <th scope="col">Ügyfélazonosító</th>
                                        <th scope="col">Név</th>
                                        <th scope="col">Okmányazonosító</th>
                                    </tr>
                                    </thead>
                                    <tbody
                                        v-for="client in currentClients"
                                        :key="client.id"
                                        class="fw-normal"
                                    >
                                    <tr>
                                        <td class="align-middle">
                                            <span>{{ client.id }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span @click="toggleClientDetails(client.id)" style="cursor: pointer;">{{ client.name }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span>{{ client.idcard }}</span>
                                        </td>
                                    </tr>

                                    <tr v-if="expandedClientId === client.id">
                                        <td colspan="3">
                                            <list-car-with-service :client="client" :clientId="client.id"></list-car-with-service>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div v-if="searchClients.length > 0" class="mt-4">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Ügyfél azonosítója</th>
                                            <th>Ügyfél neve</th>
                                            <th>Ügyfél okmányazonosítója</th>
                                            <th>Autók darabszáma</th>
                                            <th>Szerviznaplók száma</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="client in searchClients" :key="client.id">
                                            <td>{{ client.id }}</td>
                                            <td>{{ client.name }}</td>
                                            <td>{{ client.idcard }}</td>
                                            <td>{{ client.car_count }}</td>
                                            <td>{{ client.service_count }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center mt-3">
                                    <button
                                        @click="changePage(currentPage - 1)"
                                        :disabled="currentPage === 1"
                                        class="btn btn-success"
                                    >
                                        Előző
                                    </button>
                                    <span class="mx-3">{{ currentPage }} / {{ totalPages }}</span>
                                    <button
                                        @click="changePage(currentPage + 1)"
                                        :disabled="currentPage === totalPages"
                                        class="btn btn-success"
                                    >
                                        Következő
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped lang="scss">
@use "../../../scss/components/client/list.scss";
@use "../../../scss/components/loading.scss";
</style>
