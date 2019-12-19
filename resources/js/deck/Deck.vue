<template>
  <div>
    <div v-if="!loading" class="row">
      <div class="col-md-8 pb-4">
        <div class="card">
          <div class="card-body">
            <h2>{{ deck.title }}</h2>
            <router-link
              v-if="isAuthenticated"
              class="btn nav-button"
              :to="{ name: 'profile', params: {username: deck.username}}"
            >
              <i class="fas fa-user"></i>
              <h3>{{ deck.username }}</h3>
            </router-link>
            <hr />
            <img
              data-src="https://source.unsplash.com/random/200x200"
              class="card-img-top lazy"
              alt
            />
            <hr />
            <article>{{ deck.description }}</article>
          </div>
        </div>
      </div>
      <div class="col-md-4 pb-4">
        <deck-actions></deck-actions>
      </div>
    </div>
    <div class="col-md-12" v-if="!loading">
      <cards :cards="this.deck.cards"></cards>
    </div>
    <div v-else>Loading ...</div>
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
    axios
      .get(`/api/decks/${this.$route.params.slug}`)
      .then(response => {
        this.deck = response.data.data;
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
