<template>
  <div class="container text-center mt-40">
    <h1 class="text-9xl">Not Found</h1>
    <p class="text-2xl">Whoops! Looks like the page you requested cannot be found.</p>
    <router-link class="text-2xl" :to="{name: 'home'}">Go to home page</router-link>
  </div>
</template>
