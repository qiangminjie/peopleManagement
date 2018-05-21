/**
****************************************************************************************
File Description:

    SQL for people management system, execute this SQL to create tables!
护手霜
Table list:

    oes_user                        用户表，系统的登录登出、权限控等
    staff                           员工表，记录公司员工信息
    salary                          工资表 根据type subtype level的标准制定工资规范
    salary_info                     工资明细表，按月记录员工的工资
    type                            员工分类的一级分类表
    subtype                         员工分类的二级分类表
    level                           员工等级表
    interview                       面试表，记录面试信息
    resume                          简历表，记录简历信息

****************************************************************************************
 */

DROP DATABASE IF EXISTS peopleManagement;
CREATE DATABASE peopleManagement;
USE peopleManagement;

-- 用户表
DROP TABLE IF EXISTS oes_user;
CREATE TABLE oes_user (
  id              INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,          -- 用户id
  staff_id        INT           NOT NULL,                                     -- 对应员工id 系统管理员为-1 不对应员工id
  permission      INT           DEFAULT NULL,                                 -- 用户权限，本项目只使用0权限 0 = 系统管理员
  sub_permission  BIGINT(20)    DEFAULT NULL,                                 -- 子权限 0 = 全部权限
  account         VARCHAR(36)   NOT NULL UNIQUE,                              -- 用户账号
  mobile          VARCHAR(11)   DEFAULT 0,                                    -- 用户手机号码
  name            VARCHAR(20)   DEFAULT NULL,                                 -- 用户名
  memo            VARCHAR(100)  DEFAULT NULL,                                 -- 备注信息
  pass            VARCHAR(32)   NOT NULL,                                     -- MD5加密后的用户密码
  status          INT(1)        DEFAULT 0,                                    -- 账号状态， 0 = 正常， 1 = 关闭
  tag             INT(1)        DEFAULT 0,                                    -- 特殊标志位
  create_time     TIMESTAMP     DEFAULT current_timestamp,                    -- 账号创建时间
  error_time      INT(3)        DEFAULT 0                                     -- 连续输入错误密码次数
);
insert into oes_user(id, staff_id, permission, sub_permission, mobile, name, pass, account)
values (0, -1, 0, 0, '18600000000', 'Admin', md5('123456'), 'admin');


-- 员工表
DROP TABLE IF EXISTS staff;
CREATE TABLE staff(
  id        INT(6)        PRIMARY KEY AUTO_INCREMENT, -- 员工id
  number    VARCHAR(10)   NOT NULL UNIQUE ,           -- 工号
  name      VARCHAR(20)   NOT NULL,                   -- 员工姓名
  sex       INT(1)        NOT NULL,                   -- 性别 0 男性 1 女性
  education INT(1)        NOT NULL,                   -- 学历 0 博士 1 硕士 2 本科 4 其它
  school    VARCHAR(50)   DEFAULT NULL,               -- 毕业学校，education为4 此项为null
  birthday  INT(8)        NOT NULL,                   -- 出生日期
  age       INT(2)        NOT NULL,                   -- 年龄 根据生日算出
  type      INT(2)        NOT NULL,                   -- 员工类型 0 高层 1 开发 2 财务 3 行政 特殊
  subtype  INT(2)         NOT NULL,                   -- 员工子类型 type = 0： 0 董事长 1 总经理
                                                      --           type = 1： 0 java 1 php 2 c++
                                                      --           type = 2： 0 会计 1 审计
                                                      --           type = 3： 0 人事 1 业务
  level     INT(2)        NOT NULL,                   -- 员工等级 type <> 0 时 0 1 2 3 负责人/经理/主任 初级 中级 高级
  status    INT(1)        NOT NULL DEFAULT 0,         -- 员工状态 0 未入职 1 在职 2 离职
  in_time   INT(8),                                   -- 入职时间 yyyyMMMdd
  out_time  INT(8)                                    -- 离职时间 yyyyMMMdd 未离职为0
);
INSERT INTO staff(number, name, sex, education, school, birthday, age, type, subtype, level, in_time, out_time)
VALUES('000001', '小黑', 0, 2, '北京大学', 19930908, 24, 0, 0, 0, 20080808, 0);


