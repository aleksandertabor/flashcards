<template>
  <div>
    <loading v-if="loading"></loading>
    <div v-else>
      <v-card class="mx-auto" max-width="484" tile>
        <v-img height="100%" src="/images/bg-profile.png">
          <v-row align="end" class="fill-height pa-5 profile-row">
            <v-col class="d-flex justify-space-between" cols="12">
              <v-avatar class="profile" color="teal" size="120">
                <span
                  v-if="!userData.image"
                  class="white--text display-1"
                >{{ userData.username.charAt(0).toUpperCase() }}</span>
                <v-img v-show="userData.image" :src="userData.image || ''"></v-img>
              </v-avatar>
              <div class="d-flex flex-column justify-space-around align-items-center pr-5">
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
            <v-col v-if="userData.editable" cols="12">
              <v-badge bordered color="warning" icon="mdi-camera" overlap>
                <v-btn
                  class="white--text mb-2"
                  color="warning"
                  depressed
                  @click="changeView('ChangeAvatar')"
                >Change Avatar</v-btn>
              </v-badge>
            </v-col>
            <v-col class="pl-0" cols="12">
              <v-list-item color="rgba(0, 0, 0)">
                <v-list-item-content>
                  <v-list-item-title class="title" v-text="userData.username"></v-list-item-title>
                  <v-list-item-subtitle v-text="userData.email"></v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col
              v-if="userData.editable"
              class="d-flex flex-wrap justify-space-between"
              cols="12"
            >
              <v-badge bordered color="success" icon="mdi-account-edit" overlap>
                <v-btn
                  class="white--text mb-2"
                  color="success"
                  depressed
                  @click="changeView('EditProfile')"
                >Edit account</v-btn>
              </v-badge>
              <v-badge bordered color="error" icon="mdi-account-remove" overlap>
                <v-btn
                  class="white--text mb-2"
                  color="error"
                  depressed
                  @click="changeView('RemoveProfile')"
                >Remove account</v-btn>
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

      <profile-decks
        v-if="userData.decks.length > 0"
        v-bind:decks="userData.decks"
        v-bind:editable="userData.editable"
      ></profile-decks>
    </div>
  </div>
</template>

<script>
import ProfileDecks from "./ProfileDecks";
export default {
  components: {
    ProfileDecks
  },
  data() {
    return {
      userData: {},
      loading: false,
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
        return () => import("./actions/" + name);
      }
    }
  },
  created() {
    this.loading = true;
    this.loadProfile();
  },
  methods: {
    async loadProfile() {
      try {
        this.userData = await this.$store.dispatch(
          "profile/profile",
          this.$route.params.username
        );
      } catch (errors) {
        this.$router.push({ name: "home" });
      }

      this.loading = false;
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
.v-badge__badge .v-icon {
  color: inherit !important;
  font-size: 12px !important;
  margin: 0 -2px;
}
.profile-row {
  background-color: rgba(0, 255, 102, 0.59);
}

@media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
  .v-slide-group__wrapper {
    width: 80vw;
  }
}
</style>
