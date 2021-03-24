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
  methods: {
    async submitSignUp() {
      const response = await fetch(window.API_URL + '/auth/sign-up', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.form)
      });
      const result = await response.json();

      this.errors = result.errors;
      if (this.errors) return;

      alert('You\'ve successfully signed up.');

      const user = {
        profile: result.user,
        token: result.token
      };
      this.$emit('signInUser', user);
    }
  }
}
</script>
