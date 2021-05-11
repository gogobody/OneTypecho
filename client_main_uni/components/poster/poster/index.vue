<template>
<view>
<view @tap="onCreate">
	<slot></slot>
</view>
<we-canvas id="poster" @success="onCreateSuccess" @fail="onCreateFail"></we-canvas>
</view>
</template>

<script>
import weCanvas from "../index/index";

export default {
  data() {
    return {};
  },

  components: {
    weCanvas
  },
  props: {
    config: {
      type: Object,
      default: () => ({})
    },
    preload: {
      // 是否预下载图片资源
      type: Boolean,
      default: false
    },
    hideLoading: {
      // 是否隐藏loading
      type: Boolean,
      default: false
    }
  },

  mounted() {
    if (this.preload) {
      const poster = this.selectComponent('#poster').$vm;
      this.downloadStatus = 'doing';
      console.log("config", this.config);
      poster.downloadResource(this.config).then(() => {
        this.downloadStatus = 'success';
        this.trigger('downloadSuccess');
      }).catch(e => {
        this.downloadStatus = 'fail';
        this.trigger('downloadFail', e);
      });
    }
  },

  methods: {
    trigger(event, data) {
      if (this.listener && typeof this.listener[event] === 'function') {
        this.listener[event](data);
      }
    },

    once(event, fun) {
      if (typeof this.listener === 'undefined') {
        this.listener = {};
      }

      this.listener[event] = fun;
    },

    downloadResource(reset) {
      return new Promise((resolve, reject) => {
        if (reset) {
          this.downloadStatus = null;
        }

        const poster = this.selectComponent('#poster').$vm;
        if (this.downloadStatus && this.downloadStatus !== 'fail') {
          if (this.downloadStatus === 'success') {
            resolve();
          } else {
            this.once('downloadSuccess', () => resolve());
            this.once('downloadFail', e => reject(e));
          }
        } else {
          poster.downloadResource(this.config).then(() => {
            this.downloadStatus = 'success';
            resolve();
          }).catch(e => reject(e));
        }
      });
    },

    onCreate(reset = false) {
      !this.hideLoading && uni.showLoading({
        mask: true,
        title: '生成中'
      });
      return this.downloadResource(typeof reset === 'boolean' && reset).then(() => {
        !this.hideLoading && uni.hideLoading();
        const poster = this.selectComponent('#poster').$vm;
        poster.create(this.config);
      }).catch(err => {
        !this.hideLoading && uni.hideLoading();
        uni.showToast({
          icon: 'none',
          title: err.errMsg || '生成失败'
        });
        console.error("onCreate err",err);
        this.$emit('fail', {
          detail: err
        });
      });
    },

    onCreateSuccess(e) {
      const {
        detail
      } = e;
	  // console.log("onCreateSuccess succ",e);
      this.$emit('success', {
        detail: e
      });
    },

    onCreateFail(err) {
      console.error(err);
      this.$emit('fail', {
        detail: err
      });
    }

  }
};
</script>
