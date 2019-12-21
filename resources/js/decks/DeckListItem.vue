<template>
  <div class="card w-100">
    <div class="card-body">
      <router-link :to="{name: 'deck', params: {slug}}">
        <h5 class="card-title">{{ title }}</h5>
      </router-link>
      <img data-src="https://source.unsplash.com/random/200x200" class="card-img-top lazy" alt />
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
    cards_count: Number,
    id: Number,
    lang_source: String,
    lang_target: String,
    slug: String,
    created_at: String
  }
};
</script>
