<template>
    <div>
        <div class="flex justify-between items-end">
            <label for="locations" class="block text-sm font-medium leading-5">
                Locatie(s)
            </label>
            <button type="button" @click="showModal = true" class="flex w-6 h-6 items-center justify-center rounded font-bold text-lg bg-blue-800 text-yellow-200 py-2 px-2">+</button>
        </div>
        <div class="mt-1 sm:mt-0 sm:col-span-2">
            <div class="max-w-lg rounded-md shadow-sm">
                <select v-model="selectedLocations" multiple id="locations" name="locations[]" class="form-multiselect block w-full border-blue-800 focus:outline-none focus:shadow-outline-blue focus:border-blue-800  transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                    <option v-for="location in locations" :value="location.id">{{ location.title }}</option>
                </select>
            </div>
            <portal to="add-location-modal">
                <div v-show="showModal" class="z-1000 fixed bottom-0 inset-x-0 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center">
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
                                    <h3 class="text-lg text-center leading-6 font-medium" id="modal-headline">
                                        Locatie toevoegen
                                    </h3>
                                    <div class="mt-3">
                                        <label for="search-location" class="block text-sm font-medium leading-5">Locatie zoeken</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <input type="text" id="search-location" v-model="search" placeholder="Locatie zoeken" class="form-input block w-full border-blue-800 focus:outline-none focus:shadow-outline-blue focus:border-blue-800 transition duration-150 ease-in-out sm:text-sm sm:leading-5" @change="searchLocation">
                                            <button type="button" @click="searchCurrentLocation" class="absolute inset-y-0 right-0 px-3 flex items-center focus:outline-none rounded-r-md focus:border-blue-800 focus:shadow-outline-blue">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
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
                                            <label for="select-location" class="block text-sm font-medium leading-5">Kies een locatie</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <select v-model="place" id="select-location" class="form-select block w-full border-blue-800 focus:outline-none focus:shadow-outline-blue focus:border-blue-800 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                                    <option v-for="result in results" :value="result.place_id">{{ result.display_name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label for="location-title" class="block text-sm font-medium leading-5">Titel</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input type="text" id="location-title" v-model="newLocation.title" placeholder="Titel van de locatie" class="form-input block w-full border-blue-800 focus:outline-none focus:shadow-outline-blue focus:border-blue-800 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                            </div>
                                        </div>
                                        <div class="mt-3 flex">
                                            <div class="w-1/2 flex-1 min-w-0">
                                                <label for="location-lat" class="block text-sm font-medium leading-5">Latitude</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <input type="text" id="location-lat" v-model="newLocation.latitude" placeholder="latitude" class="form-input relative block w-full rounded-r-none border-blue-800 focus:outline-none focus:shadow-outline-blue focus:border-blue-800 bg-transparent focus:z-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                </div>
                                            </div>
                                            <div class="-ml-px flex-1 min-w-0">
                                                <label for="location-lng" class="block text-sm font-medium leading-5">Longitude</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <input type="text" id="location-lng" v-model="newLocation.longitude" placeholder="longitude" class="form-input relative block w-full rounded-l-none border-blue-800 focus:outline-none focus:shadow-outline-blue focus:border-blue-800 bg-transparent focus:z-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6">
                                <span class="flex w-full rounded-md shadow-sm">
                                    <button type="button" class="flex w-full justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-yellow-200 bg-blue-800 hover:bg-blue-900 focus:outline-none focus:border-blue-800 focus:shadow-outline-blue active:bg-blue-900 transition duration-150 ease-in-out sm:text-sm sm:leading-5" @click="addLocation">
                                        Toevoegen
                                    </button>
                                </span>
                            </div>
                        </div>
                    </transition>
                </div>
            </portal>
        </div>
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
        fetch('/locations', {
            headers: {
                'accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => this.locations = data);
        document.addEventListener('keyup', this.keyListener);
    },
    destroyed () {
        document.removeEventListener('keyup', this.keyListener);
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
        keyListener (e) {
            if (e.keyCode == 27) {
                this.showModal = false;
            }
        },
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
                    fetch(url, {
                        headers: {
                            accept: 'application/json'
                        }
                    })
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
            fetch(url, {
                headers: {
                    accept: 'application/json'
                }
            })
                .then(response => response.json())
                .then((data) => {
                    this.results = data;
                    this.place = data[0].place_id;
                });
        },
    },
}
</script>
