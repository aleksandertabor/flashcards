<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">Register</h6>
    <div class="form-row">
      <div class="form-group col-md-12">
        <label for="email">E-mail</label>
        <input
          type="email"
          name="email"
          class="form-control form-control-sm"
          placeholder="test@example.com"
          v-model="email"
          @keyup.enter="login"
        />
        <label for="password">Password</label>
        <input
          type="password"
          name="password"
          class="form-control form-control-sm"
          v-model="password"
          @keyup.enter="login"
        />
      </div>
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
      status: null
    };
  },
  methods: {
    search() {
      this.loading = true;
      axios
        .get(``)
        .then(response => {
          this.status = response.status;
        })
        .catch(error => {});
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
