<template>
  <Header :user="user" :signOutUser="() => $emit('signOutUser')"/>

  <section class="container">
    <div class="row">
      <div v-if="this.locations.length === 0" class="col-6">
        <h1>Sorry, no results found.</h1>
      </div>

      <div class="col-6">
        <h1 v-if="this.locations.length > 0" class="display-4 mb-7">Found locations</h1>

        <div class="mb-4">
          <div><b>Price, $:</b> [{{ this.searchPageParams.priceMin }} - {{ this.searchPageParams.priceMax }}]</div>
          <div id="slider"></div>
          <div class="d-flex justify-content-between">
            <b>0</b>
            <b>100</b>
          </div>

          <div class="mb-4">
            <b>Show count:</b>&nbsp;
            <input @input="changeShowCount" v-model="this.searchPageParams.showCount" style="width: 33px;">
          </div>

          <div>
            <b>Show order:</b>&nbsp;
            <button @click="changePriceOrder" class="btn btn-success" title="Click to toggle">{{ priceOrderText }}</button>
          </div>
        </div>

        <div v-if="this.locations.length !== 0" class="locations-list">
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
                  {{ location.bedrooms }} {{ location.bedrooms > 1 ? 'bedrooms' : 'bedroom' }}
                </span>
              </div>

              <div class="location-tab__price">
                <b>${{ location.price }}</b> / night
              </div>
            </div>
          </a>
        </div>

        <div>
          <button @click="goToPrevPage" v-if="this.searchPageParams.page > 1" class="btn btn-primary mr-6">&lt; Prev></button>
          <button @click="goToNextPage" class="btn btn-primary">Next ></button>
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
import noUiSlider from 'nouislider';
import 'nouislider/distribute/nouislider.css';
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
      searchPageParams: {
        city: '',
        bedrooms: null,
        priceMin: 5.00,
        priceMax: 100.00,
        showCount: 10,
        priceOrder: 'desc',
        page: 1
      },
      map: null,
      currentPage: 1
    }
  },
  async created() {
    // Prevent unauthenticated users from accessing search
    if (! this.user.isSignedIn) {
      await this.$router.push({name: 'access-denied'});
    }

    // this.searchPageParams = this.searchParams;
    this.searchPageParams = {
      city: this.searchParams.city,
      bedrooms: this.searchParams.bedrooms,
      priceMin: 5.00,
      priceMax: 100.00,
      showCount: 10,
      priceOrder: 'desc',
      page: 1
    };

    await this.searchLocations();
  },
  mounted() {
    this.createMap();

    setTimeout(() => {
      const slider = document.getElementById('slider');
      noUiSlider.create(slider, {
        start: [this.searchPageParams.priceMin, this.searchPageParams.priceMax],
        step: 1,
        connect: true,
        range: {
          min: 0,
          max: 100
        }
      });

      slider.noUiSlider.on('change', () => {
        const [priceMin, priceMax] = slider.noUiSlider.get();

        this.searchPageParams.priceMin = priceMin;
        this.searchPageParams.priceMax = priceMax;

        console.log(this.searchPageParams)

        this.searchLocations();
      });
    }, 200)
  },
  methods: {
    async goToNextPage() {
      this.searchPageParams.page++;

      await this.searchLocations();
    },
    async goToPrevPage() {
      this.searchPageParams.page--;

      await this.searchLocations();
    },
    async searchLocations() {
      this.locationsSearch = await ApiCaller.searchLocations(this.searchPageParams, localStorage.getItem('token'));
      this.locations = this.locationsSearch.data;

      console.log('this.locations', this.locations)

      this.listedLocations = this.locations.slice(0, 10);

      this.addPinsToMap();
    },
    changeShowCount() {
      this.searchLocations();
    },
    changePriceOrder() {
      if (this.searchPageParams.priceOrder === 'desc') {
        this.searchPageParams.priceOrder = 'asc';
      } else if (this.searchPageParams.priceOrder === 'asc') {
        this.searchPageParams.priceOrder = 'desc';
      }

      this.searchLocations();
    },
    createMap() {
      this.map = L.map('map')
    },
    addPinsToMap() {
      // Remove existing pins
      this.map.eachLayer((layer) => {
        this.map.removeLayer(layer);
      });

      // Center map on first location
      const firstLocation = this.listedLocations[0];
      this.map.setView([firstLocation.latitude, firstLocation.longitude], 13);

      // Truly create map
      L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYWxleGR6IiwiYSI6ImNrbW9zMDJ0dzI3cnAydm56MnNwZDF0aTkifQ.2HElYSbyH6nuS_5Zy4EZFg', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoiYWxleGR6IiwiYSI6ImNrbW9zMDJ0dzI3cnAydm56MnNwZDF0aTkifQ.2HElYSbyH6nuS_5Zy4EZFg'
      }).addTo(this.map);

      // Show listed locations and make popups for them that direct user to separate location page
      this.listedLocations.forEach((location) => {
        const marker = L.marker([location.latitude, location.longitude], {
          title: `${location.name}`,
          riseOnHover: true
        }).addTo(this.map);

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
  },
  computed: {
    priceOrderText() {
      if (this.searchPageParams.priceOrder === 'desc') {
        return 'Expansive to cheap';
      }

      if (this.searchPageParams.priceOrder === 'asc') {
        return 'Cheap to expansive'
      }
    }
  }
};
</script>
