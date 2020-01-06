<template>
  <div>
    <nav class="navbar sticky-top navbar-light bg-light border-bottom mb-4">
      <div class="back fa-2x" @click="$router.back()">
        <i class="fas fa-arrow-circle-left"></i>
      </div>
      <div>
        <label class="typo__label">Decks filters</label>
        <multiselect
          v-model="filter"
          deselect-label="Can't remove this value"
          track-by="name"
          label="name"
          placeholder="Select one"
          :options="filters"
          :searchable="false"
          :allow-empty="false"
          @select="changeFilter"
        >
          <template slot="filters" slot-scope="{ filter }">
            <strong>{{ filter.name }}</strong> is written in
            <strong>{{ filter.field }}</strong>
          </template>
        </multiselect>
      </div>

      <search-bar @change-filter="changeFilter"></search-bar>
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
import Multiselect from "vue-multiselect";
import DeckListItem from "./DeckListItem";
export default {
  components: {
    InfiniteLoading,
    Multiselect,
    DeckListItem,
    SearchBar
  },
  data() {
    return {
      loading: false,
      columns: 3,
      filter: {
        name: "Latest",
        field: "created_at",
        order: "DESC"
      },
      filters: [
        { name: "Latest", field: "created_at", order: "DESC" },
        { name: "Oldest", field: "created_at", order: "ASC" },
        { name: "Random", field: "created_at", order: "RAND" },
        {
          name: "Cards",
          field: "cards_count",
          order: "DESC",
          orderByCount: { model: "Deck", relation: "cards" }
        }
      ],
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
  mounted() {
    if (this.$route.query.filter) {
      this.filter = this.filters.find(obj => {
        return obj.name === this.$route.query.filter;
      });
    }
  },
  watch: {
    query: function() {
      if (this.$route.query.q !== this.query) {
        this.changeRoute();
      }
    },
    filter: function() {
      if (this.$route.query.filter !== this.filter.name) {
        this.changeRoute();
      }
    }
  },
  methods: {
    decksInRow(row) {
      return this.decks.slice((row - 1) * this.columns, row * this.columns);
    },
    infiniteHandler($state) {
      let toFind = {
        page: this.page,
        filter: { ...this.filter },
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
    changeFilter() {
      this.$store.commit("resetDecks");
      this.$store.commit("resetDecksPage");
      this.infiniteId += 1;
    },
    clean(obj) {
      delete obj.filter.name;
      for (var propName in obj) {
        if (
          obj[propName] === null ||
          obj[propName] === undefined ||
          obj[propName] === ""
        ) {
          delete obj[propName];
        }
      }
    },
    changeRoute() {
      this.$router.replace({
        query: { q: this.query, filter: this.filter.name }
      });
    }
  }
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>
</style>
