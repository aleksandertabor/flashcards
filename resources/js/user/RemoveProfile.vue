<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">Remove profile</h6>
    <div class="form-group">
      <button class="btn btn-danger btn-block mt-3" @click="remove" :disabled="loading">
        <i class="fas fa-trash-alt"></i> Remove account
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
    remove() {
      this.loading = true;

      this.$store
        .dispatch("removeProfile", this.userData)
        .then(response => {
          this.$router.push({ name: "home" });
        })
        .catch(error => {})
        .then(() => (this.loading = false));
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
