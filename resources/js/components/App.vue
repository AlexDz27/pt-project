<template>
  <router-view
    :user="user"
    @signInUser="onSignInUser"
    @signOutUser="onSignOutUser"
  />
</template>

<script>
import { User } from '../modules/User';

export default {
  data() {
    return {
      user: {
        isSignedIn: false,
        profile: null
      }
    }
  },

  mounted() {

  },

  methods: {
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
