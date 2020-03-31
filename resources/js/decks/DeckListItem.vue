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
      :to="{ name: 'deck', params: { slug } }"
    >
      <v-img :src="image"></v-img>

      <v-badge :content="cards_count || '0'" :value="cards_count" color="brown" overlap bordered>
        <v-icon x-large>mdi-cards</v-icon>
      </v-badge>

      <v-card-title>{{title}}</v-card-title>
      <v-card-text>{{created_at}}</v-card-text>
    </v-card>
  </v-skeleton-loader>
</template>

<script>
export default {
  props: {
    title: String,
    description: String,
    image: String,
    cards_count: Number,
    id: Number,
    lang_source: Object,
    lang_target: Object,
    slug: String,
    created_at: String,
    visibility: String
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
