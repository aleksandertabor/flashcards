<template>
  <div>
    <input
      class="form-control mr-sm-2"
      type="search"
      placeholder="Deck"
      aria-label="Search"
      v-model="query"
      @input="searching"
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
  mounted() {
    this.query = this.$route.query.q;
    this.search();
  },
  methods: {
    searching: _.debounce(function() {
      this.search();
    }, 300),
    search: function() {
      this.$store.commit("query", this.query);
      this.$emit("change-filter");
    }
  }
};
</script>

<style>
</style>
