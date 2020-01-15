<template>
  <div>
    <div v-if="loading">Loading ...</div>
    <div v-else>
      <v-alert type="success" v-if="success" dismissible>Your deck has been saved.</v-alert>
      <v-alert type="error" v-if="error" dismissible>Fill your data correctly.</v-alert>
      <v-snackbar v-model="success" :timeout="2000" bottom left>Your deck has been saved.</v-snackbar>

      <v-snackbar v-model="error" :timeout="2000" bottom left>Fill your data correctly.</v-snackbar>
      <v-form ref="form" lazy-validation>
        <v-card>
          <v-toolbar flat dark>
            <v-toolbar-title>
              Deck editor / mode:
              <p v-if="this.edit">Editing {{ deck.title }}</p>
              <p v-else>Creating</p>
            </v-toolbar-title>
          </v-toolbar>

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

            <v-img v-if="deck.image" :src="deck.image" aspect-ratio="1" max-height="125" contain></v-img>

            <v-file-input
              v-model="deck.image_file"
              :rules="[rules.size]"
              accept="image/png, image/jpeg, image/webp"
              placeholder="Pick an image"
              prepend-icon="mdi-camera"
              label="Image"
              @change="previewImage"
              filled
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
            ></v-text-field>

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

            <v-select
              prepend-icon="mdi-link"
              v-model="deck.visibility"
              :hint="deck.visibility.description ? deck.visibility.description : 'Select visibility' "
              :items="visibility_options"
              :rules="[rules.required]"
              :error-messages="errorFor('visibility')"
              filled
              label="Visibility"
              dense
              persistent-hint
              return-object
              no-data-text="no data"
            ></v-select>

            <v-divider class="my-2"></v-divider>

            <v-item-group multiple>
              <v-subheader>Cards</v-subheader>
              <v-item v-for="(card, index) in deck.cards" :key="'card' + index"></v-item>
            </v-item-group>

            <h2 class="display-1 success--text pl-4">
              Flashcards:&nbsp;
              <v-fade-transition leave-absolute>
                <span>{{ deck.cards.length }} / {{ this.cards_limit }}</span>
              </v-fade-transition>
            </h2>

            <v-progress-circular :value="progress" class="mr-2"></v-progress-circular>

            <transition-group name="answers-list" class="flashcards" tag="div">
              <card-editor
                v-for="(card, index) in deck.cards"
                :cardToEdit="card"
                :key="'card' + index"
                v-bind:languages="getLanguagesCodes()"
              ></card-editor>
            </transition-group>

            <v-btn color="success" depressed @click="addCard">Add card</v-btn>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="success" depressed @click="create">Add</v-btn>
            <v-btn color="success" depressed>Save</v-btn>
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
      //! todo get languages and visibility options from database
      languages: [],
      visibility_options: [],
      query: null,
      loading: false,
      status: null,
      edit: false,
      errors: null,
      error: null,
      success: null,
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
          value.size < 2000000 ||
          "File size should be less than 2 MB!"
      }
    };
  },
  computed: {
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

        const visibility_options = enumValues.map(({ name, description }) => {
          return {
            text: `${name.toLowerCase()}`,
            value: name,
            description
          };
        });
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
    addCard() {
      this.deck.cards.push({});
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
        fileReader.addEventListener("load", () => {
          this.deck.image = fileReader.result;
        });
        fileReader.readAsDataURL(file);
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
