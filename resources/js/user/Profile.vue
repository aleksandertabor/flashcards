<template>
  <div>
    <div v-if="loading">Profile is loading ...</div>
    <div v-else>
      <div v-if="editable">
        <nav class="navbar navbar-light bg-light">
          <v-badge
        bordered
        color="success"
        icon="mdi-account-edit"
        overlap
      >
        <v-btn
          class="white--text"
          color="success"
          depressed
          @click="changeView('EditProfile')"
        >
          Edit
        </v-btn>
      </v-badge>
          <v-badge
        bordered
        color="error"
        icon="mdi-account-remove"
        overlap
      >
        <v-btn
          class="white--text"
          color="error"
          depressed
          @click="changeView('RemoveProfile')"
        >
          Remove
        </v-btn>
      </v-badge>
        </nav>
      </div>
      <div class="form-row">
        <div class="col-md-6">
          <h1>{{ userData.username }}</h1>
          <p>Email: {{ userData.email }}</p>
          <p>
            <i class="fas fa-box"></i>
            Decks: {{ userData.decks.length }}
          </p>
          <p>Cards: {{ userData.cards_count }}</p>
        </div>

        <keep-alive>
          <component
            class="col-md-6"
            v-bind:is="currentView"
            v-bind:userData="userData"
            v-model="userData"
          ></component>
        </keep-alive>
      </div>
      <decks v-if="userData.decks" v-bind:decks="userData.decks"></decks>
    </div>
  </div>
</template>

<script>
import Decks from "./Decks";
export default {
  components: {
    Decks
  },
  data() {
    return {
      userData: {},
      loading: false,
      editable: false,
      additionalView: ""
    };
  },
  computed: {
    currentView() {
      if (this.additionalView) {
        const name = this.additionalView;
        return () => import(`./${name}`);
      }
    }
  },
  created() {
    this.loading = true;

    const profile = this.$store
      .dispatch("profile", this.$route.params.username)
      .then(response => {
        this.userData = response.data.profile;
      })
      .catch(error => {
        this.$router.push({ name: "home" });
      })
      .then(() => (this.loading = false));

    profile.then(() => {
      if (this.$route.params.username === this.$store.getters.user.username) {
        this.$store
          .dispatch("me")
          .then(response => {
            this.editable = true;
          })
          .catch(error => {
            this.editable = false;
          })
          .then(() => (this.loading = false));
      } else {
        this.loading = false;
      }
    });
  },
  methods: {
    changeView(view) {
      if (view === this.additionalView) {
        this.additionalView = "";
      } else {
        this.additionalView = view;
      }
    }
  }
};
</script>

<style>
</style>
