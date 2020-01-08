<template>
  <div>
    <v-sheet class="mx-auto" elevation="8">
      <h2>Decks</h2>
      <v-slide-group v-if="decks" v-model="model" class="pa-4" show-arrows>
        <v-slide-item
          v-for="(deck, index) in decks"
          :key="'deck' + index"
          v-slot:default="{ active, toggle }"
        >
          <v-card
            :color="active ? 'primary' : 'grey lighten-1'"
            class="ma-4"
            height="200"
            width="100"
            @click="toggle"
          >
            <h5 class="card-title">{{ deck.title }}</h5>
            <v-row class="fill-height" align="center" justify="center">
              <v-scale-transition>
                <v-icon v-if="active" color="white" size="48" v-text="'mdi-close-circle-outline'"></v-icon>
                <v-icon v-if="active" color="white" size="48" v-text="'mdi-circle-edit-outline'"></v-icon>
                <v-icon v-if="active" color="white" size="48" v-text="'delete-circle-outline'"></v-icon>
              </v-scale-transition>
            </v-row>
          </v-card>
        </v-slide-item>
      </v-slide-group>

      <v-expand-transition>
        <v-sheet v-if="model != null" color="grey lighten-4" height="200" tile>
          <v-row class="fill-height" align="center" justify="center">
            <h3 class="title">{{decks[model].title}}</h3>
            <router-link :to="{name: 'deck', params: {slug: decks[model].slug} }">
              <h5 class="card-title">{{decks[model].title}}</h5>
            </router-link>
            <p>{{decks[model].description}}</p>
          </v-row>
        </v-sheet>
      </v-expand-transition>
    </v-sheet>
    <div v-if="loading">Decks are loading ...</div>
    <div v-else>
      <div class="row mb-4" :class="'row-cols-' + columns" v-for="row in rows" :key="'row' + row">
        <div
          class="col d-flex align-items-stretch"
          v-for="(deck, column) in decksInRow(row)"
          :key="'row' + row + column"
        >
          <deck-list-item v-bind="deck"></deck-list-item>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DeckListItem from "../decks/DeckListItem";
export default {
  components: {
    DeckListItem
  },
  props: {
    decks: Array
  },
  data() {
    return {
      loading: false,
      columns: 3,
      model: null
    };
  },
  computed: {
    rows() {
      return this.decks === null
        ? 0
        : Math.ceil(this.decks.length / this.columns);
    },
    allDecks() {
      return this.decks === null;
    }
  },
  methods: {
    decksInRow(row) {
      return this.decks.slice((row - 1) * this.columns, row * this.columns);
    }
  },
  created() {
    console.log(this.decks);
  }
};
</script>

<style>
</style>