-- 工资等级表
DROP TABLE IF EXISTS salary;
CREATE TABLE salary (
  type      INT(2)        NOT NULL,           -- 类型
  subtype   INT(2)        NOT NULL,           -- 子类型
  level     INT(2)        NOT NULL,           -- 等级
  money     DECIMAL(10,2) NOT NULL,           -- 工资数
  flag      INT(1)        NOT NULL DEFAULT 0  -- 是否有奖金
);


-- 职务分类表
DROP TABLE IF EXISTS type;
CREATE TABLE type(
  type_id   INT(2)      NOT NULL,   -- 类型id
  type_name VARCHAR(20)  NOT NULL    -- 类型名称
);


-- 子分类表
DROP TABLE IF EXISTS subtype;
CREATE TABLE subtype(
  type_id       INT(2)       NOT NULL, -- 类型id
  subtype_id    INT(2)       NOT NULL, -- 子类型id
  subtype_name  VARCHAR(20)  NOT NULL  -- 子类型名称
);


-- 等级表
DROP TABLE IF EXISTS level;
CREATE TABLE level(
  type_id       INT(2)       NOT NULL, -- 类型id
  subtype_id    INT(2)       NOT NULL, -- 子类型id
  level_id      INT(2)       NOT NULL, -- 级别id
  level_name  VARCHAR(20)  NOT NULL  -- 级别名称
);


-- 工资明细表
DROP TABLE IF EXISTS salary_info;
CREATE TABLE salary_info (
  staff_id    INT(6)        NOT NULL,           -- 员工id
  month       INT(6)        NOT NULL,           -- 对应月份yyyyMM
  salary      DECIMAL(10,2) NOT NULL,           -- 基础工资
  bonus       DECIMAL(10,2) NOT NULL DEFAULT 0  -- 奖金，对于没有奖金的岗位此项为0
);


-- 面试表
DROP TABLE IF EXISTS interview;
CREATE TABLE interview (
  id              INT(6)     PRIMARY KEY AUTO_INCREMENT,   -- 面试id
  time            INT(10)    NOT NULL,                     -- 面试时间 YYYYMMddHHmi
  resume_id       INT(6)     NOT NULL,                     -- 简历id
  interviewer_id  INT(6)     NOT NULL,                     -- 面试官的staff_id
  over            INT(1)     NOT NULL DEFAULT 1,           -- 面试是否结束 0 结束 1 未结束
  result          INT(1)     DEFAULT -1                    -- -1 未开始都为-1 0 通过 1 未通过 2 没来
);


-- 简历表
DROP TABLE IF EXISTS resume;
CREATE TABLE resume (
  id  INT(6) PRIMARY KEY AUTO_INCREMENT,
  name      VARCHAR(20)   NOT NULL,                   -- 面试者姓名
  sex       INT(1)        NOT NULL,                   -- 性别 0 男性 1 女性
  education INT(1)        NOT NULL,                   -- 学历 0 博士 1 硕士 2 本科 4 其它
  school    VARCHAR(50)   DEFAULT NULL,               -- 毕业学校，education为4 此项为null
  birthday  INT(8)        NOT NULL,                   -- 出生日期
  age       INT(2)        NOT NULL,                   -- 年龄 根据生日算出
  type      INT(2)        NOT NULL,                   -- 面试类型 0 高层 1 开发 2 财务 3 行政
  subtype  INT(2)        NOT NULL,                   -- 面试子类型 type = 0： 0 董事长 1 总经理
                                                      --           type = 1： 0 java 1 php 2 c++
                                                      --           type = 2： 0 会计 1 审计
                                                      --           type = 3： 0 人事 1 业务
  level     INT(2)        NOT NULL,                   -- 面试职位 type <> 0 时 0 1 2 3 负责人/经理/主任 初级 中级 高级
  result    INT(1)        NOT NULL DEFAULT -1         -- 简历筛选结果 -1 未读 0 通过 1 不通过
);