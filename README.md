Magento 2 Add Extra Fee To Order
==============================

"Extra Fee" is an extension for add extra fee to order of your Magento 2 online store. Let your customers see the extra fee on cart page and also in checkout page.

Installation instructions
=========================

<b>Install Via Composer:</b>

1. Go to Magento2 root folder

2. Enter following commands to install module:

    1. `composer config repositories.mageprince git https://github.com/mageprince/magento2-extrafee.git`</br>
    2. `composer require mageprince/magento2-extrafee:dev-master` </br>
    3. `php bin/magento setup:upgrade`
    4. `php bin/magento setup:static-content:deploy`
    

<b>Install Manually:</b>

* Create folder `app/code/Prince/Extrafee` and copy the content of the repo 
* Run command:
<b>php bin/magento setup:upgrade</b>
* Run Command:
<b>php bin/magento setup:static-content:deploy</b>

<b>SCREENSHOTS</b>

<b>Configuration Settings</b> 

<img src="https://raw.githubusercontent.com/mageprince/all-module-screenshots/master/ExtraFee/configuration.png" alt="admin_Screenshot" border="0"/>

<b>Cart Page</b>

<img src="https://raw.githubusercontent.com/mageprince/all-module-screenshots/master/ExtraFee/cartpage.png" alt="cartPage" border="0"/>

<b>Checkout Page</b>

<img src="https://raw.githubusercontent.com/mageprince/all-module-screenshots/master/ExtraFee/checkoutpage.png" alt="checkout_Page" border="0"/>
