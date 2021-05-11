<template>
<view>
<!--pages/login/login.wxml-->
<view :style="'background: url(' + background + ') no-repeat;background-size: 100% auto;'">
	<view class="jiangqie-login">
		<view class="jiangqie-app-info">
			<!--替换为小程序logo-->
			<image src="/static/images/default_avatar.jpg" mode="aspectFill"></image>
			<view class="jiangqie-app-name">{{title}}</view>
			<view class="jiangqie-app-slogan">授权登录可查看更多内容</view>
		</view>
		<view class="jiangqie-login-btn">
			<button @tap.stop="handlerCancelClick" class="jiangqie-login-btnl">取消</button>
			<button open-type="getUserInfo" @getuserinfo="handlerLoginClick" class="jiangqie-login-btnr">确定</button>
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
const Util = require("../../utils/util");
const Auth = require("../../utils/auth");
const Api = require("../../utils/api.js");
const Rest = require("../../utils/rest");
import tuiLoading from "../../components/loading/loading";

export default {
  data() {
    return {
      background: Api.JIANGQIE_BG_INDEX,
      title: ''
    };
  },

  components: {
    tuiLoading
  },
  props: {},
  onLoad: function (options) {
    this.setData({
      title: getApp().appName
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
    handlerCancelClick: function (e) {
      Util.navigateBack();
    },
    handlerLoginClick: function (e) {
      Auth.getWXUser().then(res => {
		  console.log(res)
        return Rest.get(Api.JIANGQIE_USER_LOGIN, {
          code: res.code,
          encrypted_data: encodeURIComponent(res.encryptedData),
          iv: encodeURIComponent(res.iv),// nickName: res.userInfo.nickName, //测试
          plantform: getApp().globalData.plantform

        });
      }).then(res => {
        let user = res.data;
        Auth.setUser(user);
        Util.navigateBack();
      });
    }
  }
};
</script>
<style>
@import "./login.css";
</style>