<template>
<div>
  <v-list
    dense
    v-if="social.length"
  >
    <v-list-tile
      :key="i"
      v-for="(link, i) in social"
      class="pl-0"
    >
      <v-list-tile-action>
        <v-btn icon @click="social.splice(i, 1)">
          <v-icon>close</v-icon>
        </v-btn>
      </v-list-tile-action>
      <v-list-tile-content>{{ link }}</v-list-tile-content>
    </v-list-tile>
  </v-list>

  <v-form ref="form" v-model="valid">
    <v-text-field
      v-model="link"
      label="Add social link"
      :prepend-icon="link ? 'add' : 'link'"
      :prepend-icon-cb="link ? addLink : undefined"
      @keypress.enter="addLink"
      type="url"
      clearable
      :rules="[$vfRule('url'), $vfRule('nonExisting', null, social)]"
    />
  </v-form>
</div>
</template>

<script>
export default {
  name: 'social-links',
  props: {
    social: Array,
  },
  data: () => ({
    valid: false,
    link: null,
    errors: []
  }),

  methods: {
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
      if (this.social.indexOf(this.link) == -1) {
        this.social.push(this.link)
        this.link = null
      }
    }
  }
}
</script>
