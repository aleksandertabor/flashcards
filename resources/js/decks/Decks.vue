<template>
  <div>
    <nav class="navbar sticky-top navbar-light bg-light border-bottom mb-4">
      <v-row justify="space-between">
        <v-col cols="12" md="6">
          <v-select
            v-model="filter"
            :items="filters"
            filled
            label="Filters"
            return-object
            item-text="name"
            @change="changeFilter"
            class="mb-n5"
          ></v-select>
        </v-col>
        <v-col cols="12" md="6">
          <search-bar @change-filter="changeFilter"></search-bar>
        </v-col>
      </v-row>
    </nav>
    <loading v-if="loading"></loading>
    <div v-else>
      <v-row>
        <v-col sm="6" cols="12" md="3" v-for="(deck, index) in decks" :key="'deck' + index">
          <deck-list-item v-bind="deck"></deck-list-item>
        </v-col>
      </v-row>
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
        name: "Latest",
        val: {
          field: "created_at",
          order: "DESC"
        }
      },
      filters: [
        { name: "Latest", val: { field: "created_at", order: "DESC" } },
        { name: "Oldest", val: { field: "created_at", order: "ASC" } },
        {
          name: "Random",
          val: {
            field: "created_at",
            order: "RAND",
            random: Math.floor(Math.random() * 1000) + 1
          }
        },
        {
          name: "Cards",
          val: {
            field: "cards_count",
            order: "DESC",
            orderByCount: { model: "Deck", relation: "cards" }
          }
        }
      ],
      infiniteId: +new Date()
    };
  },
  computed: {
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
      this.changeRandom();
      if (this.$route.query.filter !== this.filter.name) {
        this.changeRoute();
      }
    }
  },
  methods: {
    infiniteHandler($state) {
      let toFind = {
        page: this.page,
        filter: this.filter.val,
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
    changeRandom() {
      if (this.filter.name === "Random") {
        this.filter.val.random = Math.floor(Math.random() * 1000) + 1;
      }
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

<style>
</style>
