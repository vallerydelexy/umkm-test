<template>
    <TransitionRoot as="template" :show="isOpen">
        <Dialog as="div" class="relative z-10" @close="toggleModal">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                            <div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">{{ table }}
                                    </DialogTitle>
                                    <div class="mt-2">
                                        <div class="isolate -space-y-px rounded-md shadow-sm">
                                            <div v-for="field in fields[table]" :key="field"
                                                class="relative rounded-md rounded-b-none border border-gray-300 px-3 py-2 focus-within:z-10 focus-within:border-indigo-600 focus-within:ring-1 focus-within:ring-indigo-600">
                                                <label :for="field"
                                                    class="text-left block text-xs font-medium text-gray-900">{{ field
                                                    }}</label>
                                                <input v-for="index in (field === 'image' ? 3 : 1)"
                                                    :key="`${field}_${index}`" :type="fieldType(field)"
                                                    class="block w-full border-0 p-0 text-gray-900 placeholder-gray-500 focus:ring-0 sm:text-sm"
                                                    :defaultValue="getDefaultValue(field)" :id="field"
                                                    @change="handleInputFieldChanges(field, $event, getDefaultValue('id'))" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6">
                                <button type="button"
                                    class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-sm"
                                    @click="submitForm">Submit</button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
  
<script setup>
import { ref, watchEffect, onMounted } from 'vue';
import { useAuthStore } from '@store/pinia';

const fields = {
    users: ["name", "email", "password", "phone", "address", "role"],
    enterprises: ["name", "address", "description", "image", "owner", "lat", "long", "products"],
    products: ["sku", "name", "description", "image", "price", "enterprise_id"],
}
const fieldType = (field) => {
    switch (field) {
        case 'image':
            return 'file';
        case 'password':
            return 'password';
        case 'phone':
            return 'number';
        case 'email':
            return 'email';
        default:
            return 'text';
    }
}

const authStore = useAuthStore();
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
const isOpen = ref(authStore.modal);
const table = ref(authStore.table);
const data = ref(authStore.data);
const dataChanges = ref({})
onMounted(() => {
    dataChanges.value = { ...data.value, ...dataChanges.value };
});
watchEffect(() => {
    isOpen.value = authStore.modal
    table.value = authStore.table
    data.value = authStore.data
})
const toggleModal = () => {
    dataChanges.value = {}
    authStore.setModal(false);
};
const getDefaultValue = (field) => {
    return data.value[field]
}
const handleInputFieldChanges = (field, event, id) => {
    dataChanges.value.id = id
    dataChanges.value[field] = event.target.value
}
const submitForm = async () => {
    const id = dataChanges.value.id
    const url = `http://localhost:8000/api/${table.value}/${id ?? ''}`
    const requiredKeys = fields[table.value];
    const missingKeys = requiredKeys.filter(key => !(key in dataChanges.value));
    if (missingKeys.length > 0) {
        console.log(data.value)
        console.log(dataChanges.value)
        alert(`Missing required keys: ${missingKeys.join(', ')}`);
        return
    }
    console.log(data.value)
    console.log(dataChanges.value)
    try {
        const response = await axios('/api/' + `${table.value}, ${dataChanges.value}`)
        axios({
            method: dataChanges.value.id ? 'PUT' : 'POST',
            url, data: dataChanges.value
        })
        console.log(response)
    } catch (error) {
        console.log(error)
    }

}

</script>