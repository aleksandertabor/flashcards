<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">Edit profile</h6>
    <div class="form-group">
      <label for="username">Username</label>
      <input
        type="text"
        name="username"
        class="form-control form-control-sm"
        autocomplete="off"
        placeholder="username"
        v-model="userData.username"
        @input="$emit('input', userData)"
      />
      <label for="email">Email</label>
      <input
        type="email"
        name="email"
        class="form-control form-control-sm"
        autocomplete="off"
        placeholder="email"
        v-model="userData.email"
        @input="$emit('input', userData)"
      />
      <label for="password">Password</label>
      <input
        type="password"
        name="password"
        class="form-control form-control-sm"
        autocomplete="off"
        placeholder
        v-model="user.password"
      />
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
      status: null
    };
  },
  methods: {
    save() {
      return 0;
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
