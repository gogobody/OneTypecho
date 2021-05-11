<template>
<view>
<!--pages/hot/hot.wxml-->

<!--<import src="../../templates/nodata/nodata.wxml"></import>-->

<view class="jiangqie-timeline-head">
	<image :src="setting.background" mode="aspectFill"></image>
	<view v-if="setting.title.length>0 || setting.description.length>0" class="timeline-head-text">
		<text>{{setting.title}}</text>
		<view class="timeline-head-describe">{{setting.description}}</view>
	</view>
</view>

<scroll-view scroll-x scroll-with-animation class="tab-view" :scroll-left="0">
	<view v-for="(item, index) in tabbar" :key="index" :class="'tab-bar-item ' + (currentTab==index ? 'active' : '')" :data-current="index" @tap.stop="swichNav">
		<text class="tab-bar-title">{{item}}</text>
	</view>
</scroll-view>

<view :style="'background: url(' + tl_background + ') repeat-y; background-position: -30rpx 0;'">

	<block v-if="posts.length > 0">
		<view class="jiangqie-timeline-view">

			<view v-for="(item, index) in posts" :key="index" :data-id="item.id" class="jiangqie-timeline-block" @tap.stop="handlerHotArticle">
				<view class="jiangqie-timeline-times">
					<image src="/static/images/timelinedot.png" mode="aspectFill"></image>
					<text>{{item.time}}</text>
				</view>
				<view class="jiangqie-timeline-box timeline-single">
					<image :src="item.thumbnail" mode="aspectFill" class="jiangqie-timeline-image"></image>
					<view class="jiangqie-timeline-text">
						<view class="jiangqie-timeline-title">{{item.title}}</view>
						<view class="jiangqie-timeline-info">
							<view class="jiangqie-timeline-cmt">
                                <block v-if="currentTab==0">
                                    <image src="/static/images/ctm2.png" mode="aspectFill" class></image>{{item.views}}
                                </block>
                                <block v-else-if="currentTab==1">
                                    <image src="/static/images/laud.png" mode="aspectFill" class></image>{{item.likes}}
                                </block>
                                <block v-else-if="currentTab==2">
                                    <image src="/static/images/ctm3.png" mode="aspectFill" class></image>{{item.favorites}}
                                </block>
                                <block v-else-if="currentTab==3">
                                    <image src="/static/images/ctm.png" mode="aspectFill" class></image>{{item.comment_count}}
                                </block>
							</view>
							<text v-for="(item, index2) in item.tags" :key="index2" class="jiangqie-timeline-tag">{{item.name}}</text>
						</view>
					</view>
				</view>
			</view>

			<!--加载loadding-->
			<tui-loadmore :visible="loadding"></tui-loadmore>
			<tui-nomore :visible="!pullUpOn"></tui-nomore>
			<!--加载loadding-->

		</view>

	</block>
	<block v-else-if="loaded">
		<template is="jiangqie_nodata"></template>
	</block>
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
import tuiLoading from "../../components/loading/loading";
import tuiLoadmore from "../../components/loadmore/loadmore";
import tuiNomore from "../../components/nomore/nomore";

export default {
  data() {
    return {
      setting: {},
      tabbar: ['浏览数', '点赞数', '收藏数', '评论数'],
      currentTab: 0,
      posts: [],
      loadding: false,
      pullUpOn: true,
      loaded: false,
      tl_background: Api.JIANGQIE_BG_TIMELINE,
      default: {
        background: Api.JIANGQIE_BG_HOT,
        title: '热门标题，请在后台修改',
        description: '热门描述，请在后台修改'
      },
	  sorts: ['views', 'likes', 'favorites', 'comments'],
    };
  },

  components: {
    tuiLoading,
    tuiLoadmore,
    tuiNomore
  },
  props: {},
  onLoad: function (options) {
    //获取配置
    let that = this;
    Rest.get(Api.JIANGQIE_SETTING_HOT).then(res => {
      that.setData({
        setting: {
          background: res.data.background ? res.data.background : that.default.background,
          title: res.data.title ? res.data.title : that.default.title,
          description: res.data.description ? res.data.description : that.default.description
        }
      });
    });
    this.loadPosts(true);
  },
  onReachBottom: function () {
    if (!this.pullUpOn) {
      return;
    }

    this.loadPosts(false);
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
    // 点击标题切换当前页时改变样式
    swichNav: function (e) {
      let cur = e.currentTarget.dataset.current;

      if (this.currentTab == cur) {
        return false;
      }

      this.setData({
        currentTab: cur
      });
      this.posts = [];
      this.loadPosts(true);
    },
    handlerHotArticle: function (e) {
      let post_id = e.currentTarget.dataset.id;
      uni.navigateTo({
        url: '/pages/article/article?post_id=' + post_id
      });
    },

    //加载数据
    loadPosts(refresh) {
      let that = this;
      that.setData({
        loadding: true
      });
      Rest.get(Api.JIANGQIE_POSTS_HOT, {
        'offset': refresh ? 0 : that.posts.length,
        'sort': that.sorts[that.currentTab]
      }).then(res => {
        that.setData({
          loaded: true,
          loadding: false,
          posts: refresh ? res.data : that.posts.concat(res.data),
          pullUpOn: res.data.length >= Constants.JQ_PER_PAGE_COUNT
        });
      });
    }

  }
};
</script>
<style>
@import "./hot.css";
</style>