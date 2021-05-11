<template>
<view>
<view class="container">
	<view class="tui-flex">
		<view class="tui-cube-box" @tap="tipClick">
			<view class="tui-cube tui-cube-1">❤</view>
			<view class="tui-cube tui-cube-2">V{{version}}</view>
			<view class="tui-cube tui-cube-3">即刻学术</view>
			<view class="tui-cube tui-cube-4">开源</view>
			<view class="tui-cube tui-cube-5">小程序</view>
			<view class="tui-cube tui-cube-6">免费</view>
		</view>
	</view>
	<view class="tui-content-box">
		<view class="tui-content">即刻学术小程序 是基于酱茄小程序开源版 v1.2.2 开发，二开 typecho 专用版，实现 Typecho 网站数据与小程序数据同步共享，通过简单的配置就能搭建自己的小程序。项目免费开源，源码可在
			<text class="tui-content-email" @tap="copy" data-text="https://github.com/gogobody/OneTypecho">GitHub</text>上下载，欢迎Watch & Star ★。</view>
		<view class="tui-content">项目可能存在缺陷或者bug，如果您在使用过程中发现问题或者有更好的建议，可反馈给我们。</view>
		<view class="tui-content tui-content-email" @tap="copy" data-text="ijkxs.com">技术支持：ijkxs.com</view>
		<view class="tui-footer">
			<image src="/static/images/qrcode.jpg" class="tui-applets" mode="widthFix"></image>
			<view class="tui-footer-text">扫描二维码，体验即刻学术开源小程序！</view>
		</view>
	</view>
</view>
<!--加载框 start-->
<tui-loading :visible="loading"></tui-loading>
<!--加载框 end-->
</view>
</template>

<script>
/*
 * 即刻学术小程序开源版 v1.2.2
 * Author: 即刻学术
 * Help document: https://ijkxs.com github: https://github.com/longwenjunjie/jiangqie_kafei
 * gitee: https://gitee.com/longwenjunj/jiangqie_kafei
 * License：MIT
 * Copyright ️ 2020 ijkxs.comights reserved.
 */
const Constant = require("../../utils/constants");
import tuiLoading from "../../components/loading/loading";

export default {
  data() {
    return {
      version: Constant.JQ_VERSION
    };
  },

  components: {
    tuiLoading
  },
  props: {},
  onLoad: function (options) {},
  onShareAppMessage: function () {
    return {
      title: getApp().appName,
      path: 'pages/index/index'
    };
  },
  methods: {
    onShareTimeline: function () {
      return {
        title: getApp().appName
      };
    },
    copy: function (e) {
      let text = e.currentTarget.dataset.text;
      uni.setClipboardData({
        data: text,

        success(res) {
          uni.getClipboardData({
            success(res) {
              uni.showToast({
                title: '网址已复制'
              });
            }

          });
        }

      });
    },
    tipClick: function () {
      uni.showToast({
        icon: 'none',
        title: '即刻学术小程序开源版 v1.2.2'
      });
    }
  }
};
</script>
<style>
@import "./about.css";
</style>