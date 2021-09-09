<template>
	<view>
		<block v-for="(item,index) in list" :key="item.id">
			<view @click="jump(item.id)">
				<view class="post-item">
					<view class="post-item-top-user">
						<view @tap.stop="toUcenter(item.uid)">
							<image :src="item.userInfo.avatar"></image>
						</view>
						<view class="user-active">
							<text style="color:#333">{{item.userInfo.username.substring(0,12)}}</text>
							<view v-if="handle && sessionUid == topicUid" @tap.stop="onActive(item,index)" class="arrow-down">
								<u-icon size="40" name="arrow-down"></u-icon>
							</view>
						</view>
						<text style="font-size:10px">{{item.create_time | timeFrom}}</text>
					</view>
					<text class="discuss-title" :data-id="item.discuss_id" v-if="item.discuss_id > 0" @tap.stop="toDiscuss">#{{item.discuss_title}}</text>
					<view class="post-content">
						<rich-text class="post-text" :nodes="item.content"></rich-text>
						<block v-if="item.type == 1">
							<!--一张图片-->
							<block v-if="item.media.length == 1">
								<image :lazy-load="true" mode="aspectFill" class="img-style-1" :src="item.media[0]" @tap.stop="previewImage(item.media[0],item.media)"></image>
							</block>
							<!--二张图片-->
							<block v-if="item.media.length == 2">
								<view class="img-style-2">
									<image :lazy-load="true" v-for="(mediaItem, index2) in item.media" :key="index2" @tap.stop="previewImage(mediaItem,item.media)"
									 mode="aspectFill" :src="mediaItem"></image>
								</view>
							</block>
							<!--三张以上图片-->
							<block v-if="item.media.length > 2">
								<view class="img-style-3">
									<image :lazy-load="true" v-for="(mediaItem, index2) in item.media" :key="index2" @tap.stop="previewImage(mediaItem,item.media)"
									 mode="aspectFill" :src="mediaItem"></image>
								</view>
							</block>
						</block>
						<block v-if="item.type == 2 && item.media.length > 0">
							<video :show-center-play-btn="false" :controls="false" :src="item.media[0]"></video>
						</block>
					</view>
					<view class="p-footer">
						<view v-if="showTopic" @click.stop="jumpTopic(item.topicInfo.cate_id,item.topicInfo.topic_name)" class="topic-name">{{item.topicInfo.topic_name}}</view>
						<view class="p-right">
							<view class="p-item">
								<text class="iconfont icon-yuedu"></text>
								<text class="count">{{item.read_count}}</text>
							</view>
							<view class="p-item margin50">
								<text class="iconfont icon-pinglun"></text>
								<text class="count">{{item.comment_count}}</text>
							</view>
							<view class="p-item">
								<text class="iconfont icon-dianzan1"></text>
								<text class="count">{{item.fabulous_count}}</text>
							</view>
						</view>
					</view>
					<view class="address" v-if="item.address">
						<u-icon class="icon" name="map"></u-icon>
						<text>{{item.address}}</text>
					</view>
				</view>
			</view>
		</block>
		<!-- 操作弹窗 -->
		<u-action-sheet :list="actionList" v-model="showAction" @click="actionCon"></u-action-sheet>
		<!-- 加载状态 -->
		<block v-if="list.length === 0 && loadStatus == 'nomore'">
			<u-empty margin-top="100" text="暂无内容" mode="favor"></u-empty>
		</block>
		<block v-else>
			<view style="margin: 30rpx 0;">
				<u-loadmore :status="loadStatus" />
			</view>
		</block>
	</view>
</template>

