<template>
  <div>
    <v-btn x-large block v-if="canShare()" color="yellow" app @click="share">
      <v-icon>mdi-share</v-icon>Share deck
    </v-btn>
  </div>
</template>

<script>
export default {
  props: {
    deck: Object
  },
  methods: {
    canShare() {
      if (navigator.share) {
        return true;
      }
      return false;
    },
    share() {
      if (navigator.share) {
        const title = this.deck.title;
        const url = document.location.href;
        const text = this.deck.description;
        navigator
          .share({
            title,
            url: window.location.href,
            text
          })
          .then(() => {
            alert("Thanks for sharing!");
          })
          .catch(err => {
            alert(`Couldn't share because of`, err.message);
          });
      } else {
        alert("Web Share API not supported.");
      }
    }
  }
};
</script>

<style scoped>
.custom-loader {
  animation: loader 1s infinite;
  display: flex;
}
@-moz-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@-webkit-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@-o-keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@keyframes loader {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
