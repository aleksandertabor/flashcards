<template>
  <div>
    <v-form ref="form" lazy-validation>
      <v-btn color="purple darken-3" outlined tile @click="isEditing = !isEditing">
        <v-icon left v-if="isEditing">mdi-close</v-icon>
        <v-icon left v-else>mdi-pencil</v-icon>Edit
      </v-btn>
      <v-alert type="success" v-if="success" dismissible>Your account has saved.</v-alert>
      <v-alert type="error" v-if="error" dismissible>Fill your data correctly.</v-alert>

      <v-snackbar v-model="success" :timeout="2000" bottom left>Your profile has been updated.</v-snackbar>

      <v-snackbar v-model="error" :timeout="2000" bottom left>Fill your data correctly.</v-snackbar>

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
        :disabled="loading || !isEditing"
        @input="$emit('input', userData)"
      ></v-file-input>
      <div class="crop">
        <video ref="video" id="video" autoplay></video>
      </div>
      <div>
        <v-btn v-if="hasCamera" @click="capture()">Snap Photo</v-btn>
      </div>
      <canvas ref="canvas" id="canvas"></canvas>

      <v-text-field
        v-model="userData.username"
        prepend-icon="mdi-account"
        label="Username"
        :rules="[rules.required]"
        :error-messages="errorFor('username')"
        @input="$emit('input', userData)"
        @keyup.enter="save"
        :loading="loading"
        :disabled="loading || !isEditing"
      ></v-text-field>

      <v-text-field
        v-model="userData.email"
        prepend-icon="mdi-at"
        label="Email"
        :rules="[rules.required]"
        :error-messages="errorFor('email')"
        @input="$emit('input', userData)"
        @keyup.enter="save"
        :loading="loading"
        :disabled="loading || !isEditing"
      ></v-text-field>

      <v-text-field
        v-model="userData.password"
        prepend-icon="mdi-lock"
        :rules="[rules.min]"
        :error-messages="errorFor('password')"
        :type="show1 ? 'text' : 'password'"
        name="input-10-1"
        label="New Password"
        counter
        :loading="loading"
        :disabled="loading || !isEditing"
        @keyup.enter="save"
      ></v-text-field>

      <v-text-field
        v-model="userData.password_confirmation"
        prepend-icon="mdi-lock"
        :rules="[rules.min]"
        :error-messages="errorFor('password_confirmation')"
        name="input-10-1"
        :type="show1 ? 'text' : 'password'"
        label="New Password confirmation"
        counter
        :loading="loading"
        :disabled="loading || !isEditing"
        @keyup.enter="save"
      ></v-text-field>

      <v-btn
        @click="show1 = !show1"
        :disabled="loading || !isEditing"
        class="ma-2"
        color="indigo"
        dark
      >
        <v-icon>{{ show1 ? 'mdi-eye' : 'mdi-eye-off' }}</v-icon>
      </v-btn>

      <v-btn
        :disabled="loading || !isEditing"
        color="success"
        class="mr-4"
        @click="save"
      >Save profile</v-btn>
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
    }
  },
  mounted() {
    this.video = this.$refs.video;
    this.canvas = this.$refs.canvas;
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
          if (this.hasCamera) {
            this.canvas.style.display = "block";
            navigator.mediaDevices
              .getUserMedia({ video: true })
              .then(stream => {
                this.video.srcObject = stream;
                this.video.play();
              });
            setInterval(() => {
              const width = video.videoWidth;
              const height = video.videoHeight;

              this.canvas.width = width;
              this.canvas.height = height;

              let context = this.canvas
                .getContext("2d")
                .drawImage(this.video, 0, 0, width, height);
            }, 16);
          }
        });
    }
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
