<?php
/**
 * 友情链接插件 by 懵仙兔兔（也就是第三方维护）
 * 
 * @package Links
 * @author 懵仙兔兔
 * @version 1.2.1
 * @dependence 14.10.10-*
 * @link https://2dph.com
 * 
 * version 1.2.1 at 2020-03-03 by 懵仙兔兔
 * 修复邮箱头像解析问题
 * 优化逻辑问题
 * 
 * version 1.2.0 at 2020-02-16 by 懵仙兔兔
 * 增加友链禁用功能
 * 增加友链邮箱功能
 * 增加友链邮箱解析头像链接功能
 * 修正数据表的占用大小问题
 * 
 * 历史版本 by 懵仙兔兔（第三方维护者）
 * 
 * version 1.1.3 at 2020-02-08 by 懵仙兔兔
 * 修复已存在表激活失败、表检测失败
 * 
 * version 1.1.2 at notime
 * 修复越权漏洞
 * 
 * version 1.1.1 at 2014-12-14
 * 修改支持Typecho 1.0
 * 修正Typecho 1.0下不能删除的BUG
 * 
 * 历史版本 by Hanny（原作者）
 * 
 * version 1.1.0 at 2013-12-08
 * 修改支持Typecho 0.9
 * 
 * version 1.0.4 at 2010-06-30
 * 修正数据表的前缀问题
 * 在Pattern里加上所有的数据表字段
 * 
 * version 1.0.3 at 2010-06-20
 * 修改友链图片的支持方式。
 * 增加友链分类功能
 * 增加自定义字段，以便用户自定义扩展
 * 增加多种友链输出方式。
 * 增加较详细的帮助文档
 * 增加在自定义页面引用标签，方便友情链接页面的引用
 * 
 * version 1.0.2 at 2010-05-16
 * 增加SQLite支持
 * 
 * version 1.0.1 at 2009-12-27
 * 增加显示友链描述
 * 增加首页友链数量限制功能
 * 增加友链图片功能
 * 
 * version 1.0.0 at 2009-12-12
 * 实现友情链接的基本功能
 * 包括: 添加 删除 修改 排序
 */
