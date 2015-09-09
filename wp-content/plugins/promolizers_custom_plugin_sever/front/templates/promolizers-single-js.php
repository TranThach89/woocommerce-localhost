<?php
/**
 * Created by PhpStorm.
 * User: thach
 * Date: 9/16/14
 * Time: 10:09 AM
 */
global $izwpsp;
?>

<script type="text/javascript">
    // JS STEP1     JS STEP1
    var sumquaty = <?php echo (int)min($izwpsp['DT_izw_min']);?>;//gia tri max quaty
    var position = 0;
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    jQuery(document).ready(function($){
        var max = parseInt( $('input[name="max"]').val());
        var min = parseInt( $('input[name="min"]').val());
        $("#qty-text").keypress(function (e){
            var value = String.fromCharCode(e.which);
            var value2 = $(this).val() + value;
            var charCode = (e.which) ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            if(parseInt(value2) > max){
                $("#qty-slider").slider({
                    value: max
                });
                $("#amount").text(parseInt(max));
                $("#inputAmount").val(parseInt(max));
                jquery_sum_product(parseInt(max));
                sumquaty = parseInt(max);
                return true;
            }else if(parseInt(value2) < min){
                $("#qty-slider").slider({
                    value: min
                });
                $("#amount").text(parseInt(min));
                $("#inputAmount").val(parseInt(min));
                jquery_sum_product(parseInt(min));
                sumquaty = parseInt(min);
                return true;
            }else{
                $("#qty-slider").slider({
                    value: parseInt(value2)
                });
                $("#amount").text(parseInt(value2));
                $("#inputAmount").val(parseInt(value2));
                jquery_sum_product(parseInt(value2));
                sumquaty = parseInt(value2);
                return true;
            }
            if(value2 == '' ){
                $("#amount").text(parseInt(min));
                $("#inputAmount").val(parseInt(min));
                jquery_sum_product(parseInt(min));
                sumquaty = parseInt(min);
                return false;
            }

            return true;
        });
        $("#qty-slider").slider({
            range: "min",
            value: <?php echo (int)min($izwpsp['DT_izw_min']);?>,
            min: <?php echo (int)min($izwpsp['DT_izw_min']);?>,
            max: <?php echo (int)max($izwpsp['DT_izw_max']);?>,
            step: 1,
            slide: function(event, ui){
                $("#amount").text(parseInt(ui.value));
                $("#qty-text").val(parseInt(ui.value));
                $("#inputAmount").val(parseInt(ui.value));
                jquery_sum_product(parseInt(ui.value));
                sumquaty = parseInt(ui.value);
            }
        });
$("form.cart").on("fs_shown", function(event, fsIndex){
// JS STEP2     JS STEP2
        if (fsIndex && fsIndex == 1){
            $(".addon-imprint-type").click(function(){
                imprinttype = $(this).data('string');
                <?php foreach($izwpsp['imprint_types'] as $type): ?>
                    if('<?php echo $type->type_slug;?>' == imprinttype){
                        //lay gia cua imprint type
                        var $price = parseFloat(get_price_imprint_types(position,<?php echo $type->ID;?>));
                        var $msrp = parseFloat(get_price_msrp(position,<?php echo $type->ID;?>));
                        //lay gia tri setup
                        $setup = parseFloat('<?php echo $type->setup_charge;?>');
                    }
                <?php endforeach;?>
                <?php $total_cart = (float)WC()->cart->subtotal;?>
                if(! check_waive_shipping_jquery(($price * sumquaty) + $setup + <?php echo $total_cart;?>)){
                    <?php if(!empty($izwpsp['DT_izw_override_shipping']) && $izwpsp['DT_izw_override_shipping'] == '1'):?>
                        <?php foreach($izwpsp['DT_izw_shipping'] as $key=>$val){?>
                            if(<?php echo $key;?> == position) $shipping = parseFloat('<?php echo $val;?>');
                        <?php }?>
                    <?php elseif(!empty($izwpsp['DT_izw_flat_rate_shipping']) && $izwpsp['DT_izw_flat_rate_shipping'] == '1'):?>
                            $shipping = parseFloat('<?php echo $izwpsp['DT_izw_flat_rate_amount']; ?>');
                    <?php else:?>
                            $shipping = 0;
                    <?php endif;?>
                }else $shipping = 0;
                display_total_product(sumquaty,$price,$msrp,$setup,$shipping);
            });
        }

        // JS STEP3     JS STEP3
        if (fsIndex && fsIndex == 2){
        $("#list_color").empty();
        imprinttype = $('input[name="imprintType"]').val();//lay imprint type kieu
            var listcolorHtml ='';
            for (var i = 0; i < selColors.length; i++){
                if(i==0){listcolorHtml = '';}
                if(selColors.length != 1){
                    listcolorHtml += '<div id="colorID_' + selColors[i] + '" class="colorTitle"><h3>' + toTitleCase(selColors[i]) + '</h3></div>';
                }

                listcolorHtml += '<div class="colorContent" id="ColorIDContent_' + selColors[i] + '">';

                if(imprinttype == "embroidery") {
                     listcolorHtml += '<label for="thread-color"><?php _e( "Thread Color: ", __TEXTDOMAIN__ ); ?><abbr class="required" title="required">*</abbr></label>\
                    <p style="margin-bottom: 7px;">You Should Only select a thread color if you would like add custom text you order.</p>';
                }else {listcolorHtml += '<p for="thread-color"><?php _e( "Imprint color: ", __TEXTDOMAIN__ );?><abbr class="required" title="required">*</abbr></p>';}

                listcolorHtml += '<div class="check_all">\
                    <div class="screen_check">';

    <?php if(!empty($izwpsp['imprint_colors'])){foreach ($izwpsp['imprint_colors'] as $key => $color):?>
        <?php if(($key % 4) == 0){?>
            listcolorHtml += '<p class="form-row form-row-wide product-addon"><span title="<?php echo $color->color_name;?>" id="<?php echo $color->color_slug;?>" class="addon addon-color-swatch" data-string="<?php echo $color->color_slug;?>" style="background:<?php echo $color->hex_code;?>"></span>';
        <?php }elseif(($key % 4) == 3){?>
                listcolorHtml += '<span title="<?php echo $color->color_name;?>" id="<?php echo $color->color_slug;?>" class="addon addon-color-swatch" data-string="<?php echo $color->color_slug;?>" style="background:<?php echo $color->hex_code;?>"></span></p>';
        <?php }else{?>
            listcolorHtml += '<span title="<?php echo $color->color_name;?>" id="<?php echo $color->color_slug;?>" class="addon addon-color-swatch" data-string="<?php echo $color->color_slug;?>" style="background:<?php echo $color->hex_code;?>"></span>';
    <?php }endforeach;} else{?> listcolorHtml += 'Please Choose Imprint color!';<?php }?>

                listcolorHtml += '</p>\
                    </div></div>';

                if(imprinttype != "embroidery"){
                listcolorHtml += '<div class="screen_check_color">\
                        <div class="vehicle">\
                            <input type="checkbox" class="vehicle_check" value="Car" name="vehicle">Specify Your own color(s)<br>\
                        </div>\
                        <div class="checkedcolor">\
                            <div class="addcolor">\
                                <div class="addcolor1">\
                                    <div class="input"><input type="radio" value="Pantone" name="checkedcolor[' + selColors[i] + '][1]" checked="true" />Pantone/PMS</div>\
                                    <div class="input"><input type="radio" value="RGB" name="checkedcolor[' + selColors[i] + '][1]">RGB color</div>\
                                    <div class="input"><input type="radio" value="Hex" name="checkedcolor[' + selColors[i] + '][1]">Hex color</div>\
                                    <div class="input"><input type="radio" value="Custom" name="checkedcolor[' + selColors[i] + '][1]">Custom</div>\
                                    <label class="labelSize">Pantone:</label>\
                                    <input type="text"  class="stepinputcolor" value="" name="pantone[]" readonly>\
                                    <span class="step3color">C</span><br>\
                                </div>\
                            </div>\
                            <button type="button" class="delete_color_class">Delete Color</button>\
                            <button type="button" class="add_color_class">Add Color</button>\
                        </div>\
                    </div>';}
                else{
                listcolorHtml += '<div class="screen_check_color">\
                        <input class="vehicle_none" type="checkbox" name="none_' + selColors[i] + '" value="Car">None(Select this only if you plan on uploading an artwork file and do not want to add custom text)<br>\
                        <div class="vehicle">\
                            <input type="checkbox" class="vehicle_check_embroidery" value="Car" name="vehicle_' + selColors[i] + '">Specify Your own color(s)<br>\
                        </div>\
                        <div class="checkedcolor">\
                                <div class="input"><input type="radio" value="Pantone" name="checkedcolor[' + selColors[i] + '][1]" checked>Pantone/PMS</div>\
                                <div class="input"><input type="radio" value="RGB" name="checkedcolor[' + selColors[i] + '][1]">RGB color</div>\
                                <div class="input"><input type="radio" value="Hex" name="checkedcolor[' + selColors[i] + '][1]">Hex color</div>\
                                <div class="input"><input type="radio" value="Custom" name="checkedcolor[' + selColors[i] + '][1]">Custom</div>\
                                <label class="labelSize">Pantone:</label>\
                                <input type="text"  class="stepinputcolor" value="" name="pantone[]" readonly>\
                                <label class="step3color">C</label><br>\
                        </div>\
                 </div>';
                    }
                <?php if($izwpsp['check_size']){ ?>
                listcolorHtml += '<div class="ColorSize">\
                        <p class="form-row form-row-wide product-addon">\
                            <input type="hidden" value="0" id="amount' + i + '"/>\
                            <label for="thread-color">Size:</label>\
                            <span class="amount-color-step3" id="amount-' + selColors[i] + '"></span>\
                        </p>\
                        <label class="labelSize">Small:</label><div id="slider-small-' + selColors[i] + '" class="addon addon-total-quantity"></div>\
                        <input id="text-small-' + selColors[i] + '" class="addon color_changes" type="text" value=0 name="step3size[' + selColors[i] + '][small]" readonly/><br />\
                        <label class="labelSize">Medium:</label><div id="slider-medium-' + selColors[i] + '" class="addon addon-total-quantity"></div>\
                        <input id="text-medium-' + selColors[i] + '" class="addon color_changes" type="text" value=0 name="step3size[' + selColors[i] + '][medium]" readonly/><br />\
                        <label class="labelSize">Large:</label><div id="slider-large-' + selColors[i] + '" class="addon addon-total-quantity"></div>\
                        <input id="text-large-' + selColors[i] + '" class="addon color_changes" type="text" value=0 name="step3size[' + selColors[i] + '][large]" readonly/><br />\
                        <label class="labelSize">X-Large:</label><div id="slider-xlarge-' + selColors[i] + '" class="addon addon-total-quantity"></div>\
                        <input id="text-xlarge-' + selColors[i] + '" class="addon color_changes" type="text" value=0 name="step3size[' + selColors[i] + '][xlarge]" readonly/><br />\
                    </div>\
                    <div id="ThreadColorInput' + selColors[i] + '"></div>\
                    <div class="clear"></div>\
                </div>';
                <?php }else{ ?>
                listcolorHtml += '<div class="ColorSize no-colorSize"></div>';
                <?php } ?>
            }
        $("#list_color").html(listcolorHtml);
        var size = ["small", "medium", "large", "xlarge"];
        for(i in selColors){
            sum[i] = 0;
             size.forEach(function (val,j, theArray){
                showscript(selColors[i],val,size,sumquaty,i);
            });
        };

        for (i = 0; i < selColors.length; i++){
            $colorswatches2 = $('#ColorIDContent_' + selColors[i] + ' p span.addon-color-swatch');
            $colorname2 = $('#ColorIDContent_' + selColors[i] + ' h3.addon-name');
            $colorinput2 = $('#ThreadColorInput' + selColors[i]);
            $colorarray2 = 'threadColor[' + selColors[i] + ']';
            currentSelColors[selColors[i]]= new Array();
            save_array_vehicle[selColors[i]]= new Array();
            save_num[selColors[i]] = [];// dem so luong Imprint color da lua chon
            $countadd[selColors[i]] = [];// so luong add_color
            imprinttype_js_step3(selColors[i],imprinttype,imprintsetcolor,$colorswatches2,$colorname2,$colorinput2,$colorarray2);
            //imprintsetcolor so luong color duoc chon toi da cho buoc 3
        }

        $('#list_color .colorContent:not(:first)').hide();
        $("#list_color .colorTitle").click(function(){
            $('#list_color .colorContent').slideUp('normal');
            if($(this).next('#list_color .colorContent').is(':hidden') == true){
                $(this).next('#list_color .colorContent').slideDown('normal');
            }
        });

        }
    });


    //
    //      FUNCTION
    //
    function jquery_sum_product($ui){
        <?php $max = count($izwpsp['DT_izw_max']); for($i = 0;$i < $max ;$i++){?>
            if((<?php echo (int)$izwpsp['DT_izw_min'][$i];?> <= $ui) && (<?php echo (int)$izwpsp['DT_izw_max'][$i];?> >= $ui)){
                <?php $price = get_price_DT($i,$izwpsp['DT_izw_imprint_type_default']);?>
                <?php $setup = get_step_imprint_types($izwpsp['DT_izw_imprint_type_default']);?>
                <?php $total_cart = (float)WC()->cart->subtotal;?>
                if (!(check_waive_shipping_jquery((<?php echo $price;?> * $ui) + <?php echo $setup;?> + <?php echo $total_cart;?>))){
                    <?php
                    if(!empty($izwpsp['DT_izw_override_shipping']) && $izwpsp['DT_izw_override_shipping'] == '1'):?>
                        $shipping = '<?php echo $izwpsp['DT_izw_shipping'][$i];?>';
                    <?php
                    elseif(!empty($izwpsp['DT_izw_flat_rate_shipping']) && $izwpsp['DT_izw_flat_rate_shipping'] == '1'):?>
                        $shipping = <?php echo $izwpsp['DT_izw_flat_rate_amount'];?>;
                    <?php
                    else:?>
                        $shipping = 0;
                    <?php endif;?>
                }else $shipping = 0;
                display_total_product($ui,<?php echo $price;?>,<?php echo $izwpsp['DT_izw_msrp'][$i][$izwpsp['DT_izw_imprint_type_default']];?>,<?php echo $setup;?>,parseFloat($shipping));
                get_price_imprint_types(<?php echo $i;?>,-1);
                position = <?php echo $i;?>;
            }
    <?php }?>
    }

    function display_total_product($ui,$price,$msrp,$setup,$shipping){
        //$ui           Quantity
        //$price        Price
        //$msrp         MSRP Price
        //$setup        Setup Price
        //$shipping     Shipping Price
        //.totalstep1   Total Price
        var Saving = 0;
        Saving = ($msrp - $price)*$ui;
        if(Saving <=0){
            Saving = 0;
        }
        Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
            var n = this,
                decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
                decSeparator = decSeparator == undefined ? "." : decSeparator,
                thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
                sign = n < 0 ? "-" : "",
                i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
                j = (j = i.length) > 3 ? j % 3 : 0;
            return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
        };
        $('.sub_total_table h4 .totalstep1').html("$" +($price * $ui).formatMoney(2));
        $('.sub_total_table .pricestep1').html("$" + $price.formatMoney(2) + " x " + $ui);
        $('.sub_total_table .savingstep1').html("$" + Saving.formatMoney(2));
        $('.sub_total_table .totalproduct').html("$" + (($price * $ui) + $setup + $shipping).formatMoney(2));
        if($shipping == 0) $('.sub_total_table .romoship span').html("Free");
            else $('.sub_total_table .romoship span').html("$" + $shipping.formatMoney(2) + "");
        $('input[name = "romoship"]').val(($shipping).toFixed(2));
        $('.sub_total_table .setup').html("$" +($setup).formatMoney(2));
    }

    function get_price_imprint_types($i,$posi){
        //$i        vi tri cot
        //$posi     vi tri imprint
        <?php if(check_onsale_product()):?>
            <?php foreach($izwpsp['DT_izw_sale'] as $key=>$price):?>
                if($i == <?php echo $key;?>){
                    <?php foreach($price as $k=>$v){?>
                        if($posi == -1){
                            $(".price_change_<?php echo $k; ?>").html("$<?php echo $v;?> /item");
                        }
                        if($posi == <?php echo $k; ?>) return "<?php echo $v; ?>";
                    <?php }//price ?>
                }
            <?php endforeach;//DT_izw_price ?>
        <?php else :?>
            <?php foreach($izwpsp['DT_izw_price'] as $key=>$price):?>
                if($i == <?php echo $key;?>){
                    <?php foreach($price as $k=>$v){?>
                        if($posi == -1){
                            $(".price_change_<?php echo $k; ?>").html("$<?php echo $v;?> /item");
                        }
                        if($posi == <?php echo $k; ?>) return "<?php echo $v; ?>";
                    <?php }//price ?>
                }
            <?php endforeach;//DT_izw_price ?>
        <?php endif; ?>
    }

    function get_price_msrp($i,$posi){
        //$i        vi tri cot
        //$posi     vi tri imprint
        <?php foreach($izwpsp['DT_izw_msrp'] as $key=>$price):?>
            if($i == <?php echo $key;?>){
                <?php foreach($price as $k=>$v){?>
                    if($posi == <?php echo $k; ?>) return "<?php echo $v; ?>";
                <?php }//price ?>
            }
        <?php endforeach;//DT_izw_msrp ?>
    }

    function check_waive_shipping_jquery($total){
        $return = false;
        <?php if(!empty($izwpsp['DT_izw_check_order_total']) && $izwpsp['DT_izw_check_order_total'] == '1'):?>
            if(parseFloat($total) >= parseFloat("<?php echo $izwpsp['DT_izw_shipping_order_total'];?>")) $return = true;
                else $return = false;
        <?php endif;?>
        return $return;
    }

});
</script>
