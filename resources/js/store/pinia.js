import { createPinia, defineStore } from "pinia";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";
export const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

export const useAuthStore = defineStore("auth", {
    state: () => ({
        isAuthenticatedState: false,
        userState: null,
        tokenState: null,

        modalState: false,
        dataState: null,
        tableState: null,
    }),
    actions: {
        setAuthentication(status) {
            this.isAuthenticatedState = status;
        },
        setUser(user) {
            this.userState = user;
        },
        setToken(token) {
            this.tokenState = token;
        },
        setModal(status) {
            this.modalState = status;
        },
        setData(data) {
            this.dataState = data;
        },
        setTable(table) {
            this.tableState = table;
        },
    },
    getters: {
        isAuthenticated: (state) => state.isAuthenticatedState,
        user: (state) => state.userState,
        token: (state) => state.tokenState,

        modal: (state) => state.modalState,
        data: (state) => state.dataState,
        table: (state) => state.tableState,
    },
    persist: true,
});
