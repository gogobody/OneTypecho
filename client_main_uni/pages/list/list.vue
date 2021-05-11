<template>
<view>
<!--pages/list/list.wxml-->
<!--<import src="../../templates/nodata/nodata.wxml"></import>-->
<!--<import src="../../templates/poplogin/poplogin.wxml"></import>-->

<!--新闻列表——常规-->
<view class="container">
	<view class="jiangqie-news-view">

		<block v-if="posts.length > 0">
			<block v-for="(item, index) in posts" :key="index">
				<view v-if="index%5==4" class="jiangqie-news-block image-wide" :data-id="item.id" @tap.stop="handlerArticleClick">
					<image :src="item.thumbnail" mode="aspectFill" class="jiangqie-news-image"></image>
					<view class="jiangqie-news-text">
						<view :class="'jiangqie-news-title ' + (item.excerpt?'':'jiangqie-news-no-excerpt')">{{item.title}}</view>
						<view v-if="item.excerpt" class="jiangqie-news-describe">{{item.excerpt}}</view>
						<view class="jiangqie-news-info">
							<view class="jiangqie-news-cmt">
								<image src="/static/images/ctm2.png" mode="aspectFill" class></image>{{item.views}}
							</view>
							<text v-for="(item, index2) in item.tags" :key="index2" class="jiangqie-news-tag">{{item.name}}</text>
							<text class="jiangqie-news-time">{{item.time}}</text>
						</view>
					</view>
				</view>
				<view v-else class="jiangqie-news-block" :data-id="item.id" @tap.stop="handlerArticleClick">
					<image :src="item.thumbnail" mode="aspectFill" class="jiangqie-news-image"></image>
					<view class="jiangqie-news-text">
						<view :class="'jiangqie-news-title ' + (item.excerpt?'':'jiangqie-news-no-excerpt')">{{item.title}}</view>
						<view v-if="item.excerpt" class="jiangqie-news-describe">{{item.excerpt}}</view>
						<view class="jiangqie-news-info">
							<view class="jiangqie-news-cmt">
								<image src="/static/images/ctm2.png" mode="aspectFill" class></image>{{item.views}}
							</view>
							<text v-for="(item, index2) in item.tags" :key="index2" class="jiangqie-news-tag">{{item.name}}</text>
							<text class="jiangqie-news-time">{{item.time}}</text>
						</view>
					</view>
				</view>
			</block>

			<!--加载loadding-->
			<tui-loadmore :visible="loadding"></tui-loadmore>
			<tui-nomore :visible="!pullUpOn"></tui-nomore>
			<!--加载loadding-->

		</block>
		<block v-else-if="loaded">
			<template is="jiangqie_nodata"></template>
		</block>

	</view>
</view>

<block data-type="template" data-is="jiangqie_poplogin" data-attr="show:showPopLogin">
	<cover-view v-if="showPopLogin" class="jiangqie-poplogin-mask">
		<cover-view class="jiangqie-poplogin">
			<cover-view class="jiangqie-pop-head">
				<cover-image src="/static/images/default_avatar.jpg" mode="aspectFill"></cover-image>
				<cover-view>立即登录，体验更多功能</cover-view>
			</cover-view>
			<cover-view class="jiangqie-pop-opt">
				<button class="jiangqie-pop-btnl" @tap.stop="handlerLoginCancelClick">取消</button>
				<button class="jiangqie-pop-btnr" @tap.stop="handlerDoLoginClick">登录</button>
			</cover-view>
		</cover-view>
	</cover-view>
</block>

<!--加载框 start-->
<tui-loading :visible="loading"></tui-loading>
<!--加载框 end-->
</view>
</template>

<script>
/*
 * 即刻学术小程序开源版 v1.2.2
 * Author: 即刻学术
 * Help document: https://ijkxs.com/
 * github: https://github.com/longwenjunjie/jiangqie_kafei
 * gitee: https://gitee.com/longwenjunj/jiangqie_kafei
 * License：MIT
 * Copyright ️ 2020 ijkxs.com All rights reserved.
 */
const Constants = require("../../utils/constants");
const Util = require("../../utils/util");
const Api = require("../../utils/api.js");
const Rest = require("../../utils/rest");
import tuiLoading from "../../components/loading/loading";
import tuiLoadmore from "../../components/loadmore/loadmore";
import tuiNomore from "../../components/nomore/nomore";

export default {
  data() {
    return {
      posts: [],
      loadding: false,
      pullUpOn: true,
      loaded: false,
      showPopLogin: false
    };
  },

  components: {
    tuiLoading,
    tuiLoadmore,
    tuiNomore
  },
  props: {},

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    if (options.cat_id) {
      //分类
      uni.setNavigationBarTitle({
        title: options.title
      });
      this.cat_id = options.cat_id;
    } else if (options.tag_id) {
      //标签
      uni.setNavigationBarTitle({
        title: options.title
      });
      this.tag_id = options.tag_id;
    } else if (options.search) {
      //搜索
      uni.setNavigationBarTitle({
        title: '搜索【' + options.search + '】'
      });
      this.search = options.search;
    } else if (options.track) {
      //我的足迹
      let title = '我的浏览';

      if (options.track == 'likes') {
        title = '我的点赞';
      } else if (options.track == 'favorites') {
        title = '我的收藏';
      } else if (options.track == 'comments') {
        title = '我的评论';
      }

      uni.setNavigationBarTitle({
        title: title
      });
      this.track = options.track;
    } else {
      //最新
      uni.setNavigationBarTitle({
        title: '最新文章'
      });
    }
  },
  onShow: function () {
    this.loadPost(true);
  },
  onPullDownRefresh: function () {},
  onReachBottom: function () {
    if (!this.pullUpOn) {
      return;
    }

    this.loadPost(false);
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
    handlerArticleClick: function (e) {
      let post_id = e.currentTarget.dataset.id;
      uni.navigateTo({
        url: '/pages/article/article?post_id=' + post_id
      });
    },
    loadPost: function (refresh) {
      let that = this;
      that.setData({
        loadding: true
      });
      let offset = 0;

      if (!refresh) {
        offset = that.posts.length;
      }

      let url = '';
      let params = {
        offset: offset
      };

      if (that.cat_id !== undefined) {
        url = Api.JIANGQIE_POSTS_CATEGORY;
        params.cat_id = that.cat_id;
      } else if (that.tag_id !== undefined) {
        url = Api.JIANGQIE_POSTS_TAG;
        params.tag_id = that.tag_id;
      } else if (that.search !== undefined) {
        url = Api.JIANGQIE_POSTS_SEARCH;
        params.search = that.search;
      } else if (that.track !== undefined) {
        url = Api.JIANGQIE_POSTS_MY;
        params.track = that.track;
      } else {
        url = Api.JIANGQIE_POSTS_LAST;
      }

      Rest.get(url, params).then(res => {
        that.setData({
          loaded: true,
          loadding: false,
          posts: refresh ? res.data : that.posts.concat(res.data),
          pullUpOn: res.data.length >= Constants.JQ_PER_PAGE_COUNT
        });
      });
    },
    handlerLoginCancelClick: function (e) {
      this.setData({
        showPopLogin: false
      });
      Util.navigateBack();
    },
    handlerDoLoginClick: function (e) {
      uni.navigateTo({
        url: '/pages/login/login'
      });
      this.setData({
        showPopLogin: false
      });
    }
  }
};
</script>
<style>
@import "./list.css";
</style>