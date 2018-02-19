<template>
<v-layout>
  <v-flex xs12 sm6>
    <v-menu
      transition="scale-transition"
      :close-on-content-click="false"
      v-model="timeModel.from"
      full-width
    >
      <v-text-field
        slot="activator"
        label="From"
        v-model="time.from"
        prepend-icon="access_time"
        readonly
        :rules="[
          (required ? $vfRule('required') : (e) => true)
        ]"
        :required="required"
      />
      <v-time-picker
        v-model="time.from"
        :landscape="!true"
        actions
      >
        <v-spacer/>
        <v-btn
          flat
          color="error"
          @click="cancel('from')"
          @keypress.enter="cancel('from')"
        >Cancel</v-btn>
        <v-btn
          flat
          color="primary"
          @click="updateValue('from')"
          @keypress.enter="updateValue('from')"
        >Ok</v-btn>
      </v-time-picker>
    </v-menu>
  </v-flex>
  &nbsp;
  <v-flex xs12 sm6>
    <v-menu
      transition="scale-transition"
      :close-on-content-click="false"
      v-model="timeModel.to"
      full-width
    >
      <v-text-field
        slot="activator"
        label="To"
        v-model="time.to"
        readonly
        :rules="[
          (required ? $vfRule('required') : (e) => true)
        ]"
        :required="required"
      />
      <v-time-picker
        v-model="time.to"
        :landscape="!true"
        actions
      >
        <v-spacer/>
        <v-btn
          flat
          color="error"
          @click="cancel('to')"
          @keypress.enter="cancel('to')"
        >Cancel</v-btn>
        <v-btn
          flat
          color="primary"
          @click="updateValue('to')"
          @keypress.enter="updateValue('to')"
        >Ok</v-btn>
      </v-time-picker>
    </v-menu>
  </v-flex>
</v-layout>
</template>

<script>
export default {
  name: 'time-from-to-picker',
  props: {
    value: Object,
    required: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    time: {
      from: null,
      to: null
    },
    timeModel: {
      from: false,
      to: false
    }
  }),
  watch: {
    'timeModel.from': function(e) {
      if (!e) {
        this.updateValue('from')
      }
    },
    'timeModel.to': function(e) {
      if (!e) {
        this.updateValue('to')
      }
    },
    value(e) {
      this.time = JSON.parse(JSON.stringify(e))
    }
  },

  methods: {
    updateValue(e) {
      this.$emit('input', JSON.parse(JSON.stringify(this.time)))
      this.timeModel[e] = false
    },
    cancel(e) {
      this.time[e] = JSON.parse(JSON.stringify(this.value[e]))
      this.timeModel[e] = false
    }
  }
}
</script>
