<?php
/*
* Schema for products
*/

function woocommerce_schema_product(){
    if(is_product){
        global $post; // Get current post (product) data
        $product_id = $post->ID; // Get product ID
        $product = wc_get_product($product_id); // Get product information

        $name = $product->get_name(); // Get product name
        $description = $product->get_short_description(); // Get product description
        $price = $product->get_price(); // Get product price
        $currency = get_woocommerce_currency();
        $stock_status = $product->get_stock_status(); // Get product stock status -- 1. instock 2. outofstock 3. onbackorder

        $review_count = $product->get_review_count(); // Get review count
        $reviews_args = [ // Review arguments
            'post_id' => $product_id
        ];
        $reviews = get_comments($reviews_args); // Get reviews and store as array
        $avg_rating = trim($product->get_average_rating(), '0'); // Get product rating ?>
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "Product",
                <?php if($review_count > 1){ ?>

                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "<?php echo $avg_rating; ?>",
                    "reviewCount": "<?php echo $review_count; ?>"
                },
                <?php } ?>

                "name": "<?php echo $name; ?>",
                <?php if($description != ''){
                    echo '"description": "' . $description . '",';
                } ?>

                "offers": {
                    "@type": "Offer",
                    "availability": "<?php if($stock_status == 'instock'){ // If in stock
                        echo 'https://schema.org/InStock';
                    } elseif($stock_status == 'outofstock') { // If out of stock
                        echo 'https://schema.org/OutOfStock';
                    } elseif($stock_status == 'onbackorder'){ // If on backorder
                        echo 'https://schema.org/PreOrder';
                    } ?>",
                    "price": "<?php echo $price; ?>",
                    "priceCurrency": "<?php echo $currency; ?>"
                },
                <?php if($review_count == 1){ ?>

                "review": [
                    <?php foreach($reviews as $review){
                        $id = $review->comment_ID;
                        $reviewer = $review->comment_author;
                        $date = $review->comment_date;
                        $content = $review->comment_content;
                        $rating = get_comment_meta($id, 'rating', true); ?>

                    {
                        "@type": "Review",
                        "author": "<?php echo $reviewer; ?>",
                        "datePublished": "<?php echo date("Y-m-d", strtotime($date)); ?>",
                        "description": "<?php echo $content; ?>",
                        "reviewRating": {
                            "@type": "Rating",
                            "bestRating": "5",
                            "ratingValue": "<?php echo $rating; ?>",
                            "worstRating": "1"
                        }
                    }
                    <?php } ?>

                ]
                <?php } elseif($review_count > 1){ ?>

                "review": [
                    <?php $i = 0;
                    foreach($reviews as $review){
                        $id = $review->comment_ID;
                        $reviewer = $review->comment_author;
                        $date = $review->comment_date;
                        $content = $review->comment_content;
                        $rating = get_comment_meta($id, 'rating', true); ?>

                    {
                        "@type": "Review",
                        "author": "<?php echo $reviewer; ?>",
                        "datePublished": "<?php echo date("Y-m-d", strtotime($date)); ?>",
                        "description": "<?php echo $content; ?>",
                        "reviewRating": {
                            "@type": "Rating",
                            "bestRating": "5",
                            "ratingValue": "<?php echo $rating; ?>",
                            "worstRating": "1"
                        }
                    }<?php if(++$i != $review_count){ ?>,<?php } ?>
                    <?php } ?>

                ]
                <?php } ?>

            }
        </script>
    <?php }
} add_action('wp_head', 'woocommerce_schema_product');
