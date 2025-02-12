<template>
    <div class="section d-flex justify-content-center align-items-center vh-100 flex-column text-center">
        <h1 class="error display-1">404</h1>
        <div class="page h3">Oops!!! The page you are looking for is not found</div>
        <router-link class="back-home btn btn-link" to="/">Back to home</router-link>
    </div>
</template>

<style scoped lang="scss">
@use "../../scss/components/not-found-page";
</style>
