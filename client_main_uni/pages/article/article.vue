<template>
<view>
<!--pages/article/article.wxml-->
<!--<import src="../../templates/nodata/nodata.wxml"></import>-->
<!--<import src="../../templates/poplogin/poplogin.wxml"></import>-->
<view class="loading-component" v-if="isLoadingDetail">
    <view class="j-header loading">
        <view class="title"></view>
        <view class="meta">
            <view v-for="(item, index) in 3" :key="index" class="item"></view>
        </view>
    </view>
    <view class="j-markdown loading">
        <image src="/static/images/loading.png"></image>loading...
    </view>
</view>
<view class="container jiangqie-page-view">
	<view class="jiangqie-page-head">
		<view class="jiangqie-page-title">
			<text>{{post.title}}</text>
			<view class="jiangqie-page-cmt">
				<view class="jiangqie-page-cmt-info">
					<image src="/static/images/ctm2.png" mode="widthFix" class="jiangqie-page-icon"></image>{{post.views}}
				</view>
				<text>{{post.time}}</text>
				<text v-for="(cat, cati) in post.cats" :key="cati" class="category">{{cat.name}}</text>
			</view>
		</view>
	</view>
	<view v-if="official" class="jiangqie-official-mod">
		<official-account></official-account>
	</view>
	<view class="jiangqie-page-body">

		<!-- <template is="wxParse" data="{{wxParseData:article.nodes}}" /> -->
		<wemark class="page-content" :md="post.content" link highlight type="wemark"></wemark>

		<view class="jiangqie-page-body-end">
			<text>- The End -</text>
		</view>
		<view class="jiangqie-page-body-tag">
			<text v-for="(item, index) in post.tags" :key="index" :data-id="item.id" :data-tag="item.name" @tap.stop="handlerTagClick">{{item.name}}</text>
		</view>
		<view class="jiangqie-page-laud">
			<view class="jiangqie-page-laud-contrl">
				<view v-if="post_like==1" class="jiangqie-page-laud-btn" @tap.stop="handlerLikeClick">
					<image src="/static/images/laud_on.png" mode="widthFix"></image>
					<text>已赞过</text>
				</view>
				<view v-else class="jiangqie-page-laud-btn" @tap.stop="handlerLikeClick">
					<image src="/static/images/laud.png" mode="widthFix"></image>
					<text>点个赞</text>
				</view>
				<!-- <button open-type="share" class="jiangqie-page-laud-btn">
					<image src="/static/share.png" mode="widthFix"></image>
					<text>分享好友</text>
				</button> -->
				<button class="jiangqie-page-laud-btn" @tap.stop="sharePosterClick">
					<image src="/static/images/share.png" mode="widthFix"></image>
					<text>海报分享</text>
				</button>
			</view>
			<view class="jiangqie-page-laud-list">
				<view class="jiangqie-page-laud-list-title">- {{like_list.length}}人已赞 -</view>
				<view class="jiangqie-page-laud-list-block">
					<image v-for="(item, index) in like_list" :key="index" :src="item.length>0?item:'/static/images/default_avatar.jpg'" mode="aspectFill"></image>
				</view>
			</view>
		</view>
	</view>
	<view class="jiangqie-page-cmtbox" v-if="post.switch_comment==1">
		<view class="jiangqie-page-cmt-title">
			评论<text>{{comment_count + comment_count_change}}</text>
		</view>
		<block v-if="comments.length>0">
			<view v-for="(item, index) in comments" :key="index" class="jiangqie-page-cmt-content">
				<view class="jiangqie-page-cmt-avatar">
					<image :src="item.user.avatar.length>0?item.user.avatar:'/static/images/default_avatar.jpg'" mode="aspectFill"></image>
				</view>
				<view class="jiangqie-page-cmt-head">
					{{item.user.name}}
					<text class="jiangqie-page-cmt-time">{{item.time}}</text>
					<text v-if="item.approved!=1" class="jiangqie-page-cmt-time">待审核</text>
					<text class="comment-action" :data-id="item.id" @tap.stop="handlerCommentReplyClick">回复</text>
					<text v-if="item.user.is_me==1" class="comment-action" :data-id="item.id" @tap.stop="handlerCommentDeleteClick">删除</text>
				</view>
				<view class="jiangqie-page-cmt-text">

					<block class="jiangqie-page-cmt-text-world">
						<rich-text v-if="!item.isCanvas" class="text" :nodes="item.content"></rich-text>
						<view class="canvas" v-else @tap="previewImage">
							<image :src="item.content"></image>
						</view>
					</block>

					<view class="jiangqie-page-cmt-replay">
						<view v-for="(reply, index2) in item.replys" :key="index2" class="jiangqie-page-cmt-replay-box">
							<view class="jiangqie-page-cmt-replay-nametime">
								{{reply.user.name}}
								<text class="jiangqie-page-cmt-replay-time">{{reply.time}}</text>
								<text v-if="reply.approved!=1" class="jiangqie-page-cmt-time">待审核</text>
								<text v-if="reply.user.is_me==1" class="comment-action" :data-id="reply.id" @tap.stop="handlerCommentDeleteClick">删除</text>
							</view>
							<view class="jiangqie-page-cmt-replay-world">{{reply.content}}</view>
						</view>
					</view>
				</view>
			</view>
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

