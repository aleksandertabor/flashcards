<template>
  <div>
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
      columns: 3
    };
  },
  computed: {
    rows() {
      return this.decks === null
        ? 0
        : Math.ceil(this.decks.length / this.columns);
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
