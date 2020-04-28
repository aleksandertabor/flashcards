<template>
  <div>
    <v-form ref="form" lazy-validation>
      <v-btn color="purple darken-3" outlined tile @click="isEditing = !isEditing">
        <v-icon left v-if="isEditing">mdi-close</v-icon>
        <v-icon left v-else>mdi-pencil</v-icon>Edit
      </v-btn>

      <div class="py-5 px-0">
        <v-alert type="success" v-if="success" dismissible>Your account has saved.</v-alert>
        <v-alert type="error" v-if="error" dismissible>Fill your data correctly.</v-alert>
      </div>

      <v-snackbar v-model="success" :timeout="2000" bottom left>Your profile has been updated.</v-snackbar>

      <v-snackbar v-model="error" :timeout="2000" bottom left>Fill your data correctly.</v-snackbar>

      <v-text-field
        v-model="userData.username"
        prepend-icon="mdi-account"
        label="Username"
        :rules="[rules.required]"
        :error-messages="errorFor('username')"
        @input="$emit('input', userData)"
        @keyup.enter="save"
        :loading="loading"
        :disabled="isDisable"
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
        :disabled="isDisable"
      ></v-text-field>

      <v-text-field
        v-model="password"
        prepend-icon="mdi-lock"
        :rules="[rules.min]"
        :error-messages="errorFor('password')"
        :type="showPassword ? 'text' : 'password'"
        name="input-10-1"
        label="New Password"
        counter
        :loading="loading"
        :disabled="isDisable"
        @keyup.enter="save"
      ></v-text-field>

      <v-text-field
        v-model="password_confirmation"
        prepend-icon="mdi-lock"
        :rules="[rules.min]"
        :error-messages="errorFor('password_confirmation')"
        name="input-10-1"
        :type="showPassword ? 'text' : 'password'"
        label="New Password confirmation"
        counter
        :loading="loading"
        :disabled="isDisable"
        @keyup.enter="save"
      ></v-text-field>
      <v-btn
        v-if="isEditing"
        @click="showPassword = !showPassword"
        :disabled="isDisable"
        class="ma-2"
        color="indigo"
        dark
      >
        <v-icon>{{ showPassword ? 'mdi-eye' : 'mdi-eye-off' }}</v-icon>
      </v-btn>

      <v-btn :disabled="isDisable" color="success" class="mr-4" @click="save">Save profile</v-btn>
    </v-form>
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
  props: {
    userData: Object
  },
  data() {
    return {
      password: "",
      password_confirmation: "",
      showPassword: false,
      isEditing: null
    };
  },
  methods: {
    async save() {
      this.loading = true;
      this.errors = null;
      this.error = false;
      this.success = false;

      try {
        const username = (
          await this.$store.dispatch("profile/editProfile", {
            id: this.userData.id,
            username: this.userData.username,
            email: this.userData.email,
            password: this.password,
            password_confirmation: this.password_confirmation
          })
        ).username;

        this.isEditing = !this.isEditing;
        this.success = true;

        if (username !== this.$route.params.username) {
          this.$router.push({ name: "profile", params: { username } });
        }
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
  },
  computed: {
    isDisable() {
      return this.loading || !this.isEditing;
    }
  }
};
</script>
