<template>
  <loading></loading>
</template>

<script>
export default {
  created() {
    this.$store
      .dispatch("logout")
      .then(response => {
        this.$router.push({
          name: "login"
        });
      })
      .catch(error => {
        this.$store.dispatch("forceLogout");
        this.$router.push({
          name: "login"
        });
      });
  }
};
</script>

<style>
</style>
