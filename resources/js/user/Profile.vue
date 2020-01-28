<template>
  <div>
    <div v-if="loading">Profile is loading ...</div>
    <div v-else>
      <v-card class="mx-auto" max-width="434" tile>
        <v-img height="100%" src="/img/app/bg-profile.png">
          <v-row align="end" class="fill-height pl-5 profile-row">
            <v-col v-if="editable" class="pa-0 pt-5" cols="12">
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
            </v-col>
            <v-col align-self="start" class="pa-0" cols="12">
              <v-avatar class="profile" color="teal" size="84">
                <span class="white--text headline">{{ userData.username.charAt(0).toUpperCase() }}</span>
              </v-avatar>
            </v-col>
            <v-col class="py-0">
              <v-list-item color="rgba(0, 0, 0)">
                <v-list-item-content>
                  <v-list-item-title class="title" v-text="userData.username"></v-list-item-title>
                  <v-list-item-subtitle v-text="userData.email"></v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-col>
          </v-row>
        </v-img>
      </v-card>

      <keep-alive>
        <component
          class="col-md-6"
          v-bind:is="currentView"
          v-bind:userData="userData"
          v-model="userData"
        ></component>
      </keep-alive>Decks
      <v-avatar color="teal" size="48">
        <span class="white--text headline">{{ userData.decks.length }}</span>
      </v-avatar>Cards
      <v-avatar color="teal" size="48">
        <span class="white--text headline">{{ userData.cards_count }}</span>
      </v-avatar>
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
  beforeRouteUpdate(to, from, next) {
    next();
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
    this.loadProfile();
  },
  updated() {
    console.log("updated");
  },
  methods: {
    loadProfile() {
      this.$store
        .dispatch("profile", this.$route.params.username)
        .then(response => {
          this.userData = response.data.profile;
          if (this.userData.id > 0) {
            this.editable = true;
          }
        })
        .catch(error => {
          this.$router.push({ name: "home" });
        })
        .then(() => (this.loading = false));
    },
    changeView(view) {
      if (view === this.additionalView) {
        this.additionalView = "";
      } else {
        this.additionalView = view;
      }
    }
  },
  watch: {
    $route(to, from) {
      this.loadProfile();
    }
  }
};
</script>

<style>
.profile-row {
  background-color: rgba(0, 255, 102, 0.59);
}
</style>
