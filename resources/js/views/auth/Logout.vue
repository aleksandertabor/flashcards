<template>
  <loading></loading>
</template>

<script>
export default {
  async created() {
    try {
      await this.$store.dispatch("auth/logout");
    } catch (errors) {}

    this.$router.push({ name: "login" });
  }
};
</script>

<style>
</style>
