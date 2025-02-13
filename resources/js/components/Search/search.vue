<script setup>
import { ref, defineEmits } from "vue";
import axios from "axios";

const emit = defineEmits();

let customerName = ref("");
let cardNumber = ref("");
let errorMessage = ref("");
let clients = ref([]);

const search = async () => {
    const cardNumberPattern = /^[A-Za-z0-9]+$/;
    errorMessage.value = "";
    if (!customerName.value && !cardNumber.value) {
        errorMessage.value = "Az ügyfél neve vagy az okmányazonosító megadása kötelező!";
        return;
    }

    if (customerName.value && cardNumber.value) {
        errorMessage.value = "Csak az egyik mező lehet kitöltve!";
        return;
    }
    if (cardNumber.value && !cardNumberPattern.test(cardNumber.value)) {
        errorMessage.value = "Az okmányazonosító csak számokat és betűket tartalmazhat!";
        return;
    }

    const formData = new FormData();
    formData.append("name", customerName.value);
    formData.append("idcard", cardNumber.value);

    try {
        const response = await axios.post("/api/client-search", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        clients.value = response.data.clients;
        emit('updateClients', clients.value);
    } catch (error) {
        console.error("Keresési hiba:", error);
        if (error.response && error.response.data) {
            errorMessage.value = error.response.data.error || "Hiba történt a keresés során.";
        }
    }
};
</script>

<template>
    <div class="search-container">
        <form @submit.prevent="search">
            <div class="form-group">
                <label for="customer-name">Ügyfél neve</label>
                <input
                    type="text"
                    id="customer-name"
                    v-model="customerName"
                    class="form-control"
                    placeholder="Ügyfél neve"
                />
            </div>

            <div class="form-group">
                <label for="cardNumber">Ügyfél okmányazonosítója</label>
                <input
                    type="text"
                    id="cardNumber"
                    v-model="cardNumber"
                    class="form-control"
                    placeholder="Ügyfél okmányazonosítója"
                />
            </div>

            <div v-if="errorMessage" class="alert alert-danger">
                {{ errorMessage }}
            </div>
            <button type="submit" class="btn btn-primary">Keresés</button>
        </form>
    </div>
</template>

<style scoped lang="scss">
@use "../../../scss/components/search/search";
</style>
