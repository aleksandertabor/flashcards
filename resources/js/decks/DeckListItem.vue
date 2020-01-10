<template>
  <div class="card w-100">
    <div class="card-body">
      <router-link :to="{name: 'deck', params: {slug}}">
        <h5 class="card-title">{{ title }}</h5>
      </router-link>
      <img :data-src="image" class="card-img-top lazy" alt />
      <!-- <img data-src="/img/placeholder.png" class="card-img-top lazy" alt /> -->
      <p class="card-text">{{ description }}</p>
      <p class="card-text">Cards: ({{ cards_count }})</p>
      <p class="card-text">Created at: ({{ created_at }})</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    description: String,
    image: String,
    cards_count: Number,
    id: Number,
    lang_source: Object,
    lang_target: Object,
    slug: String,
    created_at: String
  }
};
</script>
