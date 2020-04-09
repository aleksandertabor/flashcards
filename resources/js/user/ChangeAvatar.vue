<template>
  <div>
    <v-form ref="form" lazy-validation>
      <v-btn color="purple darken-3" outlined tile @click="isEditing = !isEditing;">
        <v-icon left v-if="isEditing">mdi-close</v-icon>
        <v-icon left v-else>mdi-pencil</v-icon>Edit
      </v-btn>

      <v-btn
        v-if="isEditing && userData.image"
        :disabled="isDisable"
        color="error"
        class="mr-4"
        @click="remove"
      >Remove</v-btn>

      <v-btn v-if="isEditing" :disabled="isDisable" color="success" class="mr-4" @click="save">Save</v-btn>

      <v-alert type="success" v-if="success" dismissible>Your avatar has saved.</v-alert>
      <v-alert type="error" v-if="error" dismissible>Fill your data correctly.</v-alert>

      <v-snackbar v-model="success" :timeout="2000" bottom left>Your avatar has been updated.</v-snackbar>

      <v-snackbar v-model="error" :timeout="2000" bottom left>Fill your data correctly.</v-snackbar>

      <v-btn
        fab
        color="cyan accent-2"
        bottom
        left
        absolute
        @click="dialog = !dialog; enableCamera();"
      >
        <v-icon>mdi-camera</v-icon>
      </v-btn>

      <v-dialog v-model="dialog" max-width="500px">
        <v-card>
          <v-card-text>
            <div class="crop">
              <video ref="video" id="video" autoplay></video>
            </div>
            <div>
              <v-btn v-if="hasCamera" @click="capture()">Snap Photo</v-btn>
            </div>
            <canvas ref="canvas" id="canvas"></canvas>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn text color="primary" @click="dialog = false">Submit</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-file-input
        v-model="userData.image_file"
        :rules="[rules.size]"
        accept="image/png, image/jpeg, image/webp"
        placeholder="Pick an avatar"
        label="Avatar"
        @change="previewImage"
        @click:clear="forceImageRerender()"
        show-size
        :loading="loading"
        :disabled="isDisable"
        @input="$emit('input', userData)"
      ></v-file-input>
    </v-form>
  </div>
</template>

<script>
export default {
  props: {
    userData: Object
  },
  data() {
    return {
      video: {},
      canvas: {},
      captures: [],
      hasCamera: false,
      imageRenderKey: 0,
      loading: false,
      status: null,
      errors: null,
      error: false,
      show1: false,
      success: false,
      isEditing: null,
      dialog: false,
      rules: {
        size: value =>
          !value ||
          value.size < 2097152 ||
          "File size should be less than 2 MB!",
        required: v => !!v || "Required.",
        min: v => (v && v.length) >= 6 || "Min 6 characters"
      }
    };
  },
  methods: {
    enableCamera() {
      if ("mediaDevices" in navigator) {
        if (this.isEditing) {
          if (this.hasCamera) {
            this.video = this.$refs.video;
            this.canvas = this.$refs.canvas;
            this.canvas.style.display = "block";
            navigator.mediaDevices
              .getUserMedia({ video: true })
              .then(stream => {
                this.video.srcObject = stream;
                this.video.play();
              });
            setInterval(() => {
              const width = this.video.videoWidth;
              const height = this.video.videoHeight;

              this.canvas.width = width;
              this.canvas.height = height;

              let context = this.canvas
                .getContext("2d")
                .drawImage(this.video, 0, 0, width, height);
            }, 16);
          }
        } else {
          this.canvas.style.display = "none";
          this.video.srcObject.getVideoTracks().forEach(track => track.stop());
        }
      }
    },
    remove() {
      this.userData.image = "";
      this.userData.image_file = null;
    },
    save() {
      this.loading = true;
      this.errors = null;
      this.error = false;
      this.success = false;

      this.$store
        .dispatch("editProfile", this.userData)
        .then(response => {
          const username = response.username;
          this.isEditing = !this.isEditing;
          this.success = true;
          if (username !== this.$route.params.username) {
            this.$router.push({ name: "profile", params: { username } });
          }
        })
        .catch(error => {
          if (error.graphQLErrors.validationErrors !== undefined) {
            this.errors = error.graphQLErrors.validationErrors;
            this.error = true;
          }
        })
        .then(() => (this.loading = false));
    },
    capture() {
      this.userData.image = this.canvas.toDataURL("image/png");
    },
    forceImageRerender() {
      this.imageRenderKey += 1;
    },
    previewImage(file) {
      if (file) {
        let filename = file.name;
        if (filename.lastIndexOf(".") <= 0) {
          return;
        }
        const fileReader = new FileReader();
        fileReader.readAsDataURL(file);
        fileReader.addEventListener("load", () => {
          this.userData.image = fileReader.result;
        });
        this.userData.image_file = file;
      } else {
        this.userData.image_file = null;
        this.userData.image = "";
      }
    },
    errorFor(field) {
      return this.hasErrors && this.errors[field] ? this.errors[field] : null;
    }
  },
  computed: {
    hasErrors() {
      // return 401 === this.status && this.errors !== null;
      return this.errors !== null;
    },
    isDisable() {
      return this.loading || !this.isEditing;
    }
  },
  mounted() {
    let hasCameraDevice = false;
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices
        .enumerateDevices()
        .then(function(devices) {
          devices.forEach(function(device) {
            if (device.kind === "videoinput") {
              hasCameraDevice = true;
            }
          });
        })
        .then(() => {
          this.hasCamera = hasCameraDevice;
        });
    }
    console.log("hasCamera: ", this.hasCamera);
  }
};
</script>

<style scoped>
#video {
  display: none;
  background-color: #000000;
  width: 200px;
  height: 200px;
}

#canvas {
  display: none;
  width: 100%;
}
</style>
