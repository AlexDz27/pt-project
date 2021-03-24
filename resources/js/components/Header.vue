<template>
  <header>
    <nav class="app__nav navbar navbar-light py-4 bg-pur">
      <div class="container justify-content-end">
        <router-link :to="{name: 'home'}">Home</router-link>

        <div class="app__auth ml-6">
          <router-link class="btn btn-outline-primary" :to="{name: 'sign-in'}">Sign in</router-link>
          &nbsp;
          or
          &nbsp;
          <router-link class="btn btn-outline-primary" :to="{name: 'sign-up'}">Sign up</router-link>
        </div>
      </div>
    </nav>
  </header>
</template>

<script>
export default {
  mounted() {
    console.log(this.$route)
  }
}
</script>
