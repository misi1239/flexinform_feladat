import { createRouter, createWebHistory } from "vue-router";
import notFoundPage from "../components/NotFoundPage.vue"

const routes = [
    {
        path: "/:pathMatch(.*)*",
        component: notFoundPage
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
