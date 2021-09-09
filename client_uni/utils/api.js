/*
 * 酱茄小程序开源版
 * Author: 酱茄
 * Help document: https://www.jiangqie.com/ky
 * github: https://github.com/longwenjunjie/jiangqie_kafei
 * gitee: https://gitee.com/longwenjunj/jiangqie_kafei
 * Copyright © 2020-2021 www.jiangqie.com All rights reserved.
 */
const Config = require("./config.js");

function makeURL(module, action) {
	return `http://${Config.JQ_DOMAIN}/onetypecho/v1/${module}/${action}`;
}

module.exports = {
	/**
	 * 获取首页配置
	 */
	JIANGQIE_SETTING_HOME: makeURL('setting', 'home'),

	/**
	 * 获取热门配置
	 */
	JIANGQIE_SETTING_HOT: makeURL('setting', 'hot'),

	/**
	 * 获取分类配置
	 */
	JIANGQIE_SETTING_CATEGORY: makeURL('setting', 'category'),

	/**
	 * 获取我的配置
	 */
	JIANGQIE_SETTING_UCENTER: makeURL('setting', 'ucenter'),
	
	/**
	 * 获取登录配置
	 */
	JIANGQIE_SETTING_LOGIN: makeURL('setting', 'login'),

	/**
	 * 获取分类 只获取一级分类
	 */
	JIANGQIE_CATEGORY_INDEX: makeURL('category', 'index'),

	/**
	 * 获取最新文章列表
	 */
	JIANGQIE_POSTS_LAST: makeURL('posts', 'last'),

	/**
	 * 获取某个分类下文章
	 */
	JIANGQIE_POSTS_CATEGORY: makeURL('posts', 'category'),

	/**
	 * 获取某个TAG下文章
	 */
	JIANGQIE_POSTS_TAG: makeURL('posts', 'tag'),

	/**
	 * 获取热门文章列表
	 */
	JIANGQIE_POSTS_HOT: makeURL('posts', 'hot'),

	/**
	 * 搜索文章
	 */
	JIANGQIE_POSTS_SEARCH: makeURL('posts', 'search'),

	/**
	 * 热门搜索
	 */
	JIANGQIE_POSTS_SEARCH_HOT: makeURL('posts', 'search/hot'),

	/**
	 * [我的]文章
	 */
	JIANGQIE_POSTS_MY: makeURL('posts', 'my'),

	/**
	 * 获取文章详情
	 */
	JIANGQIE_POST_DETAIL: makeURL('posts', 'detail'),

	/**
	 * 获取页面详情
	 */
	JIANGQIE_POST_PAGE: makeURL('posts', 'page'),

	/**
	 * 获取微信小程序码
	 */
	JIANGQIE_POST_WX_ACODE: makeURL('posts', 'wxacode'),
	
	/**
	 * 获取百度小程序码
	 */
	JIANGQIE_POST_BD_ACODE: makeURL('posts', 'bdacode'),
	
	/**
	 * 获取QQ小程序码
	 */
	JIANGQIE_POST_QQ_ACODE: makeURL('posts', 'qqacode'),

	/**
	 * 用户登录
	 */
	JIANGQIE_USER_LOGIN: makeURL('user', 'login3'),
	
	/**
	 * 用户登录 演示
	 */
	JIANGQIE_USER_LOGIN_TEST: makeURL('user', 'logintest'),

	/**
	 * 用户数据
	 */
	JIANGQIE_USER_INDEX: makeURL('user', 'index'),

	/**
	 * 用户 点赞文章
	 */
	JIANGQIE_USER_LIKE: makeURL('user', 'like'),

	/**
	 * 用户 收藏文章
	 */
	JIANGQIE_USER_FAVORITE: makeURL('user', 'favorite'),

	/**
	 * 文章评论
	 */
	JIANGQIE_COMMENT_INDEX: makeURL('comment', 'index'),

	/**
	 * 添加评论
	 */
	JIANGQIE_COMMENT_ADD: makeURL('comment', 'add'),

	/**
	 * 删除评论
	 */
	JIANGQIE_COMMENT_DELETE: makeURL('comment', 'delete'),
	
	JIANGQIE_CIRCLE_HOT: makeURL('circle', 'hot'),
	JIANGQIE_CIRCLE_HOT_LIST: makeURL('circle', 'hot_list'),
	JIANGQIE_CIRCLE_FOLLOWUSER: makeURL('circle', 'follow_user'),
	JIANGQIE_CIRCLE_CANCELFUSER: makeURL('circle', 'cancelf_user'),
};
