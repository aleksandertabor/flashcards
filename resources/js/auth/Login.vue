<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">Login</h6>
    <div class="form-row">
      <div class="form-group col-md-12">
        <label for="login">Username/E-mail</label>
        <input
          type="input"
          name="login"
          class="form-control form-control-sm"
          placeholder="Username/test@example.com"
          v-model="userData.login"
          @keyup.enter="login"
          :class="[{'is-invalid': this.errorFor('email') || this.errorFor('username')}]"
        />
        <div
          class="invalid-tooltip"
          v-for="(error, index) in this.errorFor('email') || this.errorFor('username')"
          :key="'login' + index"
        >{{ error }}</div>
      </div>

      <div class="form-group col-md-12">
        <label for="password">Password</label>
        <input
          type="password"
          name="password"
          class="form-control form-control-sm"
          v-model="userData.password"
          @keyup.enter="login"
          :class="[{'is-invalid': this.errorFor('password')}]"
        />
        <div
          class="invalid-tooltip"
          v-for="(error, index) in this.errorFor('password')"
          :key="'password' + index"
        >{{ error }}</div>
      </div>
      <div class="form-group col-md-12">
        <div class="alert alert-danger" v-if="error">{{ error }}</div>
      </div>
    </div>
    <button class="btn btn-secondary btn-block" @click="login" :disabled="loading">Login</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userData: {
        login: null,
        password: null
      },
      loading: false,
      status: null,
      errors: null,
      error: null
    };
  },
  methods: {
    login() {
      this.loading = true;
      this.errors = null;

      this.$store
        .dispatch("login", this.userData)
        .then(response => {
          this.$router.push({ name: "home" });
        })
        .catch(error => {
          const {
            graphQLErrors: { validationErrors }
          } = error;
          const {
            graphQLErrors: {
              0: { message }
            }
          } = error;
          if (validationErrors) {
            this.errors = validationErrors;
          }
          if (message) {
            this.error = message;
          }
        })
        .then(() => (this.loading = false));
    },
    errorFor(field) {
      return this.hasErrors && this.errors[field] ? this.errors[field] : null;
    }
  },
  computed: {
    hasErrors() {
      return this.errors !== null;
    }
  }
};
</script>

<style scoped>
label {
  font-size: 1.2em;
  text-transform: uppercase;
  font-weight: bolder;
}
</style>
