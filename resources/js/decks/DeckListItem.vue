<template>
  <div class="card w-100">
    <div class="card-body">
      <router-link :to="{name: 'deck', params: {slug}}">
        <h5 class="card-title">{{ title }}</h5>
      </router-link>
      <img src="https://source.unsplash.com/random/100x100" class="card-img-top" alt />
      <p class="card-text">{{ description }}</p>
      <p class="card-text">Cards: ({{ cards_count }})</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    description: String,
    cards_count: Number,
    id: Number,
    lang_source: String,
    lang_target: String,
    slug: String
  },
  mounted() {
    console.log("Deck List Item");
  }
};
</script>
