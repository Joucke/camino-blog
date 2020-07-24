<template>
    <div>
        <l-map
          :bounds="bounds"
          :options="mapOptions"
        >
            <l-tile-layer
                :url="url"
                :attribution="attribution"
            />
            <l-marker
                v-for="location in locations"
                :key="location.id"
                :lat-lng="latLng(location.latitude, location.longitude)">
                <l-popup>
                    <h2 class="font-bold text-base">{{ location.title }}</h2>
                    <a :href="location.url">alles op deze locatie</a>
                </l-popup>
            </l-marker>

        </l-map>
    </div>
</template>

<script>
import { latLng, latLngBounds } from "leaflet";

import { LMap, LTileLayer, LMarker, LPopup } from "vue2-leaflet";

export default {
    props: ["locations"],
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LPopup,
    },
    mounted () {
        this.calculateBounds();
    },
    data () {
        return {
            center: null,
            bounds: null,
            mapOptions: {
                zoomSnap: 0.5,
                zoomControl: false,
            },
            url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            attribution:
            '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        };
    },
    methods: {
        calculateBounds () {
            let minLat = Math.min(...this.locations.map(l => l.latitude)) / 1.0002;
            let maxLat = Math.max(...this.locations.map(l => l.latitude)) * 1.0002;
            let minLng = Math.min(...this.locations.map(l => l.longitude)) / 1.0002;
            let maxLng = Math.max(...this.locations.map(l => l.longitude)) * 1.0002;
            this.bounds = latLngBounds([
                [minLat, minLng], // southWest
                [maxLat, maxLng], // northEast
            ]);
        },
        latLng (...args) {
            return latLng(args);
        },
    },
}
</script>
