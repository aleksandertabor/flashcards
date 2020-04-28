<template>
  <div>
    <v-alert prominent type="error" border="left">
      <v-row align="center">
        <v-col class="grow">No content here :(</v-col>
        <v-col class="shrink">
          <v-btn :to="{ name: 'search' }">Find decks</v-btn>
        </v-col>
      </v-row>
    </v-alert>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>
