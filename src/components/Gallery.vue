<!--
  - Copyright (c) 2021 Azeyn
  -
  - The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
  -->

<template>
  <div class="grid" @scroll.passive="scroll" ref="gridRef">
    <div class="item" v-for="(image, index) in images" :key="image.id" :data-id="index">
      <div class="item-content">
        <a @click.prevent="toggle(index, image.url)">
          <picture class="thumbnail">
            <source :srcset="image.thumbnail_urls.lg" media="(min-width: 992px)" />
            <source :srcset="image.thumbnail_urls.md" media="(min-width: 768px)" />
            <source :srcset="image.thumbnail_urls.sm" media="(min-width: 576px)" />
            <img :src="image.thumbnail_urls.xs" @load="layout" />
          </picture>
        </a>
      </div>
    </div>
  </div>
  <Popup
    v-if="selected" :image-url="selectedImage" :back-disabled="backDisabled"
    :next-disabled="nextDisabled" @back="back" @next="next" @dismiss="dismiss"
  />
</template>

<script>
import { toRefs, ref, watchEffect } from 'vue'
import Muuri from 'muuri'
import Popup from './Popup'

export default {
  name: 'Gallery',
  props: {
    loadedImages: Array
  },
  components: {
    Popup
  },
  data() {
    return {
      selected: false,
      selectedId: null,
      selectedImage: null,
      backDisabled: false,
      nextDisabled: false
    }
  },
  setup(props) {
    const grid = ref(null)
    const { loadedImages } = toRefs(props)

    const gridRef = ref(null)
    watchEffect(() => {
      grid.value = new Muuri(gridRef.value, {
        items: '.item',
        layout: {
          fillGaps: true,
        }
      })
    }, { flush: 'post' })

    const layout = () => {
      if (grid.value !== null) {
        let muuriGrid = grid.value
        muuriGrid.refreshItems()
        muuriGrid.layout()
      }
    }

    return {
      images: loadedImages,
      grid,
      gridRef,
      layout
    }
  },
  methods: {
    scroll(event) {

    },
    toggle(id, url) {
      this.selectedId = id
      this.selectedImage = url

      const elem = $('.item[data-id="' + this.selectedId + '"]')
      this.backDisabled = elem.prev().length === 0
      this.nextDisabled = elem.next().length === 0

      this.selected = true
    },
    toggleElem(elem) {
      if (elem.length === 0) {
        console.log('No element found')
        return
      }

      this.backDisabled = elem.prev().length === 0
      this.nextDisabled = elem.next().length === 0

      const link = $('a', elem).get(0)
      link.click()
    },
    back() {
      this.toggleElem($('.item[data-id="' + this.selectedId + '"]').prev())
    },
    next() {
      this.toggleElem($('.item[data-id="' + this.selectedId + '"]').next())
    },
    dismiss() {
      this.selected = false
      this.selectedId = null
      this.selectedImage = null
    }
  }
}
</script>

<style scoped>
.grid {
  position: relative;
}

.item {
  display: block;
  position: absolute;
  margin-right: 5px;
  z-index: 1;
}

.item.muuri-item-dragging {
  z-index: 3;
}

.item.muuri-item-releasing {
  z-index: 2;
}

.item.muuri-item-hidden {
  z-index: 0;
}

.item-content {
  position: relative;
  width: 100%;
  height: 100%;
}

.thumbnail {
  max-width: 100%;
  z-index: 5;
}
</style>
