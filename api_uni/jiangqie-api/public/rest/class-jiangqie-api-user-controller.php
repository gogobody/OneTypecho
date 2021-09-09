<?php

/*
 * 酱茄小程序开源版
 * Author: 酱茄
 * Help document: https://www.jiangqie.com/docs/kaiyuan/id1
 * github: https://github.com/longwenjunjie/jiangqie_kafei
 * gitee: https://gitee.com/longwenjunj/jiangqie_kafei
 * Copyright ️© 2020-2021 www.jiangqie.com All rights reserved.
 */

class JiangQie_API_User_Controller extends JiangQie_API_Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->module = 'user';
	}

	public function register_routes()
	{
		/**
		 * 用户登陆
		 */
		register_rest_route($this->namespace, '/' . $this->module . '/login', [
			[
				'callback' => [$this, 'user_login'],
				'args' => [
					'code' => [
						'default' => '',
						'description' => 'wx code',
						'type' => 'string',
					],
					'encrypted_data' => [
						'default' => '',
						'description' => '加密数据',
						'type' => 'string',
					],
					'iv' => [
						'default' => '',
						'description' => '数据向量',
						'type' => 'string',
					],
				]
			]
		]);

		/**
		 * 用户登陆
		 */
		register_rest_route($this->namespace, '/' . $this->module . '/login2', [
			[
				'callback' => [$this, 'user_login2']
			]
		]);
		register_rest_route($this->namespace, '/' . $this->module . '/login3', [
			[
				'callback' => [$this, 'user_login3']
			]
		]);
		register_rest_route($this->namespace, '/' . $this->module . '/logintest', [
			[
				'callback' => [$this, 'user_logintest']
			]
		]);

		/**
		 * 用户配置
		 */
		register_rest_route($this->namespace, '/' . $this->module . '/index', [
			[
				'callback' => [$this, 'user_index'],
				'args' => [
					'token' => [
						'default' => '',
						'description' => '用户token',
						'type' => 'string',
					],
				]
			]
		]);

		/**
		 * 用户点赞
		 */
		register_rest_route($this->namespace, '/' . $this->module . '/like', [
			[
				'callback' => [$this, 'user_like'],
				'args' => [
					'post_id' => [
						'default' => 0,
						'description' => '文章ID',
						'type' => 'integer',
					],
					'token' => [
						'default' => '',
						'description' => '用户token',
						'type' => 'string',
					],
				]
			]
		]);

		/**
		 * 用户收藏
		 */
		register_rest_route($this->namespace, '/' . $this->module . '/favorite', [
			[
				'callback' => [$this, 'user_favorite'],
				'args' => [
					'post_id' => [
						'default' => 0,
						'description' => '文章ID',
						'type' => 'integer',
					],
					'token' => [
						'default' => '',
						'description' => '用户token',
						'type' => 'string',
					],
				]
			]
		]);
	}

	/**
	 *用户登录
	 */
	public function user_login($request)
	{
		$code = $this->param_value($request, 'code', '');
		$encrypted_data = $this->param_value($request, 'encrypted_data', '');
		$iv = $this->param_value($request, 'iv', '');

		if (empty($code) || empty($encrypted_data) || empty($iv)) {
			return $this->make_error('缺少参数');
		}

		$app_id = JiangQie_API::option_value('app_id');
		$app_secret = JiangQie_API::option_value('app_secret');
		$params = [
			'appid' => $app_id,
			'secret' => $app_secret,
			'js_code' => $code,
			'grant_type' => 'authorization_code'
		];

		$result = wp_remote_get(add_query_arg($params, 'https://api.weixin.qq.com/sns/jscode2session'));
		if (!is_array($result) || is_wp_error($result) || $result['response']['code'] != '200') {
			return $this->make_error('wx授权失败');
		}

		$body = stripslashes($result['body']);
		$wx_session = json_decode($body, true);

		$auth_code = $this->decryptData($app_id, $wx_session['session_key'], urldecode($encrypted_data), urldecode($iv), $data);
		if ($auth_code != 0) {
			return $this->make_error('wx授权失败');
		}

		$user_data = json_decode($data, true);

		$open_id = $wx_session['openid'];

		// $open_id = uniqid();
		// $open_id = '5e4fc0f56e034';
		// $user_data = [
		// 	'nickName' => $this->param_value($request, 'nickName', ''),
		// 	'avatarUrl' => $this->param_value($request, 'avatarUrl', ''),
		// ];
		if (!username_exists($open_id)) {
			$user_id = wp_insert_user([
				'user_login' => $open_id,
				'nickname' => $user_data['nickName'],
				'user_nicename' => $user_data['nickName'],
				'display_name' => $user_data['nickName'],
				'user_email' => $open_id . '@jiangqie.com',
				'role' => 'subscriber',
				'user_pass' => wp_generate_password(16, false),
			]);

			if (is_wp_error($user_id)) {
				return $this->make_error('创建用户失败');
			}
		} else {
			$user = get_user_by('login', $open_id);
			$user_id = wp_update_user([
				'ID' => $user->ID,
				'nickname' => $user_data['nickName'],
				'user_nicename' => $user_data['nickName'],
				'display_name' => $user_data['nickName'],
			]);

			if (is_wp_error($user_id)) {
				return $this->make_error('更新用户失败');
			}
		}

		update_user_meta($user_id, 'jiangqie_avatar', $user_data['avatarUrl']);

		$jiangqie_token = $this->generate_token();
		update_user_meta($user_id, 'jiangqie_token', $jiangqie_token);

		$user = array(
			"nickname" => $user_data["nickName"],
			"avatar" => $user_data["avatarUrl"],
			"token" => $jiangqie_token,
		);

		return $this->make_success($user);
	}

	/**
	 *用户登录
	 */
	public function user_login2($request)
	{
		$code = $this->param_value($request, 'code', '');
		$nickname = $this->param_value($request, 'nickName', '');
		$avatarUrl = $this->param_value($request, 'avatarUrl', '');
		if (empty($code) || empty($nickname) || empty($avatarUrl)) {
			return $this->make_error('缺少参数');
		}

		$app_id = JiangQie_API::option_value('app_id');
		$app_secret = JiangQie_API::option_value('app_secret');
		$params = [
			'appid' => $app_id,
			'secret' => $app_secret,
			'js_code' => $code,
			'grant_type' => 'authorization_code'
		];

		$result = wp_remote_get(add_query_arg($params, 'https://api.weixin.qq.com/sns/jscode2session'));
		if (!is_array($result) || is_wp_error($result) || $result['response']['code'] != '200') {
			return $this->make_error('wx授权失败');
		}

		$body = stripslashes($result['body']);
		$wx_session = json_decode($body, true);

		$openId = $wx_session['openid'];
		$user = get_user_by('login', $openId);

		if ($user) {
			$user_id = wp_update_user([
				'ID' => $user->ID,
				'nickname' => $nickname,
				'user_nicename' => $nickname,
				'display_name' => $nickname,
				'user_email' => $openId . '@jiangqie.com',
			]);

			if (is_wp_error($user_id)) {
				return $this->make_error('更新用户失败');
			}
		} else {
			$user_id = wp_insert_user([
				'user_login' => $openId,
				'nickname' => $nickname,
				'user_nicename' => $nickname,
				'display_name' => $nickname,
				'user_email' => $openId . '@jiangqie.com',
				'role' => 'subscriber',
				'user_pass' => wp_generate_password(16, false),
			]);

			if (is_wp_error($user_id)) {
				return $this->make_error('创建用户失败');
			}
		}

		update_user_meta($user_id, 'jiangqie_avatar', $avatarUrl);

		$jiangqie_token = $this->generate_token();
		update_user_meta($user_id, 'jiangqie_token', $jiangqie_token);

		$user = array(
			"nickname" => $nickname,
			"avatar" => $avatarUrl,
			"token" => $jiangqie_token,
		);

		return $this->make_success($user);
	}

	/**
	 *用户登录
	 */
	public function user_login3($request)
	{
		$code = $this->param_value($request, 'code', '');
		$nickname = $this->param_value($request, 'nickname', '');
		$avatar = $this->param_value($request, 'avatar', '');
		$channel = $this->param_value($request, 'channel', '');
		if (empty($code) || empty($nickname) || empty($avatar) || empty($channel)) {
			return $this->make_error('缺少参数');
		}

		$session = false;
		if ('weixin' == $channel) {
			$session = $this->wx_code2openid($code);
		} else if ('qq' == $channel) {
			$session = $this->qq_code2openid($code);
		} else if ('baidu' == $channel) {
			$session = $this->bd_code2openid($code);
		}

		if (!$session) {
			return $this->make_error('授权失败');
		}


		$user = get_user_by('login', $session['openid']);
		if ($user) {
			$user_id = $user->ID;
		} else {
			$email_domain = '@jiangqie.com';
			$user_id = wp_insert_user([
				'user_login' => $session['openid'],
				'nickname' => $nickname,
				'user_nicename' => $nickname,
				'display_name' => $nickname,
				'user_email' => $session['openid'] . $email_domain,
				'role' => 'subscriber',
				'user_pass' => wp_generate_password(16, false),
			]);
			
			if (is_wp_error($user_id)) {
				return $this->make_error('创建用户失败');
			}
		}
		
		update_user_meta($user_id, 'jq_channel', $channel);

		update_user_meta($user_id, 'jq_session_key', $session['session_key']);

		if (isset($session['unionid']) && !empty($session['unionid'])) {
			update_user_meta($user_id, 'jq_unionid', $session['unionid']);
		}

		//如果每次都同步微信头像 会导致小程序设置的头像失效；所以没有头像时，才同步头像
		$old_avatar = get_user_meta($user_id, 'jiangqie_avatar', true);
		if (!$old_avatar || strstr($old_avatar, 'qlogo.cn')) {
			$new_avatar = jiangqie_free_download_wx_avatar($avatar, $user_id);
			
			if ($new_avatar) {
				$new_avatar_url = $new_avatar['url'];
				$dres = jiangqie_free_import_image2attachment($new_avatar['path']);
				if (!is_wp_error($dres)) {
					$upload_dir = wp_upload_dir();
					$new_avatar_url = $upload_dir['url'] . '/' . $dres;
				}
				update_user_meta($user_id, 'jiangqie_avatar', $new_avatar_url);
			} else {
				update_user_meta($user_id, 'jiangqie_avatar', $avatar);
			}
		}


		$jiangqie_token = $this->generate_token();
		update_user_meta($user_id, 'jiangqie_token', $jiangqie_token);

		$user = array(
			'nickname' => $nickname,
			'avatar' => get_user_meta($user_id, 'jiangqie_avatar', true),
			'token' => $jiangqie_token,
		);

		return $this->make_success($user);	
	}

	public function user_logintest($request)
	{
		$jiangqie_token = $this->generate_token();

		$user_id = 4;

		$user = array(
			'nickname' => '酱茄',
			'avatar' => get_user_meta($user_id, 'jiangqie_avatar', true),
			'token' => $jiangqie_token,
		);

		update_user_meta($user_id, 'jiangqie_token', $jiangqie_token);

		return $this->make_success($user);
	}

	
	private function wx_code2openid($code)
	{
		$app_id = JiangQie_API::option_value('app_id');
		$app_secret = JiangQie_API::option_value('app_secret');
		if (!$app_id || !$app_secret) {
			return false;
		}

		$params = [
			'appid' => $app_id,
			'secret' => $app_secret,
			'js_code' => $code,
			'grant_type' => 'authorization_code'
		];

		$result = wp_remote_get(add_query_arg($params, 'https://api.weixin.qq.com/sns/jscode2session'));
		if (!is_array($result) || is_wp_error($result) || $result['response']['code'] != '200') {
			return false;
		}

		file_put_contents('wx_login', json_encode($result));

		$body = stripslashes($result['body']);
		$session = json_decode($body, true);

		// $openId = $wx_session['openid'];
		// $unionId = $wx_session['unionid'];
		// update_user_meta($user_id, 'jq_wx_session_key', $wx_session['session_key']);

		return $session;
	}

	private function qq_code2openid($code)
	{
		$app_id = JiangQie_API::option_value('qq_app_id');
		$app_secret = JiangQie_API::option_value('qq_app_secret');
		if (!$app_id || !$app_secret) {
			return false;
		}

		$params = [
			'appid' => $app_id,
			'secret' => $app_secret,
			'js_code' => $code,
			'grant_type' => 'authorization_code'
		];

		$result = wp_remote_get(add_query_arg($params, 'https://api.q.qq.com/sns/jscode2session'));
		if (!is_array($result) || is_wp_error($result) || $result['response']['code'] != '200') {
			return false;
		}

		file_put_contents('qq_login', json_encode($result));

		$body = stripslashes($result['body']);
		$session = json_decode($body, true);

		return $session;
	}

	private function bd_code2openid($code)
	{
		$app_id = JiangQie_API::option_value('bd_app_key');
		$app_secret = JiangQie_API::option_value('bd_app_secret');
		if (!$app_id || !$app_secret) {
			return false;
		}

		$params = [
			'client_id' => $app_id,
			'sk' => $app_secret,
			'code' => $code,
		];

		$result = wp_remote_get(add_query_arg($params, 'https://spapi.baidu.com/oauth/jscode2sessionkey'));
		if (!is_array($result) || is_wp_error($result) || $result['response']['code'] != '200') {
			return false;
		}

		file_put_contents('bd_login', json_encode($result));

		$body = stripslashes($result['body']);
		$session = json_decode($body, true);

		return $session;
	}

	/**
	 * 用户 数据
	 */
	public function user_index($request)
	{
		$user_id = $this->check_login($request);
		if (!$user_id) {
			return $this->make_error('还没有登陆', -1);
		}

		global $wpdb;

		$table_post_view = $wpdb->prefix . 'jiangqie_post_view';
		$view_count = $wpdb->get_var("SELECT COUNT(id) FROM `$table_post_view` WHERE user_id=$user_id");

		$table_post_like = $wpdb->prefix . 'jiangqie_post_like';
		$like_count = $wpdb->get_var("SELECT COUNT(id) FROM `$table_post_like` WHERE user_id=$user_id");

		$table_post_favorite = $wpdb->prefix . 'jiangqie_post_favorite';
		$favorite_count = $wpdb->get_var("SELECT COUNT(id) FROM `$table_post_favorite` WHERE user_id=$user_id");

		$table_comments = $wpdb->prefix . 'comments';
		$comment_count = $wpdb->get_var("SELECT COUNT(distinct comment_post_ID) FROM `$table_comments` WHERE user_id=$user_id");

		return $this->make_success([
			'view_count' => $view_count,
			'like_count' => $like_count,
			'favorite_count' => $favorite_count,
			'comment_count' => $comment_count,
		]);
	}

	/**
	 * 用户 点赞 文章
	 */
	public function user_like($request)
	{
		$post_id = $this->param_value($request, 'post_id', 0);
		if (empty($post_id)) {
			return $this->make_error('缺少参数');
		}

		$user_id = $this->check_login($request);
		if (!$user_id) {
			return $this->make_error('还没有登陆', -1);
		}

		global $wpdb;
		$table_post_like = $wpdb->prefix . 'jiangqie_post_like';
		$post_like_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM `$table_post_like` WHERE user_id=%d AND post_id=%d", $user_id, $post_id));

		$post_likes = (int) get_post_meta($post_id, "likes", true);

		if ($post_like_id) {
			$wpdb->query("DELETE FROM `$table_post_like` WHERE id=$post_like_id");

			update_post_meta($post_id, 'likes', ($post_likes - 1));
		} else {
			$wpdb->insert($table_post_like, [
				'user_id' => $user_id,
				'post_id' => $post_id,
			]);

			if (!update_post_meta($post_id, 'likes', ($post_likes + 1))) {
				add_post_meta($post_id, 'likes', 1, true);
			}
		}

		return $this->make_success();
	}

	/**
	 * 用户 收藏 文章
	 */
	public function user_favorite($request)
	{
		$post_id = $this->param_value($request, 'post_id', 0);
		if (empty($post_id)) {
			return $this->make_error('缺少参数');
		}

		$user_id = $this->check_login($request);
		if (!$user_id) {
			return $this->make_error('还没有登陆', -1);
		}

		global $wpdb;
		$table_post_favorite = $wpdb->prefix . 'jiangqie_post_favorite';
		$post_favorite_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM `$table_post_favorite` WHERE user_id=%d AND post_id=%d", $user_id, $post_id));

		$post_favorites = (int) get_post_meta($post_id, "favorites", true);

		if ($post_favorite_id) {
			$wpdb->query("DELETE FROM `$table_post_favorite` WHERE id=$post_favorite_id");

			update_post_meta($post_id, 'favorites', ($post_favorites - 1));
		} else {
			$wpdb->insert($table_post_favorite, [
				'user_id' => $user_id,
				'post_id' => $post_id,
			]);

			if (!update_post_meta($post_id, 'favorites', ($post_favorites + 1))) {
				add_post_meta($post_id, 'favorites', 1, true);
			}
		}

		return $this->make_success();
	}

	/**
	 * 检验数据的真实性，并且获取解密后的明文.
	 * @param $encryptedData string 加密的用户数据
	 * @param $iv string 与用户数据一同返回的初始向量
	 * @param $data string 解密后的原文
	 *
	 * @return int 成功 0，失败返回对应的错误码
	 */
	private function decryptData($appid, $session, $encryptedData, $iv, &$data)
	{

		$ErrorCode = array(
			'OK'                => 0,
			'IllegalAesKey'     => -41001,
			'IllegalIv'         => -41002,
			'IllegalBuffer'     => -41003,
			'DecodeBase64Error' => -41004
		);

		if (strlen($session) != 24) {
			return array('code' => $ErrorCode['IllegalAesKey'], 'message' => 'session_key 长度不合法', 'session_key' => $session);
		}
		$aesKey = base64_decode($session);
		if (strlen($iv) != 24) {
			return array('code' => $ErrorCode['IllegalIv'], 'message' => 'iv 长度不合法', 'iv' => $iv);
		}
		$aesIV = base64_decode($iv);
		$aesCipher = base64_decode($encryptedData);
		$result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
		$data_decode = json_decode($result);
		if ($data_decode  == NULL) {
			return array('code' => $ErrorCode['IllegalBuffer'], 'message' => '解密失败，非法缓存');
		}
		if ($data_decode->watermark->appid != $appid) {
			return array('code' => $ErrorCode['IllegalBuffer'], 'message' => '解密失败，AppID 不正确');
		}
		$data = $result;
		return $ErrorCode['OK'];
	}

	/**
	 * 计算token
	 */
	private function generate_token()
	{
		return md5(uniqid());
	}
}
