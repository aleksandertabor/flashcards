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
              :error-messages="errorFor('question')"
              required
              clearable
              filled
              :loading="isInAction"
              counter="255"
              persistent-hint
              hint="On Auto mode press Return/Tab when focused on this field or just finish editing."
              @keyup.enter="autoAssets()"
              @keydown.tab="autoAssets()"
              @change="autoAssets()"
              autofocus
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              v-model="card.answer"
              ref="answer"
              label="Answer"
              :rules="[rules.max(255)]"
              :error-messages="errorFor('answer')"
              required
              clearable
              filled
              :loading="isInAction"
              :disabled="isInAction"
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
              :error-messages="errorFor('example_question')"
              required
              clearable
              filled
              :loading="isInAction"
              :disabled="isInAction"
              counter="255"
            ></v-textarea>
          </v-col>
          <v-col cols="12" md="6">
            <v-textarea
              v-model="card.example_answer"
              label="Example answer"
              :rules="[rules.max(255)]"
              :error-messages="errorFor('example_answer')"
              required
              clearable
              filled
              :loading="isInAction"
              :disabled="isInAction"
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
              @change="previewImage($event, 'card')"
              filled
              :loading="isInAction"
              :disabled="isInAction"
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
              :loading="isInAction"
              :disabled="isInAction"
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
          :disabled="!valid || isInAction"
          depressed
          @click="save();"
        >Save card</v-btn>

        <v-btn
          class="mb-2"
          color="error"
          :disabled="isInAction"
          depressed
          @click="remove();"
        >Remove card</v-btn>

        <v-btn
          class="mb-2"
          v-if="card.question"
          color="primary"
          :disabled="isInAction"
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
import formsMixin from "@/shared/mixins/formsMixin";
import imageInputsMixin from "@/shared/mixins/imageInputsMixin";
import {
  validationErrors,
  errorMessage
} from "@/shared/utils/validationErrors";
export default {
  mixins: [formsMixin, imageInputsMixin],
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
      valid: true,
      autoMode: true
    };
  },
  created() {
    this.initialSetup();
  },
  computed: {
    headingQuestion() {
      return this.heading("question");
    },
    headingAnswer() {
      return this.heading("answer");
    },
    assetsLoaded() {
      for (let loading in this.loaded) {
        if (!this.loaded[loading]) {
          return false;
        }
      }
      return true;
    },
    isInAction() {
      return this.loading || !this.assetsLoaded;
    }
  },
  methods: {
    initialSetup() {
      this.card.image = null;
      this.card = this.cardToEdit !== null ? this.cardToEdit : this.card;
      this.card.deck_id =
        this.deckToEdit !== null ? this.deckToEdit : this.card.deck_id;
      this.card.image_file = null;
      this.$delete(this.cardToEdit);
    },
    heading(field) {
      let heading = "";
      heading = this.card[field];
      if (heading && heading.length > 10) {
        heading = heading.substring(0, 10) + "...";
      }
      return heading;
    },
    async save() {
      this.resetState();
      try {
        this.card.id = (
          await this.$store.dispatch(
            this.card.id ? "editor/updateCard" : "editor/createCard",
            this.card
          )
        ).id;

        this.success = true;
        this.$emit("save-card");
      } catch (errors) {
        if (validationErrors(errors)) {
          this.errors = validationErrors(errors);
        }
        if (errorMessage(errors)) {
          this.error = errorMessage(errors);
        }
      }

      this.loading = false;
    },
    async remove() {
      this.resetState();
      try {
        if (this.card.id) {
          await this.$store.dispatch("editor/removeCard", this.card.id);
        }
        this.$emit("remove-card");
      } catch (errors) {
        if (validationErrors(errors)) {
          this.errors = validationErrors(errors);
        }
        if (errorMessage(errors)) {
          this.error = errorMessage(errors);
        }
      }

      this.loading = false;
    },
    clearImageUrlValidation() {
      this.error = null;
      if (this.errors !== null) {
        this.errors["image"] = [];
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
    async translate() {
      if (this.card.question.length) {
        this.loaded.translate = false;
        try {
          const translation = await this.$store.dispatch("api/translate", {
            phrase: this.card.question,
            source: this.languages[0],
            target: this.languages[1]
          });

          if (translation.length) {
            if (this.card.answer !== translation) {
              this.$refs.answer.reset();
              this.card.answer = translation;
            }
          }
        } catch (errors) {}

        this.loaded.translate = true;
      }
    },
    async image() {
      if (this.card.question.length) {
        this.loaded.image = false;

        try {
          const image = await this.$store.dispatch("api/image", {
            phrase: this.card.question,
            source: this.languages[0]
          });
          if (image) {
            this.card.image_file = null;
            this.card.image = image;
          }
        } catch (errors) {}

        this.loaded.image = true;
      }
    },
    async example() {
      if (this.card.question.length) {
        this.loaded.example = false;

        try {
          const example = await this.$store.dispatch("api/example", {
            phrase: this.card.question,
            source: this.languages[0],
            target: this.languages[1]
          });
          if (example[0]) {
            this.card.example_question = example[0];
          }
          if (example[1]) {
            this.card.example_answer = example[1];
          }
        } catch (errors) {}
        this.loaded.example = true;
      }
    }
  }
};
</script>
