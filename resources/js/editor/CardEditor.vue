<template>
  <div>
    <div class="form-group">
      <img src="https://source.unsplash.com/random/200x200" class="rounded mx-auto d-block mt-3" />
      <div class="form-group">
        <label for="image-url-card">Card Image URL</label>
        <input
          type="text"
          name="image-url-card"
          class="form-control"
          placeholder="Image URL"
          v-model="card.image_url"
        />
        <label for="image-file-card">Card Image File</label>
        <input type="file" name="image-file-card" class="form-control-file" />
      </div>
      <div class="input-group mb-3 d-flex flex-column">
        <label for="question">Question</label>
        <input
          type="text"
          name="question"
          class="form-control form-control-sm"
          autocomplete="off"
          placeholder="Word"
          v-model="card.question"
          @input="addFinishedCard"
        />
        <label for="answer">Answer</label>
        <input
          type="text"
          name="answer"
          class="form-control form-control-sm"
          autocomplete="off"
          placeholder="Word"
          v-model="card.answer"
          @input="addFinishedCard"
        />
        <div class="input-group mb-3 mt-3 d-flex flex-column">
          <label for="example-question">Example question</label>
          <textarea
            type="text"
            name="example-question"
            class="form-control form-control-sm"
            placeholder="Sentence"
            v-model="card.example_question"
          ></textarea>
          <label for="example-answer">Example answer</label>
          <textarea
            type="text"
            name="example-answer"
            class="form-control form-control-sm"
            placeholder="Sentence"
            v-model="card.example_answer"
          ></textarea>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: { cardToEdit: Object },
  data() {
    return {
      card: {
        question: null,
        answer: null,
        image_url: null,
        image_file: null,
        example_question: null,
        example_answer: null
      },
      query: null,
      loading: false,
      status: null
    };
  },
  computed: {
    // editedCard() {
    //   return this.$attrs.card !== null ? this.$attrs.card : "";
    // }
  },
  created() {
    this.loading = true;
    // console.log(this.$attr.card);
    this.cardToEdit !== null ? (this.card = this.cardToEdit) : this.card;
  },
  methods: {
    addFinishedCard() {
      if (this.source !== null && this.target !== null) {
        this.$emit("add-finished-card", 1);
      } else {
        this.$emit("add-finished-card", -1);
      }
    },
    addCard() {
      this.loading = true;
      axios
        .get(``)
        .then(response => {
          this.status = response.status;
        })
        .catch(error => {});
    }
  }
};
</script>

<style scoped>
label {
  font-size: 0.7em;
  text-transform: uppercase;
  color: green;
  font-weight: bolder;
}
</style>
