<template>
<view>
<!--index.wxml-->
<view :style="'background: url(' + background + ') no-repeat;background-size: 100% auto;background-position-y: 100rpx;'">
	<navBar background="rgba(224, 48, 31, 100)" searchBar="true" @search="handlerSearchClick">
		<view slot="left" class="lxy-nav-bar__logo">
			<image :src="logo"></image>
		</view>
	</navBar>

	<scroll-view scroll-x scroll-with-animation class="tab-view" :scroll-left="scrollLeft">
		<view v-for="(item, index) in topNav" :key="index" :class="'tab-bar-item ' + (currentTab==index ? 'active' : '')" :data-current="index" @tap.stop="swichNav">
			<text class="tab-bar-title">{{item.name}}</text>
		</view>
		<view class="tab-bar-item">
			<text class="tab-bar-title">.</text>
		</view>
	</scroll-view>
	<view class="tab-more" @tap.stop="handlerTabMoreClick">
		<image src="/static/images/tabmorered.png" mode="aspectFill"></image>
	</view>

	<view class="tab-content" style="padding-top: 120rpx;">
		<block v-if="currentTab==0">

			<!--banner-->
			<view v-if="slide && slide.length>0" class="tui-banner-box">
				<swiper indicator-dots="true" autoplay="true" interval="5000" duration="150" class="tui-banner-swiper" circular="true" previous-margin="15rpx" next-margin="15rpx" @change="handlerSlideChange">
					<swiper-item v-for="(item, index) in slide" :key="index" class="tui-banner-item" :data-id="item.id" @tap.stop="handlerArticleClick">
						<image :src="item.thumbnail" :class="'tui-slide-image ' + ((current==index || (current==undefined && index==0))?'':'tui-banner-scale')" mode="aspectFill"></image>
					</swiper-item>
				</swiper>
			</view>
			<!--banner-->

			<view class="container">
				<!--图标模块 可单独设置背景色、背景图、图标图-->
				<view v-if="iconNav && iconNav.length>0" class="jiangqie-news-icon">
					<view v-for="(item, index) in iconNav" :key="index" class="jiangqie-news-iconbox" :data-link="item.link" @tap.stop="handlerIconNavClick">
						<view class="jiangqie-news-iconbg">
							<image class="jiangqie-news-iconimg" :src="item.icon"></image>
						</view>
						<text>{{item.title}}</text>
					</view>
				</view>

				<!--推荐模块-->
				<view v-if="actives" class="jiangqie-news-view">
					<view class="jiangqie-news-lightbox jiangqie-news-lightbox-main" :data-link="actives.left.link" @tap.stop="handlerActiveClick">
						<image class="jiangqie-news-lightimg" :src="actives.left.image" mode="aspectFill"></image>
						<view class="jiangqie-news-lighttext">
							<text class="jiangqie-news-lihgttitle">{{actives.left.title}}</text>
						</view>
					</view>
					<view class="jiangqie-news-lightbox jiangqie-news-lightbox-side" :data-link="actives.right_top.link" @tap.stop="handlerActiveClick">
						<image class="jiangqie-news-lightimg" :src="actives.right_top.image" mode="aspectFill"></image>
						<view class="jiangqie-news-lighttext">
							<text class="jiangqie-news-lihgttitle">{{actives.right_top.title}}</text>
						</view>
					</view>
					<view class="jiangqie-news-lightbox jiangqie-news-lightbox-side2" :data-link="actives.right_down.link" @tap.stop="handlerActiveClick">
						<image class="jiangqie-news-lightimg" :src="actives.right_down.image" mode="aspectFill"></image>
						<view class="jiangqie-news-lighttext">
							<text class="jiangqie-news-lihgttitle">{{actives.right_down.title}}</text>
						</view>
					</view>
				</view>

				<!--新闻列表——热门-->
				<view v-if="hot && hot.length>0" class="jiangqie-news-view jiangqie-news-hot">
					<view class="jiangqie-news-type">
						热门推荐<text>滑动查看</text>
					</view>
					<scroll-view class="jiangqie-news-scroll-x-box" scroll-x="true">
						<view v-for="(item, index) in hot" :key="index" class="jiangqie-hot-scroll-box" :data-id="item.id" @tap.stop="handlerArticleClick">
							<image :src="item.thumbnail" mode="aspectFill" class="jiangqie-hot-scroll-image"></image>
							<view class="jiangqie-hot-scroll-title">{{item.title}}</view>
						</view>
					</scroll-view>
				</view>

				<!--新闻列表——常规-->
				<view class="jiangqie-news-view">
					<view v-if="postsLast && postsLast.length>0" class="jiangqie-news-type">
						最新文章
					</view>
					<block v-for="(item, index) in postsLast" :key="index">
						<view v-if="(listMode==1 && false) || (listMode==2) || (listMode==3 && index%5==4)" class="jiangqie-news-block image-wide" :data-id="item.id" @tap.stop="handlerArticleClick">
							<image :src="item.thumbnail" mode="aspectFill" class="jiangqie-news-image"></image>
							<view class="jiangqie-news-text">
								<view :class="'jiangqie-news-title ' + (item.excerpt?'':'jiangqie-news-no-excerpt')">
									<text v-if="item.stick==1">置顶</text>
									{{item.title}}
								</view>
								<view v-if="item.excerpt" class="jiangqie-news-describe">{{item.excerpt}}</view>
								<view class="jiangqie-news-info">
									<view class="jiangqie-news-cmt">
										<image src="/static/images/ctm2.png" mode="aspectFill" class></image>{{item.views}}
									</view>
									<text v-for="(item, index2) in item.tags" :key="index2" class="jiangqie-news-tag">{{item.name}}</text>
									<text class="jiangqie-news-time">{{item.time}}</text>
								</view>
							</view>
							<!-- <image wx:if="{{item.stick==1}}" src="/static/images/set_top.png" mode="aspectFill" class="jiangqie-news-stick-image"></image> -->
						</view>
						<view v-else class="jiangqie-news-block" :data-id="item.id" @tap.stop="handlerArticleClick">
							<image :src="item.thumbnail" mode="aspectFill" class="jiangqie-news-image"></image>
							<view class="jiangqie-news-text">
								<view :class="'jiangqie-news-title ' + (item.excerpt?'':'jiangqie-news-no-excerpt')">
									<text v-if="item.stick==1">置顶</text>
									{{item.title}}
								</view>
								<view v-if="item.excerpt" class="jiangqie-news-describe">{{item.excerpt}}</view>
								<view class="jiangqie-news-info">
									<view class="jiangqie-news-cmt">
										<image src="/static/images/ctm2.png" mode="aspectFill" class></image>{{item.views}}
									</view>
									<text v-for="(item, index2) in item.tags" :key="index2" class="jiangqie-news-tag">{{item.name}}</text>
									<text class="jiangqie-news-time">{{item.time}}</text>
								</view>
							</view>
							<!-- <image wx:if="{{item.stick==1}}" src="/static/images/set_top.png" mode="aspectFill" class="jiangqie-news-stick-image"></image> -->
						</view>
					</block>
				</view>
			</view>
			<!--加载loadding-->
			<tui-loadmore :visible="loaddingLast"></tui-loadmore>
			<tui-nomore :visible="!pullUpOnLast"></tui-nomore>
			<!--加载loadding-->
		</block>

		<block v-else>
			<view class="container">
				<view class="jiangqie-news-view">
					<block v-for="(item, index) in posts" :key="index">
						<view v-if="(listMode==1 && false) || (listMode==2) || (listMode==3 && index%5==4)" class="jiangqie-news-block image-wide" :data-id="item.id" @tap.stop="handlerArticleClick">
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
				</view>
			</view>
			<!--加载loadding-->
			<tui-loadmore :visible="loadding"></tui-loadmore>
			<tui-nomore :visible="!pullUpOn"></tui-nomore>
			<!--加载loadding-->
		</block>
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
 * Copyright ️ 2020 ijkxs.com all rights reserved.
 */
