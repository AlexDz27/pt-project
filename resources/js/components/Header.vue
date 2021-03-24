<template>
  <header>
    <nav class="app__nav navbar navbar-light py-4 bg-pur">
      <div class="container justify-content-end">
        <router-link :to="{name: 'home'}" class="mr-6">Home</router-link>

        <router-link :to="{name: 'about'}" class="mr-6">About us</router-link>

        <div class="app__auth" v-if="! user.isSignedIn">
          <router-link class="btn btn-outline-primary" :to="{name: 'sign-in'}">Sign in</router-link>
          &nbsp;
          or
          &nbsp;
          <router-link class="btn btn-outline-primary" :to="{name: 'sign-up'}">Sign up</router-link>
        </div>
        <div class="app__auth" v-else>
          <button @click="signOutUser" class="btn btn-outline-primary">Sign out</button>
        </div>
      </div>
    </nav>
  </header>
</template>

<script>
export default {
  props: {
    user: Object,
    signOutUser: Function
  },
}
</script>
