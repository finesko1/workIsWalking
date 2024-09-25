import { defineStore } from "pinia";

export const useRouterStore = defineStore('router', {
    state: () => ({
        lastVisitedRoute: localStorage.getItem('lastVisitedRoute') || '/',
    }),
    actions: {
        setLastVisitedRoute(route) {
            this.lastVisitedRoute = route;
            localStorage.setItem('lastVisitedRoute', route);
        },
    },
});
