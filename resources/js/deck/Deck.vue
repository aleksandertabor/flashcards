<template>
  <div>
    <div v-if="!loading">
      <h2>{{ deck.title }}</h2>
      <router-link
        class="btn nav-button"
        :to="{ name: 'profile', params: {username: deck.user.username}}"
      >
        <i class="fas fa-user"></i>
        <h3>{{ deck.user.username }}</h3>
      </router-link>
      <v-parallax dark :src="deck.image">
        <v-row align="center" justify="center">
          <v-col class="text-center" cols="12">
            <h1 class="display-1 font-weight-thin mb-4">{{deck.title}}</h1>
            <h4 class="subheading">{{deck.description}}</h4>
          </v-col>
        </v-row>
      </v-parallax>
    </div>
    <div v-else>Loading ...</div>
    <v-item-group v-if="!loading" multiple>
      <v-container>
        <cards :cards="this.deck.cards"></cards>
      </v-container>
    </v-item-group>
  </div>
</template>

<script>
import Cards from "./Cards";
import DeckActions from "./DeckActions";
export default {
  components: {
    Cards,
    DeckActions
  },
  data() {
    return {
      deck: null,
      loading: false
    };
  },
  created() {
    this.loading = true;

    this.$store
      .dispatch("deck", this.$route.params.slug)
      .then(response => {
        this.deck = response.data.deck;
        if (this.deck === null) {
          this.$router.push({ name: "home" });
        }
      })
      .catch(error => {
        this.$router.push({ name: "home" });
      })
      .then(() => (this.loading = false));
  }
};
</script>

<style scoped>
</style>
