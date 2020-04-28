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
    <div>
      <v-row>
        <v-col sm="6" cols="12" md="3" v-for="(deck, index) in decks" :key="'deck' + index">
          <deck-list-item :deck="deck"></deck-list-item>
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
import SearchBar from "./actions/SearchBar";
import InfiniteLoading from "vue-infinite-loading";
import DeckListItem from "./DeckListItem";
import { mapGetters } from "vuex";
export default {
  components: {
    InfiniteLoading,
    DeckListItem,
    SearchBar
  },
  data() {
    return {
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
    ...mapGetters({
      decks: "decks/decks",
      page: "decks/decksPage",
      query: "decks/query"
    })
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
    async infiniteHandler($state) {
      try {
        let findQueries = {
          page: this.page,
          filter: this.filter.val,
          query: this.query
        };
        this.cleanQueriesProperties(findQueries);

        const decks = await this.$store.dispatch("decks/decks", findQueries);

        if (decks.data.length && this.page < decks.paginatorInfo.lastPage) {
          this.$store.commit("decks/decksPageIncrement");
          $state.loaded();
        } else if (decks.data.length === 0 && decks.paginatorInfo.lastPage) {
          $state.complete();
        } else {
          $state.loaded();
          $state.complete();
        }
      } catch (errors) {}
    },
    changeFilter() {
      this.$store.commit("decks/resetDecks");
      this.$store.commit("decks/resetDecksPage");
      this.infiniteId += 1;
    },
    changeRandom() {
      if (this.filter.name === "Random") {
        this.filter.val.random = Math.floor(Math.random() * 1000) + 1;
      }
    },
    cleanQueriesProperties(obj) {
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
