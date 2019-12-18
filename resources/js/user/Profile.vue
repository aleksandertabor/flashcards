<template>
  <div>
    <div v-if="loading">Profile is loading ...</div>
    <div v-else>
      <h1>{{ userData.username }}</h1>
      <p>Email: {{ userData.email }}</p>
      <p>
        <i class="fas fa-box"></i>
        Decks: {{ userData.decks.length }}
      </p>
      <p>Cards: {{ userData.cards_count }}</p>
      <decks v-if="userData.decks" v-bind:decks="userData.decks"></decks>
    </div>
  </div>
</template>

<script>
import Decks from "./Decks";
export default {
  components: {
    Decks
  },
  data() {
    return {
      userData: {},
      loading: false
    };
  },
  created() {
    this.loading = true;
    this.$store
      .dispatch("profile", this.$route.params.username)
      .then(response => {
        this.userData = response.data.data;
      })
      .catch(error => {
        this.$router.push({ name: "home" });
      })
      .then(() => (this.loading = false));
  }
};
</script>

<style>
</style>
