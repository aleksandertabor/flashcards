<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">Edit profile</h6>
    <div class="form-group">
      <div class="form-group col-md-12">
        <label for="username">Username</label>
        <input
          type="text"
          name="username"
          class="form-control form-control-sm"
          autocomplete="off"
          placeholder="username"
          v-model="userData.username"
          @input="$emit('input', userData)"
          :class="[{'is-invalid': this.errorFor('username')}]"
        />
        <div
          class="invalid-tooltip"
          v-for="(error, index) in this.errorFor('username')"
          :key="'username' + index"
        >{{ error }}</div>
      </div>
      <div class="form-group col-md-12">
        <label for="email">Email</label>
        <input
          type="email"
          name="email"
          class="form-control form-control-sm"
          autocomplete="off"
          placeholder="email"
          v-model="userData.email"
          @input="$emit('input', userData)"
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
          autocomplete="off"
          placeholder
          v-model="user.password"
          :class="[{'is-invalid': this.errorFor('password')}]"
        />
        <div
          class="invalid-tooltip"
          v-for="(error, index) in this.errorFor('password')"
          :key="'password' + index"
        >{{ error }}</div>
      </div>
      <button class="btn btn-success btn-block mt-3" @click="save" :disabled="loading">
        <i class="fas fa-save"></i> Save profile
      </button>
    </div>
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
      errors: null
    };
  },
  methods: {
    save() {
      this.loading = true;
      this.errors = null;

      this.$store
        .dispatch("editProfile", this.userData)
        .then(response => {
          const username = response.data.editProfile.username;
          if (username !== this.$route.params.username) {
            this.$router.push({ name: "profile", params: { username } });
          }
          //   this.userData = response.data.profile;
        })
        .catch(error => {
          if (error.graphQLErrors.validationErrors !== undefined) {
            this.errors = error.graphQLErrors.validationErrors;
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