<script>
	export default {
		props: {
			list: Array,
			loadStatus: String,
			handle: {
				default: false,
				type: Boolean
			},
			showTopic: {
				default: true,
				type: Boolean
			},
			uid: Number
		},
		data() {
			return {
				showAction: false,
				actionList: [{
					text: '关注TA'
				}, {
					text: '删除',
					color: 'red'
				}],
				choosePost: "",
				chooseIndex: "",
				sessionUid: "",
				topicUid: this.uid
			}
		},
		watch: {
			uid(n) {
				this.topicUid = n;
			}
		},
		created() {
			let userInfo = uni.getStorageSync("userInfo");
			if (userInfo) {
				this.sessionUid = userInfo.uid;
			}
		},
		methods: {
			onActive(postInfo, index) {
				this.showAction = true;
				this.choosePost = postInfo;
				this.chooseIndex = index;
			},
			actionCon(index) {
				if (index === 0) {
					this.follow();
				}

				if (index === 1) {
					this.postDel();
				}
			},
			postDel() {
				this.$H.post('post/del', {
					id: this.choosePost.id
				}).then(res => {
					if (res.code == 200) {
						this.list.splice(this.chooseIndex, 1)
					}
				})
			},
			follow() {
				this.$H.post('user/addFollow', {
					id: this.choosePost.uid
				}).then(res => {
					this.$u.toast(res.msg);
				})
			},
			previewImage(url, urls) {
				uni.previewImage({
					current: url, // 当前显示图片的http链接
					urls: urls // 需要预览的图片http链接列表
				})
			},
			jumpTopic(id, title) {
				uni.navigateTo({
					url: '/pages/list/list?cat_id=' + id + '&title='+ title
				})
			},
			jump(id) {
				uni.navigateTo({
					url: '/pages/article/article?post_id=' + id
				})
			},
			toDiscuss(e) {
				uni.navigateTo({
					url: "/pages/discuss/discuss?id=" + e.currentTarget.dataset.id
				})
			},
			toUcenter(uid) {
				uni.navigateTo({
					url: '/pages/ucenter/ucenter?uid=' + uid
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.post-item {
		width: 100%;
		height: auto;
		line-height: 1.5em;
		background: #fff;
		margin-bottom: 10rpx;
		overflow: hidden;
		padding-bottom: 20rpx;

		.post-content {
			.img-style-1 {
				display: block;
				width: 100%;
				max-height: 600rpx;
				border-radius: 5px;
				overflow: hidden;
			}

			.img-style-2 {
				display: flex;

				image {
					margin: 5rpx;
					border-radius: 5px;
					width: 100%;
					height: 294rpx;

				}
			}

			.img-style-3 {
				display: flex;
				flex-wrap: wrap;

				image {
					width: 31.3%;
					height: 200rpx;
					margin: 1%;
					border-radius: 5px;
				}
			}
		}
		.address{
			display: flex;
			margin-right: 20rpx;
			font-size: 20rpx;
			color: #999;
			align-items: center;
			.icon{
				margin-left: auto;
				margin-right: 10rpx;
			}
		}
	}

	.post-item-bottom {
		margin: 20rpx;
	}

	.post-item-bottom-right {
		width: 50%;
		height: 100%;
		float: right;
		text-align: center;
	}

	.post-item-bottom text {
		font-size: 12px;
		color: #999;
		margin-right: 10rpx;
	}

	.post-content {
		width: 85%;
		height: auto;
		margin-left: 85rpx;
		margin-top: 20rpx;
		font-size: 14px;
	}

	.post-item-top-user {
		height: 80rpx;
		line-height: 50rpx;
		margin-bottom: 20rpx;
		margin: 20rpx;
		font-size: 14px;

		.user-active {
			display: flex;

			.arrow-down {
				margin-left: auto;
				color: #999;
			}
		}
	}

	.post-item-top-user image {
		width: 80rpx;
		height: 80rpx;
		border-radius: 50%;
		float: left;
	}

	.post-item-top-user text {
		display: block;
		width: 60%;
		height: 40rpx;
		float: left;
		line-height: 50rpx;
		color: #999;
		margin-left: 10rpx;
	}

	.post-text {
		display: block;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 10;
		white-space: pre-wrap;
		overflow: hidden;
	}

	.discuss-title {
		height: 30rpx;
		padding: 10rpx;
		line-height: 30rpx;
		border-radius: 20rpx;
		font-size: 12px;
		background: #f0f0f5;
		color: #68c778;
		text-align: center;
		margin-left: 85rpx;
	}

	.p-footer {
		display: flex;
		margin: 20rpx 40rpx 20rpx 0;
	}

	.p-footer .topic-name {
		font-size: 20rpx;
		color: $themes-color;
		background-color: #e6e6ff;
		border-radius: 10rpx;
		display: flex;
		justify-content: center;
		align-items: center;
		padding: 10rpx 20rpx;
		margin-left: 85rpx;
	}

	.p-footer .p-right {
		display: flex;
		margin-left: auto;
		padding: 10rpx 20rpx;
	}

	.p-footer .p-right .p-item {
		color: #999;
		display: flex;
		justify-content: center;
		align-items: center;

		.count {
			font-size: 22rpx;
		}
	}

	.p-footer .p-right .p-item .iconfont {
		margin-right: 10rpx;
	}

	.margin50 {
		margin: 0 50rpx;
	}
</style>
