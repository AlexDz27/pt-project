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

  // Initialize user session if user already has token. Happens on app reload.
  async created() {
    const currentToken = localStorage.getItem('token');
    if (currentToken === null) return;

    const currentUserId = parseJwt(currentToken).sub;
    console.log('curUserId', currentUserId)

    const response = await ApiCaller.getUserById(currentUserId);
    const result = await response.json();

    const userProfile = result.user;
    console.log('got to userProfile', userProfile)

    this.user.profile = userProfile;
    this.user.isSignedIn = true;
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
      User.signIn(user.token);

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
