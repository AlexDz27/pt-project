<template>
  <Header :user="user" :signOutUser="() => $emit('signOutUser')" />

  <section class="hero">
    <section class="d-flex justify-content-center">
      <form @submit.prevent="() => $emit('submitSearchParams')" class="property-search app__property-search">
        <input v-model="searchParams.city" class="form-control mb-2" placeholder="London" required>
        <label class="text-white" for="bedrooms">Select bedrooms quantity:</label>
        <select v-model="searchParams.bedrooms" class="form-select mb-2" id="bedrooms">
          <option value="" selected>Doesn't matter</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
        </select>
        <br>
        <button class="btn btn-success w-100" type="submit">Search</button>
      </form>
    </section>

    <div class="call-to-action hero__call-to-action container">
      <h1 class="text-7xl">
        <span class="call-to-action__part bg-purple-400">Discover rooms,</span><br>
        <span class="call-to-action__part bg-indigo-400">hotels & make assets! 💰</span>
      </h1>
    </div>
  </section>

  <Footer />
</template>

<script>
import Header from '../Header';
import Footer from '../Footer';

export default {
  emits: ['signOutUser', 'submitSearchParams'],
  components: { Header, Footer },
  props: {
    user: Object,
    searchParams: Object
  },
}
</script>
