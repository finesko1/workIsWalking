import { defineStore } from "pinia";

export const useRouterStore = defineStore('router', {
    state: () => ({
        lastVisitedRoute: '/',
    }),
    actions: {
        setLastVisitedRoute(route) {
            this.setLastVisitedRoute = route;
        },
    },
});
