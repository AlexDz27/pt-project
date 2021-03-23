<template>
  <Header />

  <Content />

  <Footer />
</template>

<script>
import Header from './Header';
import Content from './Content';
import Footer from './Footer';

export default {
  components: {
    Header, Content, Footer
  },

  data() {
    return {

    }
  }
}
</script>
