/*
 * 即刻学术小程序开源版 v1.2.2
 * Author: 即刻学术
 * Help document: https://geekscholar.net/ky
 * github: https://github.com/longwenjunjie/jiangqie_kafei
 * gitee: https://gitee.com/longwenjunj/jiangqie_kafei
 * License：MIT
 * Copyright ️ 2020 geekscholar.net All rights reserved.
 */

const Constant = require('../../utils/constants');

Page({
    data: {
        version: Constant.JQ_VERSION
    },

    onLoad: function (options) {

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

    copy: function (e) {
        let text = e.currentTarget.dataset.text;
        wx.setClipboardData({
            data: text,
            success(res) {
                wx.getClipboardData({
                    success(res) {
                        wx.showToast({
                            title: '网址已复制',
                        });
                    }
                })
            }
        })
    },

    tipClick: function () {
        wx.showToast({
            icon: 'none',
            title: '即刻学术小程序开源版 v1.2.2',
        })
    }
})