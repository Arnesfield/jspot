<template>
<v-avatar
  :size="size"
  :color="color"
>
  <template v-if="imgSrc">
    <img
      v-if="imgSrc.isImg"
      :src="imgSrc.text"
    >
    <span
      v-else
      :class="{ 'caption': caption, [textClass]: true }"
      :style="textStyle"
    >{{ imgSrc.text }}</span>
  </template>
  <template v-else>
    <span
      :class="{ 'caption': caption, [textClass]: true }"
      :style="textStyle"
    >?</span>
  </template>
</v-avatar>
</template>

<script>
export default {
  name: 'icon-img',
  props: {
    item: {
      type: Object,
      default: null
    },
    size: {
      type: [Number, String],
      default: undefined
    },
    color: {
      type: String,
      default: 'accent'
    },
    caption: {
      type: Boolean,
      default: false
    },
    fname: {
      type: String,
      default: 'fname'
    },
    lname: {
      type: String,
      default: 'lname'
    },
    img: {
      type: String,
      default: 'imgSrc'
    },
    textClass: {
      type: String,
      default: 'white--text'
    },
    textStyle: {
      type: Object,
      default: null
    },
  },
  computed: {
    imgSrc() {
      let user = this.item
      if (typeof user !== 'object' || user === null) {
        return null
      }
      if (typeof user[this.img] !== 'string' || !user[this.img].length) {
        return {
          isImg: false,
          text: user[this.fname].charAt(0).toUpperCase()
        }
      }
      return {
        isImg: true,
        text: this.$wrap.localImg(user[this.img])
      }
    }
  }
}
</script>
