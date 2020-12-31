/*
 * 即刻学术小程序开源版 v1.2.2
 * Author: 即刻学术
 * Help document: https://geekscholar.net/ky
 * github: https://github.com/longwenjunjie/jiangqie_kafei
 * gitee: https://gitee.com/longwenjunj/jiangqie_kafei
 * License：MIT
 * Copyright ️ 2020 geekscholar.net All rights reserved.
 */

const Api = require('../../utils/api.js');
const Rest = require('../../utils/rest');
// const WxParse = require('../../components/wxParse/wxParse');

Page({

    data: {

    },

    onLoad: function (options) {
        let that = this;
        Rest.get(Api.JIANGQIE_POST_PAGE, {
            page_id: options.page_id
        }).then(res => {
            wx.setNavigationBarTitle({
                title: res.data.title,
                content:res.data.content
            })

            // WxParse.wxParse('article', 'html', res.data.content, that, 5);
        });
    },

    onShareAppMessage: function () {
        return {
            title: getApp().appName,
            path: 'pages/index/index',
        }
    },

    onShareTimeline: function () {
        return {
            title: getApp().appName,
        }
    },

})