class Links_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
		$info = Links_Plugin::linksInstall();
		Helper::addPanel(3, 'Links/manage-links.php', '友情链接', '管理友情链接', 'administrator');
		Helper::addAction('links-edit', 'Links_Action');
        Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('Links_Plugin', 'parse');
        Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('Links_Plugin', 'parse');
        Typecho_Plugin::factory('Widget_Abstract_Comments')->contentEx = array('Links_Plugin', 'parse');
		return _t($info);
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
	{
		Helper::removeAction('links-edit');
		Helper::removePanel(3, 'Links/manage-links.php');
	}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form) {
        $tixing = new Typecho_Widget_Helper_Form_Element_Text('Mejituu', null, null, _t('【管理】→【友情链接】进入操作页面'), _t('<div class="Mejituu"><br>
        <center>
            <div>
                <p>作者：懵仙兔兔</p>
                <p>友情链接插件更新下载地址：</p><a href="https://2dph.com/archives/typecho-links.html">https://2dph.com/archives/typecho-links.html</a>
                <p>友情链接插件使用帮助：</p><a href="https://2dph.com/archives/typecho-links-help.html">https://2dph.com/archives/typecho-links-help.html</a>
                <p>本插件推荐使用主题Violet：</p><a href="https://qqdie.com/archives/violet-typecho-themes.html">https://qqdie.com/archives/violet-typecho-themes.html</a>
            </div>
        </center>
        </div>'));
        $form->addInput($tixing);
        
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }

	public static function linksInstall()
	{
		$installDb = Typecho_Db::get();
		$type = explode('_', $installDb->getAdapterName());
		$type = array_pop($type);
		$prefix = $installDb->getPrefix();
		$scripts = file_get_contents('usr/plugins/Links/'.$type.'.sql');
		$scripts = str_replace('typecho_', $prefix, $scripts);
		$scripts = str_replace('%charset%', 'utf8', $scripts);
		$scripts = explode(';', $scripts);
		try {
			foreach ($scripts as $script) {
				$script = trim($script);
				if ($script) {
					$installDb->query($script, Typecho_Db::WRITE);
				}
			}
			return '建立友情链接数据表，插件启用成功';
		} catch (Typecho_Db_Exception $e) {
			$code = $e->getCode();
			if (('Mysql' == $type && (1050 == $code || '42S01' == $code)) ||
					('SQLite' == $type && ('HY000' == $code || 1 == $code))) {
				try {
					$script = 'SELECT `lid`, `name`, `url`, `sort`, `email`, `image`, `description`, `user`, `state`, `order` from `' . $prefix . 'links`';
					$installDb->query($script, Typecho_Db::READ);
					return '检测到友情链接数据表，友情链接插件启用成功';					
				} catch (Typecho_Db_Exception $e) {
					$code = $e->getCode();
					if (('Mysql' == $type && (1054 == $code || '42S22' == $code)) ||
							('SQLite' == $type && ('HY000' == $code || 1 == $code))) {
						return Links_Plugin::linksUpdate($installDb, $type, $prefix);
					}
					throw new Typecho_Plugin_Exception('数据表检测失败，友情链接插件启用失败。错误号：'.$code);
				}
			} else {
				throw new Typecho_Plugin_Exception('数据表建立失败，友情链接插件启用失败。错误号：'.$code);
			}
		}
	}
	
	public static function linksUpdate($installDb, $type, $prefix)
	{
		$scripts = file_get_contents('usr/plugins/Links/Update_'.$type.'.sql');
		$scripts = str_replace('typecho_', $prefix, $scripts);
		$scripts = str_replace('%charset%', 'utf8', $scripts);
		$scripts = explode(';', $scripts);
		try {
			foreach ($scripts as $script) {
				$script = trim($script);
				if ($script) {
					$installDb->query($script, Typecho_Db::WRITE);
				}
			}
			return '检测到旧版本友情链接数据表，升级成功';
		} catch (Typecho_Db_Exception $e) {
			$code = $e->getCode();
			if (('Mysql' == $type && (1060 == $code || '42S21' == $code))) {
				return '友情链接数据表已经存在，插件启用成功';
			}
			throw new Typecho_Plugin_Exception('友情链接插件启用失败。错误号：'.$code);
		}
	}

	public static function form($action = null)
	{
		/** 构建表格 */
		$options = Typecho_Widget::widget('Widget_Options');
		$form = new Typecho_Widget_Helper_Form(Typecho_Common::url('/action/links-edit', $options->index),
		Typecho_Widget_Helper_Form::POST_METHOD);
		
		/** 友链名称 */
		$name = new Typecho_Widget_Helper_Form_Element_Text('name', null, null, _t('友链名称*'));
		$form->addInput($name);
		
		/** 友链地址 */
		$url = new Typecho_Widget_Helper_Form_Element_Text('url', null, "http://", _t('友链地址*'));
		$form->addInput($url);
		
		/** 友链分类 */
		$sort = new Typecho_Widget_Helper_Form_Element_Text('sort', null, null, _t('友链分类'), _t('建议以英文字母开头，只包含字母与数字'));
		$form->addInput($sort);
		
		/** 友链邮箱 */
		$email = new Typecho_Widget_Helper_Form_Element_Text('email', null, null, _t('友链邮箱'), _t('填写友链邮箱'));
		$form->addInput($email);
		
		/** 友链图片 */
		$image = new Typecho_Widget_Helper_Form_Element_Text('image', null, null, _t('友链图片'),  _t('需要以http://或https://开头，留空表示没有友链图片'));
		$form->addInput($image);
		
		/** 友链描述 */
		$description =  new Typecho_Widget_Helper_Form_Element_Textarea('description', null, null, _t('友链描述'));
		$form->addInput($description);
		
		/** 自定义数据 */
		$user = new Typecho_Widget_Helper_Form_Element_Text('user', null, null, _t('自定义数据'), _t('该项用于用户自定义数据扩展'));
		$form->addInput($user);
		
		/** 友链状态 */
		$list = array('0' => '禁用', '1' => '启用');
		$state = new Typecho_Widget_Helper_Form_Element_Radio('state', $list, '1', '友链状态');
		$form->addInput($state);
		
		/** 友链动作 */
		$do = new Typecho_Widget_Helper_Form_Element_Hidden('do');
		$form->addInput($do);
		
		/** 友链主键 */
		$lid = new Typecho_Widget_Helper_Form_Element_Hidden('lid');
		$form->addInput($lid);
		
		/** 提交按钮 */
		$submit = new Typecho_Widget_Helper_Form_Element_Submit();
		$submit->input->setAttribute('class', 'btn primary');
		$form->addItem($submit);
		$request = Typecho_Request::getInstance();

        if (isset($request->lid) && 'insert' != $action) {
            /** 更新模式 */
			$db = Typecho_Db::get();
			$prefix = $db->getPrefix();
            $link = $db->fetchRow($db->select()->from($prefix.'links')->where('lid = ?', $request->lid));
            if (!$link) {
                throw new Typecho_Widget_Exception(_t('友链不存在'), 404);
            }
            
            $name->value($link['name']);
            $url->value($link['url']);
            $sort->value($link['sort']);
            $email->value($link['email']);
            $image->value($link['image']);
            $description->value($link['description']);
            $user->value($link['user']);
            $state->value($link['state']);
            $do->value('update');
            $lid->value($link['lid']);
            $submit->value(_t('编辑友链'));
            $_action = 'update';
        } else {
            $do->value('insert');
            $submit->value(_t('增加友链'));
            $_action = 'insert';
        }
        
        if (empty($action)) {
            $action = $_action;
        }

        /** 给表单增加规则 */
        if ('insert' == $action || 'update' == $action) {
			$name->addRule('required', _t('必须填写友链名称'));
			$url->addRule('required', _t('必须填写友链地址'));
			$url->addRule('url', _t('不是一个合法的链接地址'));
			$email->addRule('email', _t('不是一个合法的邮箱地址'));
			$image->addRule('url', _t('不是一个合法的图片地址'));
			$name->addRule('maxLength', _t('友链名称最多包含50个字符'), 50);
			$url->addRule('maxLength', _t('友链地址最多包含200个字符'), 200);
			$sort->addRule('maxLength', _t('友链分类最多包含50个字符'), 50);
			$email->addRule('maxLength', _t('友链邮箱最多包含50个字符'), 50);
			$image->addRule('maxLength', _t('友链图片最多包含200个字符'), 200);
			$description->addRule('maxLength', _t('友链描述最多包含200个字符'), 200);
			$user->addRule('maxLength', _t('自定义数据最多包含200个字符'), 200);
        }
        if ('update' == $action) {
            $lid->addRule('required', _t('友链主键不存在'));
            $lid->addRule(array(new Links_Plugin, 'LinkExists'), _t('友链不存在'));
        }
        return $form;
	}

	public static function LinkExists($lid)
	{
		$db = Typecho_Db::get();
		$prefix = $db->getPrefix();
		$link = $db->fetchRow($db->select()->from($prefix.'links')->where('lid = ?', $lid)->limit(1));
		return $link ? true : false;
	}

    /**
     * 控制输出格式
     */
	public static function output_str($pattern=null, $links_num=0, $sort=null)
	{
		$options = Typecho_Widget::widget('Widget_Options');
		if (!isset($options->plugins['activated']['Links'])) {
			return '友情链接插件未激活';
		}
		if (!isset($pattern) || $pattern == "" || $pattern == null || $pattern == "SHOW_TEXT") {
			$pattern = "<li><a href=\"{url}\" title=\"{title}\" target=\"_blank\">{name}</a></li>\n";
		} elseif ($pattern == "SHOW_IMG") {
			$pattern = "<li><a href=\"{url}\" title=\"{title}\" target=\"_blank\"><img src=\"{image}\" alt=\"{name}\" /></a></li>\n";
		} elseif ($pattern == "SHOW_MIX") {
			$pattern = "<li><a href=\"{url}\" title=\"{title}\" target=\"_blank\"><img src=\"{image}\" alt=\"{name}\" /><span>{name}</span></a></li>\n";
		}
		$db = Typecho_Db::get();
		$prefix = $db->getPrefix();
		$options = Typecho_Widget::widget('Widget_Options');
		$nopic_url = Typecho_Common::url('/usr/plugins/Links/nopic.jpg', $options->siteUrl);
		$sql = $db->select()->from($prefix.'links');
		if (!isset($sort) || $sort == "") {
			$sort = null;
		}
		if ($sort) {
			$sql = $sql->where('sort=?', $sort);
		}
		$sql = $sql->order($prefix.'links.order', Typecho_Db::SORT_ASC);
		$links_num = intval($links_num);
		if ($links_num > 0) {
			$sql = $sql->limit($links_num);
		}
		$links = $db->fetchAll($sql);
		$str = "";
		foreach ($links as $link) {
			if ($link['image'] == null) {
				$link['image'] = $nopic_url;
              if($link['email'] != null){
                $link['image'] ='https://gravatar.helingqi.com/wavatar/'.md5($link['email']).'?d=mm';
                }
			}
			if ($link['state'] == 1) {
			$str .= str_replace(
				array('{lid}', '{name}', '{url}', '{sort}', '{title}', '{description}', '{image}', '{user}'),
				array($link['lid'], $link['name'], $link['url'], $link['sort'], $link['description'], $link['description'], $link['image'], $link['user']),
				$pattern
			);
			}
		}
		return $str;
	}

	//输出
	public static function output($pattern=null, $links_num=0, $sort=null)
	{
		echo Links_Plugin::output_str($pattern, $links_num, $sort);
	}
	
    /**
     * 解析
     * 
     * @access public
     * @param array $matches 解析值
     * @return string
     */
    public static function parseCallback($matches)
    {
		$db = Typecho_Db::get();
		$pattern = $matches[3];
		$links_num = $matches[1];
		$sort = $matches[2];
		return Links_Plugin::output_str($pattern, $links_num, $sort);
    }

    public static function parse($text, $widget, $lastResult)
    {
        $text = empty($lastResult) ? $text : $lastResult;
        
        if ($widget instanceof Widget_Archive || $widget instanceof Widget_Abstract_Comments) {
            return preg_replace_callback("/<links\s*(\d*)\s*(\w*)>\s*(.*?)\s*<\/links>/is", array('Links_Plugin', 'parseCallback'), $text);
        } else {
            return $text;
        }
    }
}
