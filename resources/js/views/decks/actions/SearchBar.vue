<template>
  <div>
    <v-text-field
      flat
      filled
      hide-details
      prepend-inner-icon="mdi-magnify"
      label="Search for a deck"
      v-model="query"
      @input="searching"
    ></v-text-field>
  </div>
</template>

<script>
import { debounce } from "lodash";
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
    searching: debounce(function() {
      this.search();
    }, 300),
    search: function() {
      this.$store.commit("decks/query", this.query);
      this.$emit("change-filter");
    }
  }
};
</script>

<style>
</style>
