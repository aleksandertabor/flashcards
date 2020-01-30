<template>
  <div>
    <v-expansion-panel-header :color="!valid ? 'error' : ''">
      Flashcard {{index + 1}} {{ card.question ? card.question.substring(0,8) : ""}} {{card.answer ? card.answer.substring(0,8) : ""}}
      <template
        v-slot:actions
        v-if="!valid"
      >
        <v-icon>mdi-alert-circle</v-icon>
      </template>
    </v-expansion-panel-header>
    <v-expansion-panel-content>
      <v-form ref="cardForm" v-model="valid" lazy-validation>
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
              :loading="loading"
              counter="255"
              @change="translate(); image(); example();"
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
              :loading="loading"
              :disabled="loading"
              counter="255"
              @input="canTranslate = false"
              @click:clear="canTranslate = true"
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
              :loading="loading"
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
              :loading="loading"
              counter="255"
            ></v-textarea>
          </v-col>
        </v-row>

        <v-row justify="space-between">
          <v-col cols="12" md="6">
            <v-file-input
              v-model="card.image_file"
              :rules="[rules.size]"
              accept="image/png, image/jpeg, image/webp"
              placeholder="Pick an image"
              prepend-icon="mdi-camera"
              label="Image"
              @change="previewImage"
              filled
              :loading="loading"
              @click:clear="forceImageRerender()"
              show-size
            ></v-file-input>

            <v-text-field
              v-if="!card.image_file"
              v-model="card.image"
              prepend-icon="mdi-image"
              label="Image URL"
              :rules="[rules.url]"
              :error-messages="errorFor('image')"
              filled
              clearable
              :loading="loading"
              @click:clear="forceImageRerender()"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-img
              :key="imageRenderKey"
              :src="card.image"
              lazy-src="/img/app/bg-profile.png"
              aspect-ratio="1"
              max-height="125"
              contain
            ></v-img>
          </v-col>
        </v-row>

        <v-btn color="success" :disabled="!valid || loading" depressed @click="save();">Save card</v-btn>

        <v-btn color="error" :disabled="!valid || loading" depressed @click="remove();">Remove card</v-btn>

        <v-btn
          v-if="card.question"
          color="primary"
          :disabled="loading"
          depressed
          @click="translate(); image(); example();"
        >Translate</v-btn>

        <v-card-actions>
          <v-alert type="error" v-if="error" dismissible>{{ error }} Not saved.</v-alert>
          <v-alert v-if="success" type="success" dismissible>Your card has been saved.</v-alert>
          <v-snackbar
            v-model="success"
            color="success"
            :timeout="3000"
            bottom
            left
          >Your card has been saved.</v-snackbar>
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
        uuid: null,
        id: null,
        deck_id: null,
        question: "",
        answer: "",
        image_file: null,
        image: "",
        example_question: "",
        example_answer: ""
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
  created() {
    // this.loading = true;
    // console.log(this.$attr.card);
    this.cardToEdit !== null ? (this.card = this.cardToEdit) : this.card;
    this.cardToEdit.image_file = null;
    this.cardToEdit.image = null;
    this.deckToEdit !== null
      ? (this.card.deck_id = this.deckToEdit)
      : this.card.deck_id;
    this.$delete(this.cardToEdit);
    // this.$refs.cardForm.validate();
  },
  methods: {
    save() {
      this.loading = true;
      this.errors = null;
      this.error = false;
      this.success = false;
      if (this.card.id) {
        console.log("karta: ", this.card);
        console.log("karta image file: ", this.card.image_file);
        this.$store
          .dispatch("updateCard", this.card)
          .then(response => {
            this.success = true;
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
      if (this.card.id) {
        this.$store
          .dispatch("removeCard", this.card.id)
          .then(response => {})
          .catch(error => {
            this.loading = false;
          })
          .then(() => this.$emit("remove-card"));
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
        });
        this.card.image_file = file;
      } else {
        this.card.image_file = null;
        this.card.image = "";
      }
    },
    translate() {
      if (this.card.question.length && this.canTranslate) {
        this.loading = true;
        this.$store
          .dispatch("translate", {
            phrase: this.card.question,
            source: this.languages[0],
            target: this.languages[1]
          })
          .then(response => {
            this.card.answer = response.data.translate;
            this.$forceUpdate();
            console.log("translacja: ", this.card.answer);
            console.log("translacja: ", response.data.translate);
          })
          .catch(error => {
            console.log("translateError", error);
          });
        //   .finally(() => (this.loading = false));
      }
    },
    image() {
      if (this.card.question.length && !this.card.image) {
        this.loading = true;
        this.$store
          .dispatch("image", {
            phrase: this.card.question,
            source: this.languages[0]
          })
          .then(response => {
            this.card.image = response.data.image;
            console.log("obrazek: ", this.card.image);
          })
          .catch(error => {
            console.log("translateError", error);
          });
        //   .finally(() => (this.loading = false));
      }
    },
    example() {
      if (this.card.question.length) {
        this.loading = true;
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
          .finally(() => (this.loading = false));
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
