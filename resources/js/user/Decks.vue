<template>
  <div>
    <v-sheet class="mx-auto mt-5" elevation="8">
      <v-card-text class="display-1">Decks</v-card-text>
      <v-slide-group v-if="decks" v-model="model" class="pa-4" show-arrows center-active>
        <v-slide-item
          v-for="(deck, index) in decks"
          :key="'deck' + index"
          v-slot:default="{ active, toggle }"
        >
          <v-card
            :color="active ? 'primary' : visibility_colors[deck.visibility]"
            class="ma-4"
            height="200"
            width="100"
            @click="toggle"
          >
            <v-img :src="deck.image" height="100px">
              <v-badge
                :content="deck.cards_count || '0'"
                :value="deck.cards_count"
                color="brown"
                overlap
                bordered
              >
                <v-icon large>mdi-cards</v-icon>
              </v-badge>
              <v-row align="end" class="lightbox white--text pa-2 fill-height">
                <v-col>
                  <v-card-text class="text-truncate">{{deck.title}}</v-card-text>
                </v-col>
              </v-row>
            </v-img>
            <v-row class="fill-height" align="center" justify="center">
              <v-scale-transition>
                <v-icon v-if="active" color="white" size="32" v-text="'mdi-close-circle-outline'"></v-icon>
              </v-scale-transition>
            </v-row>
          </v-card>
        </v-slide-item>
      </v-slide-group>

      <v-expand-transition>
        <v-sheet v-if="model != null" color="grey lighten-4" height="200" tile>
          <v-row class="fill-height" align="center" justify="center">
            <v-badge v-if="editable" bordered color="error" icon="mdi-image-edit" overlap>
              <v-btn
                :to="{name: 'deck-editor', params: {deckToEdit: decks[model].slug}}"
                class="white--text"
                color="error"
                depressed
              >Edit</v-btn>
            </v-badge>
            <router-link :to="{name: 'deck', params: {slug: decks[model].slug} }">
              <h3 class="title">{{decks[model].title}}</h3>
            </router-link>
            <p class="pa-5">{{decks[model].description}}</p>
          </v-row>
        </v-sheet>
      </v-expand-transition>
    </v-sheet>
  </div>
</template>

<script>
export default {
  props: {
    decks: Array,
    editable: Boolean
  },
  data() {
    return {
      visibility_colors: {
        public: "green",
        unlisted: "orange",
        private: "purple"
      },
      active: false,
      loading: false,
      columns: 3,
      model: null
    };
  },
  computed: {
    allDecks() {
      return this.decks === null;
    }
  },
  methods: {}
};
</script>

<style>
</style>
