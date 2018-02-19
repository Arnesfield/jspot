<template>
<div>
  <v-list
    dense
    v-if="socials.length"
  >
    <v-list-tile
      :key="i"
      v-for="(link, i) in socials"
      class="pl-0"
    >
      <v-list-tile-action>
        <v-btn
          icon
          :disabled="disabled"
          @click="socials.splice(i, 1)"
        >
          <v-icon>close</v-icon>
        </v-btn>
      </v-list-tile-action>
      <v-list-tile-content>
        <a :href="wrapUrl(link)" target="1">{{ link }}</a>
      </v-list-tile-content>
    </v-list-tile>
  </v-list>

  <v-form ref="form" v-model="valid" @submit.prevent="">
    <v-text-field
      v-model="link"
      label="Add social link"
      :prepend-icon="link ? 'add' : 'link'"
      :prepend-icon-cb="link ? addLink : undefined"
      @keypress.enter="addLink"
      :disabled="disabled"
      type="url"
      clearable
      :rules="[$vfRule('url'), $vfRule('nonExisting', null, socials)]"
    />
  </v-form>
</div>
</template>

<script>
export default {
  name: 'social-links',
  props: {
    value: Array,
    disabled: Boolean
  },
  data: () => ({
    valid: false,
    link: null,
    socials: [],
    errors: []
  }),

  watch: {
    socials(e) {
      this.$emit('input', e)
    },
    value(e) {
      this.socials = e
    }
  },

  methods: {
    wrapUrl(url) {
      const pattern = /^https?:\/\/|^\/\//i
      return pattern.test(url) ? url : 'https://' + url
    },

    addLink() {
      if (!this.link) {
        return
      }

      // trim last if /
      if (this.link.charAt(this.link.length - 1) == '/') {
        this.link = this.link.substring(0, this.link.length-1)
      }

      if (!this.$refs.form.validate()) {
        return
      }

      // do not include if exists
      if (this.socials.indexOf(this.link) == -1) {
        this.socials.push(this.link)
        this.link = null
      }
    }
  }
}
</script>
