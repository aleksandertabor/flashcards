<template>
  <div>
    <v-text-field
      flat
      solo-inverted
      hide-details
      prepend-inner-icon="mdi-magnify"
      label="Search for a deck"
      v-model="query"
      @input="searching"
    ></v-text-field>
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
