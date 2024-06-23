CREATE TABLE
    "typecho_links" (
        "lid" SERIAL PRIMARY KEY, -- 自动递增的主键
        "name" VARCHAR(50) DEFAULT NULL, -- links 名称
        "url" VARCHAR(200) DEFAULT NULL, -- links 网址
        "sort" VARCHAR(50) DEFAULT NULL, -- links 分类
        "email" VARCHAR(50) DEFAULT NULL, -- links 邮箱
        "image" VARCHAR(200) DEFAULT NULL, -- links 图片
        "description" VARCHAR(200) DEFAULT NULL, -- links 描述
        "user" VARCHAR(200) DEFAULT NULL, -- 自定义
        "state" INT DEFAULT 1, -- links 状态
        "order" INT DEFAULT 0 -- links 排序
    );