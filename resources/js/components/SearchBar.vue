<template>
  <div>
    <input
      class="form-control mr-sm-2"
      type="search"
      placeholder="Deck"
      aria-label="Search"
      v-model="query"
      @input="search"
    />
  </div>
</template>

<script>
import _ from "lodash";
export default {
  data() {
    return {
      query: "",
      loading: null
    };
  },
  methods: {
    search: _.debounce(function() {
      if (this.query !== "") {
        this.$store
          .dispatch("search", this.query)
          .then(response => {
            // store decks all
            console.log(response);
          })
          .catch(error => {
            console.log(error);
          })
          .then(() => (this.loading = false));
      } else {
        this.$store.dispatch("decks");
      }
    }, 300)
  }
};
</script>

<style>
</style>
