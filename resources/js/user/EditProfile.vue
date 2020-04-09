<template>
  <div>
    <v-form ref="form" lazy-validation>
      <v-btn color="purple darken-3" outlined tile @click="isEditing = !isEditing">
        <v-icon left v-if="isEditing">mdi-close</v-icon>
        <v-icon left v-else>mdi-pencil</v-icon>Edit
      </v-btn>
      <v-alert type="success" v-if="success" dismissible>Your account has saved.</v-alert>
      <v-alert type="error" v-if="error" dismissible>Fill your data correctly.</v-alert>

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
        v-model="userData.password"
        prepend-icon="mdi-lock"
        :rules="[rules.min]"
        :error-messages="errorFor('password')"
        :type="show1 ? 'text' : 'password'"
        name="input-10-1"
        label="New Password"
        counter
        :loading="loading"
        :disabled="isDisable"
        @keyup.enter="save"
      ></v-text-field>

      <v-text-field
        v-model="userData.password_confirmation"
        prepend-icon="mdi-lock"
        :rules="[rules.min]"
        :error-messages="errorFor('password_confirmation')"
        name="input-10-1"
        :type="show1 ? 'text' : 'password'"
        label="New Password confirmation"
        counter
        :loading="loading"
        :disabled="isDisable"
        @keyup.enter="save"
      ></v-text-field>

      <v-btn
        v-if="isEditing"
        @click="show1 = !show1"
        :disabled="isDisable"
        class="ma-2"
        color="indigo"
        dark
      >
        <v-icon>{{ show1 ? 'mdi-eye' : 'mdi-eye-off' }}</v-icon>
      </v-btn>

      <v-btn :disabled="isDisable" color="success" class="mr-4" @click="save">Save profile</v-btn>
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
      isEditing: null,
      rules: {
        size: value =>
          !value ||
          value.size < 2097152 ||
          "File size should be less than 2 MB!",
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
          this.isEditing = !this.isEditing;
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
    },
    isDisable() {
      return this.loading || !this.isEditing;
    }
  }
};
</script>
