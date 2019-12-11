<template>
  <div class="card w-100 bg-success border-dark">
    <div class="card-header">
      <i class="fas fa-sync-alt"></i>
      <small class="text-muted">Last updated 3 mins ago</small>
    </div>
    <img src="https://source.unsplash.com/random/200x200" class="card-img-top" alt />
    <div class="card-body">
      <h5 class="card-title">{{ question }}</h5>
      <p class="card-text">{{ answer }}</p>
    </div>
    <div class="card-body">
      <h5 class="card-title">{{ question | reverse }}</h5>
      <p class="card-text">{{ answer | reverse }}</p>
    </div>
    <div class="card-footer">
      <i class="fas fa-sync-alt"></i>
      <small class="text-muted">Last updated 3 mins ago</small>
    </div>
  </div>
</template>

<script>
export default {
  props: { question: String, answer: String, id: Number },
  mounted() {
    console.log("Card List Item");
  },
  filters: {
    reverse: value => {
      return [...value].reverse().join("");
    }
  }
};
</script>
