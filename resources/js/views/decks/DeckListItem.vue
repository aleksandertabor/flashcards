<template>
  <v-skeleton-loader
    ref="skeleton"
    :loading="loading"
    type="card"
    tile
    transition="fade-transition"
  >
    <v-card
      v-show="loaded"
      class="mx-auto"
      max-width="374"
      :to="{ name: 'deck', params: { slug: this.deck.slug } }"
    >
      <v-img :src="deck.image"></v-img>

      <v-badge
        :content="deck.cards_count || '0'"
        :value="deck.cards_count"
        color="brown"
        overlap
        bordered
      >
        <v-icon x-large>mdi-cards</v-icon>
      </v-badge>

      <v-card-title>{{deck.title}}</v-card-title>
      <v-card-text>{{deck.created_at}}</v-card-text>
    </v-card>
  </v-skeleton-loader>
</template>

<script>
export default {
  props: {
    deck: Object
  },
  data() {
    return {
      loading: true,
      loaded: false
    };
  },
  created() {
    setTimeout(() => {
      const readyHandler = () => {
        if (document.readyState == "complete") {
          this.loading = false;
          this.loaded = true;
          document.removeEventListener("readystatechange", readyHandler);
        }
      };
      document.addEventListener("readystatechange", readyHandler);
      readyHandler();
    }, 500);
  }
};
</script>
