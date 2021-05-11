<template>
<view class="tui-nomore-class tui-loadmore" v-if="visible">
	<view :class="isDot?'tui-nomore-dot':'tui-nomore'">
		<view :style="'background:' + bgcolor" :class="isDot?'tui-dot-text':'tui-text'">{{isDot?dotText:text}}</view>
	</view>
</view>
</template>

<script>

export default {
  data() {
    return {
      dotText: "●"
    };
  },

  components: {},
  props: {
    //是否可见
    visible: {
      type: Boolean,
      default: false
    },
    //当前页面背景颜色
    bgcolor: {
      type: String,
      default: "#FFFFFF"
    },
    //是否以圆点代替 "没有更多了"
    isDot: {
      type: Boolean,
      default: false
    },
    //isDot为false时生效
    text: {
      type: String,
      default: "© 即刻学术 ijkxs.com"
    }
  },
  externalClasses: ['tui-nomore-class'],
  methods: {}
};
</script>
<style>
@import "./nomore.css";
</style>