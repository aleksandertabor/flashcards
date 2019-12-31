<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">Login</h6>
    <div class="form-row" :class="[{'bg-success': this.loggedIn}]">
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
  created() {
    //   this.loading = true;
  },
  methods: {
    login() {
      this.loading = true;
      this.errors = null;

      this.$store
        .dispatch("login", this.userData)
        .then(response => {
          this.$store.dispatch("me");
          this.$router.push({ name: "home" });
        })
        .catch(error => {
          console.log(error.message);
          if (error.graphQLErrors.validationErrors !== undefined) {
            this.errors = error.graphQLErrors.validationErrors;
          }

          if (error.message !== null) {
            this.error = error.message;
          }
          //   if (401 === error.response.status) {
          //     this.errors = error.response.data.errors;
          //   }
          //   this.status = error.response.status;
        })
        .then(() => (this.loading = false));

      //   axios
      //     .post("/api/login", { email: this.email, password: this.password })
      //     .then(response => {
      //       this.status = response.status;
      //       this.access_token = response.data.access_token;
      //       this.$store.dispatch("login", this.access_token);
      //     })
      //     .catch(error => {
      //       console.log(error.response);
      //       if (401 === error.response.status) {
      //         this.errors = error.response.data.errors;
      //       }
      //       this.status = error.response.status;
      //     })
      //     .then(() => (this.loading = false));
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
    loggedIn() {
      return 200 === this.status;
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
