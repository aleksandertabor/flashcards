<template>
  <div>
    <div v-if="loading">Profile is loading ...</div>
    <div v-else>
      <div v-if="editable">
        <v-badge bordered color="success" icon="mdi-account-edit" overlap>
          <v-btn
            class="white--text"
            color="success"
            depressed
            @click="changeView('EditProfile')"
          >Edit</v-btn>
        </v-badge>
        <v-badge bordered color="error" icon="mdi-account-remove" overlap>
          <v-btn
            class="white--text"
            color="error"
            depressed
            @click="changeView('RemoveProfile')"
          >Remove</v-btn>
        </v-badge>
      </div>
      <v-card class="mx-auto" max-width="434" tile>
        <v-img height="100%" src="https://cdn.vuetifyjs.com/images/cards/server-room.jpg">
          <v-row align="end" class="fill-height">
            <v-col align-self="start" class="pa-0" cols="12">
              <v-avatar class="profile" color="grey" size="164" tile>
                <v-img src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg"></v-img>
              </v-avatar>
            </v-col>
            <v-col class="py-0">
              <v-list-item color="rgba(0, 0, 0, .4)" dark>
                <v-list-item-content>
                  <v-list-item-title class="title">{{ userData.username }}</v-list-item-title>
                  <v-list-item-subtitle>{{ userData.email }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-col>
          </v-row>
        </v-img>
      </v-card>Decks
      <v-avatar color="teal" size="48">
        <span class="white--text headline">{{ userData.decks.length }}</span>
      </v-avatar>Cards
      <v-avatar color="teal" size="48">
        <span class="white--text headline">{{ userData.cards_count }}</span>
      </v-avatar>

      <keep-alive>
        <component
          class="col-md-6"
          v-bind:is="currentView"
          v-bind:userData="userData"
          v-model="userData"
        ></component>
      </keep-alive>
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
