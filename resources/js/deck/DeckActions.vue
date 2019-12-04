<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">Deck Actions</h6>
    <div class="form-row">
      <div class="form-group col-md-12">
        <label for="query">Search</label>
        <input
          type="text"
          name="query"
          class="form-control form-control-sm"
          placeholder="Flashcard"
          v-model="query"
          @keyup.enter="search"
        />
      </div>
    </div>

    <button class="btn btn-secondary btn-block" @click="search" :disabled="loading">Search</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      query: null,
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
