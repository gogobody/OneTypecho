<template>
<view class="tui-loadmore-class tui-loadmore" v-if="visible">
	<view :class="('tui-loading-'+index) + ' ' + ((index==3 && type)?'tui-loading-'+type:'')"></view>
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
    //显示文本
    text: {
      type: String,
      default: "正在加载..."
    },
    //是否可见
    visible: {
      type: Boolean,
      default: false
    },
    //loading 样式 ：1,2,3
    index: {
      type: Number,
      default: 1
    },
    //颜色设置，只有index=3时生效：primary，red，orange，green
    type: {
      type: String,
      default: ""
    }
  },
  externalClasses: ['tui-loadmore-class'],
  methods: {}
};
</script>
<style>
@import "./loadmore.css";
</style>