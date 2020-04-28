<template>
  <div>
    <v-form ref="form" lazy-validation>
      <v-btn color="purple darken-3" outlined tile @click="isEditing = !isEditing;">
        <v-icon left v-if="isEditing">mdi-close</v-icon>
        <v-icon left v-else>mdi-pencil</v-icon>Edit
      </v-btn>

      <v-col class="d-flex flex-wrap justify-space-between py-5 px-0">
        <v-btn
          v-if="isEditing && userData.image"
          :disabled="isDisable"
          color="error"
          @click="remove"
        >Remove</v-btn>

        <v-btn v-if="isEditing" :disabled="isDisable" color="success" @click="save">Save</v-btn>

        <v-btn color="cyan accent-2" v-if="hasCamera && isEditing" @click="enableCamera();">
          <v-icon>mdi-camera</v-icon>
        </v-btn>
      </v-col>

      <v-alert type="success" v-if="success" dismissible>Your avatar has saved.</v-alert>
      <v-alert type="error" v-if="error" dismissible>Fill your data correctly.</v-alert>

      <v-snackbar v-model="success" :timeout="2000" bottom left>Your avatar has been updated.</v-snackbar>

      <v-snackbar v-model="error" :timeout="2000" bottom left>Fill your data correctly.</v-snackbar>

      <v-dialog v-model="cameraDialog" max-width="500px" eager fullscreen scrollable>
        <v-card class="camera-dialog">
          <v-card-text class="pa-0">
            <div class="crop">
              <video ref="video" autoplay></video>
            </div>
            <canvas ref="canvas"></canvas>
          </v-card-text>

          <v-card-actions class="d-flex flex-wrap justify-space-between">
            <v-btn color="error" @click="disableCamera()">Back</v-btn>

            <v-btn color="primary" @click="capture()">Take a photo</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-file-input
        v-model="userData.image_file"
        :rules="[rules.size]"
        accept="image/png, image/jpeg, image/webp"
        placeholder="Pick an avatar"
        label="Avatar"
        :error-messages="errorFor('image_file')"
        @change="previewImage($event, 'userData')"
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
import {
  validationErrors,
  errorMessage
} from "@/shared/utils/validationErrors";
import formsMixin from "@/shared/mixins/formsMixin";
import imageInputsMixin from "@/shared/mixins/imageInputsMixin";

export default {
  mixins: [formsMixin, imageInputsMixin],
  props: {
    userData: Object
  },
  data() {
    return {
      video: {},
      canvas: {},
      hasCamera: false,
      isEditing: null,
      cameraDialog: false
    };
  },
  methods: {
    enableCamera() {
      if ("mediaDevices" in navigator) {
        this.cameraDialog = !this.cameraDialog;
        this.video = this.$refs.video;
        this.canvas = this.$refs.canvas;
        this.canvas.style.display = "block";
        navigator.mediaDevices.getUserMedia({ video: true }).then(stream => {
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
    },
    disableCamera() {
      this.cameraDialog = false;
      this.canvas.style.display = "none";
      this.video.srcObject.getVideoTracks().forEach(track => track.stop());
    },
    remove() {
      this.userData.image = "";
      this.userData.image_file = null;
    },
    async save() {
      this.resetState();

      try {
        await this.$store.dispatch("profile/changeAvatar", {
          id: this.userData.id,
          image: this.userData.image,
          image_file: this.userData.image_file
        });

        this.isEditing = !this.isEditing;

        this.success = true;
      } catch (errors) {
        if (validationErrors(errors)) {
          this.errors = validationErrors(errors);
        }
        if (errorMessage(errors)) {
          this.error = errorMessage(errors);
        }
      }

      this.loading = false;
    },
    capture() {
      this.userData.image = "";
      this.userData.image_file = null;
      this.userData.image = this.canvas.toDataURL("image/png");
      this.disableCamera();
    }
  },
  computed: {
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
          console.log("hasCamera: ", this.hasCamera);
        });
    }
  }
};
</script>
