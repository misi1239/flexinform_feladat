<script setup>
import { onMounted, ref, computed } from "vue";
import listCarService from "../CarWithService/list.vue";

let clients = ref([]);

const currentPage = ref(1);
const perPage = ref(10);

const getClients = async () => {
    try {
        const response = await axios.get("/api/clients");
        clients.value = response.data.data;
    } catch (error) {
        console.error("Error fetching projects:", error);
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

const expandedClientId = ref(null);

const toggleClientDetails = (clientId) => {
    if (expandedClientId.value === clientId) {
        expandedClientId.value = null;
    } else {
        expandedClientId.value = clientId;
    }
};

onMounted(async () => {
    await getClients();
});
</script>

<template>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-12 col-xl-10">
                    <div class="card mask-custom">
                        <div class="card-body p-4 text-white">
                            <div class="text-center pt-3 pb-2">
                                <h1 class="my-4">Clients</h1>
                            </div>
                            <div>
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
                                        style="cursor: pointer;"
                                        @click="toggleClientDetails(client.id)"
                                    >
                                    <tr>
                                        <td class="align-middle">
                                            <span>{{ client.id }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span>{{ client.name }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span>{{ client.idcard }}</span>
                                        </td>
                                    </tr>

                                    <tr v-if="expandedClientId === client.id">
                                        <td colspan="3">
                                            <list-car-service :client="client" :clientId="client.id"></list-car-service>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-center mt-3">
                                    <button
                                        @click="changePage(currentPage - 1)"
                                        :disabled="currentPage === 1"
                                        class="btn btn-success"
                                    >
                                        Previous
                                    </button>
                                    <span class="mx-3">{{ currentPage }} / {{ totalPages }}</span>
                                    <button
                                        @click="changePage(currentPage + 1)"
                                        :disabled="currentPage === totalPages"
                                        class="btn btn-success"
                                    >
                                        Next
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
</style>
