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
        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
        :rules="[rules.required, rules.min]"
        :error-messages="errorFor('password')"
        :type="showPassword ? 'text' : 'password'"
        name="input-10-1"
        label="Password"
        counter
        :loading="loading"
        @click:append="showPassword = !showPassword"
        @keyup.enter="login"
      ></v-text-field>
    </v-form>
    <v-alert type="error" v-if="error" dismissible>{{ error }}</v-alert>
    <v-btn :disabled="loading" color="success" class="mr-4" @click="login">Login</v-btn>
  </div>
</template>

<script>
import {
  validationErrors,
  errorMessage
} from "@/shared/utils/validationErrors";
import formsMixin from "@/shared/mixins/formsMixin";

export default {
  mixins: [formsMixin],
  data() {
    return {
      userData: {
        login: "",
        password: ""
      },
      showPassword: false
    };
  },
  methods: {
    async login() {
      this.loading = true;
      this.errors = null;

      try {
        await this.$store.dispatch("auth/login", this.userData);

        this.$router.push({ name: "home" });
      } catch (errors) {
        if (validationErrors(errors)) {
          this.errors = validationErrors(errors);
        }
        if (errorMessage(errors)) {
          this.error = errorMessage(errors);
        }
      }

      this.loading = false;
    }
  }
};
</script>
