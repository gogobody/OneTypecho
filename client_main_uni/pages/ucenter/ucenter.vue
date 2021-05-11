<template>
<view>
<!--pages/ucenter/ucenter.wxml-->
<!--<import src="../../templates/poplogin/poplogin.wxml"></import>-->

<view :style="'background: url(' + setting.background + ') no-repeat; background-size: 750rpx 520rpx;background-position: 0 -120rpx;'">
	<view class="jiangqie-userbox">
		<view class="jiangqie-usersetup">
			<image src="/static/images/setup.png"></image>
		</view>
		<view class="jiangqie-userinfo">
			<block v-if="user">
				<view class="jiangqie-useravatar">
					<image :src="user.avatar" mode="aspectFill"></image>
				</view>
				<view class="jiangqie-username">
					<text>{{user.nickname}}</text>
				</view>
			</block>
			<block v-else>
				<view class="jiangqie-useravatar">
					<image src="/static/images/default_avatar.jpg" mode="aspectFill"></image>
          <view class="jiangqie-useralogin"><navigator url="../login/login">立即登录</navigator></view>
				</view>
			</block>
		</view>
		<view class="jiangqie-countinfo">
			<view class="jiangqie-countblock">
				<view class="jiangqie-countnumber">39</view>
				<view class="jiangqie-counttype">帖子</view>
			</view>
			<view class="jiangqie-countblock">
				<view class="jiangqie-countnumber">56</view>
				<view class="jiangqie-counttype">文章</view>
			</view>
			<view class="jiangqie-countblock">
				<view class="jiangqie-countnumber">72</view>
				<view class="jiangqie-counttype">粉丝</view>
			</view>
			<view class="jiangqie-countblock">
				<view class="jiangqie-countnumber">18</view>
				<view class="jiangqie-counttype">关注</view>
			</view>
		</view>
	</view>
	<view class="jiangqie-listbox jiangqie-postcount">
		<block v-for="(item, index) in menu" :key="index">
			<view v-if="item.tp=='views'" :class="'jiangqie-listblock ' + (item.line=='yes'?'jiangqie-listblock-line':'')" data-track="views" @tap.stop="handlerPostTrack">
				<view class="jiangqie-listcont">
					<image src="/static/images/more.png" mode="aspectFill"></image>
				</view>
				<view class="jiangqie-listtitle">
					<text>{{item.title}}</text>
					<image v-if="item.icon" :src="item.icon" mode="aspectFill"></image>
				</view>
			</view>
			<view v-if="item.tp=='likes'" :class="'jiangqie-listblock ' + (item.line=='yes'?'jiangqie-listblock-line':'')" data-track="likes" @tap.stop="handlerPostTrack">
				<view class="jiangqie-listcont">
					<image src="/static/images/more.png" mode="aspectFill"></image>
				</view>
				<view class="jiangqie-listtitle">
					<text>{{item.title}}</text>
					<image v-if="item.icon" :src="item.icon" mode="aspectFill"></image>
				</view>
			</view>
			<view v-if="item.tp=='favorites'" :class="'jiangqie-listblock ' + (item.line=='yes'?'jiangqie-listblock-line':'')" data-track="favorites" @tap.stop="handlerPostTrack">
				<view class="jiangqie-listcont">
					<image src="/static/images/more.png" mode="aspectFill"></image>
				</view>
				<view class="jiangqie-listtitle">
					<text>{{item.title}}</text>
					<image v-if="item.icon" :src="item.icon" mode="aspectFill"></image>
				</view>
			</view>
			<view v-if="item.tp=='comments' && setting.enable_comment == '1'" :class="'jiangqie-listblock ' + (item.line=='yes'?'jiangqie-listblock-line':'')" data-track="comments" @tap.stop="handlerPostTrack">
				<view class="jiangqie-listcont">
					<image src="/static/images/more.png" mode="aspectFill"></image>
				</view>
				<view class="jiangqie-listtitle">
					<text>{{item.title}}</text>
					<image v-if="item.icon" :src="item.icon" mode="aspectFill"></image>
				</view>
			</view>
			<view v-if="item.tp=='about'" :class="'jiangqie-listblock ' + (item.line=='yes'?'jiangqie-listblock-line':'')" @tap.stop="handlerAbout">
				<view class="jiangqie-listcont">
					<image src="/static/images/more.png" mode="aspectFill"></image>
				</view>
				<view class="jiangqie-listtitle">
					<text>{{item.title}}</text>
					<image v-if="item.icon" :src="item.icon" mode="aspectFill"></image>
				</view>
			</view>
			<view v-if="item.tp=='feedback'" :class="'jiangqie-listblock ' + (item.line=='yes'?'jiangqie-listblock-line':'')">
				<view class="jiangqie-listcont">
					<image src="/static/images/more.png" mode="aspectFill"></image>
				</view>
				<button open-type="feedback" class="jiangqie-listtitle">
					<text>{{item.title}}</text>
					<image v-if="item.icon" :src="item.icon" mode="aspectFill"></image>
				</button>
			</view>
			<view v-if="item.tp=='contact'" :class="'jiangqie-listblock ' + (item.line=='yes'?'jiangqie-listblock-line':'')">
				<view class="jiangqie-listcont">
					<image src="/static/images/more.png" mode="aspectFill"></image>
				</view>
				<button open-type="contact" class="jiangqie-listtitle">
					<text>{{item.title}}</text>
					<image v-if="item.icon" :src="item.icon" mode="aspectFill"></image>
				</button>
			</view>
			<view v-if="item.tp=='clear'" :class="'jiangqie-listblock ' + (item.line=='yes'?'jiangqie-listblock-line':'')" @tap.stop="handlerClearCache">
				<view class="jiangqie-listcont">
					<image src="/static/images/more.png" mode="aspectFill"></image>
				</view>
				<view class="jiangqie-listtitle">
					<text>{{item.title}}</text>
					<image v-if="item.icon" :src="item.icon" mode="aspectFill"></image>
				</view>
			</view>
			<view v-if="item.tp=='page'" :class="'jiangqie-listblock ' + (item.line=='yes'?'jiangqie-listblock-line':'')" :data-page_id="item.page_id" @tap.stop="handlerPageClick">
				<view class="jiangqie-listcont">
					<image src="/static/images/more.png" mode="aspectFill"></image>
				</view>
				<view class="jiangqie-listtitle">
					<text>{{item.title}}</text>
					<image v-if="item.icon" :src="item.icon" mode="aspectFill"></image>
				</view>
			</view>
			<view v-if="item.tp=='link'" :class="'jiangqie-listblock ' + (item.line=='yes'?'jiangqie-listblock-line':'')" :data-link="item.link" @tap.stop="handlerLinkClick">
				<view class="jiangqie-listcont">
					<image src="/static/images/more.png" mode="aspectFill"></image>
				</view>
				<view class="jiangqie-listtitle">
					<text>{{item.title}}</text>
					<image v-if="item.icon" :src="item.icon" mode="aspectFill"></image>
				</view>
			</view>
			<view v-else-if="item.tp=='split'" class="jiangqie-list-split"></view>
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
 * Help document: https://ijkxs.com/ky
 * github: https://github.com/longwenjunjie/jiangqie_kafei
 * gitee: https://gitee.com/longwenjunj/jiangqie_kafei
 * License：MIT
 * Copyright ️ 2020 ijkxs.com All rights reserved.
 */
