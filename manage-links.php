<?php
/** 初始化组件 */
Typecho_Widget::widget('Widget_Init');

/** 注册一个初始化插件 */
Typecho_Plugin::factory('admin/common.php')->begin();

Typecho_Widget::widget('Widget_Options')->to($options);
Typecho_Widget::widget('Widget_User')->to($user);
Typecho_Widget::widget('Widget_Security')->to($security);
Typecho_Widget::widget('Widget_Menu')->to($menu);

/** 初始化上下文 */
$request = $options->request;
$response = $options->response;
include 'header.php';
include 'menu.php';
?>


<div class="main">
    <div class="body container">
        <?php include 'page-title.php'; ?>
        <div class="row typecho-page-main manage-metas">
                <div class="col-mb-12">
                    <ul class="typecho-option-tabs clearfix">
                        <li class="current"><a href="<?php $options->adminUrl('extending.php?panel=Links/manage-links.php'); ?>"><?php _e('友情链接'); ?></a></li>
						<li><a href=<?php $options->index('/action/links-edit?do=addMejituu'); ?> title="如果你喜欢，可以点击快速添加懵仙兔兔的博客。"><?php _e('添加懵仙兔兔'); ?></a></li>
                        <li><a href="<?php $options->adminUrl('options-plugin.php?config=Links'); ?>"><?php _e('设置'); ?></a></li>
                        <li><a href="https://2dph.com/archives/typecho-links-help.html" title="查看友情链接使用帮助" target="_blank"><?php _e('帮助'); ?></a></li>
                    </ul>
                </div>

                <div class="col-mb-12 col-tb-8" role="main">                  
                    <?php
						$prefix = $db->getPrefix();
						$links = $db->fetchAll($db->select()->from($prefix.'links')->order($prefix.'links.order', Typecho_Db::SORT_ASC));
                    ?>
                    <form method="post" name="manage_categories" class="operate-form">
                    <div class="typecho-list-operate clearfix">
                        <div class="operate">
                            <label><i class="sr-only"><?php _e('全选'); ?></i><input type="checkbox" class="typecho-table-select-all" /></label>
                            <div class="btn-group btn-drop">
                                <button class="btn dropdown-toggle btn-s" type="button"><i class="sr-only"><?php _e('操作'); ?></i><?php _e('选中项'); ?> <i class="i-caret-down"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a lang="<?php _e('你确认要删除这些友链吗?'); ?>" href="<?php $options->index('/action/links-edit?do=delete'); ?>"><?php _e('删除'); ?></a></li>
                                    <li><a lang="<?php _e('你确认要启用这些友链吗?'); ?>" href="<?php $options->index('/action/links-edit?do=enable'); ?>"><?php _e('启用'); ?></a></li>
                                    <li><a lang="<?php _e('你确认要禁用这些友链吗?'); ?>" href="<?php $options->index('/action/links-edit?do=prohibit'); ?>"><?php _e('禁用'); ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="typecho-table-wrap">
                        <table class="typecho-list-table">
                            <colgroup>
                                <col width="15"/>
								<col width="25%"/>
								<col width=""/>
								<col width="15%"/>
								<col width="10%"/>
								<col width="12%"/>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th> </th>
									<th><?php _e('友链名称'); ?></th>
									<th><?php _e('友链地址'); ?></th>
									<th><?php _e('分类'); ?></th>
									<th><?php _e('图片'); ?></th>
									<th><?php _e('状态'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
								<?php if (!empty($links)): $alt = 0;?>
								<?php foreach ($links as $link): ?>
                                <tr id="lid-<?php echo $link['lid']; ?>">
                                    <td><input type="checkbox" value="<?php echo $link['lid']; ?>" name="lid[]"/></td>
									<td><a href="<?php echo $request->makeUriByRequest('lid=' . $link['lid']); ?>" title="点击编辑"><?php echo $link['name']; ?></a>
									<td><?php echo $link['url']; ?></td>
									<td><?php echo $link['sort']; ?></td>
									<td><?php
										if ($link['image']) {
											echo '<a href="'.$link['image'].'" title="点击放大" target="_blank"><img class="avatar" src="'.$link['image'].'" alt="'.$link['name'].'" width="32" height="32"/></a>';
										} else {
											$options = Typecho_Widget::widget('Widget_Options');
											$nopic_url = Typecho_Common::url('/usr/plugins/Links/nopic.jpg', $options->siteUrl);
											echo '<img class="avatar" src="'.$nopic_url.'" alt="NOPIC" width="32" height="32"/>';
										}
									?></td>
									<td><?php
										if ($link['state'] == 1) {
											echo '正常';
										} elseif ($link['state'] == 0) {
											echo '禁用';
										}
									?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="5"><h6 class="typecho-list-table-title"><?php _e('没有任何友链'); ?></h6></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    </form>
				</div>
                <div class="col-mb-12 col-tb-4" role="form">
                    <?php Links_Plugin::form()->render(); ?>
                </div>
        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
?>

<script> 
$('input[name="email"]').blur(function() {
    var _email = $(this).val();
    var _image = $('input[name="image"]').val();
    if (_email != '' && _image == '') {
        var k="<?php Helper::options()->pluginUrl(); ?>Links/api/email-logo.php?type=json&email="+$(this).val();
        $.get(k,function(result){var k=jQuery.parseJSON(result).url;
        $('input[name="image"]').val(k);
        });
    }
    return false;
});
</script> 
<script type="text/javascript">
(function () {
    $(document).ready(function () {
        var table = $('.typecho-list-table').tableDnD({
            onDrop : function () {
                var ids = [];

                $('input[type=checkbox]', table).each(function () {
                    ids.push($(this).val());
                });

                $.post('<?php $options->index('/action/links-edit?do=sort'); ?>', 
                    $.param({lid : ids}));

                $('tr', table).each(function (i) {
                    if (i % 2) {
                        $(this).addClass('even');
                    } else {
                        $(this).removeClass('even');
                    }
                });
            }
        });

        table.tableSelectable({
            checkEl     :   'input[type=checkbox]',
            rowEl       :   'tr',
            selectAllEl :   '.typecho-table-select-all',
            actionEl    :   '.dropdown-menu a'
        });

        $('.btn-drop').dropdownMenu({
            btnEl       :   '.dropdown-toggle',
            menuEl      :   '.dropdown-menu'
        });

        $('.dropdown-menu button.merge').click(function () {
            var btn = $(this);
            btn.parents('form').attr('action', btn.attr('rel')).submit();
        });

        <?php if (isset($request->lid)): ?>
        $('.typecho-mini-panel').effect('highlight', '#AACB36');
        <?php endif; ?>
    });
})();
</script>
<?php include 'footer.php'; ?>

<?php /** Links by 懵仙兔兔 */ ?>
