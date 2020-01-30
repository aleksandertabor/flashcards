<template>
  <v-card class="mx-auto my-12" max-width="374">
    <v-img :src="image"></v-img>

    <v-badge :content="cards_count || '0'" :value="cards_count" color="brown" overlap bordered>
      <v-icon x-large>mdi-cards</v-icon>
    </v-badge>

    <v-card-title>{{title}}</v-card-title>

    <v-card-text>
      <div>{{description}}</div>
      <div class="my-4 subtitle-1 black--text">{{created_at}}</div>
    </v-card-text>
  </v-card>
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
    created_at: String,
    visibility: String
  }
};
</script>
