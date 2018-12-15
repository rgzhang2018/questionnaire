﻿## 简易投票系统  questionniare system

*php开发，使用CI框架*



<br>


### 演示网址
欢迎访问：www.rgzhang.top



<hr>


### 使用说明

##### 目录结构
```./application/views/```  			存放视图
```./application/controllers/```		存放控制器
```./application/models/```				存放模型文件

<br>

##### 关于数据库
**```questionnaire system.sql```存放数据库建表文件。**

*注：如果无法成功建表，可以去掉约束条件，先建表，后面单独写入约束条件，或者更改外键名词*

<br>

##### 关于UI界面
采用amazeUI框架，该框架兼容很好。

```assets```目录下存放amazeUI的样式布局，以及用到的art-template等JS文件

具体使用可以参考官网http://amazeui.org/

或者GitHub:https://github.com/amazeui/amazeui

<br>

##### 关于CI框架

相关说明参考框架官网https://codeigniter.org.cn/user_guide/

使用时请修改到对应的URI，以及修改controller/welcome.php下的转跳URL

<br>

##### 补充说明
初学者第一次尝试，作为给其他初学者的一个参考，有许多不足之处还请见谅
有问题欢迎Issues或者留言至演示网址留言板

<hr>

### 更新说明


2018.10.17
留言板

2018.10.19
完成登录以及相关cookie功能

2018.10.20
优化登录相关功能，同时完成问卷功能的相关前端页面

2018.10.26
优化登录相关功能，设计好数据库，附在根目录下


2018.10.27-28
修复手机端显示的问题,纠正页面标题

2018.11.3
完成了提交、导出的类设置和部分关联

2018.11.5
增加了验证码模块，增加了问卷填写页面

2018.11.6
改变验证码为不区分大小写

2018.11.9 
现有代码重构，包括登录、留言板、发布模块
同时完善注册页面的详细验证和阻断

2018.11.10
问卷填写部分弹框

2018.11.20-24
完成完整功能

2018.11.25-30
重构

2018.12.15
修复提交问卷的时候，可以提交信息不全的问卷