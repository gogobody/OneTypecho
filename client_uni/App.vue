<script>
	/*
	 * 酱茄小程序开源版 v1.5.3
	 * Author: 酱茄
	 * Help document: https://www.jiangqie.com/ky
	 * github: https://github.com/longwenjunjie/jiangqie_kafei
	 * gitee: https://gitee.com/longwenjunj/jiangqie_kafei
	 * Copyright © 2020-2021 www.jiangqie.com All rights reserved.
	 */
	import Vue from 'vue';
	
	const Auth = require("./utils/auth.js");

	export default {
		globalData: {
			appName: '酱茄Free'
		},
		
		onLaunch: function() {
			// Auth.checkSession();
		},
		
		methods: {

		}
	};
</script>
<style lang="scss">
	@import "uview-ui/index.scss";
	@import "static/css/iconfont.css";
	page {
		background: #FFFFFF;
	}
	uni-view, uni-text{
		box-sizing: initial;
	}
	button::after {
		border-radius: 0;
		border: none;
	}

	.jiangqie-loadmore {
		width: 48%;
		margin: 1.5em auto;
		line-height: 1.5em;
		font-size: 24rpx;
		text-align: center;
	}

	.jiangqie-nomore {
		position: relative;
		text-align: center;
		display: flex;
		justify-content: center;
		margin-top: 10rpx;
		padding-bottom: 44rpx;
	}

	.jiangqie-nomore::before {
		content: '';
		position: absolute;
		border-bottom: 1rpx solid #e5e5e5;
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
		width: 360rpx;
		top: 18rpx;
	}

	.jiangqie-text {
		position: absolute;
		color: #999999;
		font-size: 24rpx;
		text-align: center;
		padding: 0 18rpx;
		height: 36rpx;
		line-height: 36rpx;
		z-index: 1;
	}

	.jiangqie-nomore-dot {
		position: relative;
		text-align: center;
		display: flex;
		justify-content: center;
		margin-top: 10rpx;
		padding-bottom: 40rpx;
	}

	.jiangqie-nomore-dot::before {
		content: '';
		position: absolute;
		border-bottom: 1rpx solid #e5e5e5;
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
		width: 360rpx;
		top: 18rpx;
	}

	.jiangqie-dot-text {
		position: absolute;
		color: #e5e5e5;
		font-size: 10px;
		text-align: center;
		width: 50rpx;
		height: 36rpx;
		line-height: 36rpx;
		transform: scale(0.8);
		transform-origin: center center;
		z-index: 1;
	}
</style>
