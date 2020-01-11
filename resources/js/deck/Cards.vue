<template>
  <v-row>
    <v-col v-if="loading">Cards are loading ...</v-col>
    <v-col v-else v-for="(card, index) in cards" :key="'card' + index" cols="12" md="4">
      <card-list-item v-bind="card"></card-list-item>
    </v-col>
  </v-row>
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
      loading: false
    };
  },
  computed: {},
  methods: {},
  created() {}
};
</script>
