<!--
  - Copyright (c) 2021 Azeyn
  -
  - The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
  -->

<template>
  <div class="popup">
    <div class="toolbar" ref="toolbar">
      <div>
      </div>
      <div>
        <button class="toolbar-button" :disabled="zoomInDisabled" @click.prevent="zoomIn">
          <ZoomIn />
        </button>
        <button class="toolbar-button" :disabled="zoomOutDisabled" @click.prevent="zoomOut">
          <ZoomOut />
        </button>
        <button class="toolbar-button" @click.prevent="dismiss">
          <Times />
        </button>
      </div>
    </div>
    <div class="back-button">
      <button class="toolbar-button" :disabled="backDisabled" @click.prevent="back">
        <Back />
      </button>
    </div>
    <div class="next-button">
      <button class="toolbar-button" :disabled="nextDisabled" @click.prevent="next">
        <Next />
      </button>
    </div>
    <div class="image-container" ref="imageContainer">
      <picture class="image" ref="image">
        <source :srcset="urls.lg" media="(min-width: 992px)" />
        <source :srcset="urls.md" media="(min-width: 768px)" />
        <source :srcset="urls.sm" media="(min-width: 576px)" />
        <img :src="urls.xs" />
      </picture>
    </div>
  </div>
</template>

<script>
import { debounce, throttle } from 'lodash'
import Back from '../assets/back.svg'
import Next from '../assets/next.svg'
import Times from '../assets/times.svg'
import ZoomIn from '../assets/zoom-in.svg'
import ZoomOut from '../assets/zoom-out.svg'

export default {
  name: 'Popup',
  props: {
    imageUrl: String,
    backDisabled: Boolean,
    nextDisabled: Boolean
  },
  components: {
    Back,
    Next,
    Times,
    ZoomIn,
    ZoomOut
  },
  data() {
    return {
      urls: {
        xs: '',
        sm: '',
        md: '',
        lg: ''
      },
      image: [0, 0],
      viewport: [0, 0],
      offset: [0, 0],
      zoomLevel: 1/1.25,
      mouseDown: false,
      toolbarHeight: 0
    }
  },
  emits: ['back', 'next', 'dismiss'],
  watch: {
    imageUrl() {
      this.zoomLevel = 1/1.25

      $.get(this.imageUrl, {
        dataType: 'json'
      }).done(imageObj => {
        let self = this
        $('img', this.$refs.image)
          .css('height', $(this.$refs.imageContainer).height() * this.zoomLevel)
          .on('load', () => {
            self.image = [
              $('img', self.$refs.image).width(),
              $('img', self.$refs.image).height()
            ];
            $(self.$refs.image)
              .css('left', (this.viewport[0]/2 - self.image[0]/2) + 'px')
              .css('top', (this.toolbarHeight + (this.viewport[1] - this.toolbarHeight)/2 - self.image[1]/2) + 'px')
          })
        this.urls = imageObj.urls
      })
    }
  },
  computed: {
    zoomInDisabled() {
      return this.zoomLevel >= Math.pow(1.25, 5)
    },
    zoomOutDisabled() {
      return this.zoomLevel <= 1/Math.pow(1.25, 2)
    }
  },
  methods: {
    zoomIn() {
      if (this.zoomInDisabled) {
        return
      }

      this.zoomLevel *= 1.25
      this.resizeImage(this)
    },
    zoomOut() {
      if (this.zoomOutDisabled) {
        return
      }

      this.zoomLevel /= 1.25
      this.resizeImage(this)
    },
    getDimensions: debounce((self) => {
      self.toolbarHeight = $(self.$refs.toolbar).height()
      self.viewport = [
        $(window).width(),
        $(window).height()
      ]
    }, 500, { leading: true }),
    resizeImage: throttle((self) => {
      let position = $(self.$refs.image).position()
      const center = [
        position.left + $('img', self.$refs.image).width()/2,
        position.top + $('img', self.$refs.image).height()/2
      ]

      $('img', self.$refs.image).css('height', (self.viewport[1] - self.toolbarHeight) * self.zoomLevel)
      self.image = [
        $('img', self.$refs.image).width(),
        $('img', self.$refs.image).height()
      ];

      $(self.$refs.image)
        .css('left', Math.min(center[0] - self.image[0]/2, self.viewport[0] - 15) + 'px')
        .css('top', Math.min(center[1] - self.image[1]/2, self.viewport[1] - 15) + 'px')
    }, 50, { trailing: true }),
    back() {
      this.$emit('back')
    },
    next() {
      this.$emit('next')
    },
    dismiss() {
      this.$emit('dismiss')
    }
  },
  mounted() {
    this.getDimensions(this)
    $(window).on('resize', () => this.getDimensions(this))

    $.get(this.imageUrl, {
      dataType: 'json'
    }).done(imageObj => {
      let self = this
      $('img', this.$refs.image)
        .css('height', $(this.$refs.imageContainer).height() * this.zoomLevel)
        .on('load', () => {
          self.image = [
            $('img', self.$refs.image).width(),
            $('img', self.$refs.image).height()
          ];
          $(self.$refs.image)
            .css('left', (this.viewport[0]/2 - self.image[0]/2) + 'px')
            .css('top', (this.toolbarHeight + (this.viewport[1] - this.toolbarHeight)/2 - self.image[1]/2) + 'px')
        })
      this.urls = imageObj.urls
    })

    $(this.$refs.image)
      .mousedown(event => {
        event.preventDefault()
        let offset = $(this.$refs.image).offset()

        this.mouseDown = true
        this.offset = [
          offset.left - event.clientX,
          offset.top - event.clientY
        ]
      }).mouseup(() => {
        this.mouseDown = false
      }).mousemove(event => {
        event.preventDefault()

        if (this.mouseDown) {
          $(this.$refs.image).css('left', Math.min(event.clientX + this.offset[0], this.viewport[0] - 15) + 'px')
          $(this.$refs.image).css('top', Math.min(event.clientY + this.offset[1], this.viewport[1] - 15) + 'px')
        }
      }).dblclick(() => {
        this.zoomIn()
      })
    $(this.$refs.imageContainer).mousewheel(event => {
      event.preventDefault()

      let newZoom = this.zoomLevel * Math.pow(1.1, event.deltaY / -100)
      if (newZoom > Math.pow(1.25, 5) || newZoom < 1/Math.pow(1.25, 2)) {
        return
      }
      this.zoomLevel = newZoom
      this.resizeImage(this)
    })

    $(document).on('keydown', event => {
      if (event.key === 'Escape') {
        this.$emit('dismiss')
      }
    })
  },
  beforeUnmount() {
    $(window).off('resize', this.getDimensions(this))
    $(document).off('keydown', event => {
      if (event.key === 'Escape') {
        this.$emit('dismiss')
      }
    })
  }
}
</script>

