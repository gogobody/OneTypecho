<template>
<view>
<!--pages/viewhtml/viewhtml.wxml-->
<!-- <import src="../../components/wxParse/wxParse.wxml" /> -->

<view class="jiangqie-page-body">
	<!-- <template is="wxParse" data="{{wxParseData:article.nodes}}" /> -->
	<wemark :md="content" link highlight type="wemark"></wemark>
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
 * Help document: https://ijkxs.com/ky
 * github: https://github.com/longwenjunjie/jiangqie_kafei
 * gitee: https://gitee.com/longwenjunj/jiangqie_kafei
 * License：MIT
 * Copyright ️ 2020 ijkxs.com All rights reserved.
 */
const Api = require("../../utils/api.js");
const Rest = require("../../utils/rest"); // const WxParse = require('../../components/wxParse/wxParse');
import tuiLoading from "../../components/loading/loading";
import wemark from "../../wemark/wemark";

export default {
  data() {
    return {};
  },

  components: {
    tuiLoading,
    wemark
  },
  props: {},
  onLoad: function (options) {
    let that = this;
    Rest.get(Api.JIANGQIE_POST_PAGE, {
      page_id: options.page_id
    }).then(res => {
      uni.setNavigationBarTitle({
        title: res.data.title,
        content: res.data.content
      }); // WxParse.wxParse('article', 'html', res.data.content, that, 5);
    });
  },
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
    }
  }
};
</script>
<style>
@import "./viewhtml.css";
</style>