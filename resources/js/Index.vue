<template>
  <div class="d-flex align-items-stretch">
    <nav id="sidebar" class="navbar bg-info border-right navbar-light d-flex align-content-start">
      <router-link class="navbar-brand mr-auto" :to="{ name: 'home'}">Fiszkomat</router-link>
      <router-link class="btn nav-button" :to="{ name: 'login'}">Login</router-link>
      <router-link class="btn nav-button" :to="{ name: 'register'}">Register</router-link>
      <router-link class="btn nav-button" :to="{ name: 'deck-editor'}">Deck Editor</router-link>
    </nav>

    <main id="content" class="w-100 pl-4 pr-4 pt-3">
      <div class="d-flex align-items-stretch justify-content-between border-bottom">
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
export default {};
</script>

<style scoped>
#sidebar {
  min-width: 250px;
  max-width: 250px;
}
</style>