<style scoped>
.popup {
  position: fixed;
  left: 0;
  top: 0;
  width: 100vw;
  height: 100vh;
  flex-direction: column;
  z-index: 10;
}

.toolbar {
  position: absolute;
  left: 0;
  top: 0;
  display: flex;
  background: rgba(0, 0, 0, 0.3);
  width: 100%;
  flex-direction: row;
  justify-content: space-between;
  flex-grow: 0;
  padding: 5px;
  z-index: 20;
}

.toolbar-button {
  background: transparent;
  border: none;
  border-radius: 30px;
  outline: none;
  fill: white;
  padding: 7px;
  margin: 5px;
  pointer-events: auto;
}

.toolbar-button:hover {
  background: rgba(216, 216, 216, 0.5);
}

.toolbar-button:disabled {
  fill: #949494;
}

.toolbar-button:disabled:hover {
  background: rgba(100, 100, 100, 0.5);
}

.back-button {
  display: flex;
  position: absolute;
  left: 0;
  top: 0;
  height: 100vh;
  align-items: center;
  pointer-events: none;
  z-index: 20;
}

.next-button {
  display: flex;
  position: absolute;
  right: 0;
  top: 0;
  height: 100vh;
  align-items: center;
  pointer-events: none;
  z-index: 20;
}

.next-button > .toolbar-button {
  margin: auto 5px;
}

.image-container {
  position: absolute;
  left: 0;
  top: 0;
  background: rgba(0, 0, 0, 0.95);
  width: 100vw;
  height: 100vh;
  overflow: hidden;
}

.image {
  position: absolute;
  left: 0;
  top: 0;
  cursor: move;
  overflow: hidden;
}
</style>
