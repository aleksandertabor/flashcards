<template>
  <div>
    <div v-if="!loading">
      <v-row justify="space-between">
        <v-col cols="12" md="6">
          <v-btn
            color="primary"
            app
            :to="{ name: 'profile', params: {username: deck.user.username}}"
          >
            <v-icon>mdi-account</v-icon>
            {{ deck.user.username }}
          </v-btn>
        </v-col>
        <v-col cols="12" md="6">
          <print-deck :deck="deck"></print-deck>
        </v-col>
      </v-row>

      <v-img
        :src="deck.image || '/images/bg-profile.png'"
        class="parallax"
        gradient="to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)"
      >
        <v-row align="center" justify="center" class="pa-5 white--text fill-height">
          <v-col class="text-center" cols="12">
            <h1 class="text-break display-1 font-weight-thin mb-4">{{deck.title}}</h1>
            <h4 class="subheading text-break">{{deck.description}}</h4>
          </v-col>
        </v-row>
      </v-img>
    </div>
    <div v-else>Loading ...</div>
    <v-item-group v-if="!loading" multiple>
      <cards :cards="this.deck.cards"></cards>
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

<style>
.parallax .v-image__image {
  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}
</style>
