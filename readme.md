## z-blog
这是一个简单的个人博客系统,使用laravel5.4

## 安装
clone代码到你本地

composer install

npm install

复制.env文件, 配置好各项参数,尤其是数据库连接 和 博客名字  BLOG_TITLE

php artisan key:generate

修改 resouce/assets/js/components/blog/api/index.js文件中的BaseRoot为你的域名
如果你是用php artisan serve来运行的话 可以先不修改

php artisan migrate 建表
php artisan db:seed 填充数据  你最好先去seeds/UserTableSeeder文件中修改用户名和密码

##使用