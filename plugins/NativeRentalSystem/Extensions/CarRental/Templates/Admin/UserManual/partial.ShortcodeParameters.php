<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Car Rental Shortcode Parameters</span>
</h1>
<p>
    <strong>DISPLAY parameter values (required, case insensitive):</strong>
</p>
<ul>
    <li>
        display=&quot;search&quot; - uses &quot;STEPS&quot; parameter instead of &quot;LAYOUT&quot; parameter
    </li>
    <li>
        display=&quot;edit&quot; - uses &quot;STEPS&quot; parameter instead of &quot;LAYOUT&quot; parameter
    </li>
    <li>
        display=&quot;car&quot; - does not use &quot;LAYOUT&quot; parameter
    </li>
    <li>
        display=&quot;location&quot; - does not use &quot;LAYOUT&quot; parameter
    </li>
    <li>
        display=&quot;cars&quot; - supports &quot;list&quot; and &quot;slider&quot; values for &quot;LAYOUT&quot; parameter
    </li>
    <li>
        display=&quot;locations&quot; - supports &quot;list&quot; value for &quot;LAYOUT&quot; parameter
    </li>
    <li>
        display=&quot;benefits&quot; - supports &quot;slider&quot; value for &quot;LAYOUT&quot; parameter
    </li>
    <li>
        display=&quot;manufacturers&quot; - supports &quot;grid&quot; and &quot;slider&quot; values for &quot;LAYOUT&quot; parameter
    </li>
    <li>
        display=&quot;prices&quot; - supports &quot;table&quot; value for &quot;LAYOUT&quot; parameter
    </li>
    <li>
        display=&quot;extra_prices&quot; - supports &quot;table&quot; value for &quot;LAYOUT&quot; parameter
    </li>
    <li>
        display=&quot;availability&quot; - supports &quot;calendar&quot; value for &quot;LAYOUT&quot; parameter
    </li>
    <li>
        display=&quot;extras_availability&quot; - supports &quot;calendar&quot; value for &quot;LAYOUT&quot; parameter
    </li>
</ul>


<p>
    <strong>LAYOUT parameter values (optional, case insensitive):</strong>
</p>
<ul>
    <li>
        <em>(none)</em>
    </li>
    <li>
        layout=&quot;form&quot;
    </li>
    <li>
        layout=&quot;widget&quot;
    </li>
    <li>
        layout=&quot;slider&quot;
    </li>
    <li>
        layout=&quot;list&quot;
    </li>
    <li>
        layout=&quot;grid&quot;
    </li>
    <li>
        layout=&quot;table&quot;
    </li>
    <li>
        layout=&quot;calendar&quot;
    </li>
    <li>
        layout=&quot;tabs&quot;
    </li>
</ul>


<p>
    <strong>Specific parameters when DISPLAY=&quot;SEARCH&quot; or DISPLAY=&quot;EDIT&quot; (case insensitive):</strong>
</p>
<ul>
    <li>
        <em>(required)</em>&nbsp; steps=&quot;form,list,list,table,table&quot; (default is &quot;form,list,list,table,table&quot;)
    </li>
    <li>
        <em>(optional)</em>&nbsp; action_page=&quot;1&quot; (default is same page - &#39;0&#39;)
    </li>
</ul>


<p>
    <strong>Specific required parameter when DISPLAY=&quot;LOCATION&quot;:</strong>
</p>
<ul>
    <li>
        location=&quot;1&quot; (default is all - &#39;-1&#39;)
    </li>
</ul>


<p>
    <strong>Specific optional parameter when LAYOUT=&quot;FORM&quot; (or when &quot;FORM&quot; is in STEPS array):</strong>
</p>
<ul>
    <li>
        action_page=&quot;1&quot; (default is same page - &#39;0&#39;)
    </li>
</ul>


<p>
    <strong>Additional parameters (optional, case insensitive):</strong>
</p>
<ul>
    <li>
        car=&quot;1&quot; (default is all - &#39;-1&#39;)
    </li>
    <li>
        extra=&quot;1&quot; (default is all - &#39;-1&#39;)
    </li>
    <li>
        manufacturer=&quot;1&quot; (default is all - &#39;-1&#39;)
    </li>
    <li>
        body_type=&quot;1&quot; (default is all - &#39;-1&#39;)
    </li>
    <li>
        transmission_type=&quot;1&quot; (default is all - &#39;-1&#39;)
    </li>
    <li>
        fuel_type=&quot;1&quot; (default is all - &#39;-1&#39;)
    </li>
    <li>
        partner=&quot;1&quot; (default is all - &#39;-1&#39;)
    </li>
    <li>
        location=&quot;1&quot; (default is all - &#39;-1&#39;)
    </li>
    <li>
        pickup_location=&quot;1&quot; (default is all - &#39;-1&#39;)
    </li>
    <li>
        return_location=&quot;1&quot; (default is all - &#39;-1&#39;)
    </li>
</ul>


<h3>Examples:</h3>
<p><strong>#1 - Demo shortcodes:</strong></p>
<pre>
    [car_rental_system display=&quot;search&quot; steps=&quot;form,list,list,table,table&quot;]
    [car_rental_system display=&quot;search&quot; manufacturer=&quot;2&quot; action_page=&quot;255&quot; steps=&quot;form,list,list,table,table&quot;]

    [car_rental_system display=&quot;cars&quot; layout=&quot;list&quot;]
    [car_rental_system display=&quot;cars&quot; partner=&quot;4&quot; body_type=&quot;10&quot; layout=&quot;list&quot;]

    [car_rental_system display=&quot;car&quot; car=&quot;5&quot;]
    [car_rental_system display=&quot;search&quot; car=&quot;5&quot; steps=&quot;form,list,list,table,table&quot;]

    [car_rental_system display=&quot;location&quot; location=&quot;5&quot;]
    [car_rental_system display=&quot;search&quot; location=&quot;5&quot; steps=&quot;form,list,list,table,table&quot;]

</pre>
<p><strong>#2 - Exact car use case:</strong></p>
<ul>
    <li>After you will add some cars, and will know their IDs (i.e. ID=7), if you want to have a separate page, for individual car, you can create a new page, then:</li>
    <li>For exact car model description, use <strong>[car_rental_system display=&quot;item&quot; item=&quot;7&quot;]</strong> shortcode.</li>
    <li>For exact car model search, use <strong>[car_rental_system display=&quot;search&quot; item=&quot;7&quot; steps=&quot;form,list,list,table,table&quot;]</strong> shortcode.</li>
</ul>
<p><strong>#3 - Exact location use case:</strong></p>
<ul>
    <li>After you will add some locations, and will know their IDs (i.e. ID=1), if you want to have a separate page, for individual location, you can create a new page.</li>
    <li>For exact location description, use <strong>[car_rental_system display=&quot;location&quot; location=&quot;1&quot;]</strong> shortcode.</li>
    <li>For search in this location only, use <strong>[car_rental_system display=&quot;search&quot; pickup_location=&quot;1&quot; return_location=&quot;1&quot; steps=&quot;form,list,list,table,table&quot;]</strong> shortcode.</li>
</ul>