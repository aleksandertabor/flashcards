<template>
  <div>
    <div v-if="loading">Cards are loading ...</div>
    <div v-else>
      <div class="row mb-4" :class="'row-cols-' + columns" v-for="row in rows" :key="'row' + row">
        <div
          class="col d-flex align-items-stretch"
          v-for="(card, column) in cardsInRow(row)"
          :key="'row' + row + column"
        >
          <card-list-item v-bind="card"></card-list-item>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CardListItem from "./CardListItem";
export default {
  components: {
    CardListItem
  },
  props: { cards: Array },
  data() {
    return {
      loading: false,
      columns: 3
    };
  },
  computed: {
    rows() {
      return this.cards === null
        ? 0
        : Math.ceil(this.cards.length / this.columns);
    }
  },
  methods: {
    cardsInRow(row) {
      return this.cards.slice((row - 1) * this.columns, row * this.columns);
    }
  },
  created() {}
};
</script>
