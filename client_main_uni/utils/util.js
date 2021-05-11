/*
 * 即刻学术小程序开源版 v1.2.2
 * Author: 即刻学术
 * Help document: https://ijkxs.com/ky
 * github: https://github.com/longwenjunjie/jiangqie_kafei
 * gitee: https://gitee.com/longwenjunj/jiangqie_kafei
 * License：MIT
 * Copyright ️ 2020 ijkxs.com All rights reserved.
 */
function navigateBack() {
  uni.navigateBack({
    delta: 1,
    fail: function (res) {
      uni.switchTab({
        url: '/pages/index/index'
      });
    }
  });
}

module.exports = {
  navigateBack
};