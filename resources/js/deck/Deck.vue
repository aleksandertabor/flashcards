<template>
  <div>xxx</div>
</template>

<script>
export default {
  data() {
    return {
      deck: null
    };
  },
  created() {
    axios
      .get(`/api/decks/${this.$route.params.id}`)
      .then(response => (this.deck = response.data));
  }
};
</script>
