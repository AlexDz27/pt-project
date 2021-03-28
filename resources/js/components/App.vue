<template>
  <div class="app" :class="$route.name === 'search' ? 'app-fixed-header' : ''">
    <router-view
      :user="user"
      :searchParams="searchParams"
      @submitSearchParams="onSubmitSearchParams"
      @signInUser="onSignInUser"
      @signOutUser="onSignOutUser"
    />
  </div>
</template>

<script>
import { User } from '../modules/User';
import { parseJwt } from '../utils/parseJwt';
import { ApiCaller } from '../modules/ApiCaller';

export default {
  data() {
    return {
      user: {
        isSignedIn: false,
        profile: null
      },

      searchParams: {
        city: '',
        bedrooms: '1'
      }
    }
  },

  // Initialize user session in frontend when app starts, for example, to manage "Sign in", "Sign out" proper handling in header
  async created() {
    this.user.profile = JSON.parse(localStorage.getItem('profile'));
    this.user.isSignedIn = !!this.user.profile;
  },

  methods: {
    onSubmitSearchParams() {
      if (! this.user.isSignedIn) {
        this.$router.push({name: 'sign-up'});

        return;
      }

      this.$router.replace({name: 'search'});
    },

    onSignInUser(user) {
      User.signIn(user.token, user.profile);

      this.user.profile = user.profile;
      this.user.isSignedIn = true;

      this.$router.replace({name: 'home'});
    },

    onSignOutUser() {
      User.signOut();

      this.user.profile = null;
      this.user.isSignedIn = false;

      this.$router.replace({name: 'home'});
    }
  }
}
</script>
