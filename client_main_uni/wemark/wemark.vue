<template>
<!--
 * @Descripttion: 
 * @Author: 帅气的杜恒欧巴
 * @Date: 2020-12-10 17:49:04
 * @LastEditTime: 2020-12-15 10:59:35
-->
<view class="wemark_wrapper">
	<block v-for="(renderBlock, blockIndex) in parsedData" :key="blockIndex" v-if="type === 'wemark'">
		<view :class="'wemark_block_' + renderBlock.type">
			<block v-for="(renderInline, inlineIndex) in renderBlock.content" :key="inlineIndex" v-if="renderBlock.isArray">
				<text :class="'wemark_inline_' + renderInline.type" v-if="renderInline.type === 'text' || renderInline.type === 'code' || renderInline.type === 'strong' || renderInline.type === 'strong_em' || renderInline.type === 'deleted' || renderInline.type === 'em' || renderInline.type === 'table_th' || renderInline.type === 'table_td'">{{renderInline.content}}</text>
				<text :class="'wemark_inline_code_' + renderInline.type" v-if="renderInline.type&&renderBlock.highlight">{{renderInline.content}}</text>
				<text class="wemark_inline_code_text" v-if="!renderInline.type">{{renderInline}}</text>
				<navigator class="wemark_inline_link" :url="renderInline.data.href" v-if="renderInline.type === 'link'">{{renderInline.content}}</navigator>
				<image @tap="previewImage" :data-src="renderInline.src" mode="widthFix" class="wemark_inline_image" :src="renderInline.src" v-if="renderInline.type === 'image'"></image>
			</block>
			<block v-if="!renderBlock.isArray">
				<view v-if="renderBlock.type === 'code'">{{renderBlock.content}}</view>
				<video v-if="renderBlock.type == 'video'" class="wemark_block_video" :src="renderBlock.src" :poster="renderBlock.poster" controls></video>
			</block>
		</view>
	</block>
</view>
</template>

<script>
/*
 * @Descripttion:
 * @Author: 帅气的杜恒欧巴
 * @Date: 2020-12-10 17:49:04
 * @LastEditTime: 2020-12-14 09:57:45
 */
const parser = require("./parser");

export default {
  data() {
    return {
      parsedData: {}
    };
  },

  components: {},
  props: {
    md: {
      type: String,
      default: ''
    },
    type: {
      type: String,
      default: 'wemark'
    },
    link: {
      type: Boolean,
      default: false
    },
    highlight: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    md: {
      handler: function () {
        this.parseMd();
      },
      immediate: true
    }
  },
  methods: {
    parseMd() {
      if (!this.md) return;
      var parsedData = parser.parse(this.md, {
        link: this.link,
        highlight: this.highlight
      });
      this.setData({
        parsedData
      });
    },

    previewImage(e) {
      let urls = [];
      urls[0] = e.currentTarget.dataset.src;
      uni.previewImage({
        urls
      });
    }

  }
};
</script>
<style>
@import "./wemark.css";
</style>