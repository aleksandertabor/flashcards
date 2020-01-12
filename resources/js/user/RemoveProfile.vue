<template>
  <div>
    <v-dialog v-model="dialog" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">Do you really want remove your account?</v-card-title>
        <v-card-text>Your account's data, decks and cards will be deleted.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="dialog = false">Disagree</v-btn>
          <v-btn color="red darken-1" text @click="remove">Agree</v-btn>
        </v-card-actions>
      </v-card>
      <template v-slot:activator="{ on }">
        <v-btn color="red" dark v-on="on">Remove account</v-btn>
      </template>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    userData: Object
  },
  data() {
    return {
      loading: false,
      status: null,
      dialog: false
    };
  },
  methods: {
    remove() {
      this.$store
        .dispatch("removeProfile", this.userData)
        .then(response => {
          this.$router.push({ name: "home" });
        })
        .catch(error => {})
        .then(() => (this.loading = false));
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
