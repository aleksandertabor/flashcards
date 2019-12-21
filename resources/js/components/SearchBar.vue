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
      console.log("searchuje");
      this.$store.commit("query", this.query);
      this.$emit("change-type");
    }, 300)
  }
};
</script>

<style>
</style>
