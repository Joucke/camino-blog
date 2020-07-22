<template>
    <div class="mt-1 sm:mt-0 sm:col-span-2 flex items-start">
        <div class="max-w-lg rounded-md shadow-sm w-1/2">
            <select v-model="selectedLocations" multiple id="locations" name="locations[]" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                <option v-for="location in locations" :value="location.id">{{ location.title }}</option>
            </select>
        </div>
        <button type="button" @click="showModal = true"
             class="ml-2 h-auto inline-flex justify-center w-1/2 py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out"
        >nieuwe locatie toevoegen</button>
        <portal to="add-location-modal">
            <div v-show="showModal" class="fixed bottom-0 inset-x-0 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center">
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-class="opacity-100"
                    leave-to-class="opacity-0"
                    >
                    <div v-show="showModal" class="fixed inset-0 transition-opacity" @click="showModal = false">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                </transition>

                <transition
                    enter-active-class="ease-out duration-300"
                    enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                    <div class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-sm sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                        <div>
                            <div class="mt-3 sm:mt-5">
                                <h3 class="text-lg text-center leading-6 font-medium text-gray-900" id="modal-headline">
                                    Locatie toevoegen
                                </h3>
                                <div class="mt-6 text-left">
                                    <label for="search-location" class="block text-sm font-medium leading-5 text-gray-700">Locatie zoeken</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="search-location" v-model="search" placeholder="Locatie zoeken" class="form-input block w-full pr-10 sm:text-sm sm:leading-5" @change="searchLocation">
                                        <button type="button" @click="searchCurrentLocation" class="absolute inset-y-0 right-0 px-3 flex items-center focus:outline-none rounded-r-md focus:border-indigo-700 focus:shadow-outline-indigo">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div v-if="searching" class="mt-3 flex items-center">
                                    <div class="lds-ripple">
                                        <div class=""></div>
                                        <div class=""></div>
                                    </div>
                                    <span class="ml-1">Aan het zoeken...</span>
                                </div>
                                <div v-if="results.length">
                                    <div class="mt-3">
                                        <label for="select-location" class="block text-sm font-medium leading-5 text-gray-700">Kies een locatie</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <select v-model="place" id="select-location" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                                <option v-for="result in results" :value="result.place_id">{{ result.display_name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label for="location-title" class="block text-sm font-medium leading-5 text-gray-700">Titel</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <input type="text" id="location-title" v-model="newLocation.title" placeholder="Titel van de locatie" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                        </div>
                                    </div>
                                    <div class="mt-3 flex">
                                        <div class="w-1/2 flex-1 min-w-0">
                                            <label for="location-lat" class="block text-sm font-medium leading-5 text-gray-700">Latitude</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input type="text" id="location-lat" v-model="newLocation.latitude" placeholder="latitude" class="form-input relative block w-full rounded-r-none bg-transparent focus:z-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                            </div>
                                        </div>
                                        <div class="-ml-px flex-1 min-w-0">
                                            <label for="location-lng" class="block text-sm font-medium leading-5 text-gray-700">Longitude</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input type="text" id="location-lng" v-model="newLocation.longitude" placeholder="longitude" class="form-input relative block w-full rounded-l-none bg-transparent focus:z-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6">
                            <span class="flex w-full rounded-md shadow-sm">
                                <button type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150 sm:text-sm sm:leading-5" @click="addLocation">
                                    Toevoegen
                                </button>
                            </span>
                        </div>
                    </div>
                </transition>
            </div>
        </portal>
    </div>
</template>

<script>
export default {
    props: ['originalSelected'],
    data () {
        return {
            place: null,
            results: [],
            newLocation: {
                title: '',
                latitude: 0,
                longitude: 0,
            },
            search: '',
            showModal: false,
            locations: [],
            selectedLocations: this.originalSelected,
            searching: false,
        };
    },
    mounted () {
        fetch('/locations')
            .then(response => response.json())
            .then(data => this.locations = data);
    },
    watch: {
        place (selectedPlaceId) {
            let place = this.results.find(r => r.place_id == selectedPlaceId);
            if (place) {
                [this.newLocation.title] = place.display_name.split(',');
                this.newLocation.latitude = place.lat;
                this.newLocation.longitude = place.lon;
            }
        },
    },
    methods: {
        addLocation () {
            let csrf = document.querySelector('meta[name="csrf-token"]').content;

            fetch('/locations', {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': csrf,
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify(this.newLocation),
            })
                .then(response => response.json())
                .then((data) => {
                    this.locations = data;
                    this.showModal = false;
                });
        },
        searchCurrentLocation () {
            this.searching = true;
            navigator.geolocation.getCurrentPosition(
                ({coords}) => {
                    let url = `/geocode-reverse?lat=${encodeURIComponent(coords.latitude)}&lng=${encodeURIComponent(coords.longitude)}`;
                    fetch(url)
                        .then(response => response.json())
                        .then((data) => {
                            this.results = [data];
                            this.place = data.place_id;
                            this.searching = false;
                        });
                },
                (err) => {
                    console.log('error', err);
                    this.searching = false;
                }
            );
        },
        searchLocation () {
            let url = `/geocode-search?q=${encodeURIComponent(this.search)}`;
            fetch(url)
                .then(response => response.json())
                .then((data) => {
                    this.results = data;
                    this.place = data[0].place_id;
                });
        },
    },
}
</script>
