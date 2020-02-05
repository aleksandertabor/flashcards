<template>
  <div>
    <v-btn color="success" app @click="print"><v-icon>mdi-file-pdf</v-icon> Print deck</v-btn>
  </div>
</template>

<script>
import download from "downloadjs";
export default {
  props: {
    deck: Object
  },
  data() {
    return {};
  },
  methods: {
    print() {
      console.log(this.deck);
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
          download(response.data, this.deck.title + ".pdf", content);
        })
        .catch(error => {
          console.log(error);
        });
    },
  }
};
</script>

<style scoped>
</style>
