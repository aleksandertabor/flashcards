<template>
  <div class="d-flex align-items-stretch">
    <nav id="sidebar" class="navbar bg-info border-right navbar-light d-flex align-content-start">
      <router-link class="navbar-brand mr-auto" :to="{ name: 'home'}">
        <i class="fas fa-home"></i> Fiszkomat
      </router-link>
      <router-link
        v-if="isAuthenticated"
        class="btn nav-button"
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
      <router-link v-if="isAuthenticated" class="btn nav-button btn-danger" :to="{ name: 'logout'}">
        <i class="fas fa-sign-out-alt"></i> Logout
      </router-link>
      <router-link class="btn nav-button" :to="{ name: 'deck-editor'}">
        <i class="fas fa-border-style"></i> Deck Editor
      </router-link>
    </nav>

    <main id="content" class="w-100 pl-4 pr-4 pt-3">
      <div class="d-flex align-items-stretch justify-content-between border-bottom">
        <div>
          <div class="back fa-2x" @click="$router.back()">
            <i class="fas fa-arrow-circle-left"></i>
          </div>
        </div>
        <p>Flashcards - Decks</p>
        <form class="form-inline">
          <input
            class="form-control mr-sm-2"
            type="search"
            placeholder="Search"
            aria-label="Search"
          />
          <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Search</button>
        </form>
      </div>
      <div class="mt-4 mb-4">
        <router-view></router-view>
      </div>
    </main>
  </div>
</template>

<script>
export default {
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
  min-width: 250px;
  max-width: 250px;
}
.back {
  cursor: pointer;
}
</style>
