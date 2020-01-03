<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">Register</h6>
    <div class="form-row">
      <div class="form-group col-md-12">
        <label for="email">E-mail</label>
        <input
          type="email"
          name="email"
          class="form-control form-control-sm"
          placeholder="test@example.com"
          v-model="userData.email"
          @keyup.enter="register"
          :class="[{'is-invalid': this.errorFor('email')}]"
        />
        <div
          class="invalid-tooltip"
          v-for="(error, index) in this.errorFor('email')"
          :key="'email' + index"
        >{{ error }}</div>
      </div>

      <div class="form-group col-md-12">
        <label for="password">Password</label>
        <input
          type="password"
          name="password"
          class="form-control form-control-sm"
          v-model="userData.password"
          @keyup.enter="register"
          :class="[{'is-invalid': this.errorFor('password')}]"
        />
        <div
          class="invalid-tooltip"
          v-for="(error, index) in this.errorFor('password')"
          :key="'password' + index"
        >{{ error }}</div>
      </div>

      <div class="form-group col-md-12">
        <label for="password_confirmation">Password Confirmation</label>
        <input
          type="password"
          name="password_confirmation"
          class="form-control form-control-sm"
          v-model="userData.password_confirmation"
          @keyup.enter="register"
          :class="[{'is-invalid': this.errorFor('password_confirmation')}]"
        />
        <div
          class="invalid-tooltip"
          v-for="(error, index) in this.errorFor('password_confirmation')"
          :key="'password_confirmation' + index"
        >{{ error }}</div>
      </div>
      <div class="form-group col-md-12">
        <div class="alert alert-danger" v-if="error">{{ error }}</div>
      </div>
    </div>
    <button class="btn btn-secondary btn-block" @click="register" :disabled="loading">Register</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userData: {
        email: null,
        password: null,
        password_confirmation: null
      },
      loading: false,
      status: null,
      errors: null,
      error: null
    };
  },
  methods: {
    register() {
      this.loading = true;
      this.errors = null;

      this.$store
        .dispatch("register", this.userData)
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
