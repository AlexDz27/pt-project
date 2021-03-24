<template>
  <Header :user="user" :signOutUser="() => $emit('signOutUser')" />

  <div class="container">
    <div class="row">
      <div class="col">

        <h1>About page</h1>

      </div>
    </div>
  </div>

  <Footer />
</template>

<script>
import Header from '../Header';
import Footer from '../Footer';

export default {
  components: {Header, Footer},
  props: {
    user: Object,
  },
}
</script>
