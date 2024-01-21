<script setup>
import { useAuthStore } from '@store/pinia';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

import NewEntry from '@components/newEntry.vue';

const authStore = useAuthStore();
const router = useRouter();
const currentDataSet = ref('users');
onMounted(() => {
    if (!authStore.isAuthenticated || authStore.user.role !== 'admin') {
        router.push('/');
    } else {
        fetchData('users')
    }

});
const people = [
    { name: 'Lindsay Walton', title: 'Front-end Developer', email: 'lindsay.walton@example.com', role: 'Member' },
]
const head = ref([]);
const body = ref([]);
const fetchData = async (endpoint) => {
    try {
        const response = await axios.get(`http://localhost:8000/api/${endpoint}`);
        if (endpoint == 'users') {
            head.value = Object.keys(response.data[0])
            body.value = response.data

        } else {
            head.value = Object.keys(response.data.data[0])
            body.value = response.data.data
        }
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
const editEntry = (dataset, data) => {
    // newEntryStore.setDataset(dataset)
    // newEntryStore.setBody(data)
    // newEntryStore.setModal(true)
}
const openModal = (item) => {
    authStore.setModal(true)
    authStore.setTable(currentDataSet.value)
    authStore.setData(item)
}
</script>

<template>
    <div class="py-4">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <select v-model="currentDataSet" @change="fetchData(currentDataSet)"
                        class="text-xl font-semibold text-gray-900">
                        <option value="users">Users</option>
                        <option value="enterprises">Enterprises</option>
                        <option value="products">Products</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-700">A list of all the users in your account including their name,
                        title,
                        email and role.</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <a type="button"
                        class="inline-flex items-center justify-center rounded-md border border-indigo-600 px-4 py-2 text-sm font-medium text-black shadow-sm sm:w-auto"
                        href="/">
                        Map
                    </a>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <button @click="openModal" type="button"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">New
                        entry</button>
                </div>
            </div>
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" v-for="item in head" :key="item"
                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            {{ item }}
                                        </th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="item in body" :key="item" class="hover:bg-gray-50">
                                        <td v-for="value in item" :key="value"
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            {{ value }}
                                        </td>
                                        <td
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <button @click="openModal(item)"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</button>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <NewEntry />
</template>