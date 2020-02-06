<template>
  <div>
    <v-btn :loading="loading" :disabled="loading" color="success" app @click="print">
      <v-icon>mdi-file-pdf</v-icon>Print deck
    </v-btn>
  </div>
</template>

<script>
import download from "downloadjs";
export default {
  props: {
    deck: Object
  },
  data() {
    return {
      loader: null,
      loading: false
    };
  },
  watch: {
    loader() {
      const l = this.loader;
      this[l] = !this[l];

      this.loader = null;
    }
  },
  methods: {
    print() {
      this.loader = "loading";
      axios
        .post(
          "/api/deck/pdf",
          { deck: this.deck },
          {
            responseType: "blob",
            headers: {
              Accept: "application/pdf"
            }
          }
        )
        .then(response => {
          const content = response.headers["content-type"];
          download(response.data, this.deck.slug + ".pdf", content);
        })
        .catch(error => {
          console.log(error);
        })
        .finally(() => (this.loading = false));
    }
  }
};
</script>

<style scoped>
.custom-loader {
  animation: loader 1s infinite;
  display: flex;
}
@-moz-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@-webkit-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@-o-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
