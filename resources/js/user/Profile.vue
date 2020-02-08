<template>
  <div>
    <div v-if="loading">Profile is loading ...</div>
    <div v-else>
      <v-card class="mx-auto" max-width="434" tile>
        <v-img height="100%" src="/images/bg-profile.png">
          <v-row align="end" class="fill-height pa-5 profile-row">
            <v-col class="d-flex justify-space-between" cols="12">
              <v-avatar class="profile" color="teal" size="84">
                <span class="white--text headline">{{ userData.username.charAt(0).toUpperCase() }}</span>
              </v-avatar>
              <div class="d-flex flex-column justify-space-around align-items-center pr-2">
                <div class="pb-5">
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-btn icon v-on="on">
                        <v-badge :content="userData.decks.length || '0'">
                          <v-icon large color="blue darken-2">mdi-image-album</v-icon>
                        </v-badge>
                      </v-btn>
                    </template>
                    <span>All decks</span>
                  </v-tooltip>
                </div>

                <div>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-btn icon v-on="on">
                        <v-badge :content="userData.cards_count || '0'">
                          <v-icon large color="blue darken-2">mdi-cards</v-icon>
                        </v-badge>
                      </v-btn>
                    </template>
                    <span>All cards</span>
                  </v-tooltip>
                </div>
              </div>
            </v-col>
            <v-col class="pl-0" cols="12">
              <v-list-item color="rgba(0, 0, 0)">
                <v-list-item-content>
                  <v-list-item-title class="title" v-text="userData.username"></v-list-item-title>
                  <v-list-item-subtitle v-text="userData.email"></v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col v-if="editable" class="d-flex flex-wrap justify-space-between" cols="12">
              <v-badge bordered color="success" icon="mdi-account-edit" overlap>
                <v-btn
                  class="white--text mb-2"
                  color="success"
                  depressed
                  @click="changeView('EditProfile')"
                >Edit</v-btn>
              </v-badge>
              <v-badge bordered color="error" icon="mdi-account-remove" overlap>
                <v-btn
                  class="white--text mb-2"
                  color="error"
                  depressed
                  @click="changeView('RemoveProfile')"
                >Remove</v-btn>
              </v-badge>
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
      </keep-alive>

      <decks
        v-if="userData.decks.length > 0"
        v-bind:decks="userData.decks"
        v-bind:editable="editable"
      ></decks>
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
        return () => import("./" + name);
      }
    }
  },
  created() {
    this.loading = true;
    this.loadProfile();
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
