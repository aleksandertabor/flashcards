<template>
  <v-app v-cloak>
    <v-app-bar app color="blue darken-3" dark clipped-left>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

      <router-link class="homepage" :to="{name: 'home'}">
        <v-toolbar-title>Flashcards</v-toolbar-title>
      </router-link>

      <v-spacer></v-spacer>
      <v-btn rounded color="primary" dark @click="install">{{installBtnText}}</v-btn>
      <v-icon v-if="$NotificationsActive" color="green">mdi-bell</v-icon>
      <v-icon v-else color="red">mdi-bell-off</v-icon>
    </v-app-bar>

    <v-navigation-drawer
      v-model="drawer"
      :color="color"
      :expand-on-hover="expandOnHover"
      :mini-variant="miniVariant"
      :clipped="$vuetify.breakpoint.lgAndUp"
      fixed
      :hide-overlay="$vuetify.breakpoint.lgAndUp"
      disable-resize-watcher
      app
    >
      <v-list dense nav class="py-0">
        <v-list-item
          two-line
          v-if="isAuthenticated"
          :to="{ name: 'profile', params: { username: user.username } }"
          link
        >
          <v-list-item-avatar color="teal">
            <span class="white--text headline">{{ user.username.charAt(0).toUpperCase() }}</span>
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title>My profile</v-list-item-title>
            <v-list-item-subtitle>{{ user.username }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>

        <v-list-item
          v-for="link in links"
          :key="link.title"
          :to="link.to"
          :class="link.class"
          v-if="link.authenticated === isAuthenticated || link.isSearchPage"
          link
        >
          <v-list-item-icon>
            <v-icon :color="link.color">{{ link.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title v-text="link.title"></v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-content>
      <v-container fluid>
        <v-flex>
          <router-view></router-view>
        </v-flex>
      </v-container>

      <v-container :class="{'fab-options': $vuetify.breakpoint.smAndDown}">
        <v-fab-transition>
          <v-btn
            v-if="isAuthenticated && !showToTop"
            fixed
            dark
            small
            fab
            bottom
            right
            color="pink"
            :to="{ name: 'deck-editor' }"
          >
            <v-icon>mdi-image-edit</v-icon>
          </v-btn>
        </v-fab-transition>
        <v-fab-transition>
          <v-btn
            v-scroll="onScroll"
            v-show="showToTop"
            fixed
            small
            dark
            fab
            bottom
            right
            color="orange"
            @click="toTop"
          >
            <v-icon>mdi-chevron-up</v-icon>
          </v-btn>
        </v-fab-transition>
      </v-container>
    </v-content>

    <v-bottom-navigation shift fixed grow color="indigo" class="hidden-md-and-up">
      <v-btn :to="{name: 'home'}" exact>
        <span>Home</span>
        <v-icon>mdi-home</v-icon>
      </v-btn>

      <v-btn :to="{name: 'search'}">
        <span>Search</span>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>

      <v-btn :to="{name: 'deck-editor'}">
        <span>Editor</span>
        <v-icon>mdi-image-edit</v-icon>
      </v-btn>
    </v-bottom-navigation>

    <v-snackbar
      v-model="$ServiceWorkerUpdated"
      top="top"
      color="error"
    >Our app was updated! It needs reload.</v-snackbar>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      drawer: false,
      links: [
        {
          title: "Decks",
          icon: "mdi-magnify",
          to: { name: "search" },
          isSearchPage: true
        },
        {
          title: "Login",
          icon: "mdi-account-arrow-left",
          to: { name: "login" },
          authenticated: false
        },
        {
          title: "Register",
          icon: "mdi-account-plus",
          to: { name: "register" },
          authenticated: false
        },
        {
          title: "Editor",
          icon: "mdi-image-edit",
          to: { name: "deck-editor" },
          authenticated: true
        },
        {
          title: "Logout",
          icon: "mdi-logout",
          to: { name: "logout" },
          class: "logout",
          color: "red",
          authenticated: true
        }
      ],
      color: "primary",
      colors: ["primary", "blue", "success", "red", "teal"],
      miniVariant: true,
      expandOnHover: true,
      background: false,
      loading: true,
      showToTop: false,
      installBtnText: "Install"
    };
  },
  methods: {
    onScroll(e) {
      if (window.pageYOffset >= 200) {
        this.showToTop = true;
      } else {
        this.showToTop = false;
      }
    },
    toTop() {
      this.$vuetify.goTo(0);
    },
    install() {
      if (installPromptGlobal !== null) {
        installPromptGlobal.prompt();
        installPromptGlobal.userChoice.then(choiceResult => {
          if (choiceResult.outcome === "accepted") {
            console.log("User accepted the A2HS prompt");
          } else {
            console.log("User dismissed the A2HS prompt");
          }
          installPromptGlobal = null;
        });
      }
    }
  },
  mounted() {
    if (this.$vuetify.breakpoint.lgAndUp) {
      this.drawer = true;
    }
  },
  created() {
    window.addEventListener("appinstalled", evt => {
      this.installBtnText = "Installed";
      console.log("App installed.");
    });
  }
};
</script>

<style>
.v-content {
  padding-bottom: 96px !important;
}

a.logout {
  border: thin solid #f44336;
}

a.logout .v-list-item__title {
  color: #f44336 !important;
}

a.homepage {
  color: #fff !important;
}

.fab-options .v-btn--fab {
  bottom: 70px !important;
}

.nick {
  text-overflow: ellipsis;
  white-space: nowrap;
  display: block;
  overflow: hidden;
  max-width: 220px;
}

.back {
  cursor: pointer;
}
</style>
