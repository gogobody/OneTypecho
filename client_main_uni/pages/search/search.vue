<template>
<view>
<!--pages/search/search.wxml-->
<view class="container">
	<view class="jiangqie-searchbox">
		<view class="jiangqie-search-input">
			<icon type="search" size="13" color="#999"></icon>
			<input confirm-type="search" :placeholder="'大家都在搜：' + placeholder" auto-focus="ture" placeholder-class="jiangqie-input-plholder" class="jiangqie-input" @confirm="handerSearchConfirm" @input="handlerSearchInput"></input>
		</view>

		<view v-if="canSearch" class="jiangqie-search" @tap="handerSearchClick">搜索</view>
		<view v-else class="jiangqie-cancle" @tap="handerCancelClick">取消</view>

	</view>

	<!--搜索模块-->
	<view v-if="historySearch.length>0" class="jiangqie-search-box">
		<view class="jiangqie-search-head">
			<text class="jiangqie-search-clear" @tap.stop="handlerClearHistory">清除历史</text>
			<image src="/static/images/time.png" mode="widthFix" class="jiangqie-search-icon"></image>历史搜索
		</view>
		<view class="jiangqie-search-list">
			<view v-for="(item, index) in historySearch" :key="index" class="jiangqie-search-tag" :data-item="item" @tap.stop="handlerSearchItemClick">
				<image src="/static/images/del.png" mode="widthFix" class="jiangqie-search-del" :data-item="item" @tap.stop="handlerSearchItemDelete"></image>
				<text>{{item}}</text>
			</view>
		</view>
	</view>

	<!--热门模块-->
	<view v-if="hotSearch.length>0" class="jiangqie-search-box">
		<view class="jiangqie-search-head">
			<image src="/static/images/hot.png" mode="widthFix" class="jiangqie-search-icon"></image>热门
		</view>
		<view class="jiangqie-search-list jiangqie-search-hot">
			<view v-for="(item, index) in hotSearch" :key="index" class="jiangqie-search-tag" :data-item="item" @tap.stop="handlerSearchItemClick">
				<text>{{item}}</text>
			</view>
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
 * Help document: https://ijkxs.com/ky
 * github: https://github.com/longwenjunjie/jiangqie_kafei
 * gitee: https://gitee.com/longwenjunj/jiangqie_kafei
 * License：MIT
 * Copyright ️ 2020 ijkxs.com All rights reserved.
 */
const Constant = require("../../utils/constants");
const Util = require("../../utils/util");
const Api = require("../../utils/api.js");
const Rest = require("../../utils/rest");
import tuiLoading from "../../components/loading/loading";

export default {
  data() {
    return {
      canSearch: false,
      historySearch: [],
      hotSearch: [],
      placeholder: ''
    };
  },

  components: {
    tuiLoading
  },
  props: {},
  onLoad: function (options) {
    let that = this;
    uni.getStorage({
      key: Constant.JQ_SEARCH_KEY,

      success(res) {
        that.setData({
          historySearch: res.data
        });
      }

    });
    that.setData({
      placeholder: getApp().appName
    });
    Rest.get(Api.JIANGQIE_POSTS_SEARCH_HOT).then(res => {
      that.setData({
        hotSearch: res.data
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
    //输入
    handlerSearchInput: function (e) {
      this.keyword = e.detail.value;
      this.setData({
        canSearch: this.keyword.length > 0
      });
    },
    handerSearchConfirm: function (e) {
      this.search();
    },
    //搜索
    handerSearchClick: function (e) {
      this.search();
    },
    search: function () {
      let that = this;
      uni.getStorage({
        key: Constant.JQ_SEARCH_KEY,

        success(res) {
          let keys = [that.keyword];

          for (let i = 0; i < res.data.length && keys.length < Constant.JQ_SEARCH_MAX_COUNT; i++) {
            if (that.keyword == res.data[i]) {
              continue;
            }

            keys.push(res.data[i]);
          }

          that.setData({
            historySearch: keys
          });
          uni.setStorage({
            data: keys,
            key: Constant.JQ_SEARCH_KEY
          });
        },

        fail(e) {
          let keys = [that.keyword];
          that.setData({
            historySearch: keys
          });
          uni.setStorage({
            data: keys,
            key: Constant.JQ_SEARCH_KEY
          });
        }

      });
      uni.navigateTo({
        url: '/pages/list/list?search=' + this.keyword
      });
    },
    //取消搜索
    handerCancelClick: function (e) {
      Util.navigateBack();
    },
    //清楚搜索历史
    handlerClearHistory: function (e) {
      let that = this;
      uni.showModal({
        title: '提示',
        content: '确定要清除吗？',

        success(res) {
          if (res.confirm) {
            uni.setStorage({
              key: Constant.JQ_SEARCH_KEY,
              data: [],

              success() {
                that.setData({
                  historySearch: []
                });
              }

            });
          }
        }

      });
    },
    //点击 搜索历史
    handlerSearchItemClick: function (e) {
      let item = e.currentTarget.dataset.item;
      uni.navigateTo({
        url: '/pages/list/list?search=' + item
      });
    },
    //历史删除
    handlerSearchItemDelete: function (e) {
      let that = this;
      uni.showModal({
        title: '提示',
        content: '确定要删除吗？',

        success(res) {
          if (res.confirm) {
            let item = e.currentTarget.dataset.item;
            let keys = [];

            for (let i = 0; i < that.historySearch.length; i++) {
              if (item == that.historySearch[i]) {
                continue;
              }

              keys.push(that.historySearch[i]);
            }

            that.setData({
              historySearch: keys
            });
            uni.setStorage({
              data: keys,
              key: Constant.JQ_SEARCH_KEY
            });
          }
        }

      });
    }
  }
};
</script>
<style>
@import "./search.css";
</style>