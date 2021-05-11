<template name="jiangqie_poplogin">
	<cover-view v-if="showPopLogin" class="jiangqie-poplogin-mask">
		<cover-view class="jiangqie-poplogin">
			<cover-view class="jiangqie-pop-head">
				<cover-image src="/static/images/default_avatar.jpg" mode="aspectFill"></cover-image>
				<cover-view>立即登录，体验更多功能</cover-view>
			</cover-view>
			<cover-view class="jiangqie-pop-opt">
				<button class="jiangqie-pop-btnl" @tap.stop="handlerLoginCancelClick">取消</button>
				<button class="jiangqie-pop-btnr" @tap.stop="handlerDoLoginClick">登录</button>
			</cover-view>
		</cover-view>
	</cover-view>
</template>