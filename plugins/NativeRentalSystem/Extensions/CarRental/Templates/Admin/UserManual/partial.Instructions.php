<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Car Rental Instructions</span>
</h1>
<p><strong>Step 1</strong> - You already have the plugin installed in your system.</p>
<ul>
    <li><em>(Optional) Step 1.1</em> - If your theme <span class="success"><strong>already supports</strong></span> support full screen car images preview,
        please <span class="failed"><strong>disable</strong></span> FancyBox in Car Rental -&gt; Settings -&gt; &quot;Global&quot; tab.</li>
    <li><em>(Optional) Step 1.2</em> - If your theme <span class="failed"><strong>does not</strong></span> support FontAwesome icons,
        please <span class="success"><strong>enable</strong></span> FontAwesome in Car Rental -&gt; Settings -&gt; &quot;Global&quot; tab.</li>
    <li><em>(Optional) Step 1.3</em> - If your theme <span class="success"><strong>already supports</strong></span> Slick Slider,
        please <span class="failed"><strong>disable</strong></span> Slick Slider in Car Rental -&gt; Settings -&gt; &quot;Global&quot; tab.</li>
</ul>
<p><strong>Step 2</strong> - Now create a page by clicking the [Add New] button under the page menu.</p>
<p><strong>Step 3</strong> - Add <strong>[car_rental_system display=&quot;search&quot; steps=&quot;form,list,list,table,table&quot;]</strong> shortcode to page content and click on [Publish] button.</p>
<ul>
    <li><em>(Optional) Step 3.1</em> - For car slider, use <strong>[car_rental_system display=&quot;cars&quot; layout=&quot;slider&quot;]</strong> shortcode.</li>
    <li><em>(Optional) Step 3.2</em> - For reservation editing, use <strong>[car_rental_system display=&quot;edit&quot; steps=&quot;form,list,list,table,table&quot;]</strong> shortcode.</li>
    <li><em>(Optional) Step 3.3</em> - For car list, use <strong>[car_rental_system display=&quot;cars&quot; layout=&quot;list&quot;]</strong> shortcode.</li>
    <li><em>(Optional) Step 3.3</em> - For location list, use <strong>[car_rental_system display=&quot;locations&quot; layout=&quot;list&quot;]</strong> shortcode.</li>
    <li><em>(Optional) Step 3.4</em> - For cars price table, use <strong>[car_rental_system display=&quot;prices&quot; layout=&quot;table&quot;]</strong> shortcode.</li>
    <li><em>(Optional) Step 3.5</em> - For cars availability calendar, use <strong>[car_rental_system display=&quot;availability&quot; layout=&quot;calendar&quot;]</strong> shortcode.</li>
    <li><em>(Optional) Step 3.6</em> - For extras price table, use <strong>[car_rental_system display=&quot;extra_prices&quot; layout=&quot;table&quot;]</strong> shortcode.</li>
    <li><em>(Optional) Step 3.7</em> - For extras availability calendar, use <strong>[car_rental_system display=&quot;extras_availability&quot; layout=&quot;calendar&quot;]</strong> shortcode.</li>
    <li><em>(Optional) Step 3.8</em> - For manufacturers grid, use <strong>[car_rental_system display=&quot;manufacturers&quot; layout=&quot;grid&quot;]</strong> shortcode.</li>
    <li><em>(Optional) Step 3.9</em> - For benefits list, use <strong>[car_rental_system display=&quot;benefits&quot; layout=&quot;list&quot;]</strong> shortcode.</li>
</ul>
<p><strong>Step 4</strong> - In WordPress front-end page, where you added search shortcode, you will see reservation engine.</p>
<p><strong>Step 5</strong> - Congratulations, you&#39;re done! We wish you to have a pleasant work with our Native Car Rental System for WordPress.</p>
<h3>Additional Notes</h3>
<ol>
    <li>Make sure that your &quot;/wp-content/uploads/&quot; directory is writable.</li>
    <li>If server is using not apache user to write to folder, CHMOD 0755 is not enough - you need to set permissions (CHMOD) to 0777.</li>
    <li>If you have a multisite setup, you need to do the same CHMOD to 0777 to &quot;/wp-content/uploads/sites/2/&quot;,
&quot;/wp-content/uploads/sites/3/&quot; etc. folders.</li>
    <li>Please note, that if you see that after update or after uploading the first car image the &quot;/wp-content/uploads/CarRentalGallery/&quot;
directory is not created (or &quot;/wp-content/uploads/sites/2/CarRentalGallery/&quot; etc. for multisite setup),
you will have to create that directory manually via FTP client like FileZilla and set it&#39;s permission to 0777.</li>
</ol>