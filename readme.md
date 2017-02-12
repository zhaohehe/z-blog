## z-blog

这是一个简单的个人博客系统,使用laravel5.4

## 安装

clone代码到你本地

```composer install```

```npm install```

复制```.env```文件, 配置好各项参数,尤其是数据库连接 和 博客名字  BLOG_TITLE

```php artisan key:generate```

修改 resources/assets/js/components/blog/api/index.js文件中的BaseRoot为你的域名
如果你是用php artisan serve来运行的话 可以先不修改

```php artisan migrate``` 建表
```php artisan db:seed``` 填充数据  你最好先去seeds/UserTableSeeder文件中修改用户名和密码

```npm dun dev```

##使用

本系统有创建文章、删除文章和编辑文章功能,在使用这些功能前,必须进入已登录状态,方法如下:
```yoursite/login?account=xxx&password=xxx  ```

#### 创建新的文章

```
yoursite/create
```

#### 其他
登录状态下,首页会有编辑和删除的按钮在每一个文章后面

## 致谢
系统的界面设计参考了 [这个项目](https://github.com/viko16/vue-ghpages-blog)
创建文章的markdown编辑器[lepture/editor](https://github.com/lepture/editor)