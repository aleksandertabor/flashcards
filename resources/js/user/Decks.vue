<template>
  <div>
    <v-sheet class="mx-auto mt-5" elevation="8">
      <v-card-text class="display-1">Decks</v-card-text>
      <v-row justify="space-around" class="pa-4">
        <div class="text-center" v-for="(option, index) in visibility_colors" :key="option.id">
          <v-btn :color="option" fab x-small></v-btn>
          <span>{{index}}</span>
        </div>
      </v-row>
      <v-slide-group v-if="decks" v-model="model" class="pa-4" show-arrows center-active>
        <v-slide-item
          v-for="(deck, index) in decks"
          :key="'deck' + index"
          v-slot:default="{ active, toggle }"
        >
          <v-card
            :color="active ? 'primary' : visibility_colors[deck.visibility]"
            class="ma-4"
            height="350"
            width="200"
            @click="toggle"
          >
            <v-img
              :src="deck.image"
              height="150px"
              gradient="to bottom, rgba(0, 0, 0, 0.33), rgba(0,0,0,.7)"
            >
              <div class="pa-5 fill-height">
                <v-badge
                  :content="deck.cards_count || '0'"
                  :value="deck.cards_count"
                  color="brown"
                  overlap
                  bordered
                  v-if="deck.cards_count > 0"
                >
                  <v-icon dark x-large>mdi-cards</v-icon>
                </v-badge>
                <h2 class="title-card">{{deck.title}}</h2>
              </div>
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
        <v-sheet v-if="model != null" color="grey lighten-4" height="100%" tile>
          <v-row class="fill-height" align="center" justify="center">
            <v-col class="pa-5" cols="12">
              <div class="d-flex flex-wrap justify-space-between">
                <v-badge v-if="editable" bordered color="error" icon="mdi-image-edit" overlap>
                  <v-btn
                    class="white--text mb-2"
                    :to="{name: 'deck-editor', params: {deckToEdit: decks[model].slug}}"
                    color="error"
                    depressed
                  >Edit deck</v-btn>
                </v-badge>
                <v-btn
                  class="white--text mb-2"
                  :to="{name: 'deck', params: {slug: decks[model].slug} }"
                  color="success"
                  depressed
                >
                  Open deck
                  <v-icon>mdi-open-in-app</v-icon>
                </v-btn>
              </div>
              <p class="font-weight-bold mt-2">{{decks[model].title}}</p>
              <p class="text-break">{{decks[model].description}}</p>
              <div v-if="decks[model].slug">
                <v-btn color="primary" v-clipboard:copy="copyLink(decks[model].slug)">Copy link</v-btn>
              </div>
            </v-col>
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
  methods: {
    copyLink(slug) {
      return (
        window.location.origin +
        this.$router.resolve({
          name: "deck",
          params: { slug: slug }
        }).href
      );
    }
  }
};
</script>

<style>
.title-card {
  font-size: 13px;
  color: #fff;
}
</style>
