<template>
  <div>
    <div v-if="!loading">
      <v-row justify="space-between">
        <v-col cols="12" md="6">
          <v-img
            :src="deck.image || '/images/bg-profile.png'"
            class="parallax"
            gradient="to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)"
          >
            <v-row align="center" justify="center" class="pa-5 white--text fill-height">
              <v-col class="text-center" cols="12">
                <v-badge
                  :content="deck.cards_count || '0'"
                  :value="deck.cards_count"
                  color="brown"
                  overlap
                  bordered
                >
                  <v-icon dark x-large>mdi-cards</v-icon>
                </v-badge>
                <h1 class="text-break display-1 font-weight-thin mb-4">{{deck.title}}</h1>
                <h4 class="subheading text-break">{{deck.description}}</h4>
              </v-col>
            </v-row>
          </v-img>
        </v-col>
        <v-col cols="12" md="6" class="d-flex flex-column flex-wrap justify-center">
          <v-btn
            color="primary"
            app
            x-large
            :to="{ name: 'profile', params: {username: deck.user.username}}"
          >
            <v-icon>mdi-account</v-icon>
            {{ deck.user.username }}
          </v-btn>

          <print-deck class="mt-5" :deck="deck"></print-deck>
          <share-deck class="mt-5" :deck="deck"></share-deck>
        </v-col>
      </v-row>
    </div>
    <loading v-else></loading>
    <v-item-group v-if="!loading" multiple>
      <cards :cards="this.deck.cards"></cards>
    </v-item-group>
  </div>
</template>

<script>
import Cards from "./Cards";
import PrintDeck from "./PrintDeck";
import ShareDeck from "./ShareDeck";
export default {
  components: {
    Cards,
    PrintDeck,
    ShareDeck
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

<style>
.parallax .v-image__image {
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
}
</style>
