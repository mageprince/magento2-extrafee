Magento 2 Add Extra Fee To Order
==============================

"Extra Fee" is an extension for add extra fee to order of your Magento 2 online store. Let your customers see the extra fee on cart page and also in checkout page.

Installation instructions
=========================

<b>Install Via Composer:</b>

1. Go to Magento2 root folder

2. Enter following commands to install module:

    `composer config repositories.mageprince git https://github.com/mageprince/magento2-extrafee.git`</br>
    `composer require mageprince/magento2-extrafee:dev-master` </br>
    `php bin/magento setup:upgrade`
    

<b>Install Manually:</b>

* Create folder `app/code/Prince/Extrafee` and copy the content of the repo 
* Run command:
<b>php bin/magento setup:upgrade</b>
* Run Command:
<b>php bin/magento setup:static-content:deploy</b>
* Now Flush Cache: <b>php bin/magento cache:flush</b>

<b>SCREENSHOTS</b>

<b>Configuration Settings</b> 

<img src="https://raw.githubusercontent.com/mageprince/all-module-screenshots/master/ExtraFee/configuration.png" alt="admin_Screenshot" border="0"/>

<b>Cart Page</b>

<img src="https://raw.githubusercontent.com/mageprince/all-module-screenshots/master/ExtraFee/cartPage.png" alt="cartPage" border="0"/>

<b>Checkout Page</b>

<img src="https://raw.githubusercontent.com/mageprince/all-module-screenshots/master/ExtraFee/checkout_Page.png" alt="checkout_Page" border="0"/>
