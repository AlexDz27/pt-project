<template>
  <Header :user="user"/>

  <div class="container">
    <div class="row">
      <div class="col-4"></div>

      <div class="col">
        <h1 class="text-center">Reset password</h1>

        <div v-if="! tokenExists" class="text-center mt-4">
          <p>Such a password reset token doesn't exist.</p>

          <div class="text-center text-xl mt-4">
            <router-link :to="{name: 'home'}">Go to home page</router-link>
          </div>
        </div>

        <form v-if="tokenExists && ! success" @submit.prevent="submitResetPassword">
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">New password</label>
            <input v-model="form.password" type="password" class="form-control" :class="{'is-invalid': errors?.password}"
                   id="exampleInputPassword1" name="password">
            <div v-for="error in errors?.password" class="invalid-feedback">
              {{ error }}
            </div>
            <div id="passwordHelpBlock" class="form-text">
              Your password must be at least 3 characters long.
            </div>
          </div>

          <button type="submit" class="btn btn-outline-primary w-100 mt-4">Reset password</button>
        </form>

        <div v-if="success" class="text-center">
          <p>Password has been reset successfully.</p>

          <router-link :to="{name: 'sign-in'}">Sign in with new password</router-link>
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

const HTTP_CODE_BAD_REQUEST= 400;

export default {
  components: {Header, Footer},
  props: {
    user: Object
  },
  emits: ['signInUser'],
  data() {
    return {
      tokenExists: true,
      form: {
        password: ''
      },
      errors: {},
      success: false
    };
  },
  async created() {
    const response = await ApiCaller.validateResetPasswordToken(this.$route.params.token);
    const result = await response.json();

    if (response.status === HTTP_CODE_BAD_REQUEST) {
      this.tokenExists = false;
    }
  },
  methods: {
    async submitResetPassword() {
      const response = await ApiCaller.resetPassword(this.$route.params.token, this.form);
      const result = await response.json();

      this.errors = result.errors;
      if (this.errors) return;

      this.success = true;
    }
  }
};
</script>
