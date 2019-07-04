<?php get_header();?>

	<div class="main-wrapper bg-white">
    <div class="header-bg">
        <div class="container">
            <h1>Contact Us</h1>
        </div>
    </div>

    <!-- BEGIN form-->
    <div class="container">
        <div class="contact-us">
            <form action="">
                <div class="row">
                    <div class="contact-us__wrapper">
                        <div class="col-md-6">
                            <div class="contact-us__inner">
                                <div class="form-group">
                                    <div class="contact-us__label">Your message*</div>
                                    <div class="contact-us__textarea">
                                        <textarea name="message" id="" cols="40" rows="10"
                                                  placeholder="Enter Your message..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-us__inner">
                                <div class="form-group">
                                    <div class="contact-us__label">
                                        FIRST NAME*
                                    </div>
                                    <div class="contact-us__input">
                                        <input type="text" placeholder="Enter your first name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="contact-us__label">
                                        EMAIL*
                                    </div>
                                    <div class="contact-us__input">
                                        <input type="text" placeholder="email@domain.com">
                                    </div>
                                </div>
                                <div class="contact-us__submit btn">
                                    <input type="submit" value="Submit" class="btn">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--todo:need validation-->
                <div class="validation col-md-12 col-sm-12 d-none">
                    One or more fields have an error. Please check and try again.
                </div>
            </form>
        </div>
    </div>
    <!-- END form-->

    <!-- BEGIN google maps-->
    <div id="contact-up__map"></div>
    <!-- END google maps-->

</div>

<script defer type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/contact-us-page.js'></script>

<?php
get_footer();