<template>
  <div class="d-flex align-items-stretch">
    <nav id="sidebar" class="navbar bg-info border-right navbar-light d-flex align-content-start">
      <div class="left-navigation d-flex flex-column align-items-baseline">
        <router-link class="navbar-brand mr-auto" :to="{ name: 'home'}">
          <i class="fas fa-home"></i> Fiszkomat
        </router-link>
        <router-link
          v-if="isAuthenticated"
          class="btn nav-button nick"
          :to="{ name: 'profile', params: {username: user.username}}"
        >
          <i class="fas fa-user-edit"></i>
          Profile ({{ user.username }})
        </router-link>
        <router-link v-if="!isAuthenticated" class="btn nav-button" :to="{ name: 'login'}">
          <i class="fas fa-sign-in-alt"></i> Login
        </router-link>
        <router-link v-if="!isAuthenticated" class="btn nav-button" :to="{ name: 'register'}">
          <i class="fas fa-user-plus"></i> Register
        </router-link>
        <router-link
          v-if="isAuthenticated"
          class="btn nav-button btn-danger"
          :to="{ name: 'logout'}"
        >
          <i class="fas fa-sign-out-alt"></i> Logout
        </router-link>
        <router-link class="btn nav-button" :to="{ name: 'deck-editor'}">
          <i class="fas fa-border-style"></i> Deck Editor
        </router-link>
      </div>
    </nav>

    <main id="content" class="w-100 pl-4 pr-4 pt-3">
      <nav class="navbar sticky-top navbar-light bg-light border-bottom">
        <div class="back fa-2x" @click="$router.back()">
          <i class="fas fa-arrow-circle-left"></i>
        </div>
        <p>Flashcards - Decks</p>
        <search-bar></search-bar>
      </nav>
      <div class="mt-4 mb-4">
        <router-view></router-view>
      </div>
    </main>
  </div>
</template>

<script>
import SearchBar from "./components/SearchBar";
export default {
  components: {
    SearchBar
  },
  computed: {},
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

<style scoped>
#sidebar {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  min-width: 250px;
  max-width: 250px;
}

.nick {
  text-overflow: ellipsis;
  white-space: nowrap;
  display: block;
  overflow: hidden;
  max-width: 220px;
}

main {
  margin-left: 250px;
}

.back {
  cursor: pointer;
}
</style>
