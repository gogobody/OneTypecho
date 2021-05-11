<template>
<view :class="'lxy-nav-bar ' + extClass" :style="'background: ' + backgroundColorTop + ';height: ' + (navBarHeight+ navBarExtendHeight) + 'px;'">
	<view :class="'lxy-nav-bar__placeholder ' + (ios ? 'ios' : 'android')" :style="'padding-top: ' + (navBarHeight+ navBarExtendHeight) + 'px;visibility: hidden;'"></view>
	<view :class="'lxy-nav-bar__inner ' + (ios ? 'ios' : 'android')" :style="navigationbarinnerStyle + '' + displayStyle">
		<view class="lxy-nav-bar__left" :style="navBarLeft">
			<block v-if="back&&!home">
				<view @tap="goback" :class="'lxy-nav-bar__button lxy-nav-bar__btn_goback ' + iconTheme"></view>
			</block>
			<block v-if="!back&&home">
				<view @tap="gohome" :class="'lxy-nav-bar__button lxy-nav-bar__btn_gohome ' + iconTheme"></view>
			</block>
			<block v-else-if="back&&home">
				<view :class="'lxy-nav-bar__buttons ' + (ios ? 'ios' : 'android')" v-if="home">
					<view @tap="goback" :class="'lxy-nav-bar__button lxy-nav-bar__btn_goback ' + iconTheme" v-if="back"></view>
					<view @tap="gohome" :class="'lxy-nav-bar__button lxy-nav-bar__btn_gohome ' + iconTheme"></view>
				</view>
			</block>
			<block v-else>
				<slot name="left"></slot>
			</block>
		</view>
		<view class="lxy-nav-bar__center">
			<block v-if="title">
				<text>{{title}}</text>
			</block>
			<view class="lxy-nav-bar-search" :style="'height:' + capsulePosition.height + 'px;'" v-else-if="searchBar" @tap="search">
				<view class="lxy-nav-bar-search__icon"></view>
				<view class="lxy-nav-bar-search__input">{{searchText}}</view>
			</view>
			<!-- 暂时不开发标题loading. 看后期需要 -->
			<!-- <view wx:if="{{loading}}" class="lxy-nav-bar__loading">
                <view class="lxy-loading" style="width:{{size.width}}rpx;height:{{size.height}}rpx;"></view>
            </view> -->
			<block v-else>
				<slot name="center"></slot>
			</block>
		</view>
		<view class="lxy-nav-bar__right">
			<slot name="right"></slot>
		</view>
	</view>
</view>
</template>

<script>

