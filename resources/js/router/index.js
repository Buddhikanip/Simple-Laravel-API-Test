import { createRouter, createWebHistory } from "vue-router";

import allSuppliers from "../components/allSuppliers.vue";
import addSupplier from "../components/addSupplier.vue";
import editSupplier from "../components/editSupplier.vue";
import viewSupplier from "../components/viewSupplier.vue";
import notFound from "../NotFound.vue";

const routes = [
    {
        path: "/",
        component: allSuppliers,
    },
    {
        path: "/new",
        component: addSupplier,
    },
    {
        path: "/edit/:id",
        component: editSupplier,
    },
    {
        path: "/view/:id",
        component: viewSupplier,
    },
    {
        path: "/:pathMatch(.*)*",
        component: notFound,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
