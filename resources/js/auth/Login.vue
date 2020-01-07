<template>
  <div>
    <v-toolbar-title>Login</v-toolbar-title>
    <v-form ref="form" lazy-validation>
      <v-text-field
        v-model="userData.login"
        prepend-icon="mdi-account"
        label="E-mail/username"
        :rules="[rules.required]"
        :error-messages="errorFor('username') || errorFor('email')"
        required
        @keyup.enter="login"
        clearable
        :loading="loading"
      ></v-text-field>

      <v-text-field
        v-model="userData.password"
        prepend-icon="mdi-lock"
        :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
        :rules="[rules.required, rules.min]"
        :error-messages="errorFor('password')"
        :type="show1 ? 'text' : 'password'"
        name="input-10-1"
        label="Password"
        counter
        :loading="loading"
        @click:append="show1 = !show1"
        @keyup.enter="login"
      ></v-text-field>
    </v-form>
    <v-alert type="error" v-if="error" dismissible>{{ error }}</v-alert>
    <v-btn :disabled="loading" color="success" class="mr-4" @click="login">Login</v-btn>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userData: {
        login: "",
        password: ""
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
