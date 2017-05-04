![alt tag](https://github.com/Menaprocms/menapro/blob/master/img/logo.png)

Menapro
=======

###Free Pagebuilder and cms.

Content management system for websites. With pagebuilder inside. Easy to use and fast, too fast. No shortcodes needed.

----------	

**INSTALLATION**
There are two ways to install. 

1. Using composer.
    Run `composer create-project menaprocms/menapro menapro --prefer-dist`
2. Download from official website [MenaPro.com](http://menapro.com)
	Unzip and upload content to your server. 


MenaPro comes with autoinstaller. Access your hosting after uploading (or after composer)  and it will launch automatically.

**REQUIREMENTS**

PHP (from 5.4 to 7)
APACHE
MySQL or MaríaDB


----------

Wanna know more...
------------------

If you want to know more check the [Official website.](http://menapro.com)


Made for everyone
-----------------

MenaPro has been designed to be easy and powerful, and it is. Everyone can create awesome sites in less time than with other cms.

##Contribute

Have found something that does not work as expected? Tell us or create a pull request.



[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)

CHANGELOG
---------
**Menapro 1.1.2**

[\*] Update jsblocksE.js
[\*] Use FileHelper::createDirectory instead of mkdir

[-] Thumbnail function: Exception if file does not exists.
[-] 404 image extension.

**Menapro 1.1.1**

[-] News: Correct url in news list at home page.
[-] News: Added condition published in getSinglePost method.
[-] News management: Published button load the model value and is marked by default in new posts.
[-] News management: Toggle published refresh data of update post link.

[+] News: Added link to page content in view one post link.
[+] News management: Preview button.

**Menapro 1.1.0**

[+] Add news functionallity, categorizable content.
[+] New block news for use it.