export default {
  data() {
    return {
      navigationbarinnerStyle: "",
      navBarLeft: "",
      navBarHeight: "",
      capsulePosition: "",
      navBarExtendHeight: "",
      ios: ""
    };
  },

  components: {},
  props: {
    extClass: {
      type: String,
      default: ''
    },
    background: {
      type: String
    },
    // backgroundColorTop: {
    //     type: String,
    //     // value: 'rgba(224, 48, 31, 100)',
    //     observer: '_showChangeBackgroundColorTop'
    // },
    color: {
      type: String,
      default: 'rgba(255, 255, 255, 1)'
    },
    title: {
      type: String,
      default: ''
    },
    searchText: {
      type: String,
      default: '点我搜索'
    },
    searchBar: {
      type: Boolean,
      default: false
    },
    back: {
      type: Boolean,
      default: false
    },
    home: {
      type: Boolean,
      default: false
    },
    iconTheme: {
      type: String,
      default: 'black'
    },
    animated: {
      type: Boolean,
      default: true
    },
    show: {
      type: Boolean,
      default: true
    },
    delta: {
      type: Number,
      default: 1
    }
  },
  watch: {
    background: {
      handler: 'showChangeFunc',
      immediate: true
    },
    show: {
      handler: 'showChangeFunc',
      immediate: true
    }
  },
  options: {
    multipleSlots: true,
    addGlobalClass: true
  },

  created() {
    this.getSystemInfo();
  },

  beforeMount() {
    this.setStyle(); // 设置样式
  },

  onPageShow() {
    if (getApp().globalSystemInfo.ios) {
      this.getSystemInfo();
      this.setStyle(); // 设置样式1
    }
  },

  onPageHide() {},

  methods: {
    setStyle(life) {
		if(!getApp().globalSystemInfo){
			this.getSystemInfo()
		}
      const {
        statusBarHeight,
        navBarHeight,
        capsulePosition,
        navBarExtendHeight,
        ios,
        windowWidth
      } = getApp().globalSystemInfo;
      const {
        back,
        home,
        title
      } = this;
      const rightDistance = windowWidth - capsulePosition.right; // 胶囊按钮右侧到屏幕右侧的边距

      const leftWidth = windowWidth - capsulePosition.left; // 胶囊按钮左侧到屏幕右侧的边距

      const navigationbarinnerStyle = [`color: ${this.color}`, `background: ${this.background}`, `height:${navBarHeight + navBarExtendHeight}px`, `padding-top:${statusBarHeight}px`, `padding-right:${leftWidth}px`, `padding-bottom:${navBarExtendHeight}px`].join(';');
      let navBarLeft = [];

      if (back && !home || !back && home) {
        navBarLeft = [`width:${capsulePosition.width}px`, `height:${capsulePosition.height}px`].join(';');
      } else if (back && home || title) {
        navBarLeft = [`width:${capsulePosition.width}px`, `height:${capsulePosition.height}px`, `margin-left:${rightDistance}px`].join(';');
      } else {
        navBarLeft = ['width:auto', 'margin-left:0px'].join(';');
      }

      if (life === 'created') {
        this.data = {
          navigationbarinnerStyle,
          navBarLeft,
          navBarHeight,
          capsulePosition,
          navBarExtendHeight,
          ios
        };
      } else {
        this.setData({
          navigationbarinnerStyle,
          navBarLeft,
          navBarHeight,
          capsulePosition,
          navBarExtendHeight,
          ios
        });
      }
    },

    showChangeFunc() {
      this.setStyle();
    },

    // 返回事件
    goback() {
      this.$emit('back', {
        detail: {
          delta: this.data.delta
        }
      });
    },

    gohome() {
      this.$emit('home', {
        detail: {}
      });
    },

    search() {
      this.$emit('search', {
        detail: {}
      });
    },

    checkRect(rect) {
      // 胶囊信息4种任一属性为0返回true
      return !rect.width || !rect.top || !rect.left || !rect.height;
    },

    getMenuButtonBoundingClientRect(systemInfo) {
      const ios = !!(systemInfo.system.toLowerCase().search('ios') + 1);
      let rect;

      try {
        rect = uni.getMenuButtonBoundingClientRect ? uni.getMenuButtonBoundingClientRect() : null;

        if (rect === null) {
          throw new Error('getMenuButtonBoundingClientRect error');
        } // 取值为0的情况  有可能width不为0 top为0的情况


        if (this.checkRect(rect)) {
          throw new Error('getMenuButtonBoundingClientRect error');
        }
      } catch (error) {
        let gap = ''; // 胶囊按钮上下间距 使导航内容居中

        let width = 96; // 胶囊的宽度

        if (systemInfo.platform === 'android') {
          gap = 8;
          width = 96;
        } else if (systemInfo.platform === 'devtools') {
          if (ios) {
            gap = 5.5; // 开发工具中ios手机
          } else {
            gap = 7.5; // 开发工具中android和其他手机
          }
        } else {
          gap = 4;
          width = 88;
        }

        if (!systemInfo.statusBarHeight) {
          // 开启wifi的情况下修复statusBarHeight值获取不到
          systemInfo.statusBarHeight = systemInfo.screenHeight - systemInfo.windowHeight - 20;
        }

        rect = {
          // 获取不到胶囊信息就自定义重置一个
          bottom: systemInfo.statusBarHeight + gap + 32,
          height: 32,
          left: systemInfo.windowWidth - width - 10,
          right: systemInfo.windowWidth - 10,
          top: systemInfo.statusBarHeight + gap,
          width
        };
      }

      return rect;
    },

    getSystemInfo() {
      const app = getApp();

      if (app.globalSystemInfo && !app.globalSystemInfo.ios) {
        return app.globalSystemInfo;
      } else {
        const systemInfo = uni.getSystemInfoSync();
        const ios = !!(systemInfo.system.toLowerCase().search('ios') + 1);
        const rect = this.getMenuButtonBoundingClientRect(systemInfo);
        let navBarHeight = '';

        if (!systemInfo.statusBarHeight) {
          systemInfo.statusBarHeight = systemInfo.screenHeight - systemInfo.windowHeight - 20;

          navBarHeight = function () {
            const gap = rect.top - systemInfo.statusBarHeight;
            return 2 * gap + rect.height;
          }();

          systemInfo.statusBarHeight = 0;
          systemInfo.navBarExtendHeight = 0; // 下方扩展4像素高度 防止下方边距太小
        } else {
          navBarHeight = function () {
            const gap = rect.top - systemInfo.statusBarHeight;
            return systemInfo.statusBarHeight + 2 * gap + rect.height;
          }();

          if (ios) {
            systemInfo.navBarExtendHeight = 4; // 下方扩展4像素高度 防止下方边距太小
          } else {
            systemInfo.navBarExtendHeight = 0;
          }
        }

        systemInfo.navBarHeight = navBarHeight; // 导航栏高度不包括statusBarHeight

        systemInfo.capsulePosition = rect;
        systemInfo.ios = ios; // 是否ios

        app.globalSystemInfo = systemInfo; // 将信息保存到全局变量中,后边再用就不用重新异步获取了
        // console.log('systemInfo', systemInfo);

        return systemInfo;
      }
    }

  }
};
</script>
<style>
@import "./navbar.css";
</style>