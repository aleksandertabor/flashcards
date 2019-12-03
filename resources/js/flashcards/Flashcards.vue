<template>
  <div>
    Rows is: {{ rows }}
    <div v-if="loading">Data is loading ...</div>
    <div v-else>
      <div class="row mb-4" :class="'row-cols-' + columns" v-for="row in rows" :key="'row' + row">
        <div
          class="col"
          v-for="(flashcard, column) in flashcardsInRow(row)"
          :key="'row' + row + column"
        >
          <flashcard-list-item :title="flashcard.title" :content="flashcard.content" :price="100"></flashcard-list-item>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FlashcardListItem from "./FlashcardListItem";
export default {
  components: {
    FlashcardListItem
  },
  data() {
    return {
      flashcards: null,
      loading: false,
      columns: 3
    };
  },
  computed: {
    rows() {
      return this.flashcards === null
        ? 0
        : Math.ceil(this.flashcards.length / this.columns);
    }
  },
  methods: {
    flashcardsInRow(row) {
      return this.flashcards.slice(
        (row - 1) * this.columns,
        row * this.columns
      );
    }
  },
  created() {
    this.loading = true;
    console.log("Flashcards created");
    setTimeout(() => {
      console.log("Timeout");
      this.flashcards = [
        {
          title: "New title1",
          content: "New content1"
        },
        {
          title: "New title2",
          content: "New content2"
        },
        {
          title: "New title2",
          content: "New content2"
        },
        {
          title: "New title2",
          content: "New content2"
        },
        {
          title: "New title2",
          content: "New content2"
        },
        {
          title: "New title2",
          content: "New content2"
        },
        {
          title: "New title2",
          content: "New content2"
        }
      ];
      this.loading = false;
    }, 2000);
  }
};
</script>
