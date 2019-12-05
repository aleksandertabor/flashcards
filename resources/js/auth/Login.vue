<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">Login</h6>
    <div class="form-row" :class="[{'bg-success': this.loggedIn}]">
      <div class="form-group col-md-12">
        <label for="email">E-mail</label>
        <input
          type="email"
          name="email"
          class="form-control form-control-sm"
          placeholder="test@example.com"
          v-model="email"
          @keyup.enter="login"
          :class="[{'is-invalid': this.errorFor('email')}]"
        />
        <div class="invalid-tooltip" v-for="(error, index) in this.errorFor('email')" :key="'email' + index">
            {{ error }}
        </div>

        </div>

        <div class="form-group col-md-12">
        <label for="password">Password</label>
        <input
          type="password"
          name="password"
          class="form-control form-control-sm"
          v-model="password"
          @keyup.enter="login"
          :class="[{'is-invalid': this.errorFor('password')}]"
        />
        <div class="invalid-tooltip" v-for="(error, index) in this.errorFor('password')" :key="'password' + index">
            {{ error }}
        </div>
      </div>
    </div>

<div class="bg-danger" v-for="(error, index) in this.errorFor('login')" :key="'login' + index">
            {{ error }}
        </div>
    <button class="btn btn-secondary btn-block" @click="login" :disabled="loading">Login</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: null,
      password: null,
      loading: false,
      status: null,
      access_token: null,
      errors: null
    };
  },
  created() {
    //   this.loading = true;
  },
  methods: {
    login() {
      this.loading = true;
      this.errors = null;

      axios
        .post("/api/login", {email: this.email, password: this.password})
        .then(response => {
          this.status = response.status;
          this.access_token = response.data.access_token;
          localStorage.setItem('access_token',response.data.access_token)
          this.loading = false;
        })
        .catch(error => {
            console.log(error.response);
            if (401 === error.response.status) {
                this.errors = error.response.data.errors;
            }
            this.status = error.response.status;
        }).then(() => this.loading = false);
    },
    errorFor(field) {
        return this.hasErrors && this.errors[field] ? this.errors[field] : null;
    }
  },
  computed: {
      hasErrors() {
          return 401 === this.status && this.errors !== null;
      },
      loggedIn() {
          return 200 === this.status;
      }
  },
};
</script>

<style scoped>
label {
  font-size: 1.2em;
  text-transform: uppercase;
  font-weight: bolder;
}
</style>
