<template>
  <div>
    <v-expansion-panel-header :color="!valid ? 'error' : ''">
      Flashcard {{index + 1}} {{this.headingQuestion}} {{this.headingAnswer}}
      <template
        v-slot:actions
        v-if="!valid"
      >
        <v-icon>mdi-alert-circle</v-icon>
      </template>
    </v-expansion-panel-header>
    <v-expansion-panel-content>
      <v-form ref="cardForm" v-model="valid" lazy-validation>
        <v-switch v-model="autoMode" label="Auto mode" color="success" hide-details inset></v-switch>
        <v-row justify="space-between">
          <v-col cols="12" md="6">
            <v-text-field
              v-model="card.question"
              label="Question"
              :rules="[rules.max(255)]"
              :error-messages="errorFor('title')"
              required
              clearable
              filled
              :loading="loading || !assetsLoaded"
              counter="255"
              persistent-hint
              hint="On Auto mode press Return or Tab when editing this field"
              @keyup.enter="autoAssets()"
              @keydown.tab="autoAssets()"
              autofocus
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              v-model="card.answer"
              ref="answer"
              label="Answer"
              :rules="[rules.max(255)]"
              :error-messages="errorFor('title')"
              required
              clearable
              filled
              :loading="loading || !assetsLoaded"
              :disabled="loading || !assetsLoaded"
              counter="255"
            ></v-text-field>
          </v-col>
        </v-row>

        <v-row justify="space-between">
          <v-col cols="12" md="6">
            <v-textarea
              v-model="card.example_question"
              label="Example question"
              :rules="[rules.max(255)]"
              :error-messages="errorFor('description')"
              required
              clearable
              filled
              :loading="loading || !assetsLoaded"
              :disabled="loading || !assetsLoaded"
              counter="255"
            ></v-textarea>
          </v-col>
          <v-col cols="12" md="6">
            <v-textarea
              v-model="card.example_answer"
              label="Example answer"
              :rules="[rules.max(255)]"
              :error-messages="errorFor('description')"
              required
              clearable
              filled
              :loading="loading || !assetsLoaded"
              :disabled="loading || !assetsLoaded"
              counter="255"
            ></v-textarea>
          </v-col>
        </v-row>

        <v-row justify="space-between">
          <v-col cols="12" md="6">
            <v-file-input
              v-model="card.image_file"
              :rules="[rules.size]"
              :error-messages="errorFor('image_file')"
              accept="image/png, image/jpeg, image/webp"
              placeholder="Pick an image"
              prepend-icon="mdi-camera"
              label="Image"
              @change="previewImage"
              filled
              :loading="loading || !assetsLoaded"
              :disabled="loading || !assetsLoaded"
              @click:clear="forceImageRerender()"
              show-size
            ></v-file-input>

            <v-text-field
              ref="imageUrl"
              v-if="!card.image_file"
              v-model="card.image"
              prepend-icon="mdi-image"
              label="Image URL"
              :rules="[rules.url]"
              :error-messages="errorFor('image')"
              filled
              clearable
              :loading="loading || !assetsLoaded"
              :disabled="loading || !assetsLoaded"
              @input="forceImageRerender(); clearImageUrlValidation();"
              @click:clear="forceImageRerender(); clearImageUrlValidation();"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-img
              ref="image"
              :key="imageRenderKey"
              :src="card.image || ''"
              :lazy-src="'/images/bg-profile.png'"
              aspect-ratio="1"
              max-height="125"
              contain
            ></v-img>
          </v-col>
        </v-row>

        <v-btn
          class="mb-2"
          color="success"
          :disabled="!valid || loading || !assetsLoaded"
          depressed
          @click="save();"
        >Save card</v-btn>

        <v-btn
          class="mb-2"
          color="error"
          :disabled="loading || !assetsLoaded"
          depressed
          @click="remove();"
        >Remove card</v-btn>

        <v-btn
          class="mb-2"
          v-if="card.question"
          color="primary"
          :disabled="loading"
          depressed
          @click="getAssets()"
        >Find assets</v-btn>

        <v-card-actions>
          <v-alert type="error" v-if="error" dismissible>{{ error }} Not saved.</v-alert>
          <v-alert v-if="success" type="success" dismissible>Your card has been saved.</v-alert>
        </v-card-actions>
      </v-form>
    </v-expansion-panel-content>
  </div>
</template>

