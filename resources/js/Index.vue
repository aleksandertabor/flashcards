<template>
  <v-app v-cloak>
    <v-app-bar app color="blue darken-3" dark clipped-left>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

      <router-link class="homepage" :to="{name: 'home'}">
        <v-toolbar-title>Flashcards</v-toolbar-title>
      </router-link>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>

      <v-btn icon>
        <v-icon>mdi-heart</v-icon>
      </v-btn>

      <v-btn icon>
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
    </v-app-bar>

    <v-navigation-drawer
      v-model="drawer"
      :color="color"
      :expand-on-hover="expandOnHover"
      :mini-variant="miniVariant"
      :src="bg"
      :clipped="$vuetify.breakpoint.lgAndUp"
      fixed
      hide-overlay
      app
    >
      <v-list dense nav class="py-0">
        <v-list-item
          two-line
          v-if="isAuthenticated"
          :to="{ name: 'profile', params: { username: user.username } }"
          link
        >
          <v-list-item-avatar>
            <img src="https://randomuser.me/api/portraits/men/81.jpg" />
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
          v-if="link.authenticated === isAuthenticated || link.isHomePage"
          link
          exact
        >
          <v-list-item-icon>
            <v-icon>{{ link.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ link.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-content>
      <v-container fill-height fluid>
        <v-layout justify-center align-center row wrap>
          <v-flex>
            <router-view></router-view>
          </v-flex>
        </v-layout>
      </v-container>
      <v-btn
        v-if="isAuthenticated"
        fixed
        dark
        fab
        bottom
        right
        color="pink"
        :to="{ name: 'deck-editor' }"
      >
        <v-icon>mdi-plus</v-icon>
      </v-btn>
    </v-content>

    <v-footer app>
      <!-- -->
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      drawer: true,
      links: [
        // {
        //   title: "Homepage",
        //   icon: "mdi-view-dashboard",
        //   to: { name: "home" },
        //   isHomePage: true
        // },
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
          authenticated: true
        }
      ],
      color: "primary",
      colors: ["primary", "blue", "success", "red", "teal"],
      miniVariant: true,
      expandOnHover: true,
      background: false,
      loading: true
    };
  },
  computed: {
    bg() {
      return this.background
        ? "https://cdn.vuetifyjs.com/images/backgrounds/bg-2.jpg"
        : undefined;
    }
  },
  created() {}
  //   computed: {
  //     isAuthenticated() {
  //       return this.$store.getters.isAuthenticated;
  //     },
  //     user() {
  //       return this.$store.getters.user;
  //     }
  //   }
};
</script>

<style>
a.homepage {
  color: #fff !important;
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
