<template>
  <div>
    <v-toolbar-title>Register</v-toolbar-title>
    <v-form ref="form" lazy-validation>
      <v-text-field
        v-model="userData.email"
        prepend-icon="mdi-account"
        label="E-mail"
        :rules="[rules.required, rules.email]"
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
        :type="showPassword ? 'text' : 'password'"
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
        :type="showPassword ? 'text' : 'password'"
        label="Password confirmation"
        counter
        :loading="loading"
        @keyup.enter="register"
      ></v-text-field>
      <v-btn @click="showPassword = !showPassword" class="ma-2" color="indigo" dark>
        <v-icon>{{ showPassword ? 'mdi-eye' : 'mdi-eye-off' }}</v-icon>
      </v-btn>
    </v-form>
    <v-alert type="error" v-if="error" dismissible>{{ error }}</v-alert>
    <v-btn :disabled="loading" color="success" class="mr-4" @click="register">Register</v-btn>
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
        email: "",
        password: "",
        password_confirmation: ""
      },
      loading: false,
      showPassword: false
    };
  },
  methods: {
    async register() {
      this.loading = true;
      this.errors = null;

      try {
        await this.$store.dispatch("auth/register", this.userData);

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
