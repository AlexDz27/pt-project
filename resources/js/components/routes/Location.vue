<template>
  <Header :user="user" :signOutUser="() => $emit('signOutUser')"/>

  <section class="container">
    <h1>{{ this.location.name }}</h1>
    <h2>${{ this.location.price }} / night</h2>
    <h3>{{ this.location.city }}, {{ this.location.street }}</h3>

    <hr>

    <div>
      {{ this.location.description }}
    </div>
  </section>

  <Footer/>
</template>

<script>
import Header from '../Header';
import Footer from '../Footer';

import { ApiCaller } from '../../modules/ApiCaller';

export default {
  emits: ['signOutUser'],
  components: {Header, Footer},
  data() {
    return {
      location: {
        id: 0,
        name: '',
        description: '',
        price: 0.00,
        bedrooms: 0,
        city: '',
        street: '',
        longitude: 0.00,
        latitude: 0.00
      }
    };
  },
  async created() {
    // Prevent unauthenticated users from accessing location
    if (! this.user.isSignedIn) {
      await this.$router.push({name: 'access-denied'});
    }

    const response = await ApiCaller.getLocationById(this.$route.params.id, localStorage.getItem('token'));
    const location = await response.json();

    this.location = location;
  },
  props: {
    user: Object
  }
};
</script>
