<template>
  <div>
    <div v-if="!loading">
      <v-row justify="space-between">
        <v-col cols="12" md="6">
          <router-link
            class="btn nav-button"
            :to="{ name: 'profile', params: {username: deck.user.username}}"
          >
            <i class="fas fa-user"></i>
            <h3>{{ deck.user.username }}</h3>
          </router-link>
        </v-col>
        <v-col cols="12" md="6">
          <print-deck :deck="deck"></print-deck>
        </v-col>
      </v-row>

      <v-parallax :src="deck.image || '/img/app/bg-profile.png'">
        <v-row align="center" class="parallax-overlay" justify="center">
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
import PrintDeck from "./PrintDeck";
export default {
  components: {
    Cards,
    PrintDeck
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
.parallax-overlay {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>
