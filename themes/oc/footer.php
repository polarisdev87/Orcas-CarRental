<?php
$footerAboutText = get_field('footer_about_text', 'option');
$footerAboutPhone = get_field('footer_about_phone', 'option');
?>

<!-- Footer -->
<footer class="footer" id="footer">
    <div class="footer-top container">
        <div class="row">
            <aside class="col-lg-3 col-md-6">
                <h6><span>ABOUT</span> ORCASCARS.COM</h6>
                <p><?php echo $footerAboutText; ?></p>
                <?php if (!empty($footerAboutPhone)): ?>
                    <a class="link-phone" href="tel:<?php echo $footerAboutPhone; ?>">
                        <i class="stm-rental-phone_circle"></i><?php echo $footerAboutPhone; ?>
                    </a>
                <?php endif; ?>
            </aside>

            <?php \app\helpers\TemplatesHelper::showFooterRecentPosts(); ?>

            <aside class="col-lg-3 col-md-6">
                <h6>RENTAL POLICIES</h6>

                <?php wp_nav_menu([
                    'container' => '',
                    'menu_class' => 'list',
                    'theme_location' => 'footer_menu',
                ]); ?>
            </aside>

            <?php if (have_rows('footer_social_media', 'option')): ?>
                <aside class="col-lg-3 col-md-6">
                    <h6 class="footer-top__tittle">SOCIAL NETWORK</h6>
                    <ul class="social-icon">

                        <?php while (have_rows('footer_social_media', 'option')) : the_row();?>
                            <li class="social-icon__item">
                                <a href="<?php the_sub_field('url'); ?>" target="_blank">
                                    <?php if (get_row_layout() == 'facebook'): ?>
                                        <i class="fa fa-facebook"></i></a>
                                    <?php elseif (get_row_layout() == 'twitter'): ?>
                                        <i class="fa fa-twitter"></i>
                                    <?php elseif (get_row_layout() == 'instagram'): ?>
                                        <i class="fa fa-instagram"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endwhile; ?>

                    </ul>
                </aside>
            <?php endif; ?>

        </div>
    </div>
    <div class="footer-copyright container">
        <div class="row">
            <div class="col-md-9 col-sm-9">
                <div class="copyright-text">© Copyright 2018 <a href="https://www.orcascars.com/">OrcasCars.com</a>. All
                    Rights Reserved. | <a href="https://www.orcascars.com/terms-and-conditions/">Terms Of Use</a> | <a
                            href="https://www.orcascars.com/privacy-policy/">Privacy Notice</a> | <a class="link-phone"
                                                                                                     href="tel:4065307090">
                        <i class="stm-rental-phone_circle"></i>(406) 530-7090</a></div>
            </div>
            <?php if (have_rows('footer_social_media', 'option')): ?>
                <div class="col-md-3 col-sm-3">
                    <div class="copyright-socials">
                        <ul>
                            <?php while (have_rows('footer_social_media', 'option')) : the_row();?>
                                <li class="social-icon__item">
                                    <a href="<?php the_sub_field('url'); ?>" target="_blank">
                                        <?php if (get_row_layout() == 'facebook'): ?>
                                            <i class="fa fa-facebook"></i></a>
                                        <?php elseif (get_row_layout() == 'twitter'): ?>
                                            <i class="fa fa-twitter"></i>
                                        <?php elseif (get_row_layout() == 'instagram'): ?>
                                            <i class="fa fa-instagram"></i>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</footer>
<!-- END Footer -->

<?php wp_footer(); ?>

</body>
</html>
