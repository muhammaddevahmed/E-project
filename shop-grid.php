<?php
include("components/header.php");


?>

<!-- Hero Section Begin -->
<section class="hero hero-normal">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all">
                        <i class="fa fa-bars"></i>
                        <span>All departments</span>
                    </div>
                    <ul>
                        <li><a href="#">Fresh Meat</a></li>
                        <li><a href="#">Vegetables</a></li>
                        <li><a href="#">Fruit & Nut Gifts</a></li>
                        <li><a href="#">Fresh Berries</a></li>
                        <li><a href="#">Ocean Foods</a></li>
                        <li><a href="#">Butter & Eggs</a></li>
                        <li><a href="#">Fastfood</a></li>
                        <li><a href="#">Fresh Onion</a></li>
                        <li><a href="#">Papayaya & Crisps</a></li>
                        <li><a href="#">Oatmeal</a></li>
                        <li><a href="#">Fresh Bananas</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hero__search">
                    <div class="hero__search__form">
                        <form action="#">
                            <div class="hero__search__categories">
                                All Categories
                                <span class="arrow_carrot-down"></span>
                            </div>
                            <input type="text" placeholder="What do yo u need?">
                            <button type="submit" class="site-btn">SEARCH</button>
                        </form>
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
            </div>
        </div>
    </div>
</section>
<!-- Hero Section End -->

<!-- Product Section Begin -->
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4>Categories</h4>
                        <ul>
                            <li><a href="shop-grid.php">All Categories</a></li>
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <a href="shop-grid.php?category_id=<?php echo $category['category_id']; ?>">
                                        <?php echo htmlspecialchars($category['category_name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="sidebar__item">
                        <h4>Sort By</h4>
                        <form method="GET" action="">
                            <select name="sort" onchange="this.form.submit()">
                                <option value="default" <?php echo ($sort === 'default') ? 'selected' : ''; ?>>Default</option>
                                <option value="price_asc" <?php echo ($sort === 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                                <option value="price_desc" <?php echo ($sort === 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
                                <option value="name_asc" <?php echo ($sort === 'name_asc') ? 'selected' : ''; ?>>Name: A to Z</option>
                                <option value="name_desc" <?php echo ($sort === 'name_desc') ? 'selected' : ''; ?>>Name: Z to A</option>
                            </select>
                            <?php if ($category_id > 0): ?>
                                <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-7">
                <div class="filter__item">
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <div class="filter__sort">
                                <span>Sort By</span>
                                <select onchange="window.location.href = 'shop.php?sort=' + this.value + '&category_id=<?php echo $category_id; ?>'">
                                    <option value="default" <?php echo ($sort === 'default') ? 'selected' : ''; ?>>Default</option>
                                    <option value="price_asc" <?php echo ($sort === 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                                    <option value="price_desc" <?php echo ($sort === 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
                                    <option value="name_asc" <?php echo ($sort === 'name_asc') ? 'selected' : ''; ?>>Name: A to Z</option>
                                    <option value="name_desc" <?php echo ($sort === 'name_desc') ? 'selected' : ''; ?>>Name: Z to A</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="filter__found">
                                <h6><span><?php echo $total_products; ?></span> Products found</h6>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-3">
                            <div class="filter__option">
                                <span class="icon_grid-2x2"></span>
                                <span class="icon_ul"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
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

                        // Default image if missing
                        $image_path = !empty($product['image_path']) ? htmlspecialchars($product['image_path']) : 'default.jpg';
                        ?>

                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="<?php echo $image_path; ?>" 
                                    style="background-image: url('<?php echo $image_path; ?>');">
                                    <ul class="product__item__pic__hover">
                                        <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                        <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                        <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><a href="product-details.php?id=<?php echo $product['product_id']; ?>">
                                        <?php echo htmlspecialchars($product['product_name']); ?>
                                    </a></h6>
                                    <h5>$<?php echo number_format($product['price'], 2); ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="product__pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="shop-grid.php?page=<?php echo $i; ?>&sort=<?php echo $sort; ?>&category_id=<?php echo $category_id; ?>" 
                           class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <a href="shop-grid.php?page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>&category_id=<?php echo $category_id; ?>">
                            <i class="fa fa-long-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Section End -->

<?php
include("components/footer.php");
?>