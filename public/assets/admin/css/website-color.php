<?php
header("Content-Type:text/css");

// get the colors from query parameter
$primaryColor = $_GET['primary_color'];
$secondaryColor = $_GET['secondary_color'];
$footerBackgroundColor = $_GET['footer_background_color'];
$copyrightBackgroundColor = $_GET['copyright_background_color'];
$breadcrumbOverlayColor = $_GET['breadcrumb_overlay_color'];
$breadcrumbOverlayOpacity = $_GET['breadcrumb_overlay_opacity'];

// check, whether color has '#' or not, will return 0 or 1
function checkColorCode($color)
{
  return preg_match('/^#[a-f0-9]{6}/i', $color);
}

// if, primary color value does not contain '#', then add '#' before color value
if (isset($primaryColor) && (checkColorCode($primaryColor) == 0)) {
  $primaryColor = '#' . $primaryColor;
}

// if, secondary color value does not contain '#', then add '#' before color value
if (isset($secondaryColor) && (checkColorCode($secondaryColor) == 0)) {
  $secondaryColor = '#' . $secondaryColor;
}

// if, footer background color value does not contain '#', then add '#' before color value
if (isset($footerBackgroundColor) && (checkColorCode($footerBackgroundColor) == 0)) {
  $footerBackgroundColor = '#' . $footerBackgroundColor;
}

// if, copyright background color value does not contain '#', then add '#' before color value
if (isset($copyrightBackgroundColor) && (checkColorCode($copyrightBackgroundColor) == 0)) {
  $copyrightBackgroundColor = '#' . $copyrightBackgroundColor;
}

// if, breadcrumb overlay color value does not contain '#', then add '#' before color value
if (isset($breadcrumbOverlayColor) && (checkColorCode($breadcrumbOverlayColor) == 0)) {
  $breadcrumbOverlayColor = '#' . $breadcrumbOverlayColor;
}

// then add color to style
?>

