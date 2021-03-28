<template>
  <Header :user="user" :signOutUser="() => $emit('signOutUser')"/>

  <section class="container">
    <div class="row">
      <div v-if="this.locations.length === 0" class="col-6">
        <h1>Sorry, no results found.</h1>
      </div>

      <div v-else class="col-6">
        <h1 class="display-4 mb-7">Found locations</h1>

        <div class="locations-list">
          <a v-for="location in this.listedLocations" :href="`/locations/${location.id}`" class="location-tab" target="_blank">
            <div class="location-tab__photo-container">
              <span class="location-tab__no-photo">No photo</span>
            </div>

            <div class="location-tab__content">
              <h4>{{ location.name }}</h4>
              <h5>{{ location.city }}</h5>

              <hr class="location-tab-separator location-tab__separator">

              <div class="location-tab__amenities-container">
                <span class="location-tab__amenity">
                  1 bedroom
                </span>
                <span class="location-tab__amenity">
                  Wifi
                </span>
              </div>

              <div class="location-tab__price">
                <b>${{ location.price }}</b> / night
              </div>
            </div>
          </a>
        </div>
      </div>

      <div class="col-6">
        <div id="map" class="map"></div>
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
import { ApiCaller } from '../../modules/ApiCaller';
import { htmlToElement } from '../../utils/htmlToElement';

export default {
  emits: ['signOutUser'],
  components: {Header, Footer},
  props: {
    user: Object,
    searchParams: Object
  },
  data() {
    return {
      locationsSearch: {},
      locations: [],
      listedLocations: [],
    }
  },
  async created() {
    // Prevent unauthenticated users from accessing search
    if (! this.user.isSignedIn) {
      await this.$router.push({name: 'access-denied'});
    }

    this.locationsSearch = await ApiCaller.searchLocations(this.searchParams, localStorage.getItem('token'));
    this.locations = this.locationsSearch.data;

    console.log('this.locations', this.locations)

    this.listedLocations = this.locations.slice(0, 10);
  },
  mounted() {
    this.createMap();
  },
  methods: {
    createMap() {
      // Center map on first location
      const firstLocation = this.listedLocations[0];
      const map = L.map('map').setView([firstLocation.latitude, firstLocation.longitude], 13);
      // Truly create map
      L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYWxleGR6IiwiYSI6ImNrbW9zMDJ0dzI3cnAydm56MnNwZDF0aTkifQ.2HElYSbyH6nuS_5Zy4EZFg', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoiYWxleGR6IiwiYSI6ImNrbW9zMDJ0dzI3cnAydm56MnNwZDF0aTkifQ.2HElYSbyH6nuS_5Zy4EZFg'
      }).addTo(map);

      // Show listed locations and make popups for them that direct user to separate location page
      this.listedLocations.forEach((location) => {
        const marker = L.marker([location.latitude, location.longitude], {
          title: `${location.name}`,
          riseOnHover: true
        }).addTo(map);

        const popupHtml = () => {
          const linkHtml = `
            <a href="/locations/${location.id}" target="_blank">
              <h5>${location.name}</h5>
              <h6>$${location.price} / night</h6>
            </a>`
          ;

          const link = htmlToElement(linkHtml);

          return link;
        }
        marker.bindPopup(popupHtml);
      });
    }
  }
};
</script>
