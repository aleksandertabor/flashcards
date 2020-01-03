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
        <router-link v-if="isAuthenticated" class="btn nav-button" :to="{ name: 'deck-editor'}">
          <i class="fas fa-border-style"></i> Deck Editor
        </router-link>
        <!-- <ApolloQuery v-if="isAuthenticated" :query="require('./queries/me.gql')"> -->
        <!-- <template v-slot="{ result: { error, data }, isLoading }"> -->
        <!-- Loading -->
        <!-- <div v-if="isLoading" class="loading apollo">Loading...</div> -->

        <!-- Error -->
        <!-- <div v-else-if="error" class="error apollo">An error occurred</div> -->

        <!-- Result -->
        <!-- <div v-else-if="data" class="result apollo">{{ data.me.username }}</div> -->

        <!-- No result -->
        <!-- <div v-else class="no-result apollo">No result :(</div> -->
        <!-- </template> -->
        <!-- </ApolloQuery> -->
      </div>
    </nav>

    <main id="content" class="w-100 pl-4 pr-4 pt-3">
      <router-view></router-view>
    </main>
  </div>
</template>

<script>
export default {
  //   apollo: {
  //     user: gql`
  //       query {
  //         user(id: 1) {
  //           email
  //         }
  //       }
  //     `
  //   },
  data() {
    return {
      loading: true
    };
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
