<template>
  <div>
    <nav class="navbar sticky-top navbar-light bg-light border-bottom mb-4">
      <div class="back fa-2x" @click="$router.back()">
        <i class="fas fa-arrow-circle-left"></i>
      </div>
      <select v-model="filter" @change="changeType" class>
        <option :value="{field: 'created_at', order: 'DESC'}">Latest</option>
        <option :value="{field: 'created_at', order: 'ASC'}">Oldest</option>
        <option :value="{field: 'created_at', order: 'RAND'}">Random</option>
        <option
          :value="{field: 'cards_count', order: 'DESC', orderByCount: {model: 'Deck', relation: 'cards'}}"
        >Cards amount</option>
      </select>
      <search-bar @change-type="changeType"></search-bar>
    </nav>
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
    <infinite-loading :identifier="infiniteId" @infinite="infiniteHandler" spinner="spiral">
      <div slot="no-more">No more decks</div>
      <div slot="no-results">No decks</div>
    </infinite-loading>
  </div>
</template>

<script>
import SearchBar from "../components/SearchBar";
import InfiniteLoading from "vue-infinite-loading";
import DeckListItem from "./DeckListItem";
export default {
  components: {
    InfiniteLoading,
    DeckListItem,
    SearchBar
  },
  data() {
    return {
      loading: false,
      columns: 3,
      filter: {
        field: "created_at",
        order: "DESC"
      },
      infiniteId: +new Date()
    };
  },
  computed: {
    rows() {
      return this.decks === null
        ? 0
        : Math.ceil(this.decks.length / this.columns);
    },
    decks() {
      return this.$store.state.decks;
    },
    page() {
      return this.$store.state.decksPage;
    },
    query() {
      return this.$store.state.query;
    }
  },
  methods: {
    decksInRow(row) {
      return this.decks.slice((row - 1) * this.columns, row * this.columns);
    },
    infiniteHandler($state) {
      console.log(this.page);
      let toFind = {
        page: this.page,
        filter: this.filter,
        query: this.query
      };
      this.clean(toFind);
      this.$store
        .dispatch("decks", toFind)
        .then(response => {
          if (
            response.data.decks.data.length &&
            this.page < response.data.decks.paginatorInfo.lastPage
          ) {
            console.log("loaded");
            this.$store.commit("decksPageIncrement");
            $state.loaded();
          } else if (
            response.data.decks.data.length === 0 &&
            this.page === response.data.decks.paginatorInfo.lastPage
          ) {
            $state.complete();
          } else {
            $state.loaded();
            $state.complete();
          }
        })
        .catch(error => {
          console.log(error);
        })
        .then(() => (this.loading = false));
    },
    changeType() {
      console.log("zmienia typ");
      this.$store.commit("resetDecks");
      this.$store.commit("resetDecksPage");
      this.infiniteId += 1;
    },
    clean(obj) {
      for (var propName in obj) {
        if (
          obj[propName] === null ||
          obj[propName] === undefined ||
          obj[propName] === ""
        ) {
          delete obj[propName];
        }
      }
    }
  }
};
</script>

<style>
</style>
