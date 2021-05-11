<template>
<view>
<!--pages/navbar/navbar.wxml-->
<view class="container">
	<view class="categorie-header">
		<view class="categorie-text">
			<text>{{setting.title}}</text>
			<view>{{setting.description}}</view>
		</view>
		<image :src="setting.background" mode="aspectFill"></image>
	</view>
	<view class="right-box">

		<view v-for="(item, index) in categories" :key="index" class="sortbox" :data-id="item.id" :data-name="item.name" @tap.stop="handlerCategoryClick">
			<image :src="item.cover?item.cover:categorie_cover" mode="aspectFill" class="sortimage"></image>
			<view class="sorttitle">{{item.name}}</view>
			<view class="sortsummary">{{item.description}}</view>
		</view>

	</view>
</view>
<!--加载框 start-->
<tui-loading :visible="loading"></tui-loading>
<!--加载框 end-->
<view class="tui-nomore-class tui-loadmore">
	<view class="tui-nomore">
		<view class="tui-text" style="background: #FFF;">© 即刻学术 ijkxs.com</view>
	</view>
</view>
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
const Api = require("../../utils/api");
const Rest = require("../../utils/rest");
import tuiLoading from "../../components/loading/loading";

export default {
  data() {
    return {
      setting: {},
      categories: [],
      categorie_cover: Api.JIANGQIE_CAT_COVER,
      default: {
        background: Api.JIANGQIE_BG_CATEGORY,
        title: '分类标题，请在后台修改',
        description: '分类描述，请在后台修改'
      }
    };
  },

  components: {
    tuiLoading
  },
  props: {},
  onLoad: function (options) {
    //获取配置
    let that = this;
    Rest.get(Api.JIANGQIE_SETTING_CATEGORY).then(res => {
      that.setData({
        setting: {
          background: res.data.background ? res.data.background : that.default.background,
          title: res.data.title ? res.data.title : that.default.title,
          description: res.data.description ? res.data.description : that.default.description
        }
      });
    }); //获取一级分类

    Rest.get(Api.JIANGQIE_CATEGORY_INDEX).then(res => {
      that.setData({
        categories: res.data
      });
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
    },
    handlerCategoryClick: function (e) {
      let cat_id = e.currentTarget.dataset.id;
      let cat_name = e.currentTarget.dataset.name;
      uni.navigateTo({
        url: '/pages/list/list?cat_id=' + cat_id + '&title=' + cat_name
      });
    }
  }
};
</script>
<style>
@import "./categories.css";
</style>