const Constants = require("../../utils/constants");
const Api = require("../../utils/api.js");
const Rest = require("../../utils/rest");
import navBar from "../../components/navbar/navbar";
import tuiLoading from "../../components/loading/loading";
import tuiLoadmore from "../../components/loadmore/loadmore";
import tuiNomore from "../../components/nomore/nomore";

export default {
  data() {
    return {
      logo: '',
      background: '',
      //顶部导航
      topNav: [{
        id: 0,
        name: '头条'
      }],
      currentTab: 0,
      //预设当前项的值
      //幻灯片
      slide: [],
      //图片导航
      iconNav: [],
      //热门文章
      hot: [],
      //热门tab
      postsLast: [],
      loaddingLast: false,
      pullUpOnLast: true,
      //其他tab
      posts: [],
      loadding: false,
      pullUpOn: true,
      //列表模式
      listMode: 3,
      actives: "",
      current: ""
    };
  },

  components: {
    navBar,
    tuiLoading,
    tuiLoadmore,
    tuiNomore
  },
  props: {},
  onLoad: function (options) {
    let that = this; //获取配置

    Rest.get(Api.JIANGQIE_SETTING_HOME).then(res => {
      let logo = "/static/images/logo.png";

      if (res.data.logo && res.data.logo.length > 0) {
        logo = res.data.logo;
      }

      that.setData({
        logo: logo,
        topNav: that.topNav.concat(res.data.top_nav),
        slide: res.data.slide,
        iconNav: res.data.icon_nav,
        actives: res.data.actives,
        hot: res.data.hot,
        listMode: res.data.list_mode,
        background: res.data.slide && res.data.slide.length > 0 ? Api.JIANGQIE_BG_INDEX : ''
      });

      if (res.data.title && res.data.title.length > 0) {
        getApp().appName = res.data.title;
      }
    }); //加载文章

    this.loadPostLast(true);
  },
  onReachBottom: function () {
    if (this.currentTab == 0) {
      if (!this.pullUpOnLast) {
        return;
      }

      this.loadPostLast(false);
    } else {
      if (!this.pullUpOn) {
        return;
      }

      this.loadPost(false);
    }
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
    //nav start----
    handlerSearchClick: function (e) {
      uni.navigateTo({
        url: '/pages/search/search'
      });
    },
    //nav end ----
    //slide start----
    handlerSlideChange: function (e) {
      this.setData({
        current: e.detail.current
      });
    },
    //slide end----
    //tab -- start
    swichNav: function (e) {
      let cur = e.currentTarget.dataset.current;

      if (this.currentTab == cur) {
        return false;
      }

      this.setData({
        background: cur == 0 && this.slide && this.slide.length > 0 ? Api.JIANGQIE_BG_INDEX : '',
        currentTab: cur
      });

      if (cur !== 0) {
        this.loadPost(true);
      }
    },
    handlerTabMoreClick: function (e) {
      uni.switchTab({
        url: '/pages/categories/categories'
      });
    },
    //tab -- end
    handlerIconNavClick: function (e) {
      let link = e.currentTarget.dataset.link;
      this.openLink(link);
    },
    handlerActiveClick: function (e) {
      let link = e.currentTarget.dataset.link;
      this.openLink(link);
    },
    handlerArticleClick: function (e) {
      let post_id = e.currentTarget.dataset.id;
      uni.navigateTo({
        url: '/pages/article/article?post_id=' + post_id
      });
    },
    //加载数据
    loadPostLast: function (refresh) {
      let that = this;
      that.setData({
        loaddingLast: true
      });
      let offset = 0;

      if (!refresh) {
        offset = that.postsLast.length;
      }

      Rest.get(Api.JIANGQIE_POSTS_LAST, {
        'offset': offset
      }).then(res => {
        that.setData({
          loaddingLast: false,
          postsLast: refresh ? res.data : that.postsLast.concat(res.data),
          pullUpOnLast: res.data.length >= Constants.JQ_PER_PAGE_COUNT
        });
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

      Rest.get(Api.JIANGQIE_POSTS_CATEGORY, {
        'offset': offset,
        'cat_id': that.topNav[that.currentTab].id
      }).then(res => {
        that.setData({
          loadding: false,
          posts: refresh ? res.data : that.posts.concat(res.data),
          pullUpOn: res.data.length >= Constants.JQ_PER_PAGE_COUNT
        });
      });
    },
    openLink: function (link) {
      if (link.startsWith('/pages')) {
        uni.navigateTo({
          url: link
        });
      } else {
        uni.navigateToMiniProgram({
          appId: link,
          fail: res => {
            uni.showToast({
              title: '无效链接'
            });
          }
        });
      }
    }
  }
};
</script>
<style>
@import "./index.css";
</style>