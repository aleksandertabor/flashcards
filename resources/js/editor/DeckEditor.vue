<template>
  <div>
    <div v-if="loading">Loading ...</div>
    <div v-else>
      <v-form ref="form" lazy-validation>
        <v-card>
          <v-card-title class="title font-weight-regular justify-space-between">
            <span>{{ currentTitle }}</span>
            <v-avatar
              color="green lighten-2"
              class="subheading white--text"
              size="24"
              v-text="step"
            ></v-avatar>
          </v-card-title>

          <v-window v-model="step">
            <v-window-item :value="1">
              <v-card-text>
                <v-text-field
                  v-model="deck.title"
                  label="Title"
                  :rules="[rules.required, rules.max(80)]"
                  :error-messages="errorFor('title')"
                  required
                  clearable
                  filled
                  :loading="loading"
                  counter="80"
                ></v-text-field>

                <v-textarea
                  v-model="deck.description"
                  label="Description"
                  :rules="[rules.required, rules.max(320)]"
                  :error-messages="errorFor('description')"
                  required
                  clearable
                  filled
                  :loading="loading"
                  counter="320"
                ></v-textarea>

                <v-row justify="space-between">
                  <v-col cols="12" md="6">
                    <v-file-input
                      v-model="deck.image_file"
                      :rules="[rules.size]"
                      accept="image/png, image/jpeg, image/webp"
                      placeholder="Pick an image"
                      prepend-icon="mdi-camera"
                      label="Image"
                      @change="previewImage"
                      filled
                      @click:clear="forceImageRerender()"
                    ></v-file-input>

                    <v-text-field
                      v-if="!deck.image_file"
                      v-model="deck.image"
                      prepend-icon="mdi-image"
                      label="Image URL"
                      :rules="[rules.url]"
                      :error-messages="errorFor('image')"
                      filled
                      clearable
                      @click:clear="forceImageRerender()"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-img
                      :key="imageRenderKey"
                      :src="deck.image"
                      lazy-src="/img/app/bg-profile.png"
                      aspect-ratio="1"
                      max-height="125"
                      contain
                    ></v-img>
                  </v-col>
                </v-row>
                <v-row justify="space-between">
                  <v-col cols="12" md="6">
                    <v-select
                      prepend-icon="mdi-home-floor-1"
                      v-model="deck.lang_source_id"
                      :rules="[rules.required]"
                      :error-messages="errorFor('lang_source_id')"
                      :items="languages"
                      filled
                      label="Source language"
                      append-outer-icon="mdi-translate"
                    ></v-select>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-select
                      prepend-icon="mdi-home-floor-2"
                      v-model="deck.lang_target_id"
                      :rules="[rules.required]"
                      :error-messages="errorFor('lang_target_id')"
                      :items="languages"
                      filled
                      label="Target language"
                      append-outer-icon="mdi-translate"
                    ></v-select>
                  </v-col>
                </v-row>

                <v-select
                  prepend-icon="mdi-link"
                  v-model="deck.visibility"
                  :hint="deck.visibility.description ? deck.visibility.description : 'Select visibility' "
                  :items="visibility_options"
                  :rules="[rules.required]"
                  :error-messages="errorFor('visibility')"
                  label="Visibility"
                  filled
                  dense
                  persistent-hint
                  return-object
                  no-data-text="no data"
                  color="grey darken-4"
                  :background-color="deck.visibility.color"
                ></v-select>
                <v-alert type="error" v-if="error" dismissible>{{ error }}</v-alert>
                <v-alert v-if="success" type="success" dismissible>Your deck has been saved.</v-alert>
                <v-snackbar
                  v-model="success"
                  color="success"
                  :timeout="3000"
                  bottom
                  left
                >Your deck has been saved.</v-snackbar>
              </v-card-text>
              <v-card-text v-if="slug">
                <v-text-field
                  v-model="slug"
                  v-if="slug"
                  label="Link"
                  disabled
                  filled
                  :loading="loading"
                ></v-text-field>
                <v-btn color="primary" v-if="slug" v-clipboard:copy="slug">Copy link</v-btn>
              </v-card-text>
              <v-card-actions>
                <v-btn color="success" depressed @click="create">Save deck</v-btn>
              </v-card-actions>
            </v-window-item>

            <v-window-item :value="2">
              <v-card-text>
                <v-expansion-panels focusable>
                  <v-expansion-panel v-for="(card, index) in deck.cards" :key="'card' + index">
                    <v-expansion-panel-header>Flashcard {{index + 1}} {{ card.question !== undefined ? card.question.substring(0,8) : ""}} {{card.answer !== undefined ? card.answer.substring(0,8) : ""}}</v-expansion-panel-header>
                    <card-editor
                      v-on:remove-card="removeThisCard(index)"
                      :cardToEdit="card"
                      v-bind:languages="getLanguagesCodes()"
                    ></card-editor>
                  </v-expansion-panel>
                </v-expansion-panels>
                <v-row justify="space-between">
                  <v-col cols="12" md="6">
                    <h2 class="display-1 success--text">
                      Flashcards:&nbsp;
                      <v-fade-transition leave-absolute>
                        <span>{{ deck.cards.length }} / {{ this.cards_limit }}</span>
                      </v-fade-transition>
                    </h2>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-progress-circular :value="progress" class="mr-2"></v-progress-circular>
                  </v-col>
                </v-row>
                <v-btn
                  color="success"
                  depressed
                  @click="addCard"
                  :disabled="deck.cards.length >= cards_limit"
                >Add card</v-btn>
              </v-card-text>
            </v-window-item>

            <v-window-item :value="3">
              <div class="pa-4 text-center">
                <v-img
                  class="mb-4"
                  contain
                  height="128"
                  src="https://cdn.vuetifyjs.com/images/logos/v.svg"
                ></v-img>
                <h3 class="title font-weight-light mb-2">Welcome to Vuetify</h3>
                <span class="caption grey--text">Thanks for signing up!</span>
              </div>
            </v-window-item>
          </v-window>
          <v-card-actions>
            <v-btn :disabled="step === 1" text @click="step--">Back</v-btn>
            <v-spacer></v-spacer>
            <v-btn :disabled="step === 3" color="primary" depressed @click="step++">Next</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
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
        image_file: null,
        image: "",
        lang_source_id: "",
        lang_target_id: "",
        cards: [],
        visibility: ""
      },
      cards_limit: 50,
      slug: null,
      languages: [],
      visibility_options: [],
      query: null,
      loading: false,
      status: null,
      edit: false,
      errors: null,
      error: null,
      success: null,
      step: 1,
      imageRenderKey: 0,
      rules: {
        required: v => !!v || "Required.",
        min: len => v => (v && v.length) >= len || `Min ${len} characters`,
        max: len => v => (v && v.length) <= len || `Max ${len} characters`,
        length: len => v =>
          (v || "").length >= len ||
          `Invalid character length, required ${len}`,
        url: v =>
          /[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)?/gi.test(
            v
          ) || "Wrong URL.",
        size: value =>
          !value ||
          value.size < 5000000 ||
          "File size should be less than 5 MB!"
      }
    };
  },
  computed: {
    currentTitle() {
      switch (this.step) {
        case 1:
          return "Add a new deck";
        case 2:
          return "Create flashcards";
        default:
          return "Save all";
      }
    },
    progress() {
      return (this.deck.cards.length / this.cards_limit) * 100;
    },
    hasErrors() {
      return this.errors !== null;
    }
  },
  watch: {
    "deck.cards": function(cards) {
      this.deck.cards_amount = cards.length;
    }
  },
  beforeMount() {
    this.loading = true;
    this.$store
      .dispatch("deckEditor")
      .then(response => {
        const {
          data: { languages }
        } = response;
        const {
          data: {
            __type: { enumValues }
          }
        } = response;

        const languages_options = languages.map(({ id, locale, name }) => {
          return { text: `${name} (${locale})`, value: id, code: locale };
        });

        let visibility_options = enumValues.map(({ name, description }) => {
          return {
            text: `${name.toLowerCase()}`,
            value: name,
            description,
            color: "success"
          };
        });
        visibility_options[0].color = "green";
        visibility_options[1].color = "orange";
        visibility_options[2].color = "purple";
        this.visibility_options = visibility_options;
        this.languages = languages_options;
      })
      .catch(error => {})
      .then(() => (this.loading = false));
  },
  created() {
    // axios
    //   //   .get(`/api/decks/et-autem-laborum`)
    //   .get(`/api/decks/programmable-global-help-desk`)
    //   .then(response => {
    //     this.deck = response.data.data;
    //     this.edit = true;
    //   })
    //   .catch(error => {})
    //   .then(() => {
    //     this.loading = false;
    //   });
  },
  methods: {
    forceImageRerender() {
      this.imageRenderKey += 1;
    },
    removeThisCard(index) {
      this.deck.cards.splice(index, 1);
    },
    addCard() {
      if (this.deck.cards.length < this.cards_limit) {
        this.deck.cards.push({});
      }
    },
    create() {
      this.loading = true;
      this.errors = null;
      this.error = false;
      this.success = false;

      this.$store
        .dispatch("createDeck", this.deck)
        .then(response => {
          this.success = true;
          this.slug =
            window.location.origin +
            this.$router.resolve({
              name: "deck",
              params: { slug: response.data.createDeck.slug }
            }).href;
          console.log("createDeckvue", response);
        })
        .catch(error => {
          const {
            graphQLErrors: { validationErrors }
          } = error;
          const {
            graphQLErrors: {
              0: { message }
            }
          } = error;
          if (validationErrors) {
            this.errors = validationErrors;
          }
          if (message) {
            this.error = message;
          }
        })
        .then(() => (this.loading = false));
    },
    errorFor(field) {
      return this.hasErrors && this.errors[field] ? this.errors[field] : null;
    },
    previewImage(file) {
      if (file) {
        let filename = file.name;
        if (filename.lastIndexOf(".") <= 0) {
          return;
        }
        const fileReader = new FileReader();
        fileReader.readAsDataURL(file);
        fileReader.addEventListener("load", () => {
          this.deck.image = fileReader.result;
        });
        this.deck.image_file = file;
      } else {
        this.deck.image_file = null;
        this.deck.image = "";
      }
    },
    getLanguagesCodes() {
      return [
        this.languages[
          this.languages.findIndex(
            lang => lang.value === this.deck.lang_source_id
          )
        ].code,
        this.languages[
          this.languages.findIndex(
            lang => lang.value === this.deck.lang_target_id
          )
        ].code
      ];
    }
  }
};
</script>

<style scoped>
</style>
