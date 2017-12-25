Magento 2 Add Extra Fee To Order
==============================

"Extra Fee" is an extension for add extra fee to order of your Magento 2 online store. Let your customers see the extra fee on cart page and also in checkout page.

Installation instructions
=========================

<b>Install Via Composer:</b>

1. Go to Magento2 root folder

2. Enter following commands to install module:

    `composer config repositories.mageprince git https://github.com/mageprince/magento2-extrafee.git`
    `composer require mageprince/magento2-extrafee:dev-master`

   Wait while dependencies are updated.

3. Enter following commands to enable module:

    `php bin/magento module:enable Prince_Extrafee`
    `php bin/magento setup:upgrade`

<b>Install Manually:</b>

* Copy the content of the repo to the Magento 2 root folder
* Run command:
<b>php bin/magento setup:upgrade</b>
* Run Command:
<b>php bin/magento setup:static-content:deploy</b>
* Now Flush Cache: <b>php bin/magento cache:flush</b>


<b>ADMIN</b> 

<img src="https://preview.ibb.co/fcK6fa/admin_Screenshot.png" alt="admin_Screenshot" border="0"/>

<b>CART PAGE</b>

<img src="https://preview.ibb.co/j0R8tv/cartPage.png" alt="cartPage" border="0"/>

<b>CHECKOUT PAGE</b>

<img src="https://preview.ibb.co/esZuYv/checkout_Page.png" alt="checkout_Page" border="0"/>
