<template>
  <Header :user="user" :signOutUser="() => $emit('signOutUser')"/>

  <section class="container">
    Page of location with id {{ $route.params.id }}
  </section>

  <Footer/>
</template>

<script>
import Header from '../Header';
import Footer from '../Footer';

export default {
  emits: ['signOutUser'],
  components: {Header, Footer},
  props: {
    user: Object
  }
}
</script>
