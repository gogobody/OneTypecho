<template>
<view class="tui-loading-class tui-loading-init" v-if="visible">
    <view class="tui-loading-center"></view>
    <view class="tui-loadmore-tips">{{text}}</view>
  </view>
</template>

<script>

export default {
  data() {
    return {};
  },

  components: {},
  props: {
    text: {
      type: String,
      default: "正在加载..."
    },
    visible: {
      type: Boolean,
      default: false
    }
  },
  externalClasses: ['tui-loading-class'],
  methods: {}
};
</script>
<style>
@import "./loading.css";
</style>