<?php
include("components/header.php");



?>

<style>
    .set-bg {
        height: 300px; /* Adjust as needed */
        background-size: cover;
        background-position: center;
        
    }
    .featured__item__pic.set-bg {
        height: 350px; /* Adjust as needed */
    }
    .categories__item.set-bg {
        height: 250px; /* Adjust as needed */
    }
</style>
<!-- Hero Section Begin -->
<section class="hero mt-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all">
                        <i class="fa fa-bars"></i>
                        <span>All Categories</span>
                    </div>
                    <ul>
                        <?php foreach ($categories as $category): ?>
                            <li><a href="#"><?php echo htmlspecialchars($category['category_name']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hero__search">
                    <div class="hero__search__form">
                        <form action="#" method="GET" id="searchForm">
                            <div class="hero__search__categories">
                                All Categories
                                <span class="arrow_carrot-down"></span>
                            </div>
                            <input type="text" name="search_query" id="searchInput" placeholder="What do you need?">
                            <button type="submit" class="site-btn">SEARCH</button>
                        </form>
                        <!-- Search Results Container -->
                        <div id="searchResults" class="search-results-container"></div>
                    </div>
                    <div class="hero__search__phone">
                        <div class="hero__search__phone__icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="hero__search__phone__text">
                            <h5>+65 11.188.888</h5>
                            <span>support 24/7 time</span>
                        </div>
                    </div>
                </div>
                <div class="hero__item set-bg" data-setbg="images/Banner.png">
                    <div class="hero__text" style="color: #F5E1C8; font-weight: bold; text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);">
                        <span>Gifts and stationery shop</span>
                        <h2>Shop Now <br />100% Original items</h2>
                        <p>Free Pickup and Delivery Available</p>
                        <a href="shop-grid.php" class="primary-btn">SHOP NOW</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Hero Section End -->

<!-- Categories Section Begin -->
<section class="categories">
    <div class="container">
        <div class="row">
        <div class="section-title">
                    <h2>Categories</h2>
                </div>
            <div class="categories__slider owl-carousel">
                <?php foreach ($categories as $category): ?>
                    <?php 
                    // Default image if missing
                    $image_path = !empty($category['image_path']) ? htmlspecialchars($category['image_path']) : 'default.jpg';
                    ?>
                    
                    <div class="col-lg-3">
                        <div class="categories__item set-bg" data-setbg="<?php echo $image_path; ?>" 
                            style="background-image: url('<?php echo $image_path; ?>');">
                            <br>
                            <h5><a href="#"><?php echo htmlspecialchars($category['category_name']); ?></a></h5>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<!-- Categories Section End -->

<!-- Featured Section Begin -->
<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Featured Products</h2>
                </div>
                <div class="featured__controls">
                    <ul>
                        <li class="active" data-filter="*">All</li>
                        <?php foreach ($categories as $category): ?>
                            <li data-filter=".<?php echo strtolower(str_replace(' ', '-', $category['category_name'])); ?>">
                                <?php echo htmlspecialchars($category['category_name']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="row featured__filter">
            <?php foreach ($products as $product): ?>
                <?php
                // Fetch category name for the product
                $category_name = '';
                foreach ($categories as $category) {
                    if ($category['category_id'] == $product['category_id']) {
                        $category_name = $category['category_name'];
                        break;
                    }
                }

                // Sanitize category class
                $category_class = strtolower(str_replace(' ', '-', $category_name));

                // Default image if missing
                $image_path = !empty($product['image_path']) ? htmlspecialchars($product['image_path']) : 'default.jpg';
                ?>

                <div class="col-lg-3 col-md-4 col-sm-6 mix <?php echo $category_class; ?>">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="<?php echo $image_path; ?>" 
                            style="background-image: url('<?php echo $image_path; ?>');">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                            <!-- Update the link to include product ID -->
                            <h6><a href="product-details.php?id=<?php echo $product['product_id']; ?>">
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </a></h6>
                            <h5>$<?php echo number_format($product['price'], 2); ?></h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- Featured Section End -->
    <!-- Banner Begin -->
    <div class="banner mt-4" >
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                        <img src="https://i.pinimg.com/736x/84/57/bb/8457bb3f81195119007a25701ed04b09.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic" >
                        <img src="https://i.pinimg.com/736x/cc/c0/ec/ccc0ec17f2efa897cf8aecb1be1c2df9.jpg"  alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    <!-- Latest Product Section Begin -->
    <section class="latest-product spad mt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="latest-product__text">
                        <h4>Latest Products</h4>
                        <div class="latest-product__slider owl-carousel">
                            <div class="latest-prdouct__slider__item">
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/e8/f3/e5/e8f3e57a8b96e4ffe51c99c06d618ed6.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Gift Box</h6>
                                        <span>$30.00</span>
                                    </div>
                                </a>
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/50/37/cc/5037cc1a6a450d1dd33f2fda12b0ce88.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Diary</h6>
                                        <span>$50.00</span>
                                    </div>
                                </a>
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/06/a3/09/06a3093cc9e0de6ecac0d9015f861de6.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Elegant pen</h6>
                                        <span>$50.00</span>
                                    </div>
                                </a>
                            </div>
                            <div class="latest-prdouct__slider__item">
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/6f/84/c7/6f84c778c84ca132b22afb207d4c00a8.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>School Bag</h6>
                                        <span>$70.00</span>
                                    </div>
                                </a>
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/6e/fd/9b/6efd9b60bc38d2b505eaf7698d804e84.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Teddy Bear</h6>
                                        <span>$50.00</span>
                                    </div>
                                </a>
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/10/a6/9b/10a69b38efc640f709b317488dd8a9b8.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Chargable Bike</h6>
                                        <span>$100.00</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="latest-product__text">
                        <h4>Top Rated Products</h4>
                        <div class="latest-product__slider owl-carousel">
                            <div class="latest-prdouct__slider__item">
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/95/f3/f1/95f3f1d800d9436b75504a09d6531a81.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Bicycle</h6>
                                        <span>$120.00</span>
                                    </div>
                                </a>
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/4d/9c/9e/4d9c9e461aca6f28fa5b20d51d849f3e.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Toy Camera</h6>
                                        <span>$30.00</span>
                                    </div>
                                </a>
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/a8/55/a9/a855a99518e522b005d2cd4aa22d4227.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Toy lamp</h6>
                                        <span>$80.00</span>
                                    </div>
                                </a>
                            </div>
                            <div class="latest-prdouct__slider__item">
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/a8/e3/bc/a8e3bc94f5b1ad1f993c0fb76ca1a2c8.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Toy Tool Kit</h6>
                                        <span>$60.00</span>
                                    </div>
                                </a>
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/85/29/21/852921c068dcad9760adbd50a919565b.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Note Books</h6>
                                        <span>$20.00</span>
                                    </div>
                                </a>
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/a5/d3/57/a5d357db68b67acbb202044947ecf4e7.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Paper Clips Jar</h6>
                                        <span>$40.00</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="latest-product__text">
                        <h4>Review Products</h4>
                        <div class="latest-product__slider owl-carousel">
                            <div class="latest-prdouct__slider__item">
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/b2/d7/1c/b2d71cb5508ea427aca58a6240a48a96.jpgh" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Pencils</h6>
                                        <span>$30.00</span>
                                    </div>
                                </a>
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/e8/b5/f5/e8b5f57f357077ed7002d17aa647b590.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Geometry Box</h6>
                                        <span>$50.00</span>
                                    </div>
                                </a>
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/7e/b4/37/7eb43711e29558dca67c4b8674deed43.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Sharpners</h6>
                                        <span>$2.00</span>
                                    </div>
                                </a>
                            </div>
                            <div class="latest-prdouct__slider__item">
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/18/7e/06/187e061b89e0da07812e2cecbd9c7c89.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Calculator</h6>
                                        <span>$60.00</span>
                                    </div>
                                </a>
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/4b/fa/b8/4bfab8e444bfa3895b333f151b0e004b.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Transparent Files</h6>
                                        <span>$10.00</span>
                                    </div>
                                </a>
                                <a href="#" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="https://i.pinimg.com/736x/c5/ca/f5/c5caf56e64edacee35252386e568ea14.jpg" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>Staplers</h6>
                                        <span>$30.00</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Latest Product Section End -->


<?php

include("components/footer.php");
?>