.spinner>div {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.page-item.active .page-link {
  background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.header-area .header-top .header-logo form .input-box i {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

nav .pagination li a:hover {
    background: <?php echo htmlspecialchars($secondaryColor); ?>;
    border-color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.header-area .header-top .header-btns ul li a i {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.header-navigation .main-menu ul>li.menu-item>a:before {
background-color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.header-navigation .main-menu ul li:hover>a,
.header-navigation .main-menu ul li.active>a {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.header-navigation .main-menu ul li .sub-menu li a:hover {
background-color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.navbar-item .menu-icon ul li a:hover {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.banner-area .banner-content ul li a {
background: <?php echo htmlspecialchars($primaryColor); ?>;
border: <?php echo '2px solid ' . $primaryColor; ?>;
}

.banner-area .banner-content ul li a.main-btn-2:hover {
background: <?php echo htmlspecialchars($primaryColor); ?>;
border-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.dream-course-area .dream-course-content .dream-course-title span {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.dream-course-area .dream-course-content .dream-course-search .input-box i {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.dream-course-area .dream-course-content .dream-course-search .dream-course-btn button {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.services-area .services-border .single-services:hover::before {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.offer-area .offer-content ul li a {
border-color: <?php echo htmlspecialchars($primaryColor); ?>;
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.offer-area .offer-content ul li a:hover {
border-color: <?php echo htmlspecialchars($secondaryColor); ?>;
background-color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.offer-area .offer-content ul li a.main-btn-2 {
border-color: <?php echo htmlspecialchars($secondaryColor); ?>;
background-color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.offer-area .offer-content ul li a.main-btn-2:hover {
border-color: <?php echo htmlspecialchars($primaryColor); ?>;
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.single-courses .courses-thumb .corses-thumb-title a.category {
color: <?php echo htmlspecialchars($secondaryColor); ?> !important;
}

.single-courses:hover .courses-content .title {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.single-courses .courses-content .courses-info .item p {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.play-area .play-thumb::before {
border-top: <?php echo '138px solid ' . $primaryColor; ?>;
}

.play-area .play-thumb::after {
border-top: <?php echo '70px solid ' . $secondaryColor; ?>;
}

.testimonials-area .testimonials-content i {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.testimonials-area .testimonials-active .slick-arrow.prev:hover {
border-right-color: #e9ecff;
border-left-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.testimonials-area .testimonials-active .slick-arrow:hover {
border-color: <?php echo htmlspecialchars($primaryColor); ?>;
border-left-color: #e9ecff;
}

.testimonials-area .testimonials-content>span {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.community-area .community-content .input-box button {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.footer-area .footer-item .footer-list-area .footer-list ul li a:hover {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.footer-instagram .blog-info h6 a:hover {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.back-to-top a {
background: <?php echo htmlspecialchars($secondaryColor); ?>;
}

button.cookie-consent__agree {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.course-grid-area .course-grid .course-grid-top .course-filter .nice-select span {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.course-grid-area .course-grid .course-grid-top .course-filter .nice-select::after {
border-bottom: <?php echo '2px solid ' . $primaryColor; ?>;
border-right: <?php echo '2px solid ' . $primaryColor; ?>;
}

.course-grid-area .course-grid .course-grid-top .course-filter .input-box i {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.course-grid-area .single-courses .courses-thumb .corses-thumb-title span {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.course-grid-area .single-courses:hover .courses-content .title {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.course-grid-area .single-courses .courses-content .courses-info .item p {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.radio_common-2 li input[type="radio"]:checked+label span {
border-color: <?php echo htmlspecialchars($primaryColor); ?>;
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.radio_common-2 li input[type="radio"]:checked+label {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.course-price-filter .ui-widget-content .ui-slider-handle {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.course-price-filter .ui-slider-horizontal .ui-slider-range {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.course-title-area::before {
background-color: <?php echo htmlspecialchars($primaryColor) . 'E6'; ?>;
}

.course-details-area .course-details-sidebar .course-sidebar-thumb a {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.course-details-area .course-details-sidebar .course-sidebar-price .title {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

#coupon-btn {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
border-color: <?php echo htmlspecialchars($primaryColor); ?>;
color: #fff;
}

.course-details-area .course-details-sidebar .course-sidebar-btns #enrol-btn {
background: <?php echo htmlspecialchars($primaryColor); ?>;
border: <?php echo '2px solid ' . $primaryColor; ?>;
}

.course-details-area .course-details-sidebar .course-sidebar-share {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.course-details-area .course-details-sidebar .course-sidebar-share a:hover {
background-color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.single-courses .courses-thumb .corses-thumb-title.item-2 span {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.course-accordian .card .card-header .title[aria-expanded=true] {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.course-accordian .card {
border: <?php echo '1px solid ' . $primaryColor . '!important'; ?>;
}

.curriculum-accordion .card .card-header .title {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.course-details-items .instructor-box .info ul.social-link li a {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.course-details-items .instructor-box .info ul.social-link li a:hover {
background-color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.main-btn {
border: <?php echo '1px solid ' . $primaryColor; ?>;
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.main-btn:hover {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
border-color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.speakers-area .single-speakers .speakers-thumb a {
background: <?php echo htmlspecialchars($primaryColor); ?>;
color: #fff;
}

.speakers-area .single-speakers .speakers-content span {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.social-link-btn {
color: #fff;
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
border-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.social-link-btn:hover {
color: #fff;
}

.blog-standard-area .blog-standard .single-blog-grid .blog-content span {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.blog-standard-area .blog-sidebar .blog-side-about .about-title .title::before {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.blog-standard-area .blog-sidebar .blog-side-about .about-title .title::after {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.blog-standard-area .blog-sidebar .blog-side-about .blog-Search-content .input-box button {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.blog-standard-area .blog-sidebar .blog-side-about .blog-categories-content ul li.active a {
background: <?php echo htmlspecialchars($primaryColor) . 'D9'; ?>;
}

.blog-standard-area .blog-sidebar .blog-side-about .blog-categories-content ul li.active a span {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.blog-standard-area .blog-sidebar .blog-side-about .blog-categories-content ul li a {
background: <?php echo htmlspecialchars($secondaryColor) . 'D9'; ?>;
}

.blog-standard-area .blog-sidebar .blog-side-about .blog-categories-content ul li a span {
background: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.blog-dteails-content .blog-details-top>span {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.blog-dteails-content .blog-details-bar .blog-social ul li a:hover {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.faq-area .faq-accordion .accordion .card .card-header a::before {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.faq-area .faq-accordion .accordion .card .card-header a.collapsed::before {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.contact-action-area .contact-action-item .contact-form-btn {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.user-sidebar .links li a.active,
.user-sidebar .links li:hover>a {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.main-table .dataTables_wrapper td a.btn {
border: <?php echo '1px solid ' . $primaryColor; ?>;
}

.main-table .dataTables_wrapper td a.btn:hover {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.paginate_button.active .page-link {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.course-nav-left a.prev:hover,
.course-nav-right a.certificate:hover {
  background-color: <?php echo htmlspecialchars($primaryColor); ?>;
  color: #fff;
}

.course-videos-area .course-videos-sidebar .course-video-nav .course-section ul.list li a:hover,
.course-videos-area .course-videos-sidebar .course-video-nav .course-section ul.list li a.active {
background-color: <?php echo htmlspecialchars($primaryColor) . '66'; ?>;
}

.course-videos-area .course-videos-sidebar::-webkit-scrollbar-thumb {
background: <?php echo htmlspecialchars($primaryColor) . '66'; ?>;
}

.video-js .vjs-big-play-button {
background-color: <?php echo htmlspecialchars($primaryColor) . '66'; ?>;
}

.download-box button {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

#quiz-complete a {
background: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.user-dashboard .user-profile-details .edit-info-area .file-upload-area .upload-file span {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.user-dashboard .user-profile-details .edit-info-area .btn {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.single-courses .courses-content .courses-info span {
color: <?php echo htmlspecialchars($primaryColor); ?>;
background: <?php echo htmlspecialchars($primaryColor) . '1A'; ?>;
}

.single-courses .courses-content .courses-info .price span.pre-price {
background: <?php echo htmlspecialchars($secondaryColor) . '1A'; ?>;
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.course-details-area .course-details-items .course-thumb .tab-btns ul li a i,
.course-details-area .course-details-items .course-thumb .tab-btns ul li:nth-child(2) a i,
.course-details-area .course-details-items .course-thumb .tab-btns ul li:nth-child(3) a i,
.course-details-area .course-details-items .course-thumb .tab-btns ul li:nth-child(4) a i {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.course-details-area .course-details-items .course-thumb .tab-btns ul li a.active {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.course-details-area .course-details-items .course-thumb .tab-btns ul li a.active i {
color: <?php echo htmlspecialchars($secondaryColor) . '!important'; ?>;
}

.course-details-area .course-details-items .course-thumb .tab-btns ul li a.active::before {
background: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.course-details-area .trending-course>.title i {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.contact-info-area .contact-info-content .single-contact-info .info-icon i,
.contact-info-area .contact-info-content .single-contact-info.item-2 .info-icon i,
.contact-info-area .contact-info-content .single-contact-info.item-3 .info-icon i {
background: <?php echo htmlspecialchars($primaryColor); ?>;
box-shadow: <?php echo '0px 8px 16px 0px ' . $primaryColor . '4D'; ?>;
}

.advance-courses-area .courses-active .slick-arrow:hover {
background: <?php echo htmlspecialchars($secondaryColor); ?>;
border-color: <?php echo htmlspecialchars($secondaryColor); ?>;
box-shadow: <?php echo '0px 8px 16px 0px ' . $secondaryColor . '33'; ?>;
}

.header-area-two .header-navigation:before {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.sub-items-area .sub-item {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.sub-items-area .sub-item .input-box button {
background: <?php echo htmlspecialchars($secondaryColor); ?>;
color: #fff;
}

.offer-2-area .offer-content ul li a {
background: <?php echo htmlspecialchars($primaryColor); ?>;
border: <?php echo '2px solid ' . $primaryColor; ?>;
color: #fff;
}

.offer-2-area .offer-content ul li a:hover {
border-color: <?php echo htmlspecialchars($secondaryColor); ?>;
background-color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.offer-2-area .offer-content ul li a.main-btn-2 {
border-color: <?php echo htmlspecialchars($secondaryColor); ?>;
background-color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.offer-2-area .offer-content ul li a.main-btn-2:hover {
background: <?php echo htmlspecialchars($primaryColor); ?>;
border-color: <?php echo htmlspecialchars($primaryColor); ?>;
color: #fff;
}

.mentors-area .single-mentors .mentors-content span {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.testimonials-2-area .single-testimonials .testimonials-content i {
background: <?php echo htmlspecialchars($primaryColor); ?>;
box-shadow: <?php echo '0px 8px 16px 0px ' . $primaryColor . '33'; ?>;
}

.testimonials-2-area .testimonials-2-active .slick-dots li.slick-active button {
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.footer-area {
background: <?php echo htmlspecialchars($footerBackgroundColor); ?>;
}

.footer-area.footer-area-2 {
background: <?php echo htmlspecialchars($footerBackgroundColor); ?>;
}

.footer-area.footer-area-2 .footer-item .footer-content .input-box input {
border: <?php echo '4px solid ' . $copyrightBackgroundColor; ?>;
background: <?php echo htmlspecialchars($footerBackgroundColor); ?>;
}

.footer-area.footer-area-2 .footer-item .footer-content .input-box i {
color: <?php echo htmlspecialchars($secondaryColor); ?>;
}

.footer-area.footer-area-2 .footer-item .footer-content button {
background: <?php echo htmlspecialchars($secondaryColor); ?>;
color: #fff;
}

.copyright-part-two {
background: <?php echo htmlspecialchars($copyrightBackgroundColor); ?>;
}

.we-do-3 .section-title>span {
border-top: <?php echo '18px solid ' . $primaryColor; ?>;
}

.services-area-3::before {
background-color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.exp-area .exp-content-area .top-content span {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.our-courses-area .section-title>span {
border-top: <?php echo '18px solid ' . $primaryColor; ?>;
}

.our-courses-area .courses-active-3 .slick-arrow:hover {
background: <?php echo htmlspecialchars($primaryColor); ?>;
border-color: <?php echo htmlspecialchars($primaryColor); ?>;
box-shadow: <?php echo '0px 8px 16px 0px ' . $primaryColor . '33'; ?>;
}

.our-courses-area .section-title .nav li a.active {
border-bottom: <?php echo '3px solid ' . $primaryColor; ?>;
}

.our-courses-area .single-courses-3 .courses-thumb .courses-overlay ul li p {
color: #fff;
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.our-courses-area .single-courses-3 .courses-content ul li p span {
color: <?php echo htmlspecialchars($primaryColor); ?>;
}

.faq-answer-area.faq-answer-area-2 .section-title>span {
border-top: <?php echo '18px solid ' . $primaryColor; ?>;
}

.faq-answer-area form .input-box button {
background: <?php echo htmlspecialchars($primaryColor); ?>;
color: #fff;
box-shadow: <?php echo '0px 8px 16px 0px ' . $primaryColor . '4D'; ?>;
}

.counter-3-area .section-title>span {
border-top: <?php echo '18px solid ' . $primaryColor; ?>;
}

.news-3-area .section-title>span {
border-top: <?php echo '18px solid ' . $primaryColor; ?>;
}

.news-3-area .single-news .news-content .btns a.category {
color: #fff;
background: <?php echo htmlspecialchars($primaryColor); ?>;
}

.page-title::before {
background-color: <?php echo htmlspecialchars($breadcrumbOverlayColor); ?>;
opacity: <?php echo htmlspecialchars($breadcrumbOverlayOpacity); ?>;
}
.our-courses-area .courses-active-3 .slick-arrow {
    background: <?php echo htmlspecialchars($primaryColor); ?>;
}
.blog-standard-area .blog-standard nav ul li a:hover {
    background: <?php echo htmlspecialchars($secondaryColor); ?>;
}