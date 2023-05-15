# Typecho友情链接插件

### 介绍
Typecho友情链接插件——懵仙兔兔

![typecho-links-001.jpg](https://img.2dph.com/blog/2020/02/typecho-links-001.jpg/mejituu)
Ps：图是测试时截的，友链被我搞乱了::(滑稽)

### 最新版本

 - version 1.2.6 at 2023-05-15 by 懵仙兔兔

Ps：友情链接插件1.2.6全新版本已适配老版本，直接启用插件会自动升级数据

### 更新历史
```
/**
 * 友情链接插件 by 懵仙兔兔（也就是第三方维护）
 * 
 * @package Links
 * @author 懵仙兔兔
 * @version 1.2.6
 * @dependence 14.10.10-*
 * @link https://2dph.com
 * 
 * version 1.2.6 at 2023-05-15 by 泽泽社长
 * 支持主题作者自定义友链 html 结构
 * 
 * version 1.2.5 at 2023-03-27 by 懵仙兔兔
 * 友链添加 noopener 外链属性
 * 内置友链邮箱解析头像链接 api 接口调整为仅内部调用
 * Action 和内置友链邮箱解析头像链接 api 接口使用加盐地址
 * 文本字段入库过滤 XSS
 * 增加图片尺寸参数支持
 * 增加规则和默认图片尺寸设置选项
 * 修复历史遗留问题更新 lid 导致报错
 * 
 * version 1.2.3 at 2023-03-26 by 懵仙兔兔
 * 修复没有一条友链时，Typecho 1.2 友链设置界面报错问题（虽然报错不影响功能）
 * 调整表格间距
 * 删除失效链接，隐藏界面多余 input 标签
 * 修复友链邮箱解析头像链接功能，内置 api 接口
 * 
 * version 1.2.2 at 2020-03-11 by 懵仙兔兔
 * 修复一个小 BUG
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
 * version 1.1.2 at 2019-08-26 by 泽泽社长
 * 修复越权漏洞
 * 
 * version 1.1.1 at 2014-12-14
 * 修改支持 Typecho 1.0
 * 修正 Typecho 1.0 下不能删除的 BUG
 * 
 * 历史版本 by Hanny（原作者）
 * 
 * version 1.1.0 at 2013-12-08
 * 修改支持 Typecho 0.9
 * 
 * version 1.0.4 at 2010-06-30
 * 修正数据表的前缀问题
 * 在 Pattern 里加上所有的数据表字段
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
```

### 功能描述
**本版本的友情链接可以支持以下的功能：**

 - 1、自建独立数据表，干净无上限的添加友情链接信息。
 - 2、支持两种输出方式：函数方式，用于主题模板侧边栏等嵌入位置显示；HTML标签方式，用于独立页面等编辑内容显示。
 - 3、三种输出模式：文字友链、图片友链、图文混合友链等。内设三种默认输出规则，支持自定议设定输出规则。
 - 4、管理面板：支持友链的分类，拖拽排序以及友链启用禁用等
 - 5、支持友链邮箱解析头像链接（数字QQ邮箱自动优先解析无QQ号头像链接，其次有QQ号链接。其他邮箱解析Gravatar头像），方便用户添加无图片的友链。
 - 6、支持增加自定义字段，方便用户做一些个性扩展。

### 使用帮助
详见：[https://2dph.com/archives/typecho-links-help.html](https://2dph.com/archives/typecho-links-help.html)

### 仓库地址
GitHub：[https://github.com/Mejituu/Links](https://github.com/Mejituu/Links)
码云：[https://gitee.com/Mejituu/Links](https://gitee.com/Mejituu/Links)

### 问题提交
[https://2dph.com/archives/typecho-links.html](https://2dph.com/archives/typecho-links.html)