<!-- 操作栏 -->
<view class="tui-operation" v-if="!show_comment_submit && post.switch_comment==1">
	<view class="tui-operation-left tui-col-7 tui-height-full tui-ptop-zero">
		<view class="tui-btn-comment" @tap.stop="handlerCommentClick">发表你的评论...</view>
	</view>
	<view class="tui-operation-right tui-right-flex tui-col-5">
		<view class="tui-operation-item" hover-class="opcity" hover-stay-time="150" @tap="handlerCommentClick">
			<image src="/static/images/btn_com.png" mode="widthFix"></image>
			<text>{{comment_count + comment_count_change}}</text>
		</view>
		<view class="tui-operation-item" @tap.stop="handlerFavoriteClick">
			<block v-if="post_favorite==1">
				<image src="/static/images/btn_fav_on.png" mode="widthFix"></image>
			</block>
			<block v-else>
				<image src="/static/images/btn_fav.png" mode="widthFix"></image>
			</block>
		</view>
		<button open-type="share" class="tui-operation-item" hover-class="opcity" hover-stay-time="150">
			<image src="/static/images/btn_share.png" mode="widthFix"></image>
		</button>
	</view>
</view>

<!-- 发表评论 -->
<view class="textareacontent" v-if="show_comment_submit">
	<form @submit="handlerCommentSubmit">
		<view class="textheaders">
			<view @tap.stop="handlerCancelClick" class="cancel">取消</view>
			<button form-type="submit" class="publish">发布</button>
		</view>
		<view class="tips">
			<text class="tips-content">建议设置您的邮箱以便及时收到回复。</text>
			<input :value="user_mail" @input="handleInputMail" adjust-position="false" type="text" placeholder="请输入邮箱"></input>
		</view>
		<view class="textareaBox">
			<view class="textNum">{{comment_content.length}}/200</view>
			<textarea disable-default-padding autoFocus="true" name="inputComment" @input="handlerContentInput" class="textareaInput" maxlength="200" show-confirm-bar="false" :placeholder="placeholder"></textarea>
		</view>
	</form>
</view>
<view @tap.stop="handlerCancelClick" class="pagemake" v-if="show_comment_submit"></view>

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
<view class="jiangqie-goback-btn" @tap="jumpBtn">
    <image src="/static/images/goback.png" mode="widthFix"></image>
</view>
<!--加载框 start-->
<tui-loading :visible="loading"></tui-loading>
<!--加载框 end-->
<!-- 海报组件 -->
<jiangqie-poster id="poster" :hide-loading="false" :preload="false" :config="posterConfig" @success="onPosterSuccess" @fail="onPosterFail"></jiangqie-poster>
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
const Util = require("../../utils/util");
const Api = require("../../utils/api.js");
const Rest = require("../../utils/rest");
const Auth = require("../../utils/auth"); // const WxParse = require('../../components/wxParse/wxParse');
// const WxParse = require('../../components/wxParse/wxParse');
const Poster = require("../../components/poster/poster/poster");
import tuiLoading from "../../components/loading/loading";
import tuiLoadmore from "../../components/loadmore/loadmore";
import tuiNomore from "../../components/nomore/nomore";
import jiangqiePoster from "../../components/poster/poster/index";
import wemark from "../../wemark/wemark";

