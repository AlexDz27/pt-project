<template>
  <Header :user="user" />

  <div class="container">
    <div class="row">
      <div class="col-4"></div>

      <div class="col">
        <h1 class="text-center">Sign up</h1>

        <form @submit.prevent="submitSignUp">
          <div class="mb-3">
            <label for="name" class="form-label">Username</label>
            <input v-model="form.name" class="form-control" :class="{'is-invalid': errors?.name}"
                   id="name" name="name">
            <div v-for="error in errors?.name" class="invalid-feedback">
              {{ error }}
            </div>
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input v-model="form.email" class="form-control" :class="{'is-invalid': errors?.email}"
                   id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
            <div v-for="error in errors?.email" class="invalid-feedback">
              {{ error }}
            </div>
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input v-model="form.password" type="password" class="form-control" :class="{'is-invalid': errors?.password}"
                   id="exampleInputPassword1" name="password">
            <div v-for="error in errors?.password" class="invalid-feedback">
              {{ error }}
            </div>
            <div id="passwordHelpBlock" class="form-text">
              Your password must be at least 3 characters long.
            </div>
          </div>
          <button type="submit" class="btn btn-outline-primary w-100 mt-4">Sign up</button>
        </form>

        <div class="text-center">
          <span class="text-xl">Or</span>

          <button id="google-sign-in-btn" class="btn btn-outline-primary w-100 mt-2">Sign up via Google</button>
        </div>

        <div class="text-center text-xl mt-4">
          <router-link :to="{name: 'home'}">Go to home page</router-link>
        </div>
      </div>

      <div class="col-4"></div>
    </div>
  </div>

  <Footer />
</template>

<script>
import Header from '../Header';
import Footer from '../Footer';
import {ApiCaller} from '../../modules/ApiCaller';

export default {
  components: { Header, Footer },
  props: {
    user: Object
  },
  emits: ['signInUser'],
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: ''
      },
      errors: {}
    }
  },
  mounted() {
    /** Add ability to sign in via Google */
      // Add Google sign-in script
    const googleSignInScript = document.createElement('script');
    googleSignInScript.setAttribute('src', 'https://apis.google.com/js/api:client.js');
    document.head.appendChild(googleSignInScript);

    setTimeout(() => {
      gapi.load('auth2', () => {
        // Retrieve the singleton for the GoogleAuth library and set up the client.
        const auth2 = gapi.auth2.init({
          client_id: window.GOOGLE_CLIENT_ID
        });

        auth2.attachClickHandler(document.querySelector('#google-sign-in-btn'), {}, async (googleUser) => {
          const signInData = {
            googleId: googleUser.Aa,
            name: googleUser.Qs.Se,
            email: googleUser.Qs.zt,
          };

          const response = await ApiCaller.signInViaGoogle(signInData);
          const result = await response.json();

          this.errors = result.errors;
          if (this.errors) return;

          const user = {
            profile: result.user,
            token: result.token
          };
          this.$emit('signInUser', user);
        }, (error) => {
          alert('Oops, an error occurred while trying to sign you in via Google.')

          console.error(error);
        });
      });
    }, 1000);
  },
  methods: {
    async submitSignUp() {
      const response = await ApiCaller.signUp(this.form);
      const result = await response.json();

      this.errors = result.errors;
      if (this.errors) return;

      alert('You\'ve successfully signed up.');

      const user = {
        profile: result.user,
        token: result.token
      };
      // Sign in user after signing up
      this.$emit('signInUser', user);
    }
  }
}
</script>
