<template>
  <Header :user="user" />

  <section class="container">
    <h2>Sorry, only authenticated users can access that page.</h2>
  </section>

  <Footer/>
</template>

<script>
import Header from '../Header';
import Footer from '../Footer';

export default {
  components: {Header, Footer},
  props: {
    user: Object
  }
}
</script>
