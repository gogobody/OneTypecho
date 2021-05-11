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
const Auth = require("./utils/auth.js");

export default {
  globalData: {
    appName: '即刻学术',
    hasLogin: false,
    openid: null,
	// #ifdef APP-PLUS
	plantform: 'app',
	// #endif
	// #ifdef H5
	plantform: 'h5',
	// #endif
	// #ifdef MP-WEIXIN
	plantform: 'mp-weixin',
	// #endif
	// #ifdef MP-QQ
	plantform: 'mp-qq',
	// #endif	
	// #ifdef MP-ALIPAY
	plantform: 'mp-alipay',
	// #endif	
	// #ifdef MP-BAIDU
	plantform: 'mp-baidu',
	// #endif	
	// #ifdef MP-TOUTIAO
	plantform: 'mp-toutiao',
	// #endif
	
  },
  onLaunch: function () {
    Auth.checkSession();
  },
  methods: {}
};
</script>
<style>
@import "./app.css";
</style>