<template>
  <v-card>
    <v-img :src="image" v-if="image" height="200px"></v-img>
    <v-item v-slot:default="{ active, toggle }">
      <v-card
        :color="active ? 'primary' : ''"
        class="d-flex align-center"
        dark
        height="100%"
        @click="toggle"
      >
        <div v-if="!active" class="flex-grow-1 text-center">
          <div class="pa-2 text-center">{{ question }}</div>
          <v-btn icon @click.stop.prevent="show = !show">
            <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
          </v-btn>
          <v-expand-transition>
            <div v-show="show">
              <v-divider></v-divider>

              <v-card-text>{{example_question}}</v-card-text>
            </div>
          </v-expand-transition>
        </div>
        <div v-if="active" class="flex-grow-1 text-center">
          <div class="pa-2 text-center">{{ answer }}</div>
          <v-btn icon @click.stop.prevent="show = !show">
            <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
          </v-btn>
          <v-expand-transition>
            <div v-show="show">
              <v-divider></v-divider>

              <v-card-text>{{example_answer}}</v-card-text>
            </div>
          </v-expand-transition>
        </div>
      </v-card>
    </v-item>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      show: false
    };
  },
  props: {
    question: String,
    answer: String,
    example_question: String,
    example_answer: String,
    id: Number,
    image: String
  },
  filters: {
    reverse: value => {
      return [...value].reverse().join("");
    }
  }
};
</script>
