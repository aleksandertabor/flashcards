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
import DeckListItem from "./DeckListItem";
export default {
  components: {
    DeckListItem
  },
  data() {
    return {
      decks: null,
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
    this.loading = true;
    const request = axios.get("/api/decks").then(response => {
      this.decks = response.data.data;
      this.loading = false;
    });
  }
};
</script>
