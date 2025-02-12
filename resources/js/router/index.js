import { createRouter, createWebHistory } from "vue-router";
import notFoundPage from "../components/NotFoundPage.vue"
import clientList from "../components/Client/list.vue"

const routes = [
    {
        path: "/",
        component: clientList
    },
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
