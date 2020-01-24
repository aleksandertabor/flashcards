<template>
  <v-item v-slot:default="{ active, toggle }">
    <v-card
      :color="active ? 'primary' : ''"
      class="d-flex align-center"
      dark
      height="200"
      @click="toggle"
    >
      <div v-if="!active" class="display-1 flex-grow-1 text-center">
        <v-img v-if="image" :src="image" aspect-ratio="1" class="grey lighten-2" contain>
          <div class="text-no-wrap text-center">{{ question }}</div>
        </v-img>
      </div>
      <div v-if="active" class="display-1 flex-grow-1 text-center">
        <div class="text-no-wrap text-center">{{ answer }}</div>
      </div>
    </v-card>
  </v-item>
</template>

<script>
export default {
  props: { question: String, answer: String, id: Number, image: String },
  filters: {
    reverse: value => {
      return [...value].reverse().join("");
    }
  }
};
</script>
