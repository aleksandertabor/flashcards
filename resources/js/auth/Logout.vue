<template></template>

<script>
export default {
  created() {
    this.$store.dispatch("logout").then(response => {
      this.$router.push({ name: "home" });
    });
  }
};
</script>

<style>
</style>