export default {
  data() {
    return {
      post: {},
      post_like: 0,
      post_favorite: 0,
      comment_count: 0,
      comments: [],
      loadding: false,
      pullUpOn: true,
      loaded: false,
      show_comment_submit: false,
      comment_content: '',
      comment_count_change: 0,
      isLoadingDetail: false,
      user_mail: '',
      like_list: "",
      posterConfig: "",
      showPopLogin: false,
	  post_id: 0,
	  comment_id: 0,
	  //小程序码
	  wxacode: '',
	  //返回页面是否需要刷新
	  needRefresh: true,
    };
  },

  components: {
    tuiLoading,
    tuiLoadmore,
    tuiNomore,
    jiangqiePoster,
    wemark
  },
  props: {},
  onLoad: function (options) {
    if (options.scene) {
      this.post_id = decodeURIComponent(options.scene);
    } else if (options.post_id) {
      this.post_id = options.post_id;
    }

    if (Auth.getUser().mail) {
      this.setData({
        user_mail: Auth.getUser().mail
      });
    } //小程序码


    this.loadWxacode();
  },
  onShow: function () {
    // console.log(this.needRefresh)
    

    if (!this.needRefresh) {
      this.needRefresh = true;
	  this.setData({
	    isLoadingDetail: false
	  });
      return;
    }else{
		this.setData({
		  isLoadingDetail: true
		});
	}

    // console.log(this.needRefresh);
    let that = this;
    Rest.get(Api.JIANGQIE_POST_DETAIL, {
      post_id: that.post_id
    }).then(res => {
      uni.setNavigationBarTitle({
        title: res.data.title
      });
      that.setData({
        post: res.data,
        post_like: res.data.user.islike,
        post_favorite: res.data.user.isfavorite,
        comment_count: Number(res.data.comment_count),
        like_list: res.data.like_list,
        isLoadingDetail: false
      }); // WxParse.wxParse('article', 'html', res.data.content, that, 5);
    });
    this.loadComments(true);
  },
  onReachBottom: function () {
    if (!this.pullUpOn) {
      return;
    }

    this.loadComments(false);
  },
  onShareAppMessage: function () {
    return {
      title: this.post.title,
      imageUrl: this.post.thumbnail,
      path: 'pages/article/article?post_id=' + this.post_id
    };
  },
  methods: {
    onShareTimeline: function () {
      return {
        title: this.post.title,
        query: 'post_id=' + this.post_id,
        imageUrl: this.post.thumbnail
      };
    },

    /**
     * 海报分享
     */
    sharePosterClick: function (e) {
      let posterConfig = {
        width: 750,
        height: 1334,
        backgroundColor: '#E6372F',
        debug: false,
        pixelRatio: 1,
        blocks: [{
          width: 690,
          height: 1000,
          x: 30,
          y: 234,
          backgroundColor: '#FFFFFF'
        }],
        texts: [{
          x: 375,
          y: 120,
          baseLine: 'middle',
          textAlign: 'center',
          text: this.post.title,
          width: 600,
          fontSize: 38,
          color: '#FFFFFF'
        }, {
          x: 70,
          y: 780,
          fontSize: 28,
          lineHeight: 40,
          baseLine: 'middle',
          text: this.post.excerpt,
          width: 600,
          lineNum: 3,
          color: '#000000',
          zIndex: 200
        }, {
          x: 360,
          y: 1170,
          baseLine: 'middle',
          textAlign: 'center',
          text: getApp().appName,
          fontSize: 28,
          color: '#888888',
          zIndex: 200
        }],
        images: [{
          width: 690,
          height: 520,
          x: 30,
          y: 200,
          url: this.post.thumbnail,
          zIndex: 100
        }, {
          width: 200,
          height: 200,
          x: 275,
          y: 920,
          url: this.wxacode
        }]
      };
      this.setData({
        posterConfig: posterConfig
      }, () => {
        Poster.create(true); // 入参：true为抹掉重新生成 
      });
    },

    /**
     * 画报生成成功
     */
    onPosterSuccess(e) {
      this.needRefresh = false;
	  console.log(e)
      const {
        detail
      } = e;
	  // console.log("onPosterSuccess",e)
      uni.previewImage({
        current: detail,
        urls: [detail]
      });
    },

    /**
     * 画报生成失败
     */
    onPosterFail(err) {
      console.error(err);
    },

    /**
     * 文章中a标签点击
     */
    wxParseTagATap: function (e) {
      uni.setClipboardData({
        data: e.currentTarget.dataset.src
      });
    },

    /**
     * 点击 TAG
     */
    handlerTagClick: function (e) {
      let tag_id = e.currentTarget.dataset.id;
      let tag = e.currentTarget.dataset.tag;
      uni.navigateTo({
        url: '/pages/list/list?title=' + tag + '&tag_id=' + tag_id
      });
    },

    /**
     * 跳转返回
     */
    jumpBtn: function (options) {
      Util.navigateBack();
    },

    /**
     * 文章 点赞
     */
    handlerLikeClick: function (e) {
      let that = this;
      Rest.get(Api.JIANGQIE_USER_LIKE, {
        post_id: that.post.id
      }).then(res => {
        let avatar = Auth.getUser().avatar;
        var index = that.like_list.indexOf(avatar);

        if (index > -1) {
          that.like_list.splice(index, 1);
        } else {
          that.like_list.unshift(avatar);
        }

        that.setData({
          post_like: that.post_like == 1 ? 0 : 1,
          like_list: that.like_list
        });
      });
    },

    /**
     * 评论 弹框
     */
    handlerCommentClick: function (e) {
      this.comment_id = 0;
      this.setData({
        show_comment_submit: true
      });
    },

    /**
     * 评论 取消
     */
    handlerCancelClick: function (e) {
      this.setData({
        show_comment_submit: false
      });
    },

    /**
     * 评论 提交
     */
    handlerCommentSubmit: function (e) {
      let that = this;
	  if(!Auth.isLogin()){
		  that.setData({
			  showPopLogin:true
		  })
		  return;
	  }
      Rest.get(Api.JIANGQIE_COMMENT_ADD, {
        post_id: that.post_id,
        parent_id: that.comment_id,
        content: that.comment_content,
        user_mail: that.user_mail
      }).then(res => {
        if (res.code == 0) {
          that.setData({
            comment_count_change: that.comment_count_change + (res.data.comment_verify == 1 ? 0 : 1),
            show_comment_submit: false
          });
          that.loadComments(true);
        } else {
          uni.showToast({
            icon: 'none',
            title: res.msg || '生成失败'
          });
        }
      });
    },

    /**
     * 评论 回复
     */
    handlerCommentReplyClick: function (e) {
      this.comment_id = e.currentTarget.dataset.id;
      this.setData({
        show_comment_submit: true
      });
    },

    /**
     * 评论 删除
     */
    handlerCommentDeleteClick: function (e) {
      let that = this;
      uni.showModal({
        title: '提示',
        content: '确定要删除吗？',

        success(res) {
          if (res.confirm) {
            let comment_id = e.currentTarget.dataset.id;
            Rest.get(Api.JIANGQIE_COMMENT_DELETE, {
              post_id: that.post_id,
              comment_id: comment_id
            }).then(res => {
              that.setData({
                comment_count_change: that.comment_count_change - 1
              });
              that.loadComments(true);
            });
          }
        }

      });
    },

    /**
     * 评论输入
     */
    handlerContentInput: function (e) {
      this.setData({
        comment_content: e.detail.value
      });
    },

    /**
     * 文章 收藏
     */
    handlerFavoriteClick: function (e) {
      let that = this;
      Rest.get(Api.JIANGQIE_USER_FAVORITE, {
        post_id: that.post.id
      }).then(res => {
        that.setData({
          post_favorite: that.post_favorite == 1 ? 0 : 1
        });
      });
    },

    /**
     * 加载小程序码
     */
    loadWxacode: function () {
      let that = this;
      Rest.get(Api.JIANGQIE_POST_WXACODE, {
        post_id: that.post_id,
		plantform: getApp().globalData.plantform
      }).then(res => {
        that.wxacode = res.data;
		that.setData({
		  loaded: true,
		  loadding: false,
		  })
      }, err => {
        console.log(err);
		that.setData({
		  loaded: true,
		  loadding: false,
		  })
      });
    },

    /**
     * 加载 评论
     */
    loadComments: function (refresh) {
      let that = this;
      that.setData({
        loadding: true
      });
      let offset = 0;

      if (!refresh) {
        offset = that.comments.length;
      }

      Rest.get(Api.JIANGQIE_COMMENT_INDEX, {
        post_id: that.post_id,
        offset: offset
      }).then(res => {
        let data = res.data.comments;
        data.forEach(item => {
          if (/\{!\{.*\}!\}/.test(item.content)) {
            item.isCanvas = true;
            item.content = item.content.replace(/{!{/, '').replace(/}!}/, '');
          } else {
            item.isCanvas = false;
          }

          if (item.replys) {
            item.replys.forEach(_item => {
              if (/\{!\{.*\}!\}/.test(_item.content)) {
                _item.isCanvas = true;
                _item.content = _item.content.replace(/{!{/, '').replace(/}!}/, '');
              } else {
                _item.isCanvas = false;
              }
            });
          }
        });
        that.setData({
          loaded: true,
          loadding: false,
          comments: refresh ? data : that.comments.concat(data),
          pullUpOn: data.length >= Constants.JQ_PER_PAGE_COUNT,
          user_mail: res.data.user_mail
        });
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
    },

    /* 双向绑定 */
    handleInputMail(e) {
      this.setData({
        user_mail: e.detail.value
      });
      return e.detail.value;
    } // previewImage(e) {
    //     let urls = [];
    //     urls[0] = e.currentTarget.dataset.src;
    //     wx.previewImage({urls});
    // }


  }
};
</script>
<style>
@import "./article.css";
</style>