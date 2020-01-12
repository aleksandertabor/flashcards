<template>
  <div>
    <v-form ref="form" lazy-validation>
      <v-alert type="success" v-if="success" dismissible>Your account has saved.</v-alert>
      <v-alert type="error" v-if="error" dismissible>Fill your data correctly.</v-alert>
      <v-text-field
        v-model="userData.username"
        prepend-icon="mdi-account"
        label="Username"
        :rules="[rules.required]"
        :error-messages="errorFor('username')"
        @input="$emit('input', userData)"
        @keyup.enter="save"
        :loading="loading"
      ></v-text-field>

      <v-text-field
        v-model="userData.email"
        prepend-icon="mdi-at"
        label="Email"
        :rules="[rules.required]"
        :error-messages="errorFor('email')"
        @input="$emit('input', userData)"
        @keyup.enter="save"
        :loading="loading"
      ></v-text-field>

      <v-text-field
        v-model="userData.password"
        prepend-icon="mdi-lock"
        :rules="[rules.min]"
        :error-messages="errorFor('password')"
        :type="show1 ? 'text' : 'password'"
        name="input-10-1"
        label="Password"
        counter
        :loading="loading"
        @keyup.enter="save"
      ></v-text-field>

      <v-text-field
        v-model="userData.password_confirmation"
        prepend-icon="mdi-lock"
        :rules="[rules.min]"
        :error-messages="errorFor('password_confirmation')"
        name="input-10-1"
        :type="show1 ? 'text' : 'password'"
        label="Password confirmation"
        counter
        :loading="loading"
        @keyup.enter="save"
      ></v-text-field>

      <v-btn @click="show1 = !show1" class="ma-2" color="indigo" dark>
        <v-icon>{{ show1 ? 'mdi-eye' : 'mdi-eye-off' }}</v-icon>
      </v-btn>

      <v-btn :disabled="loading" color="success" class="mr-4" @click="save">Save profile</v-btn>
    </v-form>
  </div>
</template>

<script>
export default {
  props: {
    userData: Object
  },
  data() {
    return {
      loading: false,
      status: null,
      errors: null,
      error: false,
      show1: false,
      success: false,
      rules: {
        required: v => !!v || "Required.",
        min: v => (v && v.length) >= 6 || "Min 6 characters"
      }
    };
  },
  methods: {
    save() {
      this.loading = true;
      this.errors = null;
      this.error = false;
      this.success = false;

      this.$store
        .dispatch("editProfile", this.userData)
        .then(response => {
          const username = response.username;
          this.success = true;
          if (username !== this.$route.params.username) {
            this.$router.push({ name: "profile", params: { username } });
          }
        })
        .catch(error => {
          if (error.graphQLErrors.validationErrors !== undefined) {
            this.errors = error.graphQLErrors.validationErrors;
            this.error = true;
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
      // return 401 === this.status && this.errors !== null;
      return this.errors !== null;
    }
  }
};
</script>

<style scoped>
label {
  font-size: 0.7em;
  text-transform: uppercase;
  color: green;
  font-weight: bolder;
}
</style>
