<template>
  <div>
    <h1 class="text-uppercase text-secondary font-weight-bolder">Deck Editor</h1>
    <div v-if="loading">Loading ...</div>
    <div v-else>
      <h3 v-if="this.edit">Editing {{ deck.title }}</h3>
      <h3 v-else>Creating</h3>
      <div class="form-group">
        <label for="title">Title</label>
        <input
          type="text"
          name="title"
          class="form-control form-control-sm"
          autocomplete="off"
          placeholder="Deck Title"
          v-model="deck.title"
          @keyup.enter="save"
        />
        <label for="description">Description</label>
        <textarea
          name="description"
          cols="50"
          rows="5"
          class="form-control form-control-sm"
          placeholder="Deck Description"
          v-model="deck.description"
          @keyup.enter="create"
        ></textarea>
        <img src="https://source.unsplash.com/random/200x200" class="rounded mx-auto d-block mt-3" />
        <div class="form-group">
          <label for="deck-image-url">Deck Image URL</label>
          <input
            type="text"
            name="deck-image-url"
            class="form-control"
            placeholder="Image URL"
            v-model="deck.image_url"
          />
          <label for="deck-image-file">Deck Image File</label>
          <input type="file" name="deck-image-file" class="form-control-file" />
        </div>
      </div>

      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="lang_source">Source language</label>
        </div>
        <select class="custom-select" name="lang_source" v-model="deck.lang_source">
          <option disabled value>Choose...</option>
          <option value="1">One</option>
          <option value="2">Two</option>
          <option value="3">Three</option>
        </select>
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="lang_target">Target language</label>
        </div>
        <select class="custom-select" name="lang_target" v-model="deck.lang_target">
          <option disabled value>Choose...</option>
          <option value="1">One</option>
          <option value="2">Two</option>
          <option value="3">Three</option>
        </select>
      </div>

      <h1
        class="text-uppercase text-secondary font-weight-bolder"
      >Flashcards ({{ deck.cards_finished }}/{{ deck.cards_amount }})</h1>
      <!-- <cards-editor v-on:add-finished-card="deck.cards_finished += $event"></cards-editor> -->

      <transition-group name="answers-list" class="flashcards" tag="div">
        <card-editor v-for="(card, index) in deck.cards" :cardToEdit="card" :key="'card' + index"></card-editor>
      </transition-group>

      <!-- class="col d-flex align-items-stretch"
          v-for="(card, column) in cardsInRow(row)"
      :key="'row' + row + column"-->

      <button class="btn btn-primary btn-block" :disabled="loading" @click="addCard">Add card</button>

      <div class="input-group mb-3 mt-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="visibility">Visibility</label>
        </div>
        <select class="custom-select" name="visibility" v-model="deck.visibility">
          <option disabled value>Choose...</option>
          <option value="1">One</option>
          <option value="2">Two</option>
          <option value="3">Three</option>
        </select>
      </div>

      <button class="btn btn-success btn-block" @click="create" :disabled="loading">Save deck</button>
    </div>
  </div>
</template>

<script>
import CardEditor from "./CardEditor";
export default {
  components: {
    CardEditor
  },
  data() {
    return {
      deck: {
        title: null,
        description: null,
        image_url: null,
        image_file: null,
        lang_source: "",
        lang_target: "",
        cards_finished: 0,
        cards_amount: 0,
        cards: [],
        visibility: ""
      },
      query: null,
      loading: false,
      status: null,
      edit: false
    };
  },
  watch: {
    "deck.cards": function(cards) {
      this.deck.cards_amount = cards.length;
    }
  },
  created() {
    this.loading = true;
    axios
      //   .get(`/api/decks/et-autem-laborum`)
      .get(`/api/decks/dsadsa`)
      .then(response => {
        this.deck = response.data.data;
        this.edit = true;
      })
      .catch(error => {})
      .then(() => {
        this.loading = false;
      });
  },
  methods: {
    addCard() {
      this.deck.cards.push({});
    },
    create() {}
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

.flashcards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  grid-gap: 30px;
}

.answers-list-enter-active,
.answers-list-leave-active {
  transition: all 1s;
}
.answers-list-enter,
.answers-list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>