<script>
export default {
  props: {
    cardToEdit: Object,
    languages: Array,
    index: Number,
    deckToEdit: Number
  },
  data() {
    return {
      card: {
        key: null,
        id: null,
        deck_id: null,
        question: "",
        answer: "",
        image_file: null,
        image: "",
        example_question: "",
        example_answer: ""
      },
      loaded: {
        translate: true,
        example: true,
        image: true
      },
      canTranslate: true,
      query: null,
      loading: null,
      status: null,
      error: null,
      errors: null,
      success: null,
      imageRenderKey: 0,
      valid: true,
      autoMode: true,
      rules: {
        required: v => !!v || "Required.",
        min: len => v =>
          !v || (v && v.length >= len) || "Min " + len + " characters",
        max: len => v =>
          !v || (v && v.length <= len) || "Max " + len + " characters",
        length: len => v =>
          (v || "").length >= len ||
          "Invalid character length, required " + len,
        url: v =>
          !v ||
          /[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)?/gi.test(
            v
          ) ||
          "Wrong URL.",
        size: value =>
          !value ||
          value.size < 2097152 ||
          "File size should be less than 2 MB!"
      }
    };
  },
  created() {
    this.cardToEdit !== null ? (this.card = this.cardToEdit) : this.card;
    this.cardToEdit.image_file = null;
    this.cardToEdit.image = null;
    this.deckToEdit !== null
      ? (this.card.deck_id = this.deckToEdit)
      : this.card.deck_id;
    this.$delete(this.cardToEdit);
  },
  computed: {
    hasErrors() {
      return this.errors !== null;
    },
    headingQuestion() {
      let question = "";
      question = this.card.question;
      if (question && question.length > 10) {
        question = question.substring(0, 10) + "...";
      }
      return question;
    },
    headingAnswer() {
      let answer = "";
      answer = this.card.answer;
      if (answer && answer.length > 10) {
        answer = answer.substring(0, 10) + "...";
      }
      return answer;
    },
    assetsLoaded() {
      for (let loading in this.loaded) {
        if (!this.loaded[loading]) {
          return false;
        }
      }
      return true;
    }
  },
  methods: {
    save() {
      this.loading = true;
      this.errors = null;
      this.error = false;
      this.success = false;
      if (this.card.id) {
        this.$store
          .dispatch("updateCard", this.card)
          .then(response => {
            this.success = true;
            this.$emit("save-card");
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
      } else {
        this.$store
          .dispatch("createCard", this.card)
          .then(response => {
            this.success = true;
            this.card.id = response.data.createCard.id;
            this.$emit("save-card");
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
      }
    },
    remove() {
      this.loading = true;
      this.errors = null;
      this.error = false;
      this.success = false;
      if (this.card.id) {
        this.$store
          .dispatch("removeCard", this.card.id)
          .then(response => {})
          .then(() => this.$emit("remove-card"))
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
      } else {
        this.$emit("remove-card");
      }
    },
    clearImageUrlValidation() {
      this.error = null;
      if (this.errors !== null) {
        this.errors["image"] = [];
      }
    },
    forceImageRerender() {
      this.imageRenderKey += 1;
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
          this.card.image = fileReader.result;
          this.forceImageRerender();
        });
        this.card.image_file = file;
      } else {
        this.card.image_file = null;
        this.card.image = "";
      }
    },
    autoAssets() {
      if (this.autoMode) {
        this.getAssets();
      }
    },
    getAssets() {
      this.translate();
      this.example();
      this.image();
    },
    translate() {
      if (this.card.question.length) {
        this.loaded.translate = false;
        this.$store
          .dispatch("translate", {
            phrase: this.card.question,
            source: this.languages[0],
            target: this.languages[1]
          })
          .then(response => {
            if (response.data.translate.length) {
              if (this.card.answer !== response.data.translate) {
                const answerInput = this.$refs.answer;
                answerInput.reset();
                this.card.answer = response.data.translate;
              }
              this.$forceUpdate();
            }
          })
          .catch(error => {
            console.log("translateError", error);
          })
          .then(() => (this.loaded.translate = true));
      }
    },
    image() {
      if (this.card.question.length) {
        this.loaded.image = false;
        this.$store
          .dispatch("image", {
            phrase: this.card.question,
            source: this.languages[0]
          })
          .then(response => {
            if (response.data.image) {
              this.card.image = response.data.image;
            }
            console.log("obrazek: ", this.card.image);
          })
          .catch(error => {
            console.log("translateError", error);
          })
          .then(() => (this.loaded.image = true));
      }
    },
    example() {
      if (this.card.question.length) {
        this.loaded.example = false;
        this.$store
          .dispatch("example", {
            phrase: this.card.question,
            source: this.languages[0],
            target: this.languages[1]
          })
          .then(response => {
            console.log("Example: ", response);
            this.card.example_question = response.data.example[0];
            this.card.example_answer = response.data.example[1];
            this.$forceUpdate();
          })
          .catch(error => {
            console.log("translateError", error);
          })
          .then(() => (this.loaded.example = true));
      }
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
