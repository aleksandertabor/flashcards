<template>
  <div>
    <v-toolbar-title>Register</v-toolbar-title>
    <v-form ref="form" lazy-validation>
      <v-text-field
        v-model="userData.email"
        prepend-icon="mdi-account"
        label="E-mail"
        :rules="[rules.required]"
        :error-messages="errorFor('email')"
        required
        @keyup.enter="register"
        clearable
        :loading="loading"
      ></v-text-field>

      <v-text-field
        v-model="userData.password"
        prepend-icon="mdi-lock"
        :rules="[rules.required, rules.min]"
        :error-messages="errorFor('password')"
        :type="show1 ? 'text' : 'password'"
        name="input-10-1"
        label="Password"
        counter
        :loading="loading"
        @keyup.enter="register"
      ></v-text-field>

      <v-text-field
        v-model="userData.password_confirmation"
        prepend-icon="mdi-lock"
        :rules="[rules.required, rules.min]"
        :error-messages="errorFor('password_confirmation')"
        name="input-10-1"
        :type="show1 ? 'text' : 'password'"
        label="Password confirmation"
        counter
        :loading="loading"
        @keyup.enter="register"
      ></v-text-field>
      <v-btn @click="show1 = !show1" class="ma-2" color="indigo" dark>
        <v-icon>{{ show1 ? 'mdi-eye' : 'mdi-eye-off' }}</v-icon>
      </v-btn>
    </v-form>
    <v-alert type="error" v-if="error" dismissible>{{ error }}</v-alert>
    <v-btn :disabled="loading" color="success" class="mr-4" @click="register">Register</v-btn>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userData: {
        email: "",
        password: "",
        password_confirmation: ""
      },
      loading: false,
      status: null,
      errors: null,
      error: null,
      show1: false,
      rules: {
        required: v => !!v || "Required.",
        min: v => (v && v.length) >= 6 || "Min 6 characters"
      }
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
