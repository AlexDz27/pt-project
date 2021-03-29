<template>
  <Header :user="user"/>

  <div class="container">
    <div class="row">
      <div class="col-4"></div>

      <div class="col">
        <h1 class="text-center">Forgot password</h1>

        <form @submit.prevent="submitForgotPassword">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input v-model="form.email" class="form-control" :class="{'is-invalid': errors?.email}"
                   id="exampleInputEmail1" aria-describedby="emailHelp">
            <div v-for="error in errors?.email" class="invalid-feedback">
              {{ error }}
            </div>
          </div>

          <button type="submit" class="btn btn-outline-primary w-100 mt-4">Submit request</button>
        </form>

        <div v-if="successResponse.received" class="text-center mt-4">
          <p>Here's your link for password reset: <router-link :to="`/reset-password/${resetPasswordToken}`">link</router-link></p>
        </div>

        <div class="text-center text-xl mt-4">
          <router-link :to="{name: 'home'}">Go to home page</router-link>
        </div>
      </div>

      <div class="col-4"></div>
    </div>
  </div>

  <Footer/>
</template>

<script>
import Header from '../Header';
import Footer from '../Footer';
import { ApiCaller } from '../../modules/ApiCaller';

export default {
  components: {Header, Footer},
  props: {
    user: Object
  },
  emits: ['signInUser'],
  data() {
    return {
      form: {
        email: '',
      },
      errors: {},
      successResponse: {
        link: '',
        received: false
      }
    };
  },
  methods: {
    async submitForgotPassword() {
      const response = await ApiCaller.forgotPassword(this.form);
      const result = await response.json();

      this.errors = result.errors;
      if (this.errors) return;

      this.successResponse = {
        link: result.link,
        received: true
      };
    }
  },
  computed: {
    resetPasswordToken() {
      const token = this.successResponse.link.split('?token=')[1];

      return token;
    }
  }
};
</script>
