<template>
  <div>
    <div v-if="!loading" class="row">
      <div class="col-md-8 pb-4">
        <div class="card">
          <div class="card-body">
            <h2>{{ deck.title }}</h2>
            <router-link
              class="btn nav-button"
              :to="{ name: 'profile', params: {username: deck.user.username}}"
            >
              <i class="fas fa-user"></i>
              <h3>{{ deck.user.username }}</h3>
            </router-link>
            <hr />
            <v-img
              v-if="deck.image"
              :src="deck.image"
              aspect-ratio="1"
              class="grey lighten-2"
              contain
            ></v-img>
            <hr />
            <article>{{ deck.description }}</article>
          </div>
        </div>
      </div>
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
      })
      .catch(error => {
        this.$router.push({ name: "home" });
      })
      .then(() => (this.loading = false));
  }
};
</script>

<style scoped>
.back {
  cursor: pointer;
}
</style>