const Auth = require("../../utils/auth");
const Api = require("../../utils/api.js");
const Rest = require("../../utils/rest");
import tuiLoading from "../../components/loading/loading";

export default {
  data() {
    return {
      setting: {},
      user: undefined,
      menu: {},
      default: {
        background: Api.JIANGQIE_BG_MY,
        menu: [//views,likes,favorites,comments,about,feedback,contact,clear,split,link,page
        {
          tp: 'views',
          icon: "/static/images/icon_view.png",
          title: '我的浏览',
          line: 1
        }, {
          tp: 'likes',
          icon: "/static/images/icon_like.png",
          title: '我的点赞',
          line: 1
        }, {
          tp: 'favorites',
          icon: "/static/images/icon_fav.png",
          title: '我的收藏',
          line: 1
        }, {
          tp: 'comments',
          icon: "/static/images/icon_com.png",
          title: '我的评论',
          line: 0
        }, {
          tp: 'split'
        }, {
          tp: 'about',
          icon: '',
          title: '关于我们',
          line: 1
        }, {
          tp: 'feedback',
          icon: '',
          title: '意见反馈',
          line: 1
        }, {
          tp: 'contact',
          icon: '',
          title: '在线客服',
          line: 1
        }, {
          tp: 'clear',
          icon: '',
          title: '清除缓存',
          line: 0
        }]
      },
      showPopLogin: false
    };
  },

  components: {
    tuiLoading
  },
  props: {},
  onShow: function (options) {
    let that = this;
    let user = Auth.getUser();
    that.setData({
      user: user
    });
    Rest.get(Api.JIANGQIE_SETTING_UCENTER).then(res => {
      let menu = that.default.menu;

      if (res.data.menu.length > 0) {
        menu = res.data.menu;
      }

      that.setData({
        setting: {
          background: res.data.background ? res.data.background : that.default.background,
          enable_comment: res.data.enable_comment
        },
        menu: menu
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
    handlerPostTrack: function (e) {
      if (!Auth.getUser()) {
        this.setData({
          showPopLogin: true
        });
        return;
      }

      let track = e.currentTarget.dataset.track;
      uni.navigateTo({
        url: '/pages/list/list?track=' + track
      });
    },
    handlerAbout: function (e) {
      uni.navigateTo({
        url: '/pages/about/about'
      });
    },
    handlerClearCache: function (e) {
      uni.showModal({
        title: '提示',
        content: '清除缓存 需要重新登录',

        success(res) {
          if (res.confirm) {
            uni.clearStorageSync();
            uni.showToast({
              title: '清除完毕'
            });
            uni.reLaunch({
              url: '/pages/index/index'
            });
          }
        }

      });
    },
    handlerLinkClick: function (e) {
      let link = e.currentTarget.dataset.link;

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
    },
    handlerPageClick: function (e) {
      let page_id = e.currentTarget.dataset.page_id;
      uni.navigateTo({
        url: '/pages/viewhtml/viewhtml?page_id=' + page_id
      });
    },
    handlerLoginCancelClick: function (e) {
      this.setData({
        showPopLogin: false
      });
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
@import "./ucenter.css";
</style>