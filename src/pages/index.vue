<template>
    <div class="flex">
        <div class="w-1/5 p-4 bg-gray-200">
            <div class="flex-row flex justify-between">
                <div>bagian user</div>
                <a href="/login">login</a>
            </div>
            <div class="py-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <!-- Heroicon name: solid/search -->
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input id="search" name="search"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Cari UMKM" type="search" v-model="searchQuery" @keyup.enter="searchEnterprises" />
                </div>
            </div>

            <div class="py-4">
                <div v-if="currentPlace" :key="currentPlace" class="space-x-4 flex flex-row pb-4">
                    <div v-for="index in 3" :key="index" class="h-32 max-w-24">
                        <img class="object-cover" :src="'https://source.unsplash.com/random/' + currentPlace.id">
                    </div>
                </div>


                <div v-for="place in places" :key="place.id">
                    <div class="flex hover:cursor-pointer" @click="selectPlace(place)">
                        <div class="mr-4 flex-shrink-0">
                            <svg class="h-16 w-16 border border-gray-300 bg-white text-gray-300" preserveAspectRatio="none"
                                stroke="currentColor" fill="none" viewBox="0 0 200 200" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-width="1" d="M0 0l200 200M0 200L200 0" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold">{{ `${place.name} ${place.id} (${place.address})` }}</h4>
                            <p class="mt-1">{{ place.description }}</p>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div v-if="currentPlace" class="w-1/5 p-4 bg-gray-100">
            <div class="flex-row flex justify-between">
                <div>
                    produk
                </div>
                <div class="hover:cursor-pointer" @click="currentPlace = null">
                    x
                </div>
            </div>
            <div class="py-4">
                <div>{{ products?.length < 1 ? 'tidak ada produk' : '' }}</div>
                        <div v-if="products?.length > 0" :key="products?.length" class="space-x-4 flex flex-row pb-4">
                            <div v-for="index in 3" :key="index" class="h-32 max-w-24">
                                <img class="object-cover" :src="'https://source.unsplash.com/random/' + currentProduct.id">
                            </div>
                        </div>
                        <div v-for="product in products" :key="product.id">
                            <div class="flex hover:cursor-pointer" @click="selectProduct(product)">
                                <div class="mr-4 flex-shrink-0">
                                    <svg class="h-16 w-16 border border-gray-300 bg-white text-gray-300"
                                        preserveAspectRatio="none" stroke="currentColor" fill="none" viewBox="0 0 200 200"
                                        aria-hidden="true">
                                        <path vector-effect="non-scaling-stroke" stroke-width="1"
                                            d="M0 0l200 200M0 200L200 0" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold">{{ `${product.name} ${product.id}` }}</h4>
                                </div>
                            </div>
                        </div>

                </div>
            </div>

            <div>
                <GMapMap :center="{ lat: center.lat, lng: center.lng }" :zoom="17" map-type-id="terrain"
                    :style="mapContainerStyle" :options="{
                        zoomControl: true,
                        mapTypeControl: true,
                        scaleControl: true,
                        streetViewControl: true,
                        rotateControl: true,

                    }
                        ">
                    <GMapMarker :key="marker.id" v-for=" marker  in  markers " :position="marker.position" :clickable="true"
                        @click="selectPlace(marker.place)" />
                </GMapMap>
            </div>
        </div>
</template>
<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
const searchQuery = ref('');
const center = ref({ lat: -7.954286, lng: 112.6555793 });
const markers = ref([]);
const places = ref([]);
const currentPlace = ref(null);
const currentProduct = ref(null)
const products = ref(null);

const searchEnterprises = async () => {
    try {
        const response = await axios.get('/api/enterprises/search/?q=' + searchQuery.value);
        places.value = response.data.data;
    } catch (error) {
        console.error('Error fetching enterprises:', error);
    }
};

const getEnterprisesData = async () => {
    try {
        const response = await axios.get('/api/enterprises');
        places.value = response.data.data;

        markers.value = places.value.map((enterprise) => ({
            id: enterprise.id,
            position: {
                lat: Number(enterprise.lat),
                lng: Number(enterprise.long),
            },
            place: enterprise
        }));

        center.value = {
            lat: Number(places.value[0].lat),
            lng: Number(places.value[0].long),
        };
    } catch (error) {
        console.error('Error fetching enterprises:', error);
    }
};

const getProductsData = async (enterprise_id) => {
    try {
        const response = await axios.get('/api/products/enterprise/' + enterprise_id);
        products.value = response.data.data;
    } catch (error) {
        console.error('Error fetching products:', error);
    }
};

onMounted(getEnterprisesData);
const mapContainerStyle = computed(() => {
    return {
        width: currentPlace.value ? '60vw' : '80vw',
        height: '100vh',
    };
});

const selectPlace = (place) => {
    currentPlace.value = place;
    center.value = {
        lat: Number(place.lat),
        lng: Number(place.long),
    };
    getProductsData(place.id);
};

const selectProduct = (product) => {
    currentProduct.value = product;
}
</script>