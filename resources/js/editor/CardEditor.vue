<template>
  <div>
    <v-form ref="form" lazy-validation>
      <v-card>
        <v-toolbar flat dark>
          <v-toolbar-title>Card Editor</v-toolbar-title>
        </v-toolbar>

        <v-card-text>
          <v-file-input
            v-model="image_file"
            :rules="[rules.size]"
            accept="image/png, image/jpeg, image/webp"
            placeholder="Pick an image"
            prepend-icon="mdi-camera"
            label="Image"
            @change="previewImage"
            filled
          ></v-file-input>

          <v-text-field
            v-if="!image_file"
            v-model="card.image"
            prepend-icon="mdi-image"
            label="Image URL"
            :rules="[rules.url]"
            filled
            clearable
          ></v-text-field>

          <v-img
            v-if="card.image"
            :src="card.image"
            aspect-ratio="1"
            class="grey lighten-2"
            contain
          ></v-img>

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
            @change="translate"
          ></v-text-field>

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
            counter="255"
            @input="canTranslate = false"
            @click:clear="canTranslate = true"
          ></v-text-field>

          <v-textarea
            v-model="card.example_question"
            label="Example question"
            :rules="[rules.required, rules.max(255)]"
            :error-messages="errorFor('description')"
            required
            clearable
            filled
            :loading="loading"
            counter="255"
          ></v-textarea>

          <v-textarea
            v-model="card.example_answer"
            label="Example answer"
            :rules="[rules.required, rules.max(255)]"
            :error-messages="errorFor('description')"
            required
            clearable
            filled
            :loading="loading"
            counter="255"
          ></v-textarea>

          <v-btn color="success" depressed @click="translate">Translate</v-btn>

          <v-divider class="my-2"></v-divider>
        </v-card-text>
      </v-card>
    </v-form>
  </div>
</template>

<script>
export default {
  props: { cardToEdit: Object, languages: Array },
  data() {
    return {
      card: {
        question: null,
        answer: null,
        image: null,
        example_question: null,
        example_answer: null
      },
      canTranslate: true,
      image_file: null,
      query: null,
      loading: false,
      status: null,
      error: null,
      errors: null,
      rules: {
        required: v => !!v || "Required.",
        max: len => v => (v && v.length) <= len || `Max ${len} characters`,
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
    // editedCard() {
    //   return this.$attrs.card !== null ? this.$attrs.card : "";
    // }
  },
  created() {
    // this.loading = true;
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
          this.card.image = fileReader.result;
        });
        fileReader.readAsDataURL(file);
        this.image_file = file;
      } else {
        this.image_file = null;
        this.card.image = "";
      }
    },
    translate() {
      if (this.card.question.length && this.canTranslate) {
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
          })
          .then(() => (this.loading = false));
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
