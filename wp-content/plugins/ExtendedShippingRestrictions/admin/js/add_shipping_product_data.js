/**
 * Created by S on 3/17/2015.
 */

jQuery(document).ready(function($){

    $html = '<p class="form-field _available">' +
            '<label>Available Shipping Locations</label><br />' +
            '<select name="izw_available">' +
                '<option value="uni">United States</option>' +
                '<option value="hi">hihi</option>' +
            '</select>' +
            '</p>';

    $( "#shipping_product_data .options_group ._weight_field").before( $html );

});
