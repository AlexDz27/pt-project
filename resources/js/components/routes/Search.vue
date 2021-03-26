<template>
  <Header :user="user" :signOutUser="() => $emit('signOutUser')"/>

  <section class="container">
    <div class="row">
      <div class="col-6">
        <h1 class="display-4 mb-7">Found locations</h1>

        <div class="locations-list">
          <button class="location-tab">
            <div class="location-tab__photo-container">
              <span class="location-tab__no-photo">No photo</span>
            </div>

            <div class="location-tab__content">
              <h4>Apartment "Temptation height"</h4>

              <hr class="location-tab-separator location-tab__separator">

              <div class="location-tab__amenities-container">
                <span class="location-tab__amenity">
                  1 bedroom
                </span>
                <span class="location-tab__amenity">
                  Wifi
                </span>
              </div>
            </div>
          </button>
        </div>
      </div>

      <div class="col-6">
        <div id="map" class="map">

        </div>
      </div>
    </div>
  </section>

  <Footer/>
</template>

<script>
import Header from '../Header';
import Footer from '../Footer';

import '../../../../node_modules/leaflet/dist/leaflet.css';
import '../../../../node_modules/leaflet/dist/leaflet';
import { fetchLocations } from '../../modules/fetchLocations';

export default {
  emits: ['signOutUser'],
  components: {Header, Footer},
  props: {
    user: Object
  },
  mounted() {
    this.createList();

    // this.createMap();
  },
  methods: {
    createList() {
      console.log('123')
    },

    createMap() {
      const map = L.map('map').setView([51.505, -0.09], 13);

      L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYWxleGR6IiwiYSI6ImNrbW9zMDJ0dzI3cnAydm56MnNwZDF0aTkifQ.2HElYSbyH6nuS_5Zy4EZFg', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoiYWxleGR6IiwiYSI6ImNrbW9zMDJ0dzI3cnAydm56MnNwZDF0aTkifQ.2HElYSbyH6nuS_5Zy4EZFg'
      }).addTo(map);

      const locations = fetchLocations();

      for (let i = 0; i <= 10; i++) {
        const location = locations[i];

        L.marker([location.latitude, location.longitude]).addTo(map);
      }
    }
  }
};
</script>
