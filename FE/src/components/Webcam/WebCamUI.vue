<template>
  <div class="webcam-card">

    <!-- HEADER -->
    <div class="webcam-header">
      <span class="title">
        <i class="fa-solid fa-camera"></i> Ảnh khuôn mặt
      </span>

      <span class="start-date">
        <i class="fa-regular fa-calendar"></i>
        {{ startDate }}
      </span>
    </div>

    <!-- CAMERA / PREVIEW -->
    <div class="camera-view">

      <!-- CAMERA LIVE -->
      <Webcam
        v-if="!previewImage"
        ref="webcam"
        @init="webcamInit"
        @photoTaken="photoTakenEvent"
        classList="camera-video"
      />

      <!-- PREVIEW IMAGE -->
      <img
        v-else
        :src="previewImage"
        class="camera-video"
      />

    </div>

    <!-- ACTION -->
    <div class="camera-action">

      <!-- CHƯA CHỤP -->
      <button
        v-if="!previewImage"
        class="capture-btn"
        @click="takePhoto"
      >
        ●
      </button>

      <!-- ĐÃ CHỤP → CHỤP LẠI / XÁC NHẬN -->
      <div v-else class="confirm-group">
        <button class="btn-retake" @click="retakePhoto">
          <i class="fa-solid fa-rotate-left"></i> Chụp lại
        </button>

        <button class="btn-confirm" @click="confirmPhoto">
          <i class="fa-solid fa-check"></i> Xác nhận
        </button>
      </div>

    </div>

    <!-- STATUS -->
    <div class="camera-status">
      <span v-if="photoConfirmed" class="success">
        <i class="fa-solid fa-check-circle"></i> Đã chọn ảnh
      </span>

      <span v-else-if="previewImage" class="warning">
        <i class="fa-solid fa-circle-question"></i> Chưa xác nhận
      </span>

      <span v-else class="warning">
        <i class="fa-solid fa-circle-exclamation"></i> Chưa chụp ảnh
      </span>
    </div>

  </div>
</template>


<style scoped src="./output.css">
</style>

<style>
.fullscreen-ui {
    @apply fixed bottom-0 right-0 left-0 h-32
}
.fullscreen-overlay {
    @apply fixed top-0 bottom-0 left-0 right-0 z-50 w-screen h-screen bg-black
}
.camera {
    @apply text-white h-12 w-12 border-4 border-white rounded-full
}
.camera-success {
    @apply border-green-500
}
.camera-failed {
    @apply border-red-500
}
.button-control {
    @apply px-2 py-2 sm:py-0
}
.invisible {
    visibility: hidden;
}
.webcam-ui-buttons {
  @apply flex flex-col justify-center py-2 mx-auto text-center sm:flex-row items-center;
}
</style>

<script>
import Webcam from './WebCam.vue';
export default {
    components: { Webcam },
    props: {
        reloadCamerasButton: {
            type: Object,
            default: {
                display: false,
                text: 'Reload cameras',
                css: 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
            }
        },
        takePhotoButton: {
            type: Object,
            default: {
                display: true,
                text: 'Take a photo',
                css: 'inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
            }
        },
        fullscreenButton: {
            type: Object,
            default: {
                display: true,
                text: 'Fullscreen',
                css: 'inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
            }
        },
        selectCameraLabel: {
            type: String,
            default: 'Select camera...'
        },
        fullscreenState: {
            type: Boolean,
            default: false,
        },
        buttonsClass: {
            type: String,
            default: 'webcam-ui-buttons'
        }
    },
    data() {
        return {
            cameras: [],
            reloadCamInterval: null,
            startDate: new Date().toLocaleDateString('vi-VN'),

    previewImage: null, // base64
    previewBlob: null,  // blob
    photoConfirmed: false
        }
    },
    emits: ['clear','stop','start','pause','resume', 'error', 'unsupported', 'init', 'photoTaken', 'fullscreen'],
    beforeUnmount() {
        if (this.reloadCamInterval) {
            clearInterval(this.reloadCamInterval)
        }
        this.exit()
    },
    methods: {
        async takePhoto() {
            try {
                await this.$refs.webcam.takePhoto();
                this.photoTaken = true
                setTimeout(() => {
                    this.photoTaken = false
                }, 500);
            } catch (err) {
                this.photoFailed = true
                setTimeout(() => {
                    this.photoFailed = false
                }, 500);
            }
        },
        loadCameras() {
            this.$refs.webcam.loadCameras()
            this.cameras = this.$refs.webcam.cameras;
        },
        webcamInit(deviceId) {
            this.deviceId = deviceId
            this.$emit('init', this.deviceId)
        },
        setCamera() {
            this.$refs.webcam.changeCamera(this.deviceId === '' ? null : this.deviceId)
        },
        flipCamera() {
            this.loadCameras();
            // flipping camera will select the next one from the list, but on most device there will be only 2, if < 2 it will not be shown
            if (this.cameras.length > 1) {
                let currentIndex = this.cameras.findIndex(el => el.deviceId === this.deviceId)
                let newIndex = currentIndex + 1
                if (newIndex >= this.cameras.length) {
                    newIndex = 0;
                }

                this.deviceId = this.cameras[newIndex].deviceId;
                this.$refs.webcam.changeCamera(this.cameras[newIndex].deviceId)
            }
        },
        toggleFullscreen() {
            this.fullscreen = ! this.fullscreen
            this.$emit('fullscreen', this.fullscreen)
            if (this.fullscreen) {
                // try to fullscreen webcam ui element
                if (document.querySelector('#webcam-ui').requestFullscreen !== undefined) {
                    document.querySelector('#webcam-ui').requestFullscreen();
                } else {
                    document.fullscreenElement.requestFullscreen()
                }
            } else {
                document.exitFullscreen();
            }
        },
        exit() {
            this.$refs.webcam.stop()
        },


        // emits
        clear() {
            this.$emit('clear')
        },
        stop() {
            this.$emit('stop')
        },
        start() {
            this.$emit('start')
        },
        pause() {
            this.$emit('pause')
        },
        resume() {
            this.$emit('resume')
        },
        error(err) {
            this.$emit('error', err)
        },
        unsupported(err) {
            this.$emit('unsupported', err)
        },
        photoTakenEvent({ blob, image_data_url }) {
    // LƯU TẠM – CHƯA emit ra ngoài
    this.previewBlob = blob
    this.previewImage = image_data_url
    this.photoConfirmed = false
},
confirmPhoto() {
    if (!this.previewBlob) return

    this.photoConfirmed = true

    // CHỈ LÚC NÀY mới emit
    this.$emit('photoTaken', {
        blob: this.previewBlob,
        image_data_url: this.previewImage,
        start_date: this.startDate
    })
},

retakePhoto() {
    this.previewBlob = null
    this.previewImage = null
    this.photoConfirmed = false
    this.$emit('photoCleared')
},

    },
    mounted () {
        this.cameras = this.$refs.webcam.cameras;
        if (this.cameras.length === 0) {
            // if no camera found, we will try to refresh cameras list each second until there is some camera
            this.reloadCamInterval = setInterval(() => {
                this.loadCameras()
                if (this.cameras.length > 0) {
                    clearInterval(this.reloadCamInterval)
                    // most likely due to permission, so we init afterwards
                    this.$refs.webcam.init();
                }
            }, 1000);
        }
    },
    watch: {
        fullscreenState: {
            immediate: true,
            handler: function (newVal) {
                    this.fullscreen = newVal
            }
        }
    }
}
